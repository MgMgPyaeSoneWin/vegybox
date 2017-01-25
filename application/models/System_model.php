<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class System_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function get_box_limit()
	{
		$sql = "SELECT GROUP_CONCAT(IF(meta_key = 'max_box_limit', meta_value, NULL)) AS 'max_box', 
		GROUP_CONCAT(IF(meta_key = 'current_box_num', meta_value, NULL)) AS 'current_box'		
		FROM fr_system ";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return $query->row();
		else
			return false;
	}
	
	function get_setting()
	{
		$sql = "SELECT 
		GROUP_CONCAT(IF(meta_key = 'box_price', meta_value, NULL)) AS 'box_price',
		GROUP_CONCAT(IF(meta_key = 'smtp_host', meta_value, NULL)) AS 'smtp_host', 
		GROUP_CONCAT(IF(meta_key = 'smtp_username', meta_value, NULL)) AS 'smtp_username', 
		GROUP_CONCAT(IF(meta_key = 'smtp_password', meta_value, NULL)) AS 'smtp_password', 
		GROUP_CONCAT(IF(meta_key = 'smtp_port', meta_value, NULL)) AS 'smtp_port', 
		GROUP_CONCAT(IF(meta_key = 'autoreply_email', meta_value, NULL)) AS 'autoreply_email', 
		GROUP_CONCAT(IF(meta_key = 'system_title', meta_value, NULL)) AS 'system_title',
		GROUP_CONCAT(IF(meta_key = 'template', meta_value, NULL)) AS 'template'
		FROM fr_system ";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return $query->row();
		else
			return false;
	}
	
	function profile_setting()
	{
		$sql = "SELECT 
		GROUP_CONCAT(IF(meta_key = 'address', meta_value, NULL)) AS 'address',
		GROUP_CONCAT(IF(meta_key = 'phone', meta_value, NULL)) AS 'phone',
		GROUP_CONCAT(IF(meta_key = 'email', meta_value, NULL)) AS 'email',
		GROUP_CONCAT(IF(meta_key = 'website', meta_value, NULL)) AS 'website',
		GROUP_CONCAT(IF(meta_key = 'facebook', meta_value, NULL)) AS 'facebook',
		GROUP_CONCAT(IF(meta_key = 'company_name', meta_value, NULL)) AS 'company_name'
		FROM fr_system ";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return $query->row();
		else
			return false;
	}
	
	function send_notification($userid, $msg)
	{
		$data = array(
			  'user_id'     => $userid, 
			  'message'  => $msg,			  
			  'datetime'  => date("Y-m-d H:i:s")
		);
		
		return $this->db->insert('fr_notification', $data);
	}
	
	
	/*
	|
	| Daily Reminder
	|
	*/
	
	function get_dueOrder_userDetails()
	{
		$sql = "SELECT o.order_id, o.user_id, u.`email`, u.`name` FROM fr_order o LEFT OUTER JOIN `fr_user` u ON u.`user_id` = o.`user_id` WHERE (o.`week_num` - o.week_status) = 1 AND o.order_status = 'On-going' AND o.reminded = 'No'";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return $query->result();
		else
			return false;
	}
	
	function update_reminder($order_id)
	{
		$sql = "UPDATE `fr_order` SET `reminded` = 'Yes' WHERE `order_id` = '".$order_id."' ";
		return $this->db->simple_query($sql);
	}
	
}

/* End of file System_model.php */
/* Location: ./application/controllers/System_model.php */
