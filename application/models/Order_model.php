<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property string $order
 * @property string $product
 * @property string $ship
 */
#[AllowDynamicProperties]
class Order_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	    $this->order = 'orders';
	    $this->product = 'products';
	    $this->ship = 'shipping_details';
	}

	// public function get_order($where=array()){

	//     $this->db->select('o.*,p.product_image,p.product_name,c.country as countryname,s.statename,ci.city as cityname');
	//     $this->db->from($this->order.' o');
	//     $this->db->join($this->product.' p', 'p.id = o.product_id');
	//     $this->db->join('country c','o.country = c.countryid','left');
    //     $this->db->join('state s','o.state = s.id','left');
    //     $this->db->join('city ci','o.city = ci.id','left');
	//     $this->db->where($where);
	//     $this->db->order_by('o.id','desc');
	//     return $this->db->get()->result_array();

	// }

	public function get_order($where=array()){

	    $this->db->select('o.*,SUM(o.quantity) as quantity,SUM(o.subtotal) as subtotal ,,us.pharmacy_name as pharmacy_name,p.upload_image_url as product_image,p.name as product_name');
	    $this->db->from($this->order.' o');
		$this->db->join('order_user_details as od','od.order_user_details_id = o.user_order_id','left');

	    $this->db->join($this->product.' p', 'p.id = o.product_id');
		$this->db->join('users as us','us.id = o.pharmacy_id','left');
		$this->db->join('users_details as ud','ud.user_id = o.pharmacy_id','left');
	    $this->db->where($where);
		$this->db->group_by('o.order_id');             

	    $this->db->order_by('o.id','desc');
	    return  $this->db->get()->result_array();
	}

	public function get_order_details($where=array()){

	    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,o.quantity as qty,o.payment_type,o.status,o.product_name,o.order_id,o.subtotal  subtotal ,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,dc.country as countryname,ds.statename as statename,dci.city as cityname,p.upload_image_url as product_image,p.name as product_name');
    $this->db->from('orders as o');
	$this->db->join($this->product.' p', 'p.id = o.product_id');

    $this->db->join('order_user_details as od','od.order_user_details_id = o.user_order_id','left');
    $this->db->join('users as us','us.id = o.pharmacy_id','left');
    $this->db->join('country dc','od.country = dc.countryid','left');
    $this->db->join('state ds','od.state = ds.id','left');
    $this->db->join('city dci','od.city = dci.id','left');

	    $this->db->where($where);

	    $this->db->order_by('o.id','desc');
	    return  $this->db->get()->result_array();
	}
		


}