<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class summarize_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
		$this->load->library('MY_Data');
    }
	
	function get_summarizebyday($day)
	{
		try{
		$table = $this->my_data->database_table();
		/*if($day >= date('Y-m-d'))
		{*/	
			$sql = "SELECT od.box_id AS id,b.name,SUM(od.qty) AS total
					FROM `fr_order_details` AS od 
					INNER JOIN `fr_order` AS o ON o.order_id = od.order_id 
					INNER JOIN `fr_delivery_log` AS dl ON dl.order_id = od.order_id 
					INNER JOIN `fr_box` AS b ON b.box_id = od.box_id  
					WHERE dl.order_date = '".$day."' AND o.order_status = 'On-going' 
					GROUP BY od.box_id 
					UNION ALL
					SELECT ad.item_id AS id,CONCAT(a.name,' (',a.net_weight,')') AS `name`,SUM(item_qty) AS total 
					FROM `fr_additional_order` AS ad 
					INNER JOIN `fr_order` AS o ON o.order_id = ad.order_id 
					INNER JOIN `fr_delivery_log` AS dl ON dl.order_id = ad.order_id 
					INNER JOIN `fr_delivery_info` AS d ON d.order_id = ad.order_id 
					INNER JOIN `fr_additional_items` AS a ON a.item_id = ad.item_id 
					WHERE dl.order_date = '".$day."' AND o.order_status = 'On-going' 
					 AND o.additional_item_status = 'YES' AND (item_subscription = 'YES' OR (item_subscription = 'NO' && o.additional_item_status = 'YES' && 
					 (SELECT IF(order_date = '".$day."',1,0) AS test FROM `fr_delivery_log` WHERE order_id = o.order_id  ORDER BY order_date LIMIT 1) =1 )) 
					GROUP BY ad.item_id";		
			$query = $this->db->query($sql);	
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code here
		}
	}
	
	function get_detailssummarizebyday($day)
	{
		try{
		$table = $this->my_data->database_table();
		/*if($day >= date('Y-m-d'))
		{*/
			//$weekday = date('D',strtotime($day));
			$sql = "SELECT b.name,IF(o.other_info = '','Default',o.other_info) AS info,SUM(od.qty) AS total 
					FROM `fr_order_details` AS od 
					INNER JOIN `fr_order` AS o ON o.order_id = od.order_id 
					INNER JOIN `fr_delivery_info` AS d ON d.order_id = od.order_id 
					INNER JOIN `fr_box` AS b ON b.box_id = od.box_id 
					INNER JOIN `fr_delivery_log` AS l ON l.order_id = od.order_id 
					WHERE l.order_date ='".$day."'  AND o.order_status = 'On-going' 
					GROUP BY od.box_id,other_info";
		
			
			$query = $this->db->query($sql);			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		
		}catch(Exception $ex)
		{
			//error code here
		}
	}
	
	function get_incomegraph()
	{
	try{
		$table = $this->my_data->database_table();
		$sql = "SELECT `YEAR`,`MONTH`,`income`
				FROM(
				(SELECT `YEAR`,`MONTH`,`income` 
				FROM (SELECT YEAR(l.order_date) as `YEAR`,MONTHNAME(l.order_date) AS `MONTH`,SUM(o.subtotal/o.week_num) AS income
				FROM `fr_delivery_log` AS l 
				INNER JOIN `fr_order` AS o ON l.order_id = o.order_id
				WHERE l.order_date IS NOT NULL AND l.order_date > CONCAT(SUBSTRING(ADDDATE(NOW(), INTERVAL -6 MONTH), 1, 8), '01 00:00:00') 
				GROUP BY MONTH(l.order_date)
				) tbl GROUP BY `MONTH`)
				UNION ALL
				(SELECT YEAR(`MONTH`) as `YEAR`,MONTHNAME(`MONTH`) AS `MONTH`,income FROM
				(SELECT (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a+b MONTH `MONTH`,0 AS income
				FROM (SELECT 0 a UNION SELECT 1 a 
				UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 
				UNION SELECT 8 UNION SELECT 9 ) d, (SELECT 0 b UNION SELECT 10 UNION SELECT 20 UNION SELECT 30 UNION SELECT 40) m 
				WHERE (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a + b MONTH <= (SELECT DATE(NOW())) ORDER BY a + b) tbl1)
				) tbl GROUP BY `MONTH` ORDER BY `YEAR`, FIELD(MONTH,'January','February','March',
				'April','May','June','July','August','September','October','November','December')";
		/*"SELECT `YEAR`,`MONTH`,`income`
			FROM(
			(SELECT `YEAR`,`MONTH`,`income` 
			FROM (SELECT YEAR(l.delivery_date) as `YEAR`,MONTHNAME(l.delivery_date) AS `MONTH`,SUM(o.subtotal/o.week_num) AS income
			FROM `fr_delivery_log` AS l 
			INNER JOIN `fr_order` AS o ON l.order_id = o.order_id
			WHERE l.delivery_date IS NOT NULL AND l.delivery_date > CONCAT(SUBSTRING(ADDDATE(NOW(), INTERVAL -6 MONTH), 1, 8), '01 00:00:00') 
			GROUP BY MONTH(l.delivery_date)
			) tbl GROUP BY `MONTH`)
			UNION ALL
			(SELECT YEAR(`MONTH`) as `YEAR`,MONTHNAME(`MONTH`) AS `MONTH`,income FROM
			(SELECT (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a+b MONTH `MONTH`,0 AS income
			FROM (SELECT 0 a UNION SELECT 1 a 
			UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 
			UNION SELECT 8 UNION SELECT 9 ) d, (SELECT 0 b UNION SELECT 10 UNION SELECT 20 UNION SELECT 30 UNION SELECT 40) m 
			WHERE (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a + b MONTH <= (SELECT DATE(NOW())) ORDER BY a + b) tbl1)
			) tbl GROUP BY `MONTH` ORDER BY `YEAR`, FIELD(MONTH,'January','February','March',
			'April','May','June','July','August','September','October','November','December')";*/
		$query = $this->db->query($sql);			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code here
		}
				
	}
	
	function get_deliveredordergraph()
	{
	try{
		$table = $this->my_data->database_table();
		$sql = "SELECT `YEAR`,`MONTH`,`dorder`
			FROM(
			(SELECT `YEAR`,`MONTH`,`dorder` 
			FROM (SELECT YEAR(l.delivery_date) as `YEAR`,MONTHNAME(l.delivery_date) AS `MONTH`,count(l.order_id) AS `dorder`
			FROM `fr_delivery_log` AS l 			
			WHERE l.delivery_date IS NOT NULL AND l.delivery_date > CONCAT(SUBSTRING(ADDDATE(NOW(), INTERVAL -6 MONTH), 1, 8), '01 00:00:00') 
			GROUP BY MONTH(l.delivery_date)
			) tbl GROUP BY `MONTH`)
			UNION ALL
			(SELECT YEAR(`MONTH`) as `YEAR`,MONTHNAME(`MONTH`) AS `MONTH`,`dorder`FROM
			(SELECT (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a+b MONTH `MONTH`,0 AS `dorder`
			FROM (SELECT 0 a UNION SELECT 1 a 
			UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 
			UNION SELECT 8 UNION SELECT 9 ) d, (SELECT 0 b UNION SELECT 10 UNION SELECT 20 UNION SELECT 30 UNION SELECT 40) m 
			WHERE (SELECT DATE(NOW() - INTERVAL 6 MONTH)) + INTERVAL a + b MONTH <= (SELECT DATE(NOW())) ORDER BY a + b) tbl1)
			) tbl GROUP BY `MONTH` ORDER BY `YEAR`, FIELD(MONTH,'January','February','March',
			'April','May','June','July','August','September','October','November','December')";
		$query = $this->db->query($sql);			
			if($query->num_rows() > 0)
				return $query->result();
			else
				return false;
		}catch(Exception $ex)
		{
			//error code here
		}
				
	}
}

/* End of file summarize_model.php */
/* Location: ./application/models/admin/summarize_model.php */