<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $country;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'country';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('country_model','country');

    }






    public function country(){

      if ($this->input->post('form_submit')) {

        $where = array();
        $where['country'] = $this->input->post('country');
        
        $this->db->where($where);
        $record = $this->db->count_all_results('country');
        if($record >= 1)
        {
             
           $this->session->set_flashdata('message','Already exists');
           
           redirect(base_url('admin/country/country'));

        }else{  

        $data = array(

        'sortname' => trim($this->input->post('sortname')),

        'country' => trim($this->input->post('country')),

        'phonecode' => trim($this->input->post('phone_code')),

        
        );

        $result = $this->db->insert('country',$data);

        

        }


        $this->session->set_flashdata('success_message','The country has been added successfully...');
        redirect(base_url('admin/country/country'));


     }


      $this->db->select('*');
      $this->db->from('country');
      $this->data['list'] = $this->db->get()->result_array();
      $this->data['page'] = 'country';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');


    }

    public function state(){


       if ($this->input->post('form_submit')) {

       


     }

      $this->data['page'] = 'state';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');


    }


    public function state_insert(){



       $where = array();
        $where['countryid'] = $this->input->post('country');
        $where['statename'] = $this->input->post('state');
        
        $this->db->where($where);
        $record = $this->db->count_all_results('state');
        if($record >= 1)
        {
             
           $datas['result']='exe';
           $datas['status']='State already exits!';
        }else{  

        $data = array(

        'statename' => trim($this->input->post('state')),

        'countryid' => trim($this->input->post('country')),
        'state_code' => trim($this->input->post('state_code')), //State Code

        );

        $result = $this->db->insert('state',$data);

        
                        $datas['result']='true';
                        $datas['status']='State added successfully';
                     

        

        }
        echo json_encode($datas);

        
    }

    public function city(){

      if ($this->input->post('form_submit')) {

        $where = array();
        
        $where['stateid'] = $this->input->post('state');
        $where['city'] = $this->input->post('city');
        
        $this->db->where($where);
        $record = $this->db->count_all_results('city');
        if($record >= 1)
        {
             
           $this->session->set_flashdata('message','Already exists');
           
           redirect(base_url('admin/country/city'));

        }else{  

        $data = array(

        'stateid' => trim($this->input->post('state')),

        'city' => trim($this->input->post('city')),

       

        
        );

        $result = $this->db->insert('city',$data);

        

        }


        $this->session->set_flashdata('success_message','The city has been added successfully...');
        redirect(base_url('admin/country/city'));


     }

      $this->data['page'] = 'city';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');


    }

     public function city_insert(){



        $where = array();
        $where['stateid'] = $this->input->post('state');
        $where['city'] = $this->input->post('city');
        
        $this->db->where($where);
        $record = $this->db->count_all_results('city');
        if($record >= 1)
        {
             
           $datas['result']='exe';
           $datas['status']='City already exits!';
        }else{  

        $data = array(

        'stateid' => trim($this->input->post('state')),

        'city' => trim($this->input->post('city')),

        );

        $result = $this->db->insert('city',$data);

        
                        $datas['result']='true';
                        $datas['status']='City added successfully';
                     

        

        }
        echo json_encode($datas);

        
    }

     public function check_sortname()
      {
        $sortname = $this->input->post('sortname');     
        $this->db->select('countryid');
        $this->db->from('country');
        $this->db->where('sortname', $sortname);
        $result = $this->db->get()->row_array();
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
      }

      public function check_country()
      {
        $country = $this->input->post('country');     
        $this->db->select('countryid');
        $this->db->from('country');
        $this->db->where('country', $country);
        $result = $this->db->get()->row_array();
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
      }

      public function check_phonecode()
      {
        $phonecode = $this->input->post('phonecode');     
        $this->db->select('countryid');
        $this->db->from('country');
        $this->db->where('phonecode', $phonecode);
        $result = $this->db->get()->row_array();
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
      }


      public function country_edit(){

        
       $id=$this->input->post('id');
      $this->db->from('country');
      $this->db->where('countryid',$id);
      $query = $this->db->get()->row();

       echo json_encode($query);
      }


      public function state_edit(){

        
       $id=$this->input->post('id');
      $this->db->from('state');
      $this->db->where('id',$id);
      $query = $this->db->get()->row();

       echo json_encode($query);
      }

      public function city_edit(){

        
       $id=$this->input->post('id');
      $this->db->from('city');
      $this->db->where('id',$id);
      $query = $this->db->get()->row();

       echo json_encode($query);
      }


      public function country_update(){

        $id=$this->input->post('id');
        
        
        $this->db->where('country',$this->input->post('ecountry'));
        $this->db->where('countryid !=',$id);
        $record = $this->db->count_all_results('country');
        
        if($record >= 1)
        {
             
           $datas['result']='exe';
           $datas['status']='Country already exits!';

        }else{  

        $data = array(

        'sortname' => trim($this->input->post('esortname')),

        'country' => trim($this->input->post('ecountry')),

        'phonecode' => trim($this->input->post('ephonecode')),

        
        );

        $this->db->where('countryid',$id);
        $this->db->update('country',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

                    if($result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Country update successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='Country update failed!';
                     }



        

        }

        echo json_encode($datas);


      }


      public function state_update(){

        $id=$this->input->post('id');
        
        
        $this->db->where('statename',$this->input->post('estate'));
        $this->db->where('id !=',$id);
        $record = $this->db->count_all_results('state');
        
        if($record >= 1)
        {
             
           $datas['result']='exe';
           $datas['status']='State already exits!';

        }else{  

        $data = array(

        'statename' => trim($this->input->post('estate')),
        'state_code' => trim($this->input->post('statecode')), //State Code
        
        
        );

        $this->db->where('id',$id);
        $this->db->update('state',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

                    if($result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='State updated successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='State update failed!';
                     }



        

        }

        echo json_encode($datas);


      }

         public function city_update(){

        $id=$this->input->post('id');
        
        
        $this->db->where('city',$this->input->post('estate'));
        $this->db->where('id !=',$id);
        $record = $this->db->count_all_results('city');
        
        if($record >= 1)
        {
             
           $datas['result']='exe';
           $datas['status']='City already exits!';

        }else{  

        $data = array(

        'city' => trim($this->input->post('ecity')),

        
        
        );

        $this->db->where('id',$id);
        $this->db->update('city',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

                    if($result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='City update successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='City update failed!';
                     }



        

        }

        echo json_encode($datas);


      }


      public function country_delete($id)
    {
        
        $this->db->where('countryid',$id);
        $record = $this->db->count_all_results('state');

        if($record >= 1){
            $datas['result']='exe';
            $datas['status']='This country existed in states table';

        }else{

          $this->db->where('countryid',$id);
          $this->db->delete('country');
          $check=($this->db->affected_rows()!= 1)? false:true;

                    if($check==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Country delete successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='Country deleted failed!';
                     }


        }

        echo json_encode($datas);
    }

    public function state_delete($id)
    {
        
        $this->db->where('stateid',$id);
        $record = $this->db->count_all_results('city');

        if($record >= 1){
            $datas['result']='exe';
            $datas['status']='This State existed in city table';

        }else{

          $this->db->where('id',$id);
          $this->db->delete('state');
          $check=($this->db->affected_rows()!= 1)? false:true;

                    if($check==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='State deleted successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='State deleted failed!';
                     }


        }

        echo json_encode($datas);
    }

     public function city_delete($id)
    {
        
       

          $this->db->where('id',$id);
          $this->db->delete('city');
          $check=($this->db->affected_rows()!= 1)? false:true;

                    if($check==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='City deleted successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='City deleted failed!';
                     }


        

        echo json_encode($datas);
    }


  public function state_list()
   {

      $role_info=$this->session->userdata('role_details');
      $country_id=$this->input->post('country_id');

      $list = $this->country->get_state_datatables($country_id);
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $state) {

        
        $no++;
        $row = array();
        $row[] = $no;

        
        $row[] =$state["statename"];
        $edit="";
        $delete="";       
      
         
           $edit='<a class="btn btn-sm bg-success-light" onclick="edit_state('.$state['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>';
           
           $delete='<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_state('.$state['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>';
         

      $row[] = '<div class="actions">'.$edit.' '.$delete.'</div>';

       
        
        $data[] = $row;
      }



      $output = array(
             "draw" => $_POST['draw'],
              "recordsTotal" => $this->country->state_count_all($country_id),
              "recordsFiltered" => $this->country->state_count_filtered($country_id),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

public function city_list()
   {

      $role_info=$this->session->userdata('role_details');
      $country_id=$this->input->post('country_id');
      $state_id=$this->input->post('state_id');


      $list = $this->country->get_city_datatables($state_id);
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $state) {

        
        $no++;
        $row = array();
        $row[] = $no;

        
        $row[] =$state["city"];
        $edit="";
        $delete="";       
      
         
           $edit='<a class="btn btn-sm bg-success-light" onclick="edit_city('.$state['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>';
                 
           $delete='<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_city('.$state['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>';
               

      $row[] = '<div class="actions">'.$edit.' '.$delete.'</div>';

       
        
        $data[] = $row;
      }



      $output = array(
             "draw" => $_POST['draw'],
              "recordsTotal" => $this->country->city_count_all($state_id),
              "recordsFiltered" => $this->country->city_count_filtered($state_id),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }



  /*
 * State lazy load  to dataTable
 */
  public function  state_lazyload() {
    $table = 'state';
    $primaryKey = 'id';
    $country=$this->input->post('country_id');
    $columns = array(
      array( 'db' => 'id',  'dt' => 0),
      array( 'db' => 'statename', 'dt' => 1 ),
      array( 'db' => 'id',     'dt' => 2),


    );
    
    $result= json_encode($this->stSimple( $_POST, $table, $primaryKey, $columns,$country ));
    
    echo $result;
    
  }

  /*
 * State lazy load  to dataTable
 */
  public function  city_lazyload() {
    $table = 'city';
    $primaryKey = 'id';
    $country=$this->input->post('country_id');
    $state=$this->input->post('state_id');
    $columns = array(
      array( 'db' => 'id',  'dt' => 0),
      array( 'db' => 'city', 'dt' => 1 ),
      array( 'db' => 'id',    'dt' => 2),


    );
    
    $result= json_encode($this->citySimple( $_POST, $table, $primaryKey, $columns,$country,$state ));
    
    echo $result;
    
  }


  public function stSimple( $request,$table, $primaryKey, $columns ,$country)
  {
    $bindings = array();
    // Build the SQL query string from the request
    $limit =$this->limit( $request, $columns );
    $ordercolumn='id';
    $order =$this->order( $request, $columns,$ordercolumn );
    $where =$this->filter( $request, $columns,$bindings,$country);
    $main_query = $this->db->query(
      "SELECT `".implode("`, `", $this->pluck($columns, 'db'))."`
      FROM `$table`

      $where
      $order
      $limit"
    );
    $data=$main_query->result();
    
    // Data set length after filtering
    $resFilterLengthQuery = $this->db->query(
      "SELECT {$primaryKey}
      FROM   `$table`
$where
      "
    );
    $resFilterLength=$resFilterLengthQuery->num_rows();
    $recordsFiltered = $resFilterLength;
    // Total data set length
    $resTotalLengthQuery = $this->db->query(
      "SELECT {$primaryKey}
      FROM   `$table`
$where
      "
    );
    
    $resTotalLength=$resTotalLengthQuery->num_rows();
    
    $recordsTotal = $resTotalLength;
    /*
     * Output
     */
    return array(
      "draw"            =>   $this->input->get('draw') ,

      "recordsTotal"    => intval( $recordsTotal ),
      "recordsFiltered" => intval( $recordsFiltered ),
      "data"            => $this->data_output( $columns, $data )
    );
  }

   public function citySimple( $request,$table, $primaryKey, $columns ,$country,$state)
  {
    $bindings = array();
    // Build the SQL query string from the request
    $limit =$this->limit( $request, $columns );
    $ordercolumn='id';
    $order =$this->order( $request, $columns,$ordercolumn );
    $where =$this->filtercity( $request, $columns,$bindings,$country,$state);
    $main_query = $this->db->query(
      "SELECT `".implode("`, `", $this->pluck($columns, 'db'))."`
      FROM `$table`

      $where
      $order
      $limit"
    );
    $data=$main_query->result();
    
    // Data set length after filtering
    $resFilterLengthQuery = $this->db->query(
      "SELECT {$primaryKey}
      FROM   `$table`
$where
      "
    );
    $resFilterLength=$resFilterLengthQuery->num_rows();
    $recordsFiltered = $resFilterLength;
    // Total data set length
    $resTotalLengthQuery = $this->db->query(
      "SELECT {$primaryKey}
      FROM   `$table`
$where
      "
    );
    
    $resTotalLength=$resTotalLengthQuery->num_rows();
    
    $recordsTotal = $resTotalLength;
    /*
     * Output
     */
    return array(
      "draw"            =>   $this->input->get('draw') ,

      "recordsTotal"    => intval( $recordsTotal ),
      "recordsFiltered" => intval( $recordsFiltered ),
      "data"            => $this->data_output( $columns, $data )
    );
  }

  public  function data_output ( $columns, $data )
  {
      $out = array();
      
        foreach ($data as $key => $value) {
       
          $row = array();
          echo $key;
         
foreach ($columns as $key1 => $value1) {
  echo $key1;
  
              $column = $value1[$key1];
              // Is there a formatter?
              if ( isset( $column['formatter'] ) ) {
                  $row[ $column['dt'] ] = $column['formatter']( $data[$key][ $column['db'] ], $data[$key] );
              }
              else {
                  $row[$column['dt']]  = $value[$key]-> $value1[$key1]['db'];
                  
              }
            }
            $out[] = $row;
          }
          
          
        
      
      return $out;
    }
  
  
  public function pluck ( $a, $prop )
  {
      $out = array();
      for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
          $out[] = $a[$i][$prop];
      }
      return $out;
  }
  /**
   * Paging
   *
   * Construct the LIMIT clause for server-side processing SQL query
   *
   *  @param  array $request Data sent to server by DataTables
   *  @param  array $columns Column information array
   *  @return string SQL limit clause
   */
  public function limit ( $request, $columns )
  {
      $limit = '';
      if ( isset($request['start']) && $request['length'] != -1 ) {
          $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
      }
      return $limit;
  }
  /**
   * Ordering
   *
   * Construct the ORDER BY clause for server-side processing SQL query
   *
   *  @param  array $request Data sent to server by DataTables
   *  @param  array $columns Column information array
   *  @return string SQL order by clause
   */
  public function order ( $request, $columns,$primarykey )
  {
      $order = 'ORDER BY ' .$primarykey. ' asc';
      if ( isset($request['order']) && count($request['order']) ) {
          $orderBy = array();
          $dtColumns = $this->pluck( $columns, 'dt' );
          for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
              // Convert the column index into the column data property
              $columnIdx = intval($request['order'][$i]['column']);
              $requestColumn = $request['columns'][$columnIdx];
              $columnIdx = array_search( $requestColumn['data'], $dtColumns );
              $column = $columns[ $columnIdx ];
              if ( $requestColumn['orderable'] == 'true' ) {
                  $dir = $request['order'][$i]['dir'] === 'asc' ?
                  'ASC' :
                  'DESC';
                  $orderBy[] = '`'.$column['db'].'` '.$dir;
              }
          }
          if ( count( $orderBy ) ) {
              $order = 'ORDER BY '.implode(', ', $orderBy);
          }
      }
      return $order;
  }


  /**
   * Searching / Filtering
   *
   * Construct the WHERE clause for server-side processing SQL query.
   *
   * NOTE this does not match the built-in DataTables filtering which does it
   * word by word on any field. It's possible to do here performance on large
   * databases would be very poor
   *
   *  @param  array $request Data sent to server by DataTables
   *  @param  array $columns Column information array
   *  @param  array $bindings Array of values for PDO bindings, used in the
   *    sql_exec() function
   *  @return string SQL where clause
   */
  public function filter ( $request, $columns, &$bindings,$country )
  {
      $globalSearch = array();
      $columnSearch = array();
      $dtColumns =$this->pluck( $columns, 'dt' );
      if ( isset($request['search']) && $request['search']['value'] != '' ) {
          $str = $request['search']['value'];
          for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
              $requestColumn = $request['columns'][$i];
              $columnIdx = array_search( $requestColumn['data'], $dtColumns );
              $column = $columns[ $columnIdx ];
              if ( $requestColumn['searchable'] == 'true' ) {
                /** @var object $this */
                  $binding = $this->bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                  $globalSearch[] = "`".$column['db']."` LIKE ". "'%".$str."%'";
              }
          }
      }
      // Individual column filtering
      if ( isset( $request['columns'] ) ) {
          for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
              $requestColumn = $request['columns'][$i];
              $columnIdx = array_search( $requestColumn['data'], $dtColumns );
              $column = $columns[ $columnIdx ];
              $str = $requestColumn['search']['value'];
              if ( $requestColumn['searchable'] == 'true' &&
                  $str != '' ) {
                      $binding = $this->bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                      $columnSearch[] = "`".$column['db']."` LIKE ". "'%".$str."%'";
                  }
          }
      }
      // Combine the filters into a single string
      $where = '';
      if ( count( $globalSearch ) ) {
          $where = '('.implode(' OR ', $globalSearch).')';
      }
      if ( count( $columnSearch ) ) {
          $where = $where === '' ?
          implode(' AND ', $columnSearch) :
          $where .' AND '. implode(' AND ', $columnSearch);
      }
      $scl_search="";
      if($country != 0){
          
          $scl_search=' AND state.countryid ='.$country;
      }
      if ( $where !== '' ) {
          $where = 'WHERE '.$where.$scl_search;
      }else{
          if($country != 0){
          $where = 'WHERE state.countryid ='.$country;
          }
      }
      return $where;
  }

  /**
   * Searching / Filtering
   *
   * Construct the WHERE clause for server-side processing SQL query.
   *
   * NOTE this does not match the built-in DataTables filtering which does it
   * word by word on any field. It's possible to do here performance on large
   * databases would be very poor
   *
   *  @param  array $request Data sent to server by DataTables
   *  @param  array $columns Column information array
   *  @param  array $bindings Array of values for PDO bindings, used in the
   *    sql_exec() function
   *  @return string SQL where clause
   */
  public function filtercity ( $request, $columns, &$bindings,$country ,$state)
  {
      $globalSearch = array();
      $columnSearch = array();
      /** @var object $this */
      $dtColumns =$this->pluck( $columns, 'dt' );
      if ( isset($request['search']) && $request['search']['value'] != '' ) {
          $str = $request['search']['value'];
          for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
              $requestColumn = $request['columns'][$i];
              $columnIdx = array_search( $requestColumn['data'], $dtColumns );
              $column = $columns[ $columnIdx ];
              if ( $requestColumn['searchable'] == 'true' ) {

                  $binding = $this->bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                  $globalSearch[] = "`".$column['db']."` LIKE ". "'%".$str."%'";
              }
          }
      }
      // Individual column filtering
      if ( isset( $request['columns'] ) ) {
          for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
              $requestColumn = $request['columns'][$i];
              $columnIdx = array_search( $requestColumn['data'], $dtColumns );
              $column = $columns[ $columnIdx ];
              $str = $requestColumn['search']['value'];
              if ( $requestColumn['searchable'] == 'true' &&
                  $str != '' ) {
                      $binding = $this->bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                      $columnSearch[] = "`".$column['db']."` LIKE ". "'%".$str."%'";
                  }
          }
      }
      // Combine the filters into a single string
      $where = '';
      if ( count( $globalSearch ) ) {
          $where = '('.implode(' OR ', $globalSearch).')';
      }
      if ( count( $columnSearch ) ) {
          $where = $where === '' ?
          implode(' AND ', $columnSearch) :
          $where .' AND '. implode(' AND ', $columnSearch);
      }
      $scl_search="";
      if($country != 0){
          
          $scl_search=' AND city.stateid ='.$state;
      }
      if ( $where !== '' ) {
          $where = 'WHERE '.$where.$scl_search;
      }else{
          if($country != 0){
          $where = 'WHERE city.stateid ='.$state;
          }
      }
      return $where;
  }




}
