<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class order_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function get_extra_items($num,  &$offset, &$row_count)
	{
		again:
 		$strQuery = "SELECT SQL_CALC_FOUND_ROWS item_id, `name`, `type`, description, GROUP_CONCAT(net_weight) AS net_weight, GROUP_CONCAT(price) AS price, image, GROUP_CONCAT(item_id) AS ids, COUNT(parent) as number FROM fr_additional_items WHERE status='enabled' GROUP BY parent ORDER BY`type` LIMIT ";				
		

		if($offset != 0)
			$strQuery .= $offset.",".$num; 
		else
			$strQuery .= $num; 

		$query = $this->db->query($strQuery);
		
		$row_count = $this->db->query("SELECT FOUND_ROWS() as Num_Rows;");
		
		$row_count = $row_count->row()->Num_Rows;
		
		if($query->num_rows() == 0 && $row_count > 0)
		{
			$offset = $offset - $num;
			goto again;
		}
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function get_item_price($item_id)
	{
		$sql = "SELECT price FROM fr_additional_items WHERE item_id='".$item_id."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->price;
		}
		else
			return false;
	}
	
	function get_boxes()
	{

		$sql = "SELECT * FROM fr_box WHERE status = 'enabled' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function get_user_address($id)
	{
		$sql = "SELECT a.*, t.name AS `township_name`, d.name AS delivery_day
FROM `fr_addresses` a 
LEFT OUTER JOIN `fr_township_detail` td ON a.`township_id` = td.`tspdetail_id`
LEFT OUTER JOIN `fr_township` t ON t.`township_id` = td.`township_id`
LEFT OUTER JOIN `fr_delivery_day` d ON d.`day_id` = td.`day_id`
WHERE a.user_id = '".$id."'  AND d.`delivery` = 'YES' GROUP BY address_id";

    	$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			/* 
				SY : 28/11/16 
				Modify : Update the delivery day changes before returning the address
			*/

			foreach ($query->result() as $row)
			{
				// check if delivery day for current township of the address is still an active delivery day
				//echo "SELECT township_id, `status` FROM `fr_township_detail` WHERE tspdetail_id = '".$row->township_id."'";
				$query2 = $this->db->query("SELECT township_id, `status` FROM `fr_township_detail` WHERE tspdetail_id = '".$row->township_id."'");
			    if($query2->num_rows > 0)
			    {	//echo $query2->row()->township_id;
			    	if($query2->row()->status == 'NO')
			    	{
			    		//echo '<br> do update to '.$query->row()->address_id.' <br>';
			    		$address_to_update = $row->address_id;
			    		//echo "SELECT tspdetail_id FROM fr_township_detail WHERE `status` = 'YES' AND township_id = '".$query2->row()->township_id."' LIMIT 1";
			    		$query3 = $this->db->query("SELECT tspdetail_id FROM fr_township_detail WHERE `status` = 'YES' AND township_id = '".$query2->row()->township_id."' LIMIT 1");

			    		if($query3->num_rows > 0)
			    		{
			    			//echo "UPDATE `fr_addresses` SET `township_id` = '".$query3->row()->tspdetail_id."' WHERE `address_id` = '".$query->row()->address_id."'";
			    			$query4 = $this->db->query("UPDATE `fr_addresses` SET `township_id` = '".$query3->row()->tspdetail_id."' WHERE `address_id` = '".$address_to_update."'");
			    		}
			    	}
			    }
			} 
			return $query->result();
		}
		else
			return false;
	}
	
	function get_selected_address($address_id)
	{
		$sql = "SELECT a.*, td.`township_id` AS tsp_id, t.`name` AS township, d.delivery_instruction, td.`day_id` FROM fr_addresses a
LEFT OUTER JOIN fr_township t ON a.`township_id` = t.`township_id`
LEFT OUTER JOIN `fr_delivery_info` d ON d.address_id = a.address_id 
LEFT OUTER JOIN `fr_township_detail` td ON a.`township_id` = td.`tspdetail_id`
LEFT OUTER JOIN `fr_delivery_day` dd ON dd.`day_id` = td.`day_id`
WHERE a.address_id = '".$address_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
			return false;
	}
	
	function get_townships()
	{
		$sql = "SELECT * FROM fr_township ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function get_delivery_days($tsp_id)
	{
		$sql = "SELECT d.day_id, t.name, td.`tspdetail_id`, d.name AS delivery_day  FROM fr_township_detail td 
LEFT OUTER JOIN fr_delivery_day d ON d.day_id = td.day_id
LEFT OUTER JOIN fr_township t ON t.township_id = td.township_id
WHERE td.township_id = '".$tsp_id."'  AND td.`status` = 'YES'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function get_order_delivery_day($order_id)
	{
		$sql = "SELECT td.day_id FROM fr_order o 
LEFT OUTER JOIN `fr_delivery_info` d ON d.order_id = o.order_id
LEFT OUTER JOIN `fr_addresses` a ON a.`address_id` = d.address_id
LEFT OUTER JOIN `fr_township_detail` td ON td.tspdetail_id = a.township_id WHERE o.order_id = '".$order_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->day_id;
		}
		else
			return false;
	}
	
	function get_ordered_boxes_count($order_id)
	{
		$sql = "SELECT SUM(qty) AS box_num FROM `fr_order_details` WHERE order_id = '".$order_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->box_num;
		}
		else
			return false;
	}
	
	function get_pickup_address()
	{
		$sql = "SELECT GROUP_CONCAT(IF(meta_key = 'drop_off_point', meta_value, NULL)) AS 'drop_off_point', GROUP_CONCAT(IF(meta_key = 'pick_up_address', meta_value, NULL)) AS 'pick_up_address' FROM fr_system ";

		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
			return $query->row();
		else
			return false;
	}

	
	function check_box_limit($delivery_day)
	{
		$sql = "SELECT IFNULL(SUM(od.`qty`), 0) AS current_box
FROM fr_order o
LEFT OUTER JOIN `fr_order_details` od ON od.`order_id` = o.`order_id`
LEFT OUTER JOIN fr_delivery_info d ON d.`order_id` = o.`order_id`
LEFT OUTER JOIN `fr_addresses` a ON a.`address_id` = d.address_id
LEFT OUTER JOIN `fr_township_detail` td ON td.tspdetail_id = a.township_id
WHERE td.`day_id` = '".$delivery_day."' AND o.`order_status` = 'On-going' AND o.week_num > o.week_status ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->current_box;
		}
		else
			return false;
	}
	
	function check_weekDay($order_id)
	{
		$sql = "SELECT DAYNAME(order_date) as week_day FROM fr_delivery_log WHERE order_id = '".$order_id."' AND delivery_date IS NULL ORDER BY order_date ASC LIMIT 1"; 
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->week_day;
		}
		else
			return false;
	}
	
	function get_next_delivery($order_id)
	{
		$sql = "SELECT order_date as week_day FROM fr_delivery_log WHERE order_id = '".$order_id."' AND delivery_date IS NULL ORDER BY order_date ASC LIMIT 1";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->week_day;
		}
		else
			return false;
	}
	
	function insert_order($data)
	{
		try 
		{		
            $this->db->simple_query('SET time_zone = "+06:30"');
            
			//SY: 22/8/2016
			$next_delivery_date = '';

			// generate orderRef
			$orderRef = $this->generate_orderRef();		
			
			$this->db->trans_begin();
		
			// Insert order Info
			$order_db_data = array(
				  'order_ref'	=> $orderRef,
				  'user_id'     => $data['userdata']['id'], 
				  'subscription'=> ($data['subscription'] == '' ? 'Yes' : ucfirst($data['subscription'])),
				  'order_date'  => date("Y-m-d H:i:s"),
				  'week_num'  => $data['week_status'],
				  'week_status'  => 0,
				  'additional_item_status' => (count($data['items']) > 0 ? 'Yes' : 'No' ),
				  'item_subscription' => ucfirst($data['item_subscription']),
				  'other_info' => $data['info'],
				  'subtotal' => $data['subtotal']
			);
			
			$this->db->insert('fr_order', $order_db_data);
			$order_id = $this->db->insert_id();	
			
			//insert delivery log
			switch ($data['delivery_day']) {
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
			
			$queryString='';
			if($data['week_status'] > 0)
			{
				for ($i=0; $i < $data['week_status']; $i++) 
				{
					if($i == 0)
					{
						// SY: 22/8/16
						$next_delivery_date = $date;
						$next_date = $date;
					}	
					else
					{
						$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 					
					}
						

					//$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$order_id.", '".$next_date."'); ");
					/*if($data['week_status'] == '1' && $queryString == '')
						$queryString .= "(".$order_id.", '".$next_date."')";*/
					
					if($data['week_status'] && $queryString == '')
						$queryString .= "(".$order_id.", '".$next_date."')";
					else 
						$queryString .= ",(".$order_id.", '".$next_date."')";
					
					$date = $next_date;

				}
				$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES ".$queryString."; "); 
			}	
			
			
			// Insert Order Details	
			if( ! empty($data['boxes']))
			{
				$queryString2 = '';
				foreach ($data['boxes'] as $b_id => $b_qty) 
				{
					if($queryString2 == '')
						$queryString2 .= "(".$order_id.", '".$b_id."', '".$b_qty."')";
					else 
						$queryString2 .= ",(".$order_id.", '".$b_id."', '".$b_qty."')";

					/*$order_detail_data = array(
						  'order_id' => $order_id,
						  'box_id'   => $b_id,
						  'qty'   => $b_qty		   
					);
					$this->db->insert('fr_order_details', $order_detail_data);*/
				}
				$this->db->simple_query("INSERT INTO `fr_order_details` (`order_id`, `box_id`, `qty`) VALUES ".$queryString2."; "); 
			}
			
					
			// Insert additional items
			if(! empty($data['items']))
			{
				$queryString3 = '';
				foreach ($data['items'] as $id => $qty) 
				{
					if($queryString3 == '')
						$queryString3 .= "(".$order_id.", '".$id."', '".$qty."')";
					else 
						$queryString3 .= ",(".$order_id.", '".$id."', '".$qty."')";

					/*$items_data = array(
						  'order_id' => $order_id,
						  'item_id'  => $id, 
						  'item_qty' => $qty
					);
					$this->db->insert('fr_additional_order', $items_data);*/			
				}
				$this->db->simple_query("INSERT INTO `fr_additional_order` (`order_id`, `item_id`, `item_qty`) VALUES ".$queryString3."; "); 
			}
			
			if($data['selected_address'] == 'old')
			{
				$address_id = $data['address_id'];
				//$delivery_day = $this->get_address_delivery_day($address_id);
			}
			else
			{
				// insert address into db			
				//$delivery_day = $data['delivery_day'];			
				$address_data = array(
					  'user_id'			=> $data['userdata']['id'],
					  'contact_person'  => $data['contact_person'], 
					  'ph_no'			=> $data['phone'],
					  'mobile'  		=> $data['mobile'],
					  'address' 		=> $data['address'],
					  'township_id' 	=> $data['tspdetail_id'],
					  'lat' 			=> $data['lat'],
					  'long' 			=> $data['lon']
				);
				
				$this->db->insert('fr_addresses', $address_data);
				$address_id = $this->db->insert_id();
			}
			
			// Insert delivery Info
			$delivery_data = array(
								  'order_id'	=> $order_id,
								  'address_id'  => $address_id, 
								  'delivery_instruction' => $data['instruction'],
								//  'delivery_day'	=> ($delivery_day == '' ? 'Sat' : $delivery_day),
								  'delivery_status'	=> 'Pending'
							);
				
			$this->db->insert('fr_delivery_info', $delivery_data);	
			
			// update user status
			if($data['userdata']['status'] == 'New')
			{
				if($data['subscription'] == 'YES')
				{
					$status = 'Old';
				}
				else
				{
					$status = 'Pending';
				}
				
				$this->update_user_status($status, $data['userdata']['id'], $data['userdata']['email']);
			}
			else if($data['userdata']['status'] == 'Pending')
			{
				
				$status = 'Old';
				$this->update_user_status($status, $data['userdata']['id'], $data['userdata']['email']);
			}
			
			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return false;
			}
			else
			{
				$this->db->trans_commit();
				// SY: 22/8/16
				$res = array('status' => true, 'next_del' => $next_delivery_date);
				return $res;
			}		
		} 
		catch(Exception $e) 
		{
		  echo $e->getMessage();  // formatted nicely or a generic message or something.
		}

	}
	
	function edit_order($data)
	{
        $this->db->simple_query('SET time_zone = "+06:30"');
        
		$this->db->trans_begin();
		// SY: 22/8/16
		$next_delivery_date = '';
		
		$old_week_num =  $this->db->query("SELECT week_num FROM fr_order WHERE order_id = '".$data['order_id']."'");
		
		// Update order Info
		$order_db_data = array(
			  'edited_status'	=> 'Yes',
			  'week_num'  => $data['week_status'],
			  'subscription'=> ($data['subscription'] == '' ? 'Yes' : ucfirst($data['subscription'])),
			  'additional_item_status' => (count($data['items']) > 0 ? 'Yes' : 'No' ),
			  'item_subscription' => ucfirst($data['item_subscription']),
			  'other_info' => $data['info'],
			  'subtotal' => $data['subtotal']
		);
		$this->db->where('order_id', $data['order_id']);
		$this->db->update('fr_order', $order_db_data);
		
		//insert delivery log
		switch ($data['delivery_day']) {
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
		
		$old_delivery_day = $this->db->query("SELECT td.day_id FROM `fr_addresses` a LEFT OUTER JOIN `fr_delivery_info` d ON d.address_id =  a.address_id LEFT OUTER JOIN `fr_township_detail` td ON td.tspdetail_id = a.township_id WHERE d.order_id = '".$data['order_id']."' ");
		
		//if both delivery day and number of subscription week is changed
		if($data['delivery_day'] != $old_delivery_day->row()->day_id && $old_week_num->row()->week_num != $data['week_status'])
		{ 
			$days_delivered = $this->db->query("SELECT COUNT(*) as days FROM `fr_delivery_log` WHERE `order_id` = '".$data['order_id']."' AND delivery_date IS NOT NULL");
			$this->db->simple_query("DELETE FROM `fr_delivery_log` WHERE `order_id` = '".$data['order_id']."' AND delivery_date IS NULL");
			
			$days = $data['week_status'] - $days_delivered->row()->days; 

			$queryString1 = '';
			for ($i=0; $i < $days; $i++) 
			{
				if($i == 0)
				{
					// SY: 22/8/16
					$next_delivery_date = $date;
					$next_date = $date;
				}	
				else
					$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 
				
				//$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$data['order_id'].", '".$next_date."'); ");
				/*if($days == '1' && $queryString1 == '')
					$queryString1 .= "(".$data['order_id'].", '".$next_date."')";*/
				
				if($queryString1 == '')
					$queryString1 .= "(".$data['order_id'].", '".$next_date."')";
				else 
					$queryString1 .= ",(".$data['order_id'].", '".$next_date."')";

				$date = $next_date;
			}
			$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES ".$queryString1."; "); 
			
		}
		else if($data['delivery_day'] != $old_delivery_day->row()->day_id) // if delivery_day is changed
		{ 
			$days_left = $this->db->query("SELECT COUNT(*) as day_num FROM `fr_delivery_log` WHERE `order_id` = '".$data['order_id']."' AND delivery_date IS NULL");
			
			$this->db->simple_query("DELETE FROM `fr_delivery_log` WHERE `order_id` = '".$data['order_id']."' AND delivery_date IS NULL");

			$queryString2 = '';
			for ($i=0; $i < $days_left->row()->day_num ; $i++) 
			{
				if($i == 0)
				{
					// SY: 22/8/16
					$next_delivery_date = $date;
					$next_date = $date;
				}
				else
					$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 
				
				//$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$data['order_id'].", '".$next_date."'); ");
				if($days_left->row()->day_num == '1' && $queryString2 == '')
					$queryString2 .= "(".$data['order_id'].", '".$next_date."')";
				
				if($days_left->row()->day_num !== '1' && $queryString2 == '')
					$queryString2 .= "(".$data['order_id'].", '".$next_date."')";
				else 
					$queryString2 .= ",(".$data['order_id'].", '".$next_date."')";

				$date = $next_date;
			}
			$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES ".$queryString2."; "); 
		}
		else if($old_week_num->row()->week_num != $data['week_status']) // if number of subscription week is changed
		{
			if($old_week_num->row()->week_num < $data['week_status'])
			{
				$added_days = $data['week_status'] - $old_week_num->row()->week_num;
				$last_day = $this->db->query("SELECT order_date FROM fr_delivery_log WHERE order_id = '".$data['order_id']."' AND delivery_date IS NULL ORDER BY order_date DESC LIMIT 1");
				
				if($last_day->num_rows > 0)
					$last_day = $last_day->row()->order_date;
				else
					$last_day = $date;
					
				$queryString3 = '';
				for ($i=0; $i < $added_days; $i++) 
				{ 
					$next_date = date('Y-m-d', strtotime($last_day. ' + 7 days')); 
					// SY: 22/8/16
					$next_delivery_date = $next_date;

					//$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$data['order_id'].", '".$next_date."'); ");
					/*if($added_days == '1' && $queryString3 == '')
						$queryString3 .= "(".$data['order_id'].", '".$next_date."')";*/
					
					if($queryString3 == '')
						$queryString3 .= "(".$data['order_id'].", '".$next_date."')";
					else 
						$queryString3 .= ",(".$data['order_id'].", '".$next_date."')";

					$last_day = $next_date;
				}
				$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES ".$queryString3."; "); 			
			}
			else
			{
				$sub_days = $old_week_num->row()->week_num - $data['week_status']; 
				$this->db->simple_query("DELETE FROM fr_delivery_log WHERE order_id = '".$data['order_id']."'  ORDER BY order_date DESC LIMIT ".$sub_days.""); 
			}
		}
	
		// Delete all the previous order details
		$this->db->simple_query("DELETE FROM `fr_order_details` WHERE `order_id` = '".$data['order_id']."' ");
		
		// Insert Updated Order Details	
		if(count($data['boxes']) > 0)
		{
			$queryString4 = '';
			foreach ($data['boxes'] as $b_id => $b_qty) 
			{
				/*$order_detail_data = array(
					  'order_id' => $data['order_id'],
					  'box_id'   => $b_id,
					  'qty'   => $b_qty		   
				);
				$this->db->insert('fr_order_details', $order_detail_data);*/
				if($queryString4 == '')
					$queryString4 .= "(".$data['order_id'].", '".$b_id."', '".$b_qty."')";
				else 
					$queryString4 .= ",(".$data['order_id'].", '".$b_id."', '".$b_qty."')";
			}
			$this->db->simple_query("INSERT INTO `fr_order_details` (`order_id`, `box_id`, `qty`) VALUES ".$queryString4."; ");
		}
		
		// Delete all the previous item orders
		$this->db->simple_query("DELETE FROM `fr_additional_order` WHERE `order_id` = '".$data['order_id']."' ");
		
		// Insert updated additional items 
	
		if(count($data['items']) > 0)
		{
			$queryString5 = '';
			foreach ($data['items'] as $id => $qty) 
			{
				/*$items_data = array(
					  'order_id' => $data['order_id'],
					  'item_id'  => $id, 
					  'item_qty' => $qty
				);
				$this->db->insert('fr_additional_order', $items_data);*/			
				if($queryString5 == '')
					$queryString5 .= "(".$data['order_id'].", '".$id."', '".$qty."')";
				else 
					$queryString5 .= ",(".$data['order_id'].", '".$id."', '".$qty."')";
			}
			$this->db->simple_query("INSERT INTO `fr_additional_order` (`order_id`, `item_id`, `item_qty`) VALUES ".$queryString5."; ");
		}
		
		// Check if user edited the old address
		if($data['selected_address'] == 'old' && $data['edited_add'] !== '')
		{
			// update address 		
			$address_id = $data['edited_add'];	
			//$delivery_day = $data['delivery_day'];			
			$address_data = array(
				  'contact_person'  => $data['contact_person'], 
				  'ph_no'			=> $data['phone'],
				  'mobile'  		=> $data['mobile'],
				  'address' 		=> $data['address'],
				  'township_id' 	=> $data['tspdetail_id'],
				  'lat' 			=> $data['lat'],
				  'long' 			=> $data['lon']
			);
			
			$this->db->where('address_id', $address_id);
			$this->db->update('fr_addresses', $address_data);			
		}
		else if($data['selected_address'] == 'old' )
		{
			$address_id = $data['address_id'];
			//$delivery_day = $this->get_address_delivery_day($address_id);
		}
		else
		{
			// insert address into db			
			//$delivery_day = $data['delivery_day'];			
			$address_data = array(
				  'user_id'			=> $data['userdata']['id'],
				  'contact_person'  => $data['contact_person'], 
				  'ph_no'			=> $data['phone'],
				  'mobile'  		=> $data['mobile'],
				  'address' 		=> $data['address'],
				  'township_id' 	=> $data['tspdetail_id'],
				  'lat' 			=> $data['lat'],
				  'long' 			=> $data['lon']
			);
			
			$this->db->insert('fr_addresses', $address_data);
			$address_id = $this->db->insert_id();
		}
		
		// Insert delivery Info
		$delivery_data = array(
							  'address_id'  => $address_id, 
							  'delivery_instruction' => $data['instruction'],
							  //'delivery_day'	=> ($delivery_day == '' ? 'Sat' : $delivery_day),
							  'delivery_status'	=> 'Pending'
						);
		
		$this->db->where('order_id', $data['order_id']);
		$this->db->update('fr_delivery_info', $delivery_data);	

		// update user status
		if($data['userdata']['status'] == 'New')
		{
			if($data['subscription'] == 'YES')
			{
				$status = 'Old';
			}
			else
			{
				$status = 'Pending';
			}
			
			$this->update_user_status($status, $data['userdata']['id'], $data['userdata']['email']);
		}
		else if($data['userdata']['status'] == 'Pending')
		{
			
			$status = 'Old';
			$this->update_user_status($status, $data['userdata']['id'], $data['userdata']['email']);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			// SY: 22/8/16
			$res = array('status' => true, 'next_del' => $next_delivery_date);
			return $res;
		}		

	}
	
	function update_user_status($status, $user_id, $email)
	{
		$query = $this->db->query("UPDATE `fr_user` SET `status` = '".$status."' WHERE `user_id` = '".$user_id."';");
		
		$new_sess = array(
					   'id'  => $user_id,
					   'email'  => $email,
					   'status' => $status
					);
		
		$this->session->set_userdata('userdata',$new_sess);
	}
	
	function generate_orderRef()
	{
		// get previous order ref 
		$query = $this->db->query("SELECT SUBSTRING(order_ref, 5) AS order_ref FROM `fr_order` ORDER BY order_id DESC LIMIT 1");
		

		if($query->num_rows > 0)
		{
			$code =  (int)($query->row()->order_ref)+1;	
			if($code >=0 && $code <= 9){
				$ref = '00000'.$code;
			}elseif($code >= 10 && $code <= 99){
				$ref = '0000'.$code;
			}elseif($code >= 100 && $code <= 999){
				$ref = '000'.$code;
			}elseif($code >= 1000 && $code <= 9999){
				$ref = '00'.$code;	
			}elseif($code >= 10000 && $code <= 99999){
				$ref = '0'.$code;		
			}else{
				$ref = $code;
			}
			return 'ORD-'.$ref;		
		}
		else
		{
			return 'ORD-000001';
		}
	}
	
	function get_address_delivery_day($address_id)
	{
		$sql = "SELECT td.day_id as delivery_day FROM `fr_addresses` a 
LEFT OUTER JOIN `fr_township_detail` td ON td.tspdetail_id = a.township_id
WHERE a.`address_id` = '".$address_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->delivery_day;
		}
		else
			return false;
	}
	
	function get_tsp_delivery_day($tsp_id)
	{
		$sql = "SELECT day_id as delivery_day FROM `fr_township_detail` WHERE `tspdetail_id` = '".$tsp_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->delivery_day;
		}
		else
			return false;
	}
	
	function check_order_status($user_id)
	{
		// check if there is any order of this user_id
		$check1 = "SELECT order_id FROM fr_order WHERE `user_id` = '".$user_id."' ";
		$query1 = $this->db->query($check1);
		
		// if there is no record, allow him to add order.
		if($query1->num_rows == 0)
		{
			return true;
		}
		else
		{
			// check if there is any order of this user_id has "Stopped" or "Paused"
			$check2 = "SELECT order_status FROM fr_order WHERE (order_status = 'Stop' OR order_Status = 'Pause')  AND `user_id` = '".$user_id."' ";
			$query2 = $this->db->query($check2);
			// if YES, do not allow him to add order
			if($query2->num_rows > 0)
			{
				return false;
			}
			else
			{		
				// get last order of this user_id
				$sql = "SELECT order_id, week_status, week_num FROM fr_order WHERE user_id = '".$user_id."' ORDER BY order_date DESC LIMIT 1";				
				$query3 = $this->db->query($sql);
				
				if($query3->num_rows > 0)
				{
					// if all the subscribed orders are fully delivered or if there is only one weeks left to delivered
					if($query3->row()->week_status == $query3->row()->week_num || ((int)$query3->row()->week_num - (int)$query3->row()->week_status) == 1)
						return true; // allow him to order
					else
						return false;
				}
				else
					return false;
			}
		}		
	}
	
	function check_user_order($user_id, $order_id)
	{
		$sql = "SELECT order_ref FROM `fr_order` WHERE user_id = '".$user_id."'  AND order_id ='".$order_id."' ";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return true;
		}
		else
			return false;
	}
	
	function remove_address($address_id)
	{
		$sql = "DELETE FROM `fr_addresses` WHERE `address_id` = '".$address_id."' ";
		return $this->db->simple_query($sql);
	}
	
	function get_order_details($order_id)
	{
		$sql = "SELECT o.`order_id`, o.`subscription`,o.`additional_item_status`, o.`item_subscription`, o.week_num, o.`other_info`, o.`subtotal` 
, o.order_date, d.`address_id`, dd.name as `delivery_day`, td.tspdetail_id
,ad.`lat`, ad.`long`, td.`township_id`
FROM `fr_order` o 
LEFT OUTER JOIN `fr_delivery_info` d ON o.`order_id` = d.`order_id`
LEFT OUTER JOIN `fr_addresses` ad ON ad.`address_id` = d.`address_id`
LEFT OUTER JOIN `fr_township_detail` td ON ad.`township_id` = td.`tspdetail_id`
LEFT OUTER JOIN `fr_delivery_day` dd ON dd.`day_id` = td.`day_id`
WHERE o.`order_id` = '".$order_id."'
GROUP BY o.`order_id`"; 
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
			return false;
	}
	
	function get_ordered_boxes($order_id)
	{
		$sql = "SELECT od.box_id, od.qty AS `box_qty`, (SELECT SUM(qty) FROM fr_order_details WHERE order_id = '".$order_id."' ) AS box_num FROM `fr_order_details`od WHERE od.order_id = '".$order_id."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function get_ordered_items($order_id)
	{
		$sql = "SELECT ad.item_id, ad.item_qty FROM `fr_additional_order` ad WHERE ad.order_id = '".$order_id."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function change_day_to_number($day)
	{
		$sql = "SELECT day_id FROM `fr_delivery_day` WHERE `name` = '".$day."'";
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0)
		{
			return $query->row()->day_id;
		}
		else
			return false;
	}
	
	/*
	|------------------------------------------------------------
	|   MY ORDERS
	|------------------------------------------------------------
	*/
	
	function get_user_orders($user_id, $num,  &$offset, &$row_count)
	{
		again:
 		$strQuery = "SELECT SQL_CALC_FOUND_ROWS o.`order_id`, o.`order_ref`, o.`order_date`, o.`subscription`, o.week_num, o.`order_status`, o.`subtotal`, o.week_status, o.order_date, dd.name as `delivery_day`, d.`delivery_status`
, GROUP_CONCAT(DISTINCT CONCAT(b.`name`, ':', od.`qty`)) AS boxes
, GROUP_CONCAT(DISTINCT CONCAT(a.`name`, IF(a.`type` IS NULL, ' ' , CONCAT(' - ',  a.`type`)) , ':', a.`net_weight`, ':', ad.`item_qty`)) AS items
, COUNT(DISTINCT od.`box_id`) AS box_num, COUNT(DISTINCT a.`item_id`) AS item_num
, (SELECT dl.order_date FROM fr_delivery_log dl WHERE dl.order_id = o.`order_id` AND dl.delivery_date IS NULL ORDER BY dl.order_date ASC LIMIT 1) AS next_del
FROM `fr_order` o
LEFT OUTER JOIN `fr_order_details` od ON o.`order_id` = od.`order_id` 
LEFT OUTER JOIN `fr_box` b ON b.`box_id` = od.`box_id`
LEFT OUTER JOIN `fr_additional_order` ad ON o.`order_id` = ad.`order_id`
LEFT OUTER JOIN `fr_additional_items` a ON a.`item_id` = ad.`item_id`
LEFT OUTER JOIN `fr_delivery_info` d ON o.`order_id` = d.`order_id`
LEFT OUTER JOIN `fr_addresses` adr ON adr.`address_id` = d.`address_id`
LEFT OUTER JOIN `fr_township_detail` td ON adr.`township_id` = td.`tspdetail_id`
LEFT OUTER JOIN `fr_delivery_day` dd ON dd.`day_id` = td.`day_id`
WHERE o.`user_id` = '".$user_id."'
GROUP BY o.`order_id`
ORDER BY o.`order_date` DESC LIMIT ";				
		

		if($offset != 0)
			$strQuery .= $offset.",".$num; 
		else
			$strQuery .= $num; 

		$query = $this->db->query($strQuery);
		
		$row_count = $this->db->query("SELECT FOUND_ROWS() as Num_Rows;");
		
		$row_count = $row_count->row()->Num_Rows;
		
		if($query->num_rows() == 0 && $row_count > 0)
		{
			$offset = $offset - $num;
			goto again;
		}
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
		else
			return false;
	}
	
	function update_order_status($user_id, $order_id, $status)
	{ 
        $this->db->simple_query('SET time_zone = "+06:30"');
        
		if($status == 'Stop')
    	{
			return $this->db->simple_query("UPDATE `fr_order` SET `order_status` = 'Stop' WHERE `user_id` = '".$user_id."' ");	
		}
		else if($status == 'Pause')
		{		
			//delete the order dates from delivery log which are not delivered yet.
			$sql1 = "DELETE FROM `fr_delivery_log` WHERE `order_id` = '".$order_id."' AND delivery_date IS NULL";
			$this->db->simple_query($sql1);
			
			$sql2 = "UPDATE `fr_order` SET `order_status` = '".$status."' WHERE `order_id` = '".$order_id."' ";
			return $this->db->simple_query($sql2);
		}
		else
		{
			/* insert new order_date of the remaining weeks to deliver into delivery log*/
			
			// get remaining weeks to deliver
			$rw = $this->db->query("SELECT week_num - week_status AS remaining_week FROM `fr_order` WHERE order_id = '".$order_id."'");
			
			// get delivery day
			$delivery_day = $this->db->query("SELECT td.day_id FROM `fr_addresses` a LEFT OUTER JOIN `fr_delivery_info` d ON d.address_id =  a.address_id LEFT OUTER JOIN `fr_township_detail` td ON td.tspdetail_id = a.township_id WHERE d.order_id = '".$order_id."' ");
			
			//insert delivery log
			switch ($delivery_day->row()->day_id) {
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
			
			// set delivery date to next week if coming delivery day is not before 3 days of delivery
			if($days_left <= 3)
			{
				$date = date('Y-m-d', strtotime($date. ' + 7 days'));
			}
			
			$days = $rw->row()->remaining_week; 
			for ($i=0; $i < $days; $i++) 
			{
				if($i == 0)
					$next_date = $date;
				else
					$next_date = date('Y-m-d', strtotime($date. ' + 7 days')); 
				
				$this->db->simple_query("INSERT INTO `fr_delivery_log` (`order_id`, `order_date`) VALUES (".$order_id.", '".$next_date."'); ");
				$date = $next_date;
			}
				
			
			$sql = "UPDATE `fr_order` SET `order_status` = '".$status."' WHERE `order_id` = '".$order_id."' ";
			return $this->db->simple_query($sql);
		}
	}
	
}

/* End of file Order_model.php */
/* Location: ./application/controllers/Order_model.php */