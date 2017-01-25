<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('MY_Data');
    }
	
	function get_customer($num,$offset, &$row_count)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT SQL_CALC_FOUND_ROWS c.user_id, c.*,(SELECT count(order_status) 
    				FROM `".$table["order_table"]."` WHERE ('Stop') IN (select o.order_status FROM `".$table["order_table"]."` as o 
					WHERE o.user_id = c.user_id)) as `order_status` , n.reply
					FROM `".$table["user_table"]."` as c
					LEFT OUTER JOIN fr_notification AS n ON n.`user_id` = c.`user_id`
					WHERE c.user_role = 'Customer' AND c.status != 'Banned' AND (SELECT count(order_status) 
					FROM `".$table["order_table"]."` WHERE ('Stop') IN (select o.order_status FROM `".$table["order_table"]."` as o 
					WHERE o.user_id = c.user_id)) = 0  Order By c.user_id DESC LIMIT ";				
			if($offset != "" || $offset != NULL)
				$sql .= $offset.",".$num; 
			else
				$sql .= $num; 
			$query = $this->db->query($sql);
			$row_count = $this->db->query("SELECT FOUND_ROWS() as Num_Rows;");
			
			$row_count = $row_count->row()->Num_Rows;
	
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $e)
		{
			//error code here
		}
	}
    
    function get_archievecustomer($num,$offset, &$row_count)
    {
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT SQL_CALC_FOUND_ROWS c.user_id, c.*,(SELECT count(order_status) 
					FROM `".$table["order_table"]."` WHERE ('Stop') IN (select o.order_status FROM `".$table["order_table"]."` as o 
					WHERE o.user_id = c.user_id)) as `order_status` , n.reply
					FROM `".$table["user_table"]."` as c
					LEFT OUTER JOIN fr_notification AS n ON n.`user_id` = c.`user_id`
					WHERE c.user_role = 'Customer' AND (c.status = 'Banned' OR (SELECT count(order_status) 
					FROM `".$table["order_table"]."` WHERE ('Stop') IN (select o.order_status FROM `".$table["order_table"]."` as o 
					WHERE o.user_id = c.user_id)) != 0 ) Order By c.user_id DESC LIMIT ";			
			if($offset != "" || $offset != NULL)
				$sql .= $offset.",".$num; 
			else
				$sql .= $num; 
			$query = $this->db->query($sql);
			$row_count = $this->db->query("SELECT FOUND_ROWS() as Num_Rows;");
			
			$row_count = $row_count->row()->Num_Rows;
	
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $e)
		{
			//error code here
		}
	}
	
	function search_customer($name,$email,$num,$offset, &$row_count)
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS c.user_id, c.*,(SELECT count(order_status) FROM `".$table["order_table"]."` WHERE ('Stop') IN (select o.order_status FROM `".$table["order_table"]."` as o WHERE o.user_id = c.user_id)) as `order_status`, n.reply
FROM `".$table["user_table"]."` as c 
LEFT OUTER JOIN fr_notification AS n ON n.`user_id` = c.`user_id`
WHERE user_role = 'Customer' ";
			if(($email != "" || $email != NULL) && ($name != "" || $name != NULL))
			{
				$sql .= "AND `name` LIKE '%".$name."%' AND `email` = '".$email."' ";
			}
			else if($email != "" || $email != NULL)
			{
				$sql .= "AND `email` = '".$email."' ";
			}
			else if($name != "" || $name != NULL)
			{
				$sql .= "AND `name` LIKE '%".$name."%' ";
			}
			else
			{
				return false;
			}
			$sql .= "LIMIT ";
			if($offset != "" || $offset != NULL)
				$sql .= $offset.",".$num; 
			else
				$sql .= $num;
			$query = $this->db->query($sql);
			$row_count = $this->db->query("SELECT FOUND_ROWS() as Num_Rows;");
			
			$row_count = $row_count->row()->Num_Rows;
	
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $e)
		{
			//error code here
		}
	}
	
	function activate_customer($id)
	{
		try{
			$table = $this->my_data->database_table();
			$query = array(
						'activation_date' => date('Y-m-d H:i:s')
			);
			$this->db->where('user_id', $id);
			echo $this->db->update($table["user_table"], $query);
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function ban_customer($id)
	{
		try{
			$table = $this->my_data->database_table();
			$query = array(
						'status' => 'Banned'
			);
			$this->db->where('user_id', $id);
			echo $this->db->update($table["user_table"], $query);
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function unban_customer($id)
	{
		try{
			$table = $this->my_data->database_table();
			$query = array(
						'status' => 'Pending'
			);
			$this->db->where('user_id', $id);
			echo $this->db->update($table["user_table"], $query);
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function get_email($id)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT  c.*,n.message FROM `".$table["user_table"]."` as c INNER JOIN `".$table["notification_table"]."` AS n ON n.user_id =c.user_id WHERE user_role = 'Customer' AND c.user_id = ".$id;			
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $e)
		{
			//error code here
		}
	}
	
	function update_noti_status($id)
	{
		try{
			$query = array(
						'reply' => 'Yes'
			);
			$this->db->where('user_id', $id);
			echo $this->db->update('fr_notification', $query);
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function get_user($id)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT u.name,u.email, ord.week_num, ord.week_status, ord.order_status,IF(ord.order_status='Stop',(SELECT DATE_FORMAT(fn.datetime,'%d-%m-%Y') FROM `fr_notification` as fn where user_id=".$id." ),'') `order_stop_date`,(SELECT COUNT(*) FROM `".$table["order_table"]."` AS o WHERE o.user_id = ".$id.") AS total_order,(SELECT COUNT(*) FROM `".$table["delivery_log_table"]."` WHERE order_id IN (SELECT o.order_id FROM `".$table["order_table"]."` AS o WHERE o.user_id = ".$id." ) ) AS total_deliver FROM `".$table["user_table"]."` AS u left join `".$table["order_table"]."` as ord on u.user_id = ord.user_id WHERE u.user_id =".$id;			
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false; 
		}catch(Exception $e)
		{
			//error code here
		}
	}
	
	function pdf_customerdetails($id)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT o.order_ref, o.item_subscription, a.address_id,u.name as user_name,u.email,a.address,a.ph_no,a.mobile,a.contact_person,tw.name as township_name,o.other_info, d.`delivery_instruction`,
    				(SELECT GROUP_CONCAT((SELECT b.name FROM `fr_box` AS b WHERE b.box_id= od.box_id),' x ',od.qty) FROM `fr_order_details` AS od WHERE od.order_id=o.order_id)
					 AS box_type,(SELECT GROUP_CONCAT((SELECT i.name FROM `fr_additional_items` AS i WHERE i.item_id= ad.item_id),' x ',ad.item_qty) 
					 FROM `fr_additional_order` AS ad WHERE ad.order_id=o.order_id) AS add_type
					FROM `fr_order` AS o 
					INNER JOIN `fr_user` AS u ON u.user_id = o.user_id
					INNER JOIN `fr_addresses` AS a ON a.user_id = o.user_id
					INNER JOIN `fr_township_detail` AS t ON a.township_id = t.tspdetail_id
					INNER JOIN `fr_township` AS tw ON tw.township_id = t.township_id
                    INNER JOIN `fr_delivery_info` AS d ON d.order_id = o.order_id
					WHERE o.order_id =".$id." ORDER BY a.address_id DESC";		
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $e)
		{
			//error code here
		}	
	}
	
}