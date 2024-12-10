<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * @property object $db
 */
class Home_model extends CI_Model
{

  var $post ='posts p';  
  var $doctor ='users d';
  var $doctor_details ='users_details dd';
  var $category ='categories c';
  var $subcategory ='subcategories s';
  var $admin ='administrators a';
  var $comments ='comments c';
  var $replies ='replies r';
  var $tags='tags t';

	public function __construct()
	{
		parent::__construct();
	}



  public function get_blogs($page,$limit,$type)
  {
        $this->db->select('p.*,IF(p.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(p.post_by="Admin","Admin", CONCAT(d.first_name," ", d.last_name)) as name,d.username,c.category_name,s.subcategory_name');
        $this->db->from($this->post); 
        $this->db->join($this->doctor,'p.user_id = d.id','left'); 
        $this->db->join($this->category,'p.category = c.id','left');
        $this->db->join($this->subcategory,'p.subcategory = s.id','left');
        $this->db->join($this->tags,'p.id = t.post_id','left');
        $this->db->join($this->admin,'1 = a.id','left');
        $this->db->where('p.status','1'); 
        $this->db->where('p.is_verified','1');
        $this->db->where('p.is_viewed','1');
        $this->db->order_by('p.id','DESC');
        $this->db->group_by('p.id');

        if(!empty($_POST['category']))
        {
           $this->db->where('c.slug',$_POST['category']); 
        }

        if(!empty($_POST['tags']))
        {
           $this->db->where('t.slug',$_POST['tags']); 
        }

         if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          $this->db->like('p.title',$_POST['keywords'],'after');
          $this->db->or_like('c.category_name',$_POST['keywords'],'after');
          $this->db->or_like('s.subcategory_name',$_POST['keywords'],'after');
          $this->db->or_like('t.tag',$_POST['keywords'],'after');
          $this->db->group_end();
        }

        if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }

  }

  public function blog_details($title)
  {
        $this->db->select('p.*,IF(p.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(p.post_by="Admin","Admin", CONCAT(d.first_name," ", d.last_name)) as name,IF(p.post_by="Admin",a.biography, dd.biography) as about_author,d.username,c.category_name,s.subcategory_name');
        $this->db->from($this->post); 
        $this->db->join($this->doctor,'p.user_id = d.id','left'); 
        $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
        $this->db->join($this->category,'p.category = c.id','left');
        $this->db->join($this->subcategory,'p.subcategory = s.id','left');
        $this->db->join($this->admin,'1 = a.id','left');
        $this->db->where('p.status','1'); 
        $this->db->where('p.is_verified','1');
        $this->db->where('p.is_viewed','1');
        $this->db->where('p.slug',$title);
       return $this->db->get()->row_array();
       

  }

  public function add_comments($inputdata)
  {
       $this->db->insert('comments',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function add_reply($inputdata)
  {
       $this->db->insert('replies',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function get_comments($post_id,$page,$limit,$type)
  {
    $this->db->select('c.*,IF(c.role="3",a.profileimage, d.profileimage) as profileimage,IF(c.role="3","Admin", CONCAT(d.first_name," ", d.last_name)) as name,d.username');
        $this->db->from($this->comments); 
        $this->db->join($this->doctor,'c.user_id = d.id','left');
        $this->db->join($this->admin,'1 = a.id','left');
        $this->db->where('c.status','1'); 
        $this->db->where('c.post_id',$post_id);
        $this->db->order_by('c.id','DESC');
        if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }
  }

  public function get_replies($comment_id)
  {
    $this->db->select('r.*,IF(r.role="3",a.profileimage, d.profileimage) as profileimage,IF(r.role="3","Admin", CONCAT(d.first_name," ", d.last_name)) as name,d.username');
        $this->db->from($this->replies); 
        $this->db->join($this->doctor,'r.user_id = d.id','left');
        $this->db->join($this->admin,'1 = a.id','left');
        $this->db->where('r.status','1'); 
        $this->db->where('r.comment_id',$comment_id);
        $this->db->order_by('r.id','ASC');
       return $this->db->get()->result_array();
  }

  public function latest_posts()
  {
        $this->db->select('p.*');
        $this->db->from($this->post); 
        $this->db->where('p.status','1'); 
        $this->db->where('p.is_verified','1');
        $this->db->where('p.is_viewed','1');
        $this->db->order_by('p.id','DESC');
        $this->db->limit('10');
        return $this->db->get()->result_array();
      
  }

  public function categories()
  {
        $this->db->select('c.*,(SELECT COUNT(p.id) FROM posts AS p WHERE p.category=c.id AND p.status=1 AND p.is_verified=1 AND p.is_viewed=1) AS count');
        $this->db->from($this->category); 
        $this->db->where('c.status','1'); 
        $this->db->order_by('count','DESC');
        $this->db->limit('10');
        return $this->db->get()->result_array();
      
  }

  public function tags()
  {
        
        $this->db->select('t.*');
        $this->db->from($this->tags); 
        $this->db->join($this->post, 'p.id = t.post_id');
        $this->db->group_by('t.slug');
        $this->db->order_by('rand()');
        $this->db->where('p.status','1'); 
        $this->db->where('p.is_verified','1');
        $this->db->where('p.is_viewed','1');
        $this->db->limit(15);
         return $this->db->get()->result_array();
      
  }




  

 
}

