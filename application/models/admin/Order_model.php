<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class order_model extends CI_Model
{	
	function __construct()
	{
		parent::__construct();
			$this->load->database();
			$this->load->library('MY_Data');
			$this->load->model('admin/delivery_model');
	}

	function get_order($num,$offset, &$row_count)
	{
		try{
			$table = $this->my_data->database_table();            
			$sql = "SELECT SQL_CALC_FOUND_ROWS o.order_id,o.order_ref,DATE_FORMAT(o.order_date,'%d-%m-%Y') as `order_date`,u.name,u.user_id,o.order_status,o.week_status,o.week_num,
					(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty) SEPARATOR ',')
					FROM `".$table["order_details_table"]."` AS d 
					INNER JOIN `".$table["box_table"]."` AS b ON b.box_id = d.box_id
					WHERE d.order_id = o.order_id
					GROUP BY d.order_id ) AS `Type`,
					(SELECT `name` FROM `".$table["dday_table"]."` WHERE day_id = ts.day_id) AS `Day`,
					o.subscription,(SELECT t.name FROM `".$table["township_table"]."` AS t WHERE township_id = ts.township_id) AS `township`,
					(SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR ',')
					FROM `".$table["additional_order_table"]."` AS ad
					INNER JOIN `".$table["additional_items_table"]."` AS adi ON adi.item_id = ad.item_id
					WHERE ad.order_id = o.order_id
					GROUP BY ad.order_id ) AS `additional`
					FROM `fr_order` AS o
					INNER JOIN `".$table["user_table"]."` AS u ON u.user_id = o.user_id
					INNER JOIN `".$table["delivery_info_table"]."` AS l ON l.order_id = o.order_id
					INNER JOIN `".$table["addresses_table"]."` AS a ON a.address_id = l.address_id
					INNER JOIN `".$table["tspdetails_table"]."` AS ts ON ts.tspdetail_id = a.township_id ORDER BY o.order_date DESC LIMIT ";

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
	
	function search_order($data,$num,$offset, &$row_count)
	{ 
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS o.*,o.order_id,o.order_ref,o.order_date,u.name,o.order_status,o.week_status,o.week_num,
					(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty) SEPARATOR ',')
					FROM `".$table["order_details_table"]."` AS d 
					INNER JOIN `".$table["box_table"]."` AS b ON b.box_id = d.box_id
					WHERE d.order_id = o.order_id
					GROUP BY d.order_id ) AS `Type`,
					(SELECT `name` FROM `".$table["dday_table"]."` WHERE day_id = ts.day_id) AS `Day`,
					o.subscription,(SELECT t.name FROM `".$table["township_table"]."` AS t WHERE township_id = ts.township_id) AS `township`,
					(SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR ',')
					FROM `".$table["additional_order_table"]."` AS ad
					INNER JOIN `".$table["additional_items_table"]."` AS adi ON adi.item_id = ad.item_id
					WHERE ad.order_id = o.order_id
					GROUP BY ad.order_id ) AS `additional`
					FROM `".$table["order_table"]."` AS o
					INNER JOIN `".$table["user_table"]."` AS u ON u.user_id = o.user_id
					INNER JOIN `".$table["delivery_info_table"]."` AS l ON l.order_id = o.order_id
					INNER JOIN `".$table["addresses_table"]."` AS a ON a.address_id = l.address_id
					INNER JOIN `".$table["tspdetails_table"]."` AS ts ON ts.tspdetail_id = a.township_id
					WHERE u.user_role = 'Customer'  ";
			
            if(($data['ref'] != "" && $data['ref'] != "NULL"))
    		{
				$sql .= "AND o.order_ref = '".$data['ref']."' ";
			}
			
			/* SY : 14/8/2016 */
			if($data['delivery'] != "" && $data['delivery'] != "NULL")
			{
				//$sql .= "AND (".$data['box'].") IN (SELECT d.box_id FROM `fr_order_details` AS d WHERE d.order_id = o.order_id) ";
				$sql .= "AND ts.day_id = ".$data['delivery']." ";
			}
			
			if($data['name'] != "" && $data['name'] != "NULL")
			{
				$sql .= "AND u.name LIKE '%".$data['name']."%' ";
			}
			
	
			if($data['town'] != "" && $data['town'] != "NULL")
			{
				$sql .= "AND (SELECT name FROM `".$table["township_table"]."` WHERE township_id = ts.township_id) = '".$data['town']."' ";
			}
			
			$sql .= " ORDER BY o.order_date DESC LIMIT ";
			
			if($offset != "" || $offset != "NULL")
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
	
	function get_box()
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT * FROM `".$table["box_table"]."`";
			
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
	
	function get_township()
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT * FROM `".$table["township_table"]."`";
			
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
	
	function deliver_order($id,$date)
	{
		try{	
			$this->db->simple_query('SET time_zone = "+06:30"');
            
			$dday = $this->delivery_model->delivery_day();			
			$this->db->trans_begin();
			$table = $this->my_data->database_table();
			$sql1 = "SELECT week_status, week_num  FROM `".$table["order_table"]."` WHERE order_id =". $id;
			$userdata = $this->db->query($sql1);
            $userResult = $userdata->result();
            
            /* insert order delivered date if number of weeks delivered is equal to weeks subscribed*/
			if($userResult[0]->week_status == $userResult[0]->week_num)
			{
				$sql = "UPDATE `".$table["delivery_info_table"]."` SET delivery_status='delivered',delivered_date='".$date."' WHERE order_id =". $id;
				$this->db->query($sql);
			}
            
            /* Add +1 count to number of weeks delivered */
			$sql = "UPDATE `".$table["order_table"]."` SET week_status = week_status +1 WHERE order_id =". $id;
			$this->db->query($sql);
            
            /* Insert delivery date in log table */
			$sql2 = "UPDATE `".$table["delivery_log_table"]."` SET delivery_date = order_date WHERE DATE_FORMAT(order_date,'%d-%m-%Y')='".$date."' AND order_id =".$id;
			$this->db->query($sql2);
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo false;
			}
			else
			{
				$this->db->trans_commit();
				echo true;
			}
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function get_viewdata()
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT (SELECT count(*) FROM `".$table["user_table"]."` WHERE user_role = 'Customer' ) AS total_cus,(select 
				sum((o.subtotal/o.week_num)) from fr_delivery_log as l inner join fr_order as o on l.order_id = o.order_id where 
				MONTH(l.delivery_date) = MONTH(NOW())) as subtotal,(select sum((o.subtotal/o.week_num)) from fr_delivery_log as l inner join 
				fr_order as o on l.order_id = o.order_id where MONTH(l.delivery_date) = MONTH(ADDDATE( NOW( ) , Interval -1 Month ))) as f_total,
				(SELECT IF(count(*)=0,0,count(*)) FROM `".$table["order_table"]."` WHERE MONTH(order_date) = MONTH(NOW())) as tm_order";	
			
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
	
	function get_comingorder($num,$offset, &$row_count)
	{
		try{
			$today = getdate();		
			$dday = $this->delivery_model->delivery_day();
			$first;
			$addday;
			$check = false;
			$count = 0;
			//echo $today['wday'];
			foreach($dday as $dd)
			{
				if($dd->delivery == 'YES')
				{
					if($dd->day_id == $today['wday']+1)
					{						
						$check = true;
						$addday = 0;
						break;
					}
					else
					{
						if($count == 0)
							$first = $dd->day_id;
						if($today['wday']+1 < $dd->day_id)
						{
							$check = true;
							$addday = $dd->day_id - ($today['wday']+1);
							break;
						}
						$count++;
					}
				}
			}
			if($check == false)
			{
				$addday = (7 + ($today['wday']+1) ) - $first;
			}
			$day = date('Y-m-d', strtotime("+".$addday." days"));
			$wday = date('w',strtotime($day))+1;							
			$table = $this->my_data->database_table();
            $todaydate = date('Y-m-d');
			$sql = "SELECT SQL_CALC_FOUND_ROWS o.*,o.order_id,o.order_ref,o.order_date,u.name,u.email,o.order_status,o.week_status,
					(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty)) 
					FROM `fr_order_details` AS d INNER JOIN `fr_box` AS b ON b.box_id = d.box_id 
					WHERE d.order_id = o.order_id GROUP BY d.order_id ) AS `Type`, 
					(SELECT `name` FROM `fr_delivery_day` WHERE day_id = ts.day_id) AS `Day`,
					o.subscription,(SELECT t.name FROM `fr_township` AS t WHERE township_id = ts.township_id) AS `township`, 
					 (SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR ' ') 
					 FROM `fr_additional_order` AS ad INNER JOIN `fr_additional_items` AS adi ON adi.item_id = ad.item_id 
					 WHERE ad.order_id = o.order_id AND (o.item_subscription = 'YES' OR (o.item_subscription = 'NO' && o.additional_item_status = 'YES' && 
					 (SELECT IF(delivery_date IS NULL,1,0) AS test FROM `fr_delivery_log` WHERE order_id = o.order_id  ORDER BY order_date LIMIT 1) =1 ) ) 
					 GROUP BY ad.order_id ) AS `additional`
					FROM `fr_order` AS o
					INNER JOIN `fr_user` AS u ON u.user_id = o.user_id
					INNER JOIN `fr_delivery_info` AS l ON l.order_id = o.order_id 
					INNER JOIN `fr_addresses` AS a ON a.address_id = l.address_id 
					INNER JOIN `fr_township_detail` AS ts ON ts.tspdetail_id = a.township_id
					WHERE o.week_num > o.week_status AND o.order_status = 'On-going' AND ts.day_id = ".$wday."
					AND '".$todaydate."' > o.order_date + INTERVAL 3 DAY  LIMIT ";
             //echo $sql;die();
			/*SELECT SQL_CALC_FOUND_ROWS o.*,o.order_id,o.order_ref,o.order_date,u.name,u.email,o.order_status,o.week_status,
				(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty))
				FROM `".$table["order_details_table"]."` AS d 
				INNER JOIN `".$table["box_table"]."` AS b ON b.box_id = d.box_id
				WHERE d.order_id = o.order_id
				GROUP BY d.order_id ) AS `Type`,
				(SELECT `name` FROM `".$table["dday_table"]."` WHERE day_id = ts.day_id) AS `Day`,
				o.subscription,(SELECT t.name FROM `".$table["township_table"]."` AS t WHERE township_id = ts.township_id) AS `township`,
				(SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR '\n')
				FROM `".$table["additional_order_table"]."` AS ad
				INNER JOIN `".$table["additional_items_table"]."` AS adi ON adi.item_id = ad.item_id
				WHERE ad.order_id = o.order_id
				GROUP BY ad.order_id ) AS `additional`				
				FROM `".$table["order_table"]."` AS o
				INNER JOIN `".$table["user_table"]."` AS u ON u.user_id = o.user_id
				INNER JOIN `".$table["delivery_info_table"]."` AS l ON l.order_id = o.order_id
				INNER JOIN `".$table["addresses_table"]."` AS a ON a.address_id = l.address_id
				INNER JOIN `".$table["tspdetails_table"]."` AS ts ON ts.tspdetail_id = a.township_id
				WHERE (SELECT `name` FROM `".$table["dday_table"]."` WHERE day_id = ts.day_id) = '".$weekday."' 
				AND o.week_num > o.week_status AND o.order_status = 'On-going' AND (SELECT DATEDIFF(NOW(),o.order_date)) > 3
				*/  
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
	
	function pdf_box($id)
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT o.order_ref,u.name as `cname`,ad.address,bo.name,b.qty,bo.price,(bo.price*b.qty*o.week_num) AS amount,o.week_num
    				FROM `".$table["order_details_table"]."` AS b 
					INNER JOIN `".$table["order_table"]."` AS o ON o.order_id = b.order_id 
					INNER JOIN `".$table["user_table"]."` AS u ON u.user_id = o.user_id
					INNER JOIN `".$table["delivery_info_table"]."` AS d ON d.order_id = o.order_id
					INNER JOIN `".$table["addresses_table"]."` AS ad ON ad.address_id = d.address_id
					INNER JOIN `".$table["box_table"]."` AS bo ON bo.box_id = b.box_id
					WHERE o.order_id = ".$id;
			
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
	
	function pdf_addition($id,$day)
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT i.name,d.item_qty,i.price,(i.price*d.item_qty) AS amount,o.item_subscription,o.week_num
					FROM `".$table["additional_order_table"]."` AS d 
					INNER JOIN `".$table["additional_items_table"]."` AS i ON i.item_id = d.item_id
					INNER JOIN `".$table["order_table"]."` AS o ON o.order_id = d.order_id
					WHERE d.order_id = ".$id;
			
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
	
	function noti()
	{
		try{
			$table = $this->my_data->database_table();
			
			$sql = "SELECT n.noti_id,n.message,n.datetime,u.name,u.email,u.user_id
					FROM `".$table["notification_table"]."` AS n
					INNER JOIN `".$table["user_table"]."` AS u ON n.user_id = u.user_id
					WHERE n.status = 'Unread' AND n.delete IS NULL";
			
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
	
	function readnoti($noti_id)
	{
		try{
			$table = $this->my_data->database_table();
			$query = array(
						'status' => 'Read'
			);
			$this->db->where('noti_id',$noti_id);
			echo $this->db->update($table["notification_table"], $query);
		}catch(Exception $e)
		{
			//error code here
		}
	}
	
	function get_deliver_order($customer, $ref, $delivery, $num,$offset, &$row_count)
	{		
		try{

			// SY : 22-8-2016
			$searching = '';
			
			// Searching
			if($customer !== "" && $customer !== "NULL")
			{
				$searching .= " AND u.name LIKE '%".$customer."%' ";
			}

			if($ref !== "" && $ref !== "NULL")
			{
				$searching .= " AND o.order_ref ='".$ref."' ";
			}

			if($delivery !== "" && $delivery !== "NULL")
			{
				$searching .= " AND ts.day_id =".$delivery." ";
			}
			
			$dday = $this->delivery_model->delivery_day();			
			$cday = date('Y-m-d');						
			$table = $this->my_data->database_table();
			$sql = "SELECT  SQL_CALC_FOUND_ROWS o.order_id,o.order_ref,o.order_date,u.name,u.email,o.order_status,o.week_status,o.week_num,DATE_FORMAT(dl.order_date,'%d-%m-%Y') AS order_delivery_date,
					(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty)) 
					FROM `fr_order_details` AS d INNER JOIN `fr_box` AS b ON b.box_id = d.box_id 
					WHERE d.order_id = o.order_id GROUP BY d.order_id ) AS `Type`, 
					(SELECT `name` FROM `fr_delivery_day` WHERE day_id = ts.day_id) AS `Day`,
					o.subscription,(SELECT t.name FROM `fr_township` AS t WHERE township_id = ts.township_id) AS `township`, 
					 (SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR ' ') 
					 FROM `fr_additional_order` AS ad INNER JOIN `fr_additional_items` AS adi ON adi.item_id = ad.item_id 
					 WHERE ad.order_id = o.order_id GROUP BY ad.order_id ) AS `additional`
					FROM `fr_order` AS o
					INNER JOIN `".$table["delivery_log_table"]."` AS dl ON dl.order_id = o.order_id
					INNER JOIN `fr_user` AS u ON u.user_id = o.user_id
					INNER JOIN `fr_delivery_info` AS l ON l.order_id = o.order_id 
					INNER JOIN `fr_addresses` AS a ON a.address_id = l.address_id 
					INNER JOIN `fr_township_detail` AS ts ON ts.tspdetail_id = a.township_id
					WHERE o.week_num > o.week_status AND o.order_status = 'On-going' AND (SELECT DATEDIFF(NOW(),o.order_date)) > 3 
					AND dl.order_date <= '".$cday."' AND dl.delivery_date IS NULL ".$searching." ORDER BY dl.order_date DESC LIMIT ";
				
			if($offset != "" || $offset != 'NULL')
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
	
	function change_week($id,$date)
	{
		try{	
            $this->db->simple_query('SET time_zone = "+06:30"');
            
			$this->db->trans_begin();
			$table = $this->my_data->database_table();
			$sql1 = "SELECT l.order_date FROM `".$table["delivery_log_table"]."` AS l WHERE l.order_id = ".$id." AND l.delivery_date IS NULL ORDER BY l.order_date DESC LIMIT 1";
			$query1 = $this->db->query($sql1);
            $queryResult = $query1->result();
			$date1 = strtotime($queryResult[0]->order_date);
			$date1 = strtotime("+7 day", $date1);
			$date = strtotime($date);
			$date = date('Y-m-d',$date);
			$newdate = date('Y-m-d',$date1);
			while ($newdate <= date('Y-m-d'))
			{
				$newdate = strtotime($newdate);
				$newdate = strtotime("+7 day", $newdate);
				$newdate = date('Y-m-d',$newdate);
			}
			$sql2 = "DELETE FROM `".$table["delivery_log_table"]."` WHERE order_id = ".$id." AND order_date = '".$date."'  AND delivery_date IS NULL";
			$this->db->query($sql2);
			$sql3 = "INSERT INTO `".$table["delivery_log_table"]."` VALUE(".$id.",'".$newdate."',NULL)";
			$this->db->query($sql3);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo false;
			}
			else
			{
				$this->db->trans_commit();
				echo true;
			}
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
	function get_date($id)
	{
	 
 		try{
			$sql = "SELECT order_date FROM `fr_delivery_log` WHERE order_id =".$id." AND delivery_date IS NULL ORDER BY order_date LIMIT 1";
			$query = $this->db->query($sql);
			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//code here
		}
	}
    
    function update_order_status($user_id, $order_id, $status)
    { 
	
		return $this->db->simple_query("UPDATE `fr_order` SET `order_status` = 'Stop' WHERE `user_id` = '".$user_id."' ");	
	}
    
    //Add get order details function by Ei Mon Kyaw 22 Apr 2016
    function get_orderdetails($id)
	{
		try{
			$sql = "SELECT o.order_id,o.order_ref,o.subscription,o.order_date,o.order_status,o.week_num,o.week_status,o.additional_item_status,
					o.item_subscription,o.other_info,tsp.day_id,
					(SELECT order_date FROM fr_delivery_log dl WHERE dl.order_id = o.order_id AND dl.delivery_date IS NULL ORDER BY order_date ASC LIMIT 1)  as next_delivery,
					(SELECT GROUP_CONCAT(CONCAT(b.name,' x ',d.qty) SEPARATOR '<br/>&nbsp;&nbsp;&nbsp;&nbsp;') 
					FROM `fr_order_details` AS d INNER JOIN `fr_box` AS b ON b.box_id = d.box_id 
					WHERE d.order_id = o.order_id GROUP BY d.order_id ) AS box_name,
					(SELECT GROUP_CONCAT(CONCAT(adi.name,' x ',ad.item_qty) SEPARATOR '<br/>&nbsp;&nbsp;&nbsp;&nbsp;') 
					FROM `fr_additional_order` AS ad INNER JOIN `fr_additional_items` AS adi ON adi.item_id = ad.item_id 
					WHERE ad.order_id = o.order_id GROUP BY ad.order_id ) AS `additional`,
					a.contact_person,a.ph_no,a.mobile,(SELECT `name` FROM `fr_township` WHERE township_id = tsp.township_id) AS `township`,
					a.address,d.delivery_instruction
					FROM `fr_order` AS o
					INNER JOIN `fr_delivery_info` AS i ON i.order_id = o.order_id
					INNER JOIN `fr_addresses` AS a ON a.address_id = i.address_id
					INNER JOIN `fr_township_detail` AS tsp ON tsp.tspdetail_id = a.township_id
					INNER JOIN `fr_delivery_info` AS d ON d.order_id = o.order_id
					WHERE o.order_id =" . $id;
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//code here
		}
	}
	
}