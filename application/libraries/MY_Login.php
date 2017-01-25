<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Login
{
	private $CI;
	
	function __construct()
    {
        $this->CI =& get_instance();		
		$this->CI->load->model("user_model");
    }

	
	public function verify_user($data,$tablename)
	{
		
		$this->CI->load->library('form_validation');
		
		// Get posted data as an array
		$key = array();
		foreach($data as $k=>$val)
		{
			array_push( $key, array('key' => $k , 'value' => $val));
		}
		
		$this->CI->form_validation->set_rules($key[0]['key'],'Email','trim|required|xss_clean');
		$this->CI->form_validation->set_rules($key[1]['key'],'Password','trim|required|xss_clean');
		
		if($this->CI->form_validation->run()!== FALSE)
		{
			$result	  = $this->CI->user_model->check_user($key[0]['value'],$key[1]['value'],$tablename);
			
			
			if($result)
				return $result;
			else
				return false;
		}
		else
		{
			return false;
		}
	}
	
}

/* End of file MY_Login.php */
/* Location: ./application/libraries/MY_Login.php */