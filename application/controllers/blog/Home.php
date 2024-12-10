<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $lang;
   public $input;
   public $db;
   public $home;
   public $language;

   public function __construct() {

        parent::__construct();

                
        $this->data['theme']     = 'blog';
        $this->data['module']    = 'home';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
        $lan=default_language();
        $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
        $this->data['language'] = $this->lang->load('content', $lang, true);
        $this->language = $this->lang->load('content', $lang, true);
       $this->load->model('blog/home_model','home');
        

    }


    public function index()
    {
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/blogtemplate');
    }

    public function get_blogs()
    {
       $response=array();
       $result=array();
        $page=$this->input->post('page');
        $limit=10;
        $response['count'] =$this->home->get_blogs($page,$limit,1);
        $blog_list = $this->home->get_blogs($page,$limit,2);

        if (!empty($blog_list)) {
          foreach ($blog_list as $rows) {
            $image_url=explode(',', $rows['upload_image_url']);
            $data['id']=$rows['id'];
            $data['preview']=($rows['post_by']=='Admin')?'javascript:void(0);':base_url().'doctor-preview/'.$rows['username'];
            $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
            $data['name']=($rows['post_by']=='Admin')?ucfirst($rows['name']):'Dr '.ucfirst($rows['name']);
            $data['post_image']=base_url().$image_url[0];
            $data['title']=$rows['title'];
            $data['slug']=$rows['slug'];
            $data['description']=character_limiter($rows['description'], 70, '...');
            $data['created_date']=date('d M Y',strtotime($rows['created_date']));
            $result[]=$data;
          }
        }
        $response['current_page_no']= $page;
        $response['total_page']= ceil($response['count']/$limit);
        $response['data']= $result;

     echo json_encode($response);
    }

    public function blog_details($title)
    {
      $this->data['page'] = 'blog_details';
      $this->data['posts']=$this->home->blog_details(urldecode($title));
      if(!empty($this->data['posts']))
      {
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/blogtemplate');
      }
      else
      {
        $this->session->set_flashdata('error_message','Oops something went wrong try valid credentials!');
         redirect(base_url().'blog');
      }
    }

    public function add_comments()
    {
        $inputdata=array();
        $response=array();
       if($this->session->userdata('admin_id') =='' && $this->session->userdata('user_id') ==''){
           $response['msg']=$this->language['lg_please_login_to1'];
           $response['status']=500;
        } 
        else
        {
          
          $inputdata['post_id']=$this->input->post('post_id');
          $inputdata['comments']=$this->input->post('comments');
          $inputdata['role']=$this->session->userdata('role');
          if(!empty($inputdata['role']))
          {
            $inputdata['user_id']=$this->session->userdata('user_id');
          }
          else
          {
            $inputdata['role']='3';//Admin
            $inputdata['user_id']=0;
          }
          $inputdata['created_date']=date('Y-m-d H:i:s');

          $result=$this->home->add_comments($inputdata);
          if($result==true)
          {   
               $response['msg']=$this->language['lg_comment_added_s'];
               $response['status']=200;              
          }
         else
          {
              $response['msg']=$this->language['lg_comment_added_f'];
              $response['status']=500; 
          } 
          
        }

         echo json_encode($response);
    }

    public function add_reply()
    {
        $inputdata=array();
        $response=array();
       if($this->session->userdata('admin_id') =='' && $this->session->userdata('user_id') ==''){
           $response['msg']=$this->language['lg_please_login_to2'];
           $response['status']=500;
        } 
        else
        {
          
          $inputdata['comment_id']=$this->input->post('comment_id');
          $inputdata['replies']=$this->input->post('reply');
          $inputdata['role']=$this->session->userdata('role');
          if(!empty($inputdata['role']))
          {
            $inputdata['user_id']=$this->session->userdata('user_id');
          }
          else
          {
            $inputdata['role']='3';//Admin
            $inputdata['user_id']=0;
          }
          $inputdata['created_date']=date('Y-m-d H:i:s');

          $result=$this->home->add_reply($inputdata);
          if($result==true)
          {   
               $response['msg']=$this->language['lg_reply_added_suc'];
               $response['status']=200;              
          }
         else
          {
              $response['msg']=$this->language['lg_reply_added_fai'];
              $response['status']=500; 
          } 
          
        }

         echo json_encode($response);
    }

    public function get_comments()
    {
      $post_id=$this->input->post('post_id');
      $page=$this->input->post('page');
      $limit=5;
      $response['count'] =$this->home->get_comments($post_id,$page,$limit,1);
      $data['comments']=$this->home->get_comments($post_id,$page,$limit,2);
      $data['language']=$this->language;
      $response['comments_list']= $this->load->view('blog/modules/home/comments_view',$data,TRUE);
      $response['comments_count']=count($data['comments']);
      $response['current_page_no']= $page;
      $response['total_page']= ceil($response['count']/$limit);
      echo json_encode($response);

    }

     public function get_replies()
    {
      $comment_id=$this->input->post('comment_id');
      $data['replies']=$this->home->get_replies($comment_id);
      $data['language']=$this->language;
      $response['replies_list']= $this->load->view('blog/modules/home/replies_view',$data,TRUE);
      echo json_encode($response);

    }

    public function delete_comment_reply()
    {
      $id=$this->input->post('id');
      $type=$this->input->post('type');

      if($type==1)
      {
        $this->db->where('id',$id);
        $this->db->delete('comments');

        $this->db->where('comment_id',$id);
        $this->db->delete('replies');
      }

      if($type==2)
      {
        $this->db->where('id',$id);
        $this->db->delete('replies');

      }

      echo true;

    }



  }