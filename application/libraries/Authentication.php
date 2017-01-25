<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Authentication {
	private $CI;
	
	function __construct()
    {
         $this->CI =& get_instance();
    }
	
	public function get_hashed_password($username) {
	
		$user = $this->CI->db->select('password')->where('username',$username)->get('users');
		
		if ($user->num_rows == 0) {
			return false;
		}
		
		$user_details = $user->row();
		$HA1 = $user_details->password;
		return $HA1;
	}
}