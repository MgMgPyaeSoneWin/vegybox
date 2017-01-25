<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class delivery_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
			$this->load->database();
			$this->load->library('MY_Data');
	}
    
    /* SY : 14/8/2016 */
    function get_delivery_day()
	{
		try{
			$sql = 'SELECT * FROM `fr_delivery_day` WHERE delivery = "YES"';					
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code
		}
	}

	function get_daybytownship($id)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT t.township_id,t.name,(IF((SELECT `status` FROM `".$table['tspdetails_table']."` WHERE day_id = ".$id." AND township_id = t.township_id) = 'YES','YES','NO')) AS `select` FROM `".$table['township_table']."` AS t";					
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code
		}
	}
	
	function delivery_day()
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT * FROM `".$table['dday_table']."`";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code
		}
	}
	
	function add_daybytownship($day_id,$townships)
	{
		try{
    		$table = $this->my_data->database_table();
			$this->db->trans_begin();
			if($townships != "")
				$sql = "UPDATE `".$table['tspdetails_table']."` SET `status` = 'YES' WHERE day_id = ".$day_id." AND (";
			$count = 0;
			if($townships != "")
			{
				foreach($townships as $town)
				{
					if($count != 0)
						$sql .= " OR";
					$sql .= " township_id = ".$town;
					$count ++;
					$data = $this->db->query("SELECT TRUE AS test FROM `".$table['tspdetails_table']."` WHERE day_id=".$day_id." AND township_id = ".$town);
					if(!$data->num_rows() > 0)
					{
						$this->db->query("INSERT INTO `".$table['tspdetails_table']."` (day_id,township_id) VALUE (".$day_id.",".$town.")");
					}			
				}
			}
			$sql1 = "UPDATE `".$table['tspdetails_table']."` SET `status` = 'NO' WHERE day_id = ".$day_id;
			$this->db->query($sql1);
			if($townships != "")
			{
				$sql .= ")";			
				$this->db->query($sql);
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
		}catch(Exception $ex)
		{
		}
	}
	
	function add_deliverydayid($day_id)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "UPDATE `".$table['dday_table']."` SET delivery = 'YES' WHERE";			
			$count = 0;			
			foreach($day_id as $day)
			{
			 	if($count != 0)
					$sql .= " OR";
				$sql .= " day_id = ".$day;
				$count ++;
			}
			$sql1 = "UPDATE `".$table['dday_table']."` SET delivery = 'NO'";
			$this->db->query($sql1);
			echo $query = $this->db->query($sql);
		}catch(Exception $ex)
		{
			//error code
		}
	}
	
	function add_townshipdata($town,$lat,$lon)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "INSERT INTO `".$table['township_table']."` (`name`,`long`,`lat`) VALUE('".$town."',".$lon.",".$lat.")";			
			echo $query = $this->db->query($sql);
		}catch(Exception $ex)
		{
			//error code
		}
	}
	
	function get_tspOrders($search_date)
	{
		try
		{
			// Get the week day of the searched date 
			// since only the first ordered date is recorded, we need to know its delivery day to get the records for coming deliveries.			
			$day = date("w", strtotime($search_date)) + 1;
			
			$search_date= date('Y-m-d', strtotime($search_date));
			
			// Get the order according to the day which order status is not 'Stop' and Ended.
			// Order is ended when the subscribed weeks (week_num) is equal to the weeks delivered (week_status)
			// Since the order will be delivered only after the week ordered is submited, get the order which search date is greater than order date
			
			$sql = "SELECT td.tspdetail_id, GROUP_CONCAT(o.`order_id`) AS order_id, COUNT(o.order_id) AS order_num, t.`name` AS township, DATE_FORMAT(order_date, '%d-%m-%Y') AS order_date, GROUP_CONCAT(a.`address_id`) AS address_id 
FROM `fr_township_detail` td
LEFT OUTER JOIN `fr_addresses` a ON a.`township_id` = td.`tspdetail_id`
LEFT OUTER JOIN `fr_delivery_info` d ON d.`address_id` = a.`address_id`
LEFT OUTER JOIN `fr_order` o ON o.`order_id` = d.`order_id`
LEFT OUTER JOIN `fr_township` t ON t.`township_id` = td.`township_id`
WHERE td.day_id = '".$day."' AND o.order_status != 'Stop'  AND o.order_status != 'Pause' AND o.week_num > o.week_status AND  order_date <'".$search_date."'
AND '".$search_date."' > order_date + INTERVAL 3 DAY
GROUP BY td.`township_id`"; 

			$query = $this->db->query($sql);
			
			if($query->num_rows > 0)
			{
				return $query->result();
			}
			else
			{
				return false;	
			}
		}
		catch(Exception $ex)
		{
			//error code
		}
	}
	
	function get_locations($address_id, $date , $orders)
	{
		try
		{
			$sql = "SELECT a.lat, a.long, a.address, o.`order_ref`, o.`order_id` 
FROM `fr_addresses` a 
LEFT OUTER JOIN `fr_delivery_info` d ON d.`address_id` = a.`address_id`
LEFT OUTER JOIN `fr_order` o ON o.`order_id` = d.`order_id`
WHERE o.`order_id` IN (".$orders.")";

			$query = $this->db->query($sql);
			
			if($query->num_rows > 0)
			{
				return $query->result();
			}
			else
				return false;	
		}
		catch(Exception $ex)
		{
			//error code
		}
	}
	
}