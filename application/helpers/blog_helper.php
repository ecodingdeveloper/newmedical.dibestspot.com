<?php 

   if (!function_exists('str_slug')) {
    function str_slug($string_name, $separator = 'dash', $lowercase = TRUE)
    {
        $rand_no = 200;
       
    $username_parts = array_filter(explode(" ", mb_strtolower($string_name,'UTF-8'))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?mb_substr($username_parts[0], 0,8,'utf-8'):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?mb_substr($username_parts[1], 0,5,'utf-8'):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";
        $username = $part1. $part2. $part3; //str_shuffle to randomly shuffle all characters
        return $username;
    }
}

if (!function_exists('auth_check')) {
    function auth_check()
    {
        $CI =& get_instance();
        if($CI->session->userdata('admin_id') =='' && $CI->session->userdata('user_id') ==''){
            return false;
        }
        return true;
    }
}

if(!function_exists('is_admin')){
    function is_admin(){   
        $ci = &get_instance();      
        if($ci->session->userdata('admin_id') !=''){
            return true;
        }else{
            return false;
        }

    }
}

if (!function_exists('view_reply')) {
    function view_reply($comment_id)
    {
        $CI =& get_instance();
        $CI->load->model('blog/home_model','home');
        $data['replies']=$CI->home->get_replies($comment_id);
        return $CI->load->view('blog/modules/home/replies_view',$data,TRUE);
    }
}

if (!function_exists('latest_posts')) {
    function latest_posts()
    {
        $CI =& get_instance();
        $CI->load->model('blog/home_model','home');
        return $CI->home->latest_posts();
        
    }
}

if (!function_exists('categories')) {
    function categories()
    {
        $CI =& get_instance();
        $CI->load->model('blog/home_model','home');
        return $CI->home->categories();
        
    }
}

if (!function_exists('tags')) {
    function tags()
    {
        $CI =& get_instance();
        $CI->load->model('blog/home_model','home');
        return $CI->home->tags();
        
    }
}

