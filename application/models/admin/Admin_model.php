<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model
{	
	function __construct()
	{
	parent::__construct();
		$this->load->database();
		$this->load->library('MY_Data');
	}
	
	function check_login($email,$password)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT * FROM `".$table["user_table"]."` WHERE email = ".$this->db->escape($email);
	
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
			{
				if($query->row()->password == MD5(MD5($password)))
					return $query->row();
				else
					return false;
			}
			else
				return false;
		}
		catch(Exception $ex)
		{
			//code here
		}
	}
	
	function check_oldpass($email,$old_pass)
	{
		try{
			$table = $this->my_data->database_table();
			$sql = "SELECT password FROM `".$table["user_table"]."` WHERE email = ".$this->db->escape($email);
			$query = $this->db->query($sql);
            $queryResult = $query->result();
			if($queryResult[0]->password != MD5(MD5($old_pass)))
			{
				return false;
			}
			else
				return true;
		}
		catch(Exception $ex)
		{
			//code here
		}
	}
	
	function change_password($email,$new_pass)
	{
		try{
			$table = $this->my_data->database_table();
			$query = array(
						'password' => MD5(MD5($new_pass))
			);
			$this->db->where('email', $email);
			$this->db->update($table["user_table"], $query);
		}
		catch(Exception $ex)
		{
			//code here
		}
	}
	
}
	