<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Email extends CI_Email 
{

    public function __construct()
    {
         parent::__construct();
    }

	
	public function sent_email($to,$subject,$message)
	{
		$CI = & get_instance();
		
		$CI->load->model('system_model');
		$settings = $CI->system_model->get_setting();
		
		$smtp_host =  $settings->smtp_host;
		$smtp_username =  $settings->smtp_username;
		$smtp_password =  $settings->smtp_password;
		$smtp_port =  $settings->smtp_port;
		//$smtp_timeout =  $settings->smtp_timeout;		
		
		$from = $settings->autoreply_email;
		$title = $settings->system_title;

		
		try {
			
			$config = array(
			  'protocol' => 'smtp',
			  'smtp_host' => $smtp_host,
			  'smtp_port' => 25,
			  'smtp_user' => '',
			  'smtp_pass' => '',
			  'mailtype' => 'html',
			  'wordwrap' => TRUE
			);
			
			$CI->load->library('email');
			$CI->email->initialize($config);
			$CI->email->set_newline("\r\n");
			$CI->email->from($from,$title); 
			$CI->email->to($to);
			$CI->email->subject($subject);
			$CI->email->message($message);	
			
			$result = $CI->email->send();
			if(!$result)
			{
			  show_error($CI->email->print_debugger());
			}
			else
				return true;

			
		}catch(Exception $e)
		{
			return false;//var_dump($e);
			exit();
		}
	}
	
	
}

/* End of file My_Email.php */