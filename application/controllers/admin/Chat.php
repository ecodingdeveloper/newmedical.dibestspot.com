<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chat extends CI_Controller
{

  public $data;
  public $session;
  public $timezone;
  public $lang;
  public $language;
  public $messages;
  public $input;
  public $db;
  public $upload;

  public function __construct()
  {

    parent::__construct();

    if ($this->session->userdata('admin_id') == '') {
      redirect(base_url() . 'admin/login');
    }

    $this->data['theme']     = 'admin';
    $this->data['module']    = 'chat';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->timezone = $this->session->userdata('time_zone');
    if (!empty($this->timezone)) {
      date_default_timezone_set($this->timezone);
    }
    $this->load->model('chat_model', 'chat');

    //$this->load->model('messages_model', 'messages');

    $lan = default_language();
    $lang = !empty($this->session->userdata('language')) ? strtolower($this->session->userdata('language')) : strtolower($lan['language']);
    $this->data['language'] = $this->lang->load('content', $lang, true);
    $this->language = $this->lang->load('content', $lang, true);
    //$this->load->model('messages_model', 'messages');
  }

  public function index()
  {

    $user_id = $this->session->userdata('admin_id');
    $this->data['page'] = 'index';
    $this->data['users'] =  $this->chat->getAllChats($user_id);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function search_users()
  {
    
    $user_id = $this->session->userdata('user_id');
    $keywords = $this->input->post('keywords');


    $users =  $this->chat->getAllChats($user_id, $keywords);

    // echo $this->db->last_query(); exit;
    // echo '<pre>';print_r($users);exit;
    // die("fgtkdg");

    $response = array();
    $result = array();


    if (!empty($users)) {
      foreach ($users as $rows) {

        $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
        $data['userid'] = $rows['userid'];
        $data['username'] = $rows['username'];
        $data['first_name'] = ucfirst($rows['first_name']);
        $data['last_name'] = ucfirst($rows['last_name']);
        $data['role'] = $rows['role'];
        $data['type'] = $rows['type'];
        $data['unread_count'] = $rows['unread_count'];
        $data['last_msg'] = strval($rows['last_msg']);
        if ($rows['chatdate'] != null) {

          $data['chatdate'] = time_elapsed_string($rows['chatdate']);
        } else {
          $data['chatdate'] = "";
        }
        $data['online_status'] = "avatar-offline";

        if ($rows['date_time']) {

          $current_timezone = $rows['time_zone'];
          $old_timezone = $this->session->userdata('time_zone');

          $appointment_date = date('Y-m-d H:i:s', strtotime(converToTz($rows['date_time'], $old_timezone, $current_timezone)));

          $datetime1 = new DateTime();
          $datetime2 = new DateTime($appointment_date);
          $interval = $datetime1->diff($datetime2);

          $interval_time = $interval->format('%i');
          if ($interval_time < 5) {
            $data['online_status'] = "avatar-online";
          } elseif (($interval_time > 5 && $interval_time < 10)) {
            $data['online_status'] = "avatar-away";
          } elseif ($interval_time > 5) {
            $data['online_status'] = "avatar-offline";
          }
        }

        $data['admin_role'] = 1;


        $result[] = $data;
      }

      $response['status'] = '200';
    } else {
      $response['status'] = '500';
    }
    $response['users_list'] = $result;
    echo json_encode($response);
  }


  public function get_chat_user()
  {
    $user_id = $this->session->userdata('admin_id');
    $data = array();
    $data['status'] = 500;



    $adminChat = $this->chat->get_admin_chats($user_id);
    if (!empty($adminChat)) {
      $data['user_id'] = $adminChat[0]['userid'];
      $data['username'] = $adminChat[0]['username'];
      $data['name'] = $adminChat[0]['first_name'];
      $data['admin_role'] = 1;
      $data['status'] = 200;
    }


    echo json_encode($data);
  }

  public function get_chat_img()
  {
    $user_id = $this->input->post('user_id');
    $admin_role = $this->input->post('admin_role');
    $result = $this->db->select('profileimage')->where('id', $user_id)->get('users')->row_array();
    $status_result = $this->db->select('*')->where('user_id', $user_id)->get('user_online_status')->row_array();
    $response['online_status'] = "avatar-offline";
    $response['status'] = "Offline";

    if (!empty($status_result) && $status_result['date_time']) {

      $current_timezone = $status_result['time_zone'];
      $old_timezone = $this->session->userdata('time_zone');

      $appointment_date = date('Y-m-d H:i:s', strtotime(converToTz($status_result['date_time'], $old_timezone, $current_timezone)));

      $datetime1 = new DateTime();
      $datetime2 = new DateTime($appointment_date);
      $interval = $datetime1->diff($datetime2);

      $interval_time = $interval->format('%i');
      if ($interval_time < 5) {
        $response['online_status'] = "avatar-online";
        $response['status'] = $this->language['lg_online'];
      } elseif ($interval_time > 5 && $interval_time < 10) {
        $response['online_status'] = "avatar-away";
        $response['status'] = $this->language['lg_idle'];
      } else {
        $response['online_status'] = "avatar-offline";
        $response['status'] = $this->language['lg_offline'];
      }
    }
    $response['profileimage'] = (!empty($result['profileimage'])) ? base_url() . $result['profileimage'] : base_url() . 'assets/img/user.png';

    echo json_encode($response);
  }

  public function get_messages()
  {

    $user_id = $this->session->userdata('admin_id');
    $selected_user = $_POST['selected_user_id'];
    $admin_role = $_POST['admin_role']; //New
  //   echo $admin_role;
  //   echo $user_id;

  //  die("dsfasd");
    $latest_chat = $this->chat->get_latest_chat($selected_user, $user_id, $admin_role);
    $total_chat = $this->chat->get_total_chat_count($selected_user, $user_id, $admin_role);
    $last_message_id = $this->chat->get_last_message_id($selected_user, $user_id, $admin_role);



    $page = 0;
    if ($total_chat > 5) {
      $total_chat = $total_chat - 5;
      $page = $total_chat / 5;
      $page = ceil($page);
      $page--;
    }



    // echo $this->db->last_query();
    // exit;

    if (count($latest_chat) > 4) {

      $html = '<div class="load-more-btn text-center" total="' . @$page . '">
    <button class="btn btn-sm" data-page="2"><i class="fa fa-refresh"></i> ' . $this->language['lg_load_more'] . '</button>
    </div><div id="ajax_old" class="ajax_old"></div>';
    } else {
      $html = '';
    }



    if (!empty($latest_chat)) {
      foreach ($latest_chat as $key => $currentuser) :


        $class_name = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';


        $img = (!empty($currentuser['senderImage'])) ? base_url() . $currentuser['senderImage'] : base_url() . 'assets/img/user.png';

        $time_zone = $this->session->userdata('time_zone');
        $from_timezone = $currentuser['time_zone'];
        $date_time = $currentuser['chatdate'];
        $date_time  = converToTz($date_time, $time_zone, $from_timezone);
        $time = date('d-m-Y h:i a', strtotime($date_time));


        if ($currentuser['type'] == 'image') {

          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-away">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p><img alt="" src="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" class="img-fluid"></p>
      <p>' . $currentuser['file_name'] . '</p>
      <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download>' . $this->language['lg_download'] . '</a>
  <ul class="chat-msg-info">
    <li>
      <div class="chat-time">
        <span>' . $time . '</span>
      </div>
    </li>
  </ul>
      </div>
      </div>
      </div>
      </li>';
        } else if ($currentuser['type'] == 'others') {

          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-away">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p><img alt="" src="' . base_url() . 'assets/img/download.png" class="img-responsive"></p>
      <p>' . $currentuser['file_name'] . '</p>
      <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download class="chat-time">' . $this->language['lg_download'] . '</a>
    <ul class="chat-msg-info">
      <li>
        <div class="chat-time">
          <span>' . $time . '</span>
        </div>
      </li>
    </ul>
      </div>
      </div>
      </div>
      </li>';
        } else {
          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-offline">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p>
      ' . $currentuser['msg'] . '
      </p>
  <ul class="chat-msg-info">
    <li>
      <div class="chat-time">
        <span>' . $time . '</span>
      </div>
    </li>
  </ul>
      </div>
      </div>
      </div>
      </li>';
        }




      endforeach;
    }
    $html .= '<div id="ajax"></div><input type="hidden"  id="hidden_id" value="">';

    if ($total_chat == 0) {
      $html .= '<div class="no_message">' . $this->language['lg_no_record_found'] . '</div>';
    }


    echo $html;
  }

  public function insert_chat()
  {

    //error_reporting(1);
    $admin_role = $this->input->post('admin_role');
    $data['recieved_id'] = $this->get_recieved_id($admin_role);
    $data['sent_id'] = $this->session->userdata('admin_id');
    $data['time_zone'] = $this->session->userdata('time_zone');
    $data['chatdate'] = date('Y-m-d H:i:s');
    $data['msg'] = $_POST['input_message'];
    $data['is_admin'] = 1;

    //print_r($data);
    $result = $this->db->insert('chat', $data);
    $chat_id = $this->db->insert_id();
    $users = array($data['recieved_id'], $data['sent_id']);
    for ($i = 0; $i < 2; $i++) {
      $datas = array('chat_id' => $chat_id, 'can_view' => $users[$i]);
      $this->db->insert('chat_deleted_details', $datas);
    }

    $notification=array(
			'user_id'=>0,
			'to_user_id'=>$data['recieved_id'],
			'type'=>"Chat",
			'text'=>'replied the message of ',
			'created_at'=>date("Y-m-d H:i:s"),
			'time_zone'=>$this->timezone
		);
        $this->db->insert('notification',$notification);

    // Notification Code
    $this->load->model('api_model', 'api');
    //$latest_chat = $this->api->get_latest_chat($this->get_user_id(), $this->session->userdata('user_id'),$data['is_admin']);
    $latest_chat = $this->api->get_latest_chat($this->get_recieved_id($data['is_admin']), $this->session->userdata('admin_id'), $data['is_admin']);

    if (!empty($latest_chat)) {

      foreach ($latest_chat as $key => $currentuser) {

        $from_timezone = $currentuser['time_zone'];

        $date_time = $currentuser['chatdate'];

        $time_zone = $this->session->userdata('time_zone');

        $user_id = $this->session->userdata('user_id');

        $date_time  = converToTz($date_time, $time_zone, $from_timezone);

        $msgdata['chat_time'] = date('Y-m-d H:i:s', strtotime($date_time));

        $type = $currentuser['type'];

        $attachment_file = ($currentuser['file_path']) ? ($currentuser['file_path'] . '/' . $currentuser['file_name']) : '';

        $message = $currentuser['msg'];

        $msgdata['type'] = 'Message';

        $msgdata['file_name'] = ($currentuser['file_path']) ? ($currentuser['file_path'] . '/' . $currentuser['file_name']) : '';

        $msgdata['msg_type'] = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';

        $msgdata['chat_from'] = $currentuser['sender_id'];

        $msgdata['chat_to'] = $currentuser['receiver_id'];

        $msgdata['from_user_name'] = $currentuser['sender_from_firstusername'];

        $msgdata['to_user_name'] = $currentuser['reciever_first_username'];

        $msgdata['profile_from_image'] = (!empty($currentuser['senderImage'])) ? base_url() . $currentuser['senderImage'] : base_url() . 'assets/img/user.png';

        $msgdata['profile_to_image'] = (!empty($currentuser['receiverImage'])) ? base_url() . $currentuser['receiverImage'] : base_url() . 'assets/img/user.png';

        $msgdata['content'] = ($message) ? $message : '';
      }


      $notifydata['include_player_ids'] = $currentuser['receiver_device_id'];

      $device_type = $currentuser['receiver_device_type'];

      $notifydata['message'] = $message;

      $notifydata['notifications_title'] = $msgdata['from_user_name'];

      $notifydata['additional_data'] = $msgdata;


      if ($device_type == 'Android') {
        sendFCMNotification($notifydata);
      }

      if ($device_type == 'IOS') {
        sendiosNotification($notifydata);
      }
    }
    //echo  $result;


  }

  public function get_user_id()
  {
    $where = array('username' => $_POST['to_username']);
    return $this->db->get_where('users', $where)->row()->id;
  }

  public function get_recieved_id($admin_role)
  {
    $where = array('username' => $_POST['to_username']);
    return $this->db->get_where('users', $where)->row()->id;
  }

  public function upload_files()
  {



    try {

      $user_id = $this->session->userdata('admin_id');

      $path = "uploads/msg_uploads/" . $user_id;
      if (!is_dir($path)) {
        mkdir($path, 0777, true);
      }

      $target_file = $path . basename($_FILES["userfile"]["name"]);
      $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

      if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
        $type = 'others';
      } else {
        $type = 'image';
      }

      if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
          echo  json_encode(array('error' => 'invalid extension! Allowed JPG, GIF, PNG or PDF'));
          exit;
      }


      $config['upload_path']   = './' . $path;
      $config['allowed_types'] = '*';
      $this->load->library('upload', $config);

      if ($this->upload->do_upload('userfile')) {




        $file_name = $this->upload->data('file_name');
        $data = array(
          'recieved_id' => $_POST['to_user_id'],
          'sent_id' => $user_id,
          'msg' => 'file',
          'file_name' => $file_name,
          'chatdate' => date('Y-m-d H:i:s'),
          'type' => $type,
          'read_status' => 0,
          'time_zone' => $this->session->userdata('time_zone'),
          'file_path' => $path,
          'is_admin' => 1
        );

        $result = $this->db->insert('chat', $data);
        $chat_id = $this->db->insert_id();
        $users = array($data['recieved_id'], $data['sent_id']);
        for ($i = 0; $i < 2; $i++) {
          $datas = array('chat_id' => $chat_id, 'can_view' => $users[$i]);
          $this->db->insert('chat_deleted_details', $datas);
        }

        echo  json_encode(array('img' => $path . '/' . $file_name, 'type' => $type, 'file_name' => $file_name));
      } else {
        echo  json_encode(array('error' => $this->upload->display_errors()));
      }
    } catch (Exception $e) {
      echo '<pre>';
      print_r($e);
      die();
    }
  }

  public function get_old_messages()
  {
    // echo '<pre>';
    // print_r($_POST);
    // exit;
    if ($_POST['total'] < 0) {
      return false;
    }
    $total = $_POST['total'];
    $total = $total * 5;
    $user_id = $this->session->userdata('admin_id');
    $selected_user = $_POST['selected_user_id'];
    $latest_chat = $this->chat->get_old_chat($selected_user, $user_id, $total);



    // echo $this->db->last_query();
    // exit;
    $html = '';
    if (isset($latest_chat) != '') {
      foreach ($latest_chat as $key => $currentuser) :

        $class_name = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';


        $img = (!empty($currentuser['senderImage'])) ? base_url() . $currentuser['senderImage'] : base_url() . 'assets/img/user.png';

        $time_zone = $this->session->userdata('time_zone');
        $from_timezone = $currentuser['time_zone'];
        $date_time = $currentuser['chatdate'];
        $date_time  = converToTz($date_time, $time_zone, $from_timezone);
        $time = date('d-m-Y h:i a', strtotime($date_time));


        if ($currentuser['type'] == 'image') {

          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-away">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p><img alt="" src="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" class="img-fluid"></p>
      <p>' . $currentuser['file_name'] . '</p>
      <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download>' . $this->language['lg_download'] . '</a>
  <ul class="chat-msg-info">
    <li>
      <div class="chat-time">
        <span>' . $time . '</span>
      </div>
    </li>
  </ul>
      </div>
      </div>
      </div>
      </li>';
        } else if ($currentuser['type'] == 'others') {

          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-away">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p><img alt="" src="' . base_url() . 'assets/img/download.png" class="img-responsive"></p>
      <p>' . $currentuser['file_name'] . '</p>
      <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download class="chat-time">' . $this->language['lg_download'] . '</a>
    <ul class="chat-msg-info">
      <li>
        <div class="chat-time">
          <span>' . $time . '</span>
        </div>
      </li>
    </ul>
      </div>
      </div>
      </div>
      </li>';
        } else {
          $html .= '<li class="media ' . $class_name . '">';
          if ($class_name == 'received') {
            $html .= '<div class="avatar  avatar avatar-away">
        <img src="' . $img . '" class="avatar-img rounded-circle">
        </div>';
          }
          $html .= '<div class="media-body">
      <div class="msg-box">
      <div>
      <p>
      ' . $currentuser['msg'] . '
      </p>
  <ul class="chat-msg-info">
    <li>
      <div class="chat-time">
        <span>' . $time . '</span>
      </div>
    </li>
  </ul>
      </div>
      </div>
      </div>
      </li>';
        }




      endforeach;
      $html .= '<div id="ajax"></div><input type="hidden"  id="hidden_id">';
    }

    echo $html;
  }

  public function get_message()
  {
    $dat['message'] = $this->check_new_message();
    $dat['other_message'] = $this->get_message_counts();
    $dat['status'] = true;
    echo json_encode($dat);
  }


  public function check_new_message()
  {
    $user_selected_id = $this->input->post('user_selected_id');
    $recieved_id = $this->session->userdata('admin_id');
    $where = array('recieved_id' => $recieved_id, 'read_status' => 0, 'is_admin' => 1, 'sent_id' => $user_selected_id);
    $result = $this->db->order_by('id', 'desc')->get_where('chat', $where)->result_array();

    $messages = array();
    if (!empty($result)) {
      foreach ($result as $r) {
        $sql = "SELECT a.id,a.profileimage as senderImage
      from users a  
     where a.id = " . $r['sent_id'];

        $data = $this->db->query($sql)->row_array();

        $img = (!empty($data['senderImage'])) ? base_url() . $data['senderImage'] : base_url() . 'assets/img/user.png';


        $msg['image'] =  $img;
        $msg['type'] = $r['type'];
        $msg['file_path'] = $r['file_path'];
        $msg['file_name'] = $r['file_name'];
        $msg['read_status'] = $r['read_status'];
        $msg['message'] = $r['msg'];
        $msg['time'] = date('d-m-Y h:i A', strtotime($r['chatdate']));
        $messages[] = $msg;
      }
    }

    $where = array('recieved_id' => $recieved_id, 'read_status' => 0, 'sent_id' => $user_selected_id);
    $this->db->update('chat', array('read_status' => 1), $where);

    return  json_encode($messages);
  }


  public function get_message_counts()
  {
    $user_selected_id = $this->input->post('user_selected_id');
    $where = array();
    if (!empty($_POST['selected_user_id'])) {
      $where += array('c.sent_id !=' => $user_selected_id);
    }
    $recieved_id = $this->session->userdata('user_id');
    $where += array('c.recieved_id' => $recieved_id, 'c.read_status' => 0);
    $data = $this->db
      ->select('a.username,COUNT(c.id) as count')
      ->join('users a ', 'a.id = c.sent_id', 'left')
      ->group_by('c.sent_id')
      ->get_where('chat c', $where)->result();
    return json_encode($data);
  }
}
