<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Messages_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // public function get_patients($user_id, $keywords = '')
    // {
    //     $this->db->select("CONCAT(u.first_name,' ',u.last_name),u.id as userid,u.role,u.first_name,u.last_name,u.username,u.profileimage,(select chatdate from chat where recieved_id = userid OR sent_id = userid ORDER BY chatdate DESC LIMIT 1) as chatdate,(select msg from chat where sent_id =userid OR recieved_id = userid ORDER BY id DESC LIMIT 1) as last_msg,(select type from chat where sent_id = userid OR recieved_id = userid ORDER BY id DESC LIMIT 1) as type,(select date_time from user_online_status where user_id=userid) as date_time,(select time_zone from user_online_status where user_id=userid) as time_zone,(select count(id) from chat where recieved_id=" . $user_id . " and sent_id=userid and read_status=0) as unread_count");

        
    //     $this->db->from('appointments a');
    //     $this->db->join('users u', 'u.id = a.appointment_from', 'left');
    //     $this->db->where('a.appointment_to', $user_id);
    //     if (!empty($keywords)) {
    //         $this->db->group_start();
    //         $this->db->like('CONCAT(u.first_name,\' \',u.last_name)', $keywords, 'both');
    //         $this->db->or_like('u.last_name', $keywords, 'both');
    //         $this->db->group_end();
    //     }
    //     $this->db->group_by('a.appointment_from');
    //     $this->db->order_by('chatdate', 'DESC');
    //     // $qu=  $this->db->get_compiled_select();
    //     // echo $qu.'<br>';
    //     //   die('test');
    //     return $this->db->get()->result_array();
    // }

    public function get_patients($user_id, $keywords = '')
{
    $this->db->select("CONCAT(u.first_name,' ',u.last_name) AS full_name, 
                       u.id as userid, 
                       u.role, 
                       u.first_name, 
                       u.last_name, 
                       u.username, 
                       u.profileimage, 
                       (SELECT chatdate 
                        FROM chat 
                        WHERE recieved_id = u.id OR sent_id = u.id 
                        ORDER BY chatdate DESC 
                        LIMIT 1) as chatdate, 
                       (SELECT msg 
                        FROM chat 
                        WHERE sent_id = u.id OR recieved_id = u.id 
                        ORDER BY id DESC 
                        LIMIT 1) as last_msg, 
                       (SELECT type 
                        FROM chat 
                        WHERE sent_id = u.id OR recieved_id = u.id 
                        ORDER BY id DESC 
                        LIMIT 1) as type, 
                       (SELECT date_time 
                        FROM user_online_status 
                        WHERE user_id = u.id) as date_time, 
                       (SELECT time_zone 
                        FROM user_online_status 
                        WHERE user_id = u.id) as time_zone, 
                       (SELECT count(id) 
                        FROM chat 
                        WHERE recieved_id = " . $this->db->escape($user_id) . " 
                        AND sent_id = u.id 
                        AND read_status = 0) as unread_count");

    $this->db->from('appointments a');
    $this->db->join('users u', 'u.id = a.appointment_from', 'left');
    $this->db->where('a.appointment_to', $user_id);

    if (!empty($keywords)) {
        $this->db->group_start();
        $this->db->like("CONCAT(u.first_name,' ',u.last_name)", $keywords, 'both');
        $this->db->or_like('u.last_name', $keywords, 'both');
        $this->db->group_end();
    }

    $this->db->group_by('u.id, u.role, u.first_name, u.last_name, u.username, u.profileimage');
    $this->db->order_by('chatdate', 'DESC');
    //   $qu=  $this->db->get_compiled_select();
    //    echo $qu.'<br>';
    //      die('test');
    return $this->db->get()->result_array();
}


    public function get_doctors($user_id, $keywords = '')
    {
        $this->db->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,u.profileimage,
		(select chatdate from chat where is_admin=0 and (recieved_id = userid OR sent_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY chatdate DESC LIMIT 1) as chatdate,
		(select msg from chat where is_admin=0 and (sent_id=userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as last_msg,
		(select type from chat where is_admin=0 and (sent_id = userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as type,
		(select date_time from user_online_status where user_id=userid)  as date_time,
		(select time_zone from user_online_status where user_id=userid) as time_zone,
		(select count(id) from chat where recieved_id="' . $user_id . '" and sent_id=userid and read_status=0) as unread_count');
        $this->db->from('appointments a');
        $this->db->join('users u', 'u.id = a.appointment_to', 'left');
        $this->db->where('a.appointment_from', $user_id);
        if (!empty($keywords)) {
            $this->db->group_start();
            $this->db->like('u.first_name', $keywords, 'after');
            $this->db->or_like('u.last_name', $keywords, 'after');
            $this->db->or_like('CONCAT(u.first_name," ", u.last_name)', $keywords);
            $this->db->group_end();
        }
        $this->db->group_by('a.appointment_to');
        $this->db->order_by('chatdate', 'DESC');
        // $this->db->get()->result_array();
        // echo $this->db->last_query();exit;
        return $this->db->get()->result_array();
    }

    public function get_latest_chat($selected_user, $user_id,$admin_role=0)
    {


        $per_page = 5;
        $total =  $this->get_total_chat_count($selected_user, $user_id,$admin_role);
        if ($total > 5) {
            $total = $total - 5;
        } else {
            $total = 0;
        }

        $this->update_counts($selected_user, $user_id);

        $query = $this->db->query("SELECT DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id,msg.msg, msg.chatdate,msg.id,msg.type,msg.file_name,msg.file_path,msg.time_zone,msg.id
        from chat msg
        LEFT  join users sender on msg.sent_id = sender.id
        left join chat_deleted_details cd on cd.chat_id  = msg.id
        where cd.can_view = $user_id AND msg.is_admin = $admin_role AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id ASC LIMIT $total,$per_page ");
        $result = $query->result_array();
        return $result;
    }
    public function get_old_chat($selected_user, $user_id, $total)
    {
        $per_page = 5;

        $query = $this->db->query("SELECT DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id,msg.msg, msg.chatdate,msg.id,msg.type,msg.file_name,msg.file_path,msg.time_zone,msg.id
        from chat msg
         LEFT  join users sender on msg.sent_id = sender.id
        left join chat_deleted_details cd on cd.chat_id  = msg.id
        where cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id ASC LIMIT $total,$per_page  ");
        $result = $query->result_array();
        return $result;
    }
    public function get_last_message_id($selected_user, $user_id,$admin_role)
    {



        $sql = "SELECT msg.id  from chat msg
    left join chat_deleted_details cd on cd.chat_id  = msg.id
    where  cd.can_view = $user_id AND msg.is_admin = $admin_role AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id DESC ";

        $data =   $this->db->query($sql)->row_array();


        if (!empty($data['id'])) {
            return $data['id'];
        } else {
            return 0;
        }
    }
    public function get_total_chat_count($selected_user, $user_id,$admin_role)
    {

        $sql = "SELECT msg.id  from chat msg
    left join chat_deleted_details cd on cd.chat_id  = msg.id
    where  cd.can_view = $user_id AND msg.is_admin = $admin_role AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id DESC ";

        return  $this->db->query($sql)->num_rows();
    }

    public function update_counts($selected_user, $user_id)
    {

        $query = $this->db->query("SELECT msg.id
        from chat msg
        LEFT  join users sender on msg.sent_id = sender.id
        where msg.delete_sts = 0 AND  msg.read_status = 0 AND (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user) ");
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $d) {
                $this->db->update('chat', array('read_status' => 1), array('id' => $d['id']));
            }
        } else {
            return true;
        }
    }

    public function get_admin_chats($user_id) {
        $this->db->select('u.id as userid,"1" as role,u.name as first_name, "" as last_name,u.name  as username,u.profileimage,
		(select chatdate from chat where (recieved_id = userid OR sent_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY chatdate DESC LIMIT 1) as chatdate,
		(select msg from chat where (sent_id=userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as last_msg,
		(select type from chat where (sent_id = userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as type,
		(select date_time from user_online_status where user_id=userid)  as date_time,
		(select time_zone from user_online_status where user_id=userid) as time_zone,
		(select count(id) from chat where recieved_id="' . $user_id . '" and sent_id=userid and read_status=0) as unread_count');
        $this->db->from('chat a');
        $this->db->join('administrators u', 'u.id = a.sent_id', 'left');
        $this->db->where('a.is_admin', '1');
        $this->db->where("((a.recieved_id = 1 AND a.sent_id = $user_id) or  (a.recieved_id = $user_id AND a.sent_id =  1)) ");
        $this->db->order_by('chatdate', 'DESC');
        // $this->db->get()->result_array();
        // echo $this->db->last_query();exit;
        return $this->db->get()->result_array();

    }
}