<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class System_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	/*	
	|-------------------------------------------------
	| System Settings
	|_________________________________________________
	*/
	
	function get_setting()
	{
		$query = $this->db->query("SELECT 
		GROUP_CONCAT(IF(meta_key = 'max_box_limit', meta_value, NULL)) AS 'max_box_limit',
		GROUP_CONCAT(IF(meta_key = 'address', meta_value, NULL)) AS 'address',
		GROUP_CONCAT(IF(meta_key = 'phone', meta_value, NULL)) AS 'phone',
		GROUP_CONCAT(IF(meta_key = 'email', meta_value, NULL)) AS 'email',
		GROUP_CONCAT(IF(meta_key = 'autoreply_email', meta_value, NULL)) AS 'autoreply_email',
		GROUP_CONCAT(IF(meta_key = 'box_price', meta_value, NULL)) AS 'box_price',
		GROUP_CONCAT(IF(meta_key = 'smtp_username', meta_value, NULL)) AS 'smtp_username',
		GROUP_CONCAT(IF(meta_key = 'smtp_password', meta_value, NULL)) AS 'smtp_password'
		FROM fr_system  ");	
				
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		return false;
	}
	
	function update_settings($data)
	{ 
		$result = '';
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['max_box']."' WHERE `meta_key` = 'max_box_limit'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['address']."' WHERE `meta_key` = 'address'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['phone']."' WHERE `meta_key` = 'phone'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['email']."' WHERE `meta_key` = 'email'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['autoreply']."' WHERE `meta_key` = 'autoreply_email'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['box_price']."' WHERE `meta_key` = 'box_price'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['smtp_username']."' WHERE `meta_key` = 'smtp_username'");
		$result .= $this->db->query("UPDATE `fr_system` SET `meta_value` = '".$data['smtp_pwd']."' WHERE `meta_key` = 'smtp_password'");
		
		if(strpos($result, 0) !== false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/*	
	|-------------------------------------------------
	| FAQs
	|_________________________________________________
	*/
	function get_faq_list( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM fr_faq LIMIT ";
		
		if($offset != 0)
			$strQuery .= $offset.",".$num; 
		else
			$strQuery .= "0,".$num; 
		
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
	
	function get_faq_details($id)
	{
		$query = $this->db->query("SELECT * FROM `fr_faq` WHERE faq_id = '".$id."'");
		if($query->num_rows > 0)
		{
			return $query->row();
		}
		else
			return false;
	}
	
	function insert_faq($data)
	{
		// Insert box Info
		$box_data = array(
			  'question'	=> $data['question'],
			  'answer'     => $data['answer'], 
			  'status' => $data['status']
		);
		
		if($data['id'] !== '')
		{				
			$this->db->where('faq_id', $data['id']);
			return $this->db->update('fr_faq', $box_data);
		}
		else
		{
			return $this->db->insert('fr_faq', $box_data);
		}
	}
	
	function delete_faq($id)
	{
		$this->db->where('faq_id', $id);
		return $this->db->delete('fr_faq');

	}
	
}

/* End of file System_model.php*/
/* Location: ./application/models/admin/System_model.php */