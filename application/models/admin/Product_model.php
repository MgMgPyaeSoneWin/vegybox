<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function get_product_list( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM fr_box LIMIT  ";
		
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
	
	function get_box_details($id)
	{
		$query = $this->db->query("SELECT * FROM `fr_box` WHERE box_id = '".$id."'");
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
			return false;
	}
	
	function insert_box($data)
	{
		// Insert box Info
		$box_data = array(
			  'name'	=> $data['name'],
			  'price'     => $data['price'], 
			  'description'  => $data['description'],
			  'status' => $data['status']
		);
		
		if($data['id'] !== '')
		{ 
			if(isset($data['img']) )
				$box_data['image'] = $data['img'];
				
			$this->db->where('box_id', $data['id']);
			return $this->db->update('fr_box', $box_data);
		}
		else
		{
			$box_data['image'] = $data['img'];
			return $this->db->insert('fr_box', $box_data);
		}
	}
	
	function delete_box($box_id)
	{
		$this->db->where('box_id', $box_id);
		return $this->db->delete('fr_box');

	}
	
	/*
	|-----------------------------------------------------
	| ADDITIONAL ITEM
	|-----------------------------------------------------
	*/
	
	function get_item_list( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS parent as item_id, `name`, `type`, `status`, description, GROUP_CONCAT(net_weight) AS net_weight, GROUP_CONCAT(price) AS price, image, GROUP_CONCAT(item_id) AS ids, GROUP_CONCAT(status) AS status, COUNT(parent) as number FROM fr_additional_items GROUP BY parent ORDER BY item_id DESC LIMIT  ";
		
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
	
	function get_item_details($id)
	{
		$query = $this->db->query("SELECT parent AS item_id, `name`, `type`, description, image, `status`, GROUP_CONCAT(net_weight) AS net_weight, GROUP_CONCAT(price) AS price, image, GROUP_CONCAT(item_id) AS ids, GROUP_CONCAT(status) AS status, COUNT(parent) AS number 
FROM fr_additional_items WHERE item_id = '".$id."' OR parent = '".$id."' GROUP BY parent ");
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
			return false;
	}
	
	function insert_item($data, $sub)
	{ 
		// Insert item Info
		$result = '';
		$num = count($sub['weight']); 
		$id = array();
		if($num > 1)
		{
			for($i =0; $i < $num; $i++)
			{
				$item_data = array(
				  'name'	=> $data['name'],
				  'type'     => $data['type'], 
				  'description'  => $data['description'],
				  'net_weight'	=> $sub['weight'][$i],
				  'price'     => $sub['price'][$i], 
				  'status'  => $sub['status'][$i]
				);
			
				if($data['id'] !== '')
				{ 
					$item_data['parent'] = $data['id'];
					
					if(isset($data['img']) )
						$item_data['image'] = $data['img'];
					
					if($sub['subID'][$i] != '')
					{
						$this->db->where('item_id', $sub['subID'][$i]);
						$result .= $this->db->update('fr_additional_items', $item_data);
					}
					else
					{
						$result .= $this->db->insert('fr_additional_items', $item_data);
					}
				}
				else
				{
					$item_data['image'] = $data['img'];
					$this->db->insert('fr_additional_items', $item_data);
					array_push($id,$this->db->insert_id());
					$result .= $this->db->query("UPDATE `fr_additional_items` SET `parent` = '".$id[0]."' WHERE `item_id` = '".$this->db->insert_id()."'");
				}
			} 
			
		}
		else
		{
			$item_data = array(
			  'name'	=> $data['name'],
			  'type'     => $data['type'], 
			  'description'  => $data['description'],
			  'net_weight'	=> $sub['weight'][0],
			  'price'     => $sub['price'][0], 
			  'status'  => $sub['status'][0]
			);

			if($data['id'] !== '')
			{ 
				$item_data['parent'] = $data['id'];
				
				if(isset($data['img']) )
					$item_data['image'] = $data['img'];
				
				if($sub['subID'][0] != '')
				{
					$this->db->where('item_id', $sub['subID'][0]);
					$result .= $this->db->update('fr_additional_items', $item_data);
				}
				else
				{
					$result .= $this->db->insert('fr_additional_items', $item_data);
				}
					
				$this->db->where('item_id', $data['id']);
				$result = $this->db->update('fr_additional_items', $item_data);
			}
			else
			{
				$item_data['image'] = $data['img'];
				$this->db->insert('fr_additional_items', $item_data);
				array_push($id,$this->db->insert_id());
				$result = $this->db->query("UPDATE `fr_additional_items` SET `parent` = '".$id[0]."' WHERE `item_id` = '".$this->db->insert_id()."'");
			}
		}
	
		if(strpos($result, 0) !== false)
		{
			return false;
		}
		else
		{
			return true;
		}

	}
	
	function remove_sub_item($item_id)
	{
		return $this->db->delete('fr_additional_items', array('item_id' => $item_id)); 
	}
	
	function delete_item($item_id)
	{
		return $this->db->query('DELETE FROM `fr_additional_items` WHERE `item_id` IN ('.$item_id.')');
	}
	
}

/* End of file Product_model.php*/
/* Location: ./application/models/admin/Product_model.php */