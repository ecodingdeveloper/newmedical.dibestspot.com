<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $language;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'language';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('language_model','language');
        

    }

    public function index()
  {

     if ($this->input->post('form_submit')) {

        $where = array();
        $where['tag'] = $this->input->post('tag');
        $where['language'] = $this->input->post('language');
        $where['language_value'] = $this->input->post('language_value');
        $this->db->where($where);
        $record = $this->db->count_all_results('language');
        if($record >= 1)
        {
             
           $this->session->set_flashdata('message','Already exists');
          redirect(base_url('admin/language'));

        }else{  

        $data = array(

        'language_value' => trim($this->input->post('language_value')),

        'language' => trim($this->input->post('language')),

        'tag' => trim($this->input->post('tag')),

        'status' => 2

        );

        $result = $this->db->insert('language',$data);

        

        }


        $this->session->set_flashdata('success_message','The Language has been added successfully...');
        redirect(base_url('admin/language'));


     }
      $this->data['page'] = 'index';
      $this->data['list'] = $this->language->languages_list();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }


	public function keywords()
	{
	    $this->data['page'] = 'keywords';
       $this->data['active_language'] = $this->language->active_language();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function language_list()
    {
        $lists = $this->language->language_list();
            $data = array();
            $no = $_POST['start'];
            $active_language = $this->language->active_language();
            foreach ($lists as $keyword) {
              $no++;
            $row    = array();
            $row[] = $no;
            $exist_key = array();
              if (!empty($active_language))
              {
                $l = 0;
                  foreach ($active_language as $lang)
                  {
                     $lg_language_name = $keyword['lang_key'];
                     $language_key = $lang['language_value'];
                     $key = $keyword['language'];
                     $value = ($language_key == $key)?$keyword['lang_value']:'';
                     $key = $keyword['language'];
                     $this->data['currenct_page_key_value'] = $this->language->currenct_page_key_value($lists);
                     $value = (!empty($this->data['currenct_page_key_value'][$lg_language_name][$language_key]))?$this->data['currenct_page_key_value'][$lg_language_name][$language_key]:'';
                     $row[] = $lg_language_name.'<input type="text" class="form-control" name="'.$lg_language_name.'['.$language_key.']" value="'.$value.'" onchange=update_language(\''.$lg_language_name.'\',\''.$language_key.'\')>
                       <input type="hidden" class="form-control" name="prev_'.$lg_language_name.'['.$language_key.']" value="'.$value.'">';

                     $l++;
                   }

                 }
                $data[] = $row;
          }

          $output = array(
                          "draw" => $_POST['draw'],
                          "recordsTotal" => $this->language->language_list_all(),
                          "recordsFiltered" => $this->language->language_list_filtered(),
                          "data" => $data,
                  );

          //output to json format
          echo json_encode($output);

    }

    public function add_keywords()
    {

       if ($this->input->post('form_submit')) {

            $data = array();
            $pdata = array();
            $multiple = $this->input->post('multiple_key');
            $multiple_keyword = explode('|',$multiple);
            $multiple_keyword = array_filter($multiple_keyword);

            if(!empty($multiple_keyword)) {
              foreach($multiple_keyword as $lang) {
                $lang = trim($lang);
                if($lang != null) {
              $lang_for_key = preg_replace("/[^ \w]+/", "", $lang);
              $count = strlen($lang_for_key);
              if($count > 15)
              {
                $lang_for_key = substr($lang_for_key, 0, 15);
              }
             
              $language = 'lg_'.str_replace(array(' ','!','&'),'_',strtolower($lang_for_key));
              $data['lang_key'] = $language;
              $data['lang_value'] = $pdata['lang_value'] = $lang;
              $data['language'] = $pdata['language'] = 'en';
              $this->db->where($pdata);
                $record = $this->db->count_all_results('language_management');
                if($record > 0)
                  {
                    $already_exits[] = $lang;
                  }else{
                $cdata['lang_key'] = $language;
                $cdata['language'] = 'en';
                $this->db->where("lang_key LIKE '".$language."%' AND language = 'en'");
                $chk_record = $this->db->count_all_results('language_management');
                if($chk_record > 0){
                  $data['lang_key'] = $language.$chk_record;
                }
                  $result = $this->db->insert('language_management', $data);
                  language_file_create(); // language folder create
                 }
              }

              }
            }
           if(!empty($already_exits))
           {
            $this->session->set_flashdata('success_message','Keywords added successfully, But some keywords already exist');
             redirect(base_url().'admin/language/keywords');
           }
           else
           {
             $this->session->set_flashdata('success_message','Keywords added successfully');
             redirect(base_url().'admin/language/keywords');
           }
      

       }
        $this->data['page'] = 'add_keywords';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    public function update_language()
    {
      $lang_key = $insert['lang_key'] = $this->input->post('lang_key');
      $lang = $insert['language'] = $this->input->post('lang');
      $data['lang_value'] = $insert['lang_value'] = $this->input->post('cur_val');
      $this->db->where('lang_key',$lang_key);
      $this->db->where('language',$lang);
      $this->db->from('language_management');
      $ext = $this->db->count_all_results();
      if($ext >0 ){
        if($lang == 'en')
        {
          if(!empty($data['lang_value']))
          {
            
            $check['lang_value'] = $data['lang_value'];
            $check['language'] = 'en';
            $this->db->where($check);
            $record = $this->db->count_all_results('language_management');
            if($record == 0)
            {
              $this->db->where('lang_key',$lang_key);
              $this->db->where('language',$lang);
              $result = $this->db->update('language_management',$data);
              language_file_create(); // language folder create
            }
            else {
              $result = 0;
            }
          }
          else {
            $result = 2;
          }
        }
        else{
          $this->db->where('lang_key',$lang_key);
          $this->db->where('language',$lang);
          $result = $this->db->update('language_management',$data);
          language_file_create(); // language folder create
        }
      }
      else {
        $result = $this->db->insert('language_management',$insert);
        language_file_create(); // language folder create
      }
      echo $result;
      die();
    }


     public function update_language_status()

  {

    $id = $this->input->post('id');

    $status = $this->input->post('update_language');

    if($status==2)
    {
      $this->db->where('id',$id);
      $this->db->where('default_language',1);
      $data=$this->db->get('language')->result_array();

      if(!empty($data))
      {
        echo "0";
      }
      else
      {
        $this->db->query(" UPDATE `language` SET `status` = ".$status." WHERE `id` = ".$id." ");
        echo "1";
      }

    }
    else
    {
      $this->db->query(" UPDATE `language` SET `status` = ".$status." WHERE `id` = ".$id." ");
      echo "1";
    }

    

  }


  public function update_language_default()

  {

     $id = $this->input->post('id');

        $this->db->where('id',$id);
      $this->db->where('status',1);
      $data=$this->db->get('language')->result_array();

      if(!empty($data))
      {
        $this->db->query("UPDATE language SET default_language = ''");
        $this->db->query(" UPDATE `language` SET `default_language` = 1 WHERE `id` = ".$id." ");
            echo "1";
      }
      else
      {
        echo "0";
      }

    

  }

  public function check_language()
  {
        $language = $this->input->post('language');     
        $result = $this->is_valid_language($language);
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
  }
  public function check_language_value()
  {
        $language_value = $this->input->post('language_value');     
        $result = $this->is_valid_language_value($language_value);
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
  }
  public function is_valid_language($language){

        $this->db->select('id,language');
        $this->db->from('language');
        $this->db->where('language', $language);
        $result = $this->db->get()->row_array();
        
       return $result;        
    }

    public function is_valid_language_value($language_value){

        $this->db->select('id,language_value');
        $this->db->from('language');
         $this->db->where('language_value', $language_value);
        $result = $this->db->get()->row_array();
        
       return $result;        
    }

    public function pages()
    {

      $this->data['list'] = $this->language->page_list();
      $this->data['page'] = 'pages';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    
    }

    public function add_page()

  {

      if($this->input->post()){
    
        $result = $this->language->add_page();

        if($result == true){

          $this->session->set_flashdata('success_message','The page has been added successfully...');
          redirect(base_url().'admin/language/pages');

        } else{

          $this->session->set_flashdata('error_message','Already exists');

        }
      
         redirect(base_url().'admin/language/add_page');

    }



        $this->data['page'] = 'add_page';

        $this->load->vars($this->data);

        $this->load->view($this->data['theme'].'/template');



  }



  public function app_keywords()
  {
      $this->data['page'] = 'app_keywords';
       $this->data['active_language'] = $this->language->active_language();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }


  public function add_app_keywords()
  {

       if($this->input->post()){
          $page_key = $this->input->post('page_key');
          
          $result = $this->language->add_app_keywords();

          if($result == true){

            $this->session->set_flashdata('success_message','The Keyword has been added successfully...');

          }elseif(is_array($result) && count($result)==0){

            $this->session->set_flashdata('success_message','The Keyword has been added successfully...');

          }elseif(is_array($result) && count($result)!= 0){

            $this->session->set_flashdata('error_message','Already exists'.implode(',',$result));

          } else{

            $this->session->set_flashdata('error_message','Already exists');

          }
        
             redirect(base_url().'admin/language/pages/'.$page_key.'');

        }
        $this->data['page'] = 'add_app_keywords';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
  }


  public function app_language_list()
    {
      $page_key = $this->input->post('page_key');
        $lists = $this->language->app_language_list($page_key);

         $data = array();
            $no = $_POST['start'];
            $active_language = $this->language->active_language();
            foreach ($lists as $keyword) {
             // $no++;
            $row    = array();
           // $row[] = $no;
               if (!empty($active_language))
                            {
                                      foreach ($active_language as $rows)
                                      { 

                                       $lg_language_name = $keyword['lang_key'];   
                                       $language_key = $rows['language_value']; 


                                      $key = $keyword['language'] ;
                                      $value = ($language_key == $key)?$keyword['lang_value']:'';
                                      $key = $keyword['language'];
                                      $currenct_page_key_value = $this->language->app_currenct_page_key_value($lists);

                                    
                                     
                                      $name =(!empty($currenct_page_key_value[$lg_language_name][$language_key]['name']))?$currenct_page_key_value[$lg_language_name][$language_key]['name']:'';
                                      $lang_key =(!empty($currenct_page_key_value[$lg_language_name][$language_key]['lang_key']))?$currenct_page_key_value[$lg_language_name][$language_key]['lang_key']:'';

                                    
                                      $type =$currenct_page_key_value[$lg_language_name]['en']['type'];
                                      

                                      //$readonly = ($language_key=='en')?'readonly':'';

                                      $readonly = '';
                                      

                           $row[] = '<input type="text" class="form-control" placeholder="Name" name="'.$lg_language_name.'['.$language_key.'][lang_value]" value="'.$name.'" '.$readonly.' >
                          <input type="text" class="form-control" value="'.$lang_key.'" readonly >
                          <input type="hidden" class="form-control" name="'.$lg_language_name.'['.$language_key.'][type]" value="'.$type.'" '.$readonly.' >';
                                      }

                      }

                      $data[] = $row;
                      }
          $output = array(
                          "draw" => $_POST['draw'],
                          "recordsTotal" => $this->language->app_language_list_all($page_key),
                          "recordsFiltered" => $this->language->app_language_list_filtered($page_key),
                          "data" => $data,
                  );

          //output to json format
          echo json_encode($output);

    }


    public function update_app_language()

    {
      
      $page_key ="";
      if ($this->input->post()) {

        $page_key = $this->input->post('page_key');

         
        $data = $this->input->post();

     
        

        foreach($data as $row => $object)

        {

          if (!empty($object)) {



            foreach ($object as $key => $value) {

                       



              $this->db->where('language', $key);
              $this->db->where('lang_key', $row);
              $this->db->where('type', $value['type']);
              $this->db->where('page_key', $page_key);

            $record = $this->db->count_all_results('app_language_management');



              if ($record==0) {
                 $array = array(
                          'language' =>$key,
                          'lang_key' =>$row,
                          'lang_value' =>$value['lang_value'],
                          'type'=>$value['type'],
                          'page_key'=>$page_key,
                        );

                $this->db->insert('app_language_management', $array);

              }else{

                 $this->db->where('language', $key);
                 $this->db->where('lang_key', $row);
                 $this->db->where('type', $value['type']);
                 $this->db->where('page_key', $page_key);


                 $array = array(
                          'lang_value' =>$value['lang_value'],
                          'type'=>$value['type'],
                          'page_key'=>$page_key,
                        );

                  $this->db->update('app_language_management', $array);

              }

            }



          }

        }

      
    }

      
    
      redirect(base_url().'admin/language/pages/'.$page_key.'');

    }



}
