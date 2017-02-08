<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class faq_model extends CI_Model
{	
	function __construct()
    {
        parent::__construct();
		$this->load->database();
    }
	
	function get_faq_list( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM fr_faq WHERE status='enabled' LIMIT  ";
		
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

	function get_faq_list_unicode( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM fr_faq WHERE status='enabled' AND language_type='unicode' LIMIT  ";
		
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

	function get_faq_list_zawgyi( $num,  &$offset, &$row_count)
	{
		again:
		$strQuery = "SELECT SQL_CALC_FOUND_ROWS * FROM fr_faq WHERE status='enabled' AND language_type='zawgyi' LIMIT  ";
		
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
	
	
}

/* End of file Faq_model.php*/
/* Location: ./application/controllers/Faq_model.php */