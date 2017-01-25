<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class user_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function check_old_password($id, $password)
	{
		$sql = "SELECT * FROM fr_user WHERE user_id='".$id."' AND password='".$password."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return true;
		else
			return false;
	}
	
	function update_new_password($id, $password)
	{
		$data = array(  'password' => $password);
			
		$this->db->where('user_id',$id);
		return $this->db->update('fr_user', $data); 
	}
	
	function check_user($email,$password,$tablename)
	{
		$sql = "SELECT * FROM ".$tablename." WHERE email = '".$email."'";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			if($query->row()->password == MD5(MD5($password)))
				return $query->row();
			else
				return false;
		}
		else
			return false;
	}
	
	function check_activation($email)
	{
		$sql = "SELECT activation_date FROM fr_user WHERE email = '".$email."'";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			if($query->row()->activation_date == '')
				return false;
			else
				return true;
		}
		else
			return false;
	}
	
	function update_password($email, $new_password)
	{
		$sql = "SELECT user_id, password FROM `fr_user` WHERE email = '".$email."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{					
			$result = $this->db->query("UPDATE `fr_user` SET password = MD5(MD5('".$new_password."')) WHERE user_id = '". $query->row()->user_id ."' ");
			if($result)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	function check_email_duplication($email)
	{
		$sql = "SELECT email FROM `fr_user` WHERE email = '".$email."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return false;
		else
			return true;
	}
	
	function register_user($info)
	{
		$data = array(
		   'name' => $info['name'],
		   'email' => $info['email'] ,
		   'password' => MD5(MD5($info['password'])) ,
		   'activation_code' => $info['activation_code'] ,
		   'status' => $info['status'],
		   'user_role' => 'Customer'
		);
		
		$this->db->set('reg_date', 'NOW()', FALSE);
		return $this->db->insert('fr_user', $data); 

	}
	
	function activate_user($code)
	{
		$sql = "SELECT * FROM `fr_user` WHERE activation_code = '".$code."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			if($query->row()->activation_date == '')
				$this->db->query("UPDATE `fr_user` SET activation_date = NOW() WHERE user_id = '". $query->row()->user_id ."' ");
			return true;
		}
		else
			return false;
	}
	
	function update_contact($data)
	{
		// update delivery Info
		$insert = array(
						  'name' => $data['name']
					);
		$this->db->where('user_id', $data['userid']);
		return $this->db->update('fr_user', $insert);	
	}
	
	function update_address($data)
	{
		$this->db->trans_begin();
		
		$add = array(
		  'user_id'			=> $data['userid'],
		  'contact_person'  => $data['contact_person'], 
		  'ph_no'			=> $data['phone'],
		  'mobile'  		=> $data['mobile'],
		  'address' 		=> $data['address'],
		  'township_id' 	=> $data['tspdetail_id'],
		  'lat' 			=> $data['lat'],
		  'long' 			=> $data['lon']
		);
		
		if($data['edited_add'] != '')
		{
			$address_id = $data['edited_add'];
			
			$this->db->where('address_id', $address_id);
			$this->db->update('fr_addresses', $add);	
		}
		else
		{
			$this->db->insert('fr_addresses', $add);
			$address_id = $this->db->insert_id();
		}
		
		
		
		if($data['edited_add'] != '')
		{	
			// update delivery Info
			$delivery_data = array(
							  'delivery_instruction' => $data['instruction']
						);
			$this->db->where('address_id', $address_id);
			$this->db->update('fr_delivery_info', $delivery_data);	
			
			// check if address is being used in on-going order
			$check = $this->db->query("SELECT GROUP_CONCAT(d.order_id) AS orders FROM `fr_delivery_info` d LEFT OUTER JOIN `fr_order`o ON o.`order_id` = d.`order_id` WHERE d.`address_id` = ".$address_id." AND o.`order_status` != 'Stop'");
			
			if($check->row()->orders != "")
			{
				$delivery = $this->db->query("SELECT day_id FROM `fr_township_detail` WHERE tspdetail_id = ".$data['tspdetail_id'] );
				
				// if delivery_day is changed
				if($data['tspdetail_id'] != $delivery->row()->day_id) 
				{
					//TODO: check box limit					
					switch ($delivery->row()->day_id) {
						case "2":
							$coming_delivery_day = 'monday';
							break;
						case "3":
							$coming_delivery_day = 'tuesday';
							break;
						case "4":
							$coming_delivery_day = 'wednesday';
							break;
						case "5":
							$coming_delivery_day = 'thursday';
							break;
						case "6":
							$coming_delivery_day = 'friday';
							break;
						case "7":
							$coming_delivery_day = 'saturday';
							break;
						case "1":
							$coming_delivery_day = 'sunday';
							break;
					}
		
					
					$date = date('Y-m-d', strtotime('next '.$coming_delivery_day));
				
					$date_diff = strtotime($date) - strtotime(date('d-m-Y'));
					$days_left = floor($date_diff/(60*60*24));
					
					// set delivery date to next week if chosen delivery day is not before 3 days of delivery
					if($days_left <= 3)
					{
						$date = date('Y-m-d', strtotime($date. ' + 7 days'));
					}
					
					// if the address is being used in multiple orders
					if (strpos($check->row()->orders, ',') !== false) 
					{
						// update all the orders delivery days
						$ord = explode(",", $check->row()->orders);
						for($i=0; $i < count($ord); $i++)
						{
							$days_left = $this->db->query("SELECT COUNT(*) as day_num FROM `fr_delivery_log` WHERE `order_id` = '".$ord[$i]."' AND delivery_date IS NULL");
						
							$this->db->simple_query("DELETE FROM `fr_delivery_log` WHERE `order_id` = '".$ord[$i]."' AND delivery_date IS NULL");
							for ($i=0; $i < $days_left->row()->day_num ; $i++) 
							{
								if($i == 0)
									$next_date = $date;
								else
									$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 
								
								$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$ord[$i].", '".$next_date."'); ");
								$date = $next_date;
							}
						}
					}
					else
					{					 
						$days_left = $this->db->query("SELECT COUNT(*) as day_num FROM `fr_delivery_log` WHERE `order_id` = '".$check->row()->orders."' AND delivery_date IS NULL");
						
						$this->db->simple_query("DELETE FROM `fr_delivery_log` WHERE `order_id` = '".$check->row()->orders."' AND delivery_date IS NULL");
						for ($i=0; $i < $days_left->row()->day_num ; $i++) 
						{
							if($i == 0)
								$next_date = $date;
							else
								$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 
							
							$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$check->row()->orders.", '".$next_date."'); ");
							$date = $next_date;
						}
					}
				}
			}
					
		}	
		
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}	
		
	}
}

/* End of file User_model.php*/
/* Location: ./application/controllers/User_model.php */