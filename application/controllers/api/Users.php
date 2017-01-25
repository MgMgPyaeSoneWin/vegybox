<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Users extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->model('user_model');
		$this->load->library('email');	
		$this->load->model("system_model");

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function verify_get()
    {
        $this->load->helper('email');
        
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$email = $arr['txtemail'];
		$password = $arr['txtpassword'];
		$emailcheck = filter_var($email, FILTER_VALIDATE_EMAIL)&& preg_match('/@.+\./', $email);
		if(valid_email($email))
		{
			$check  = $this->user_model->check_user($email,$password,'fr_user');
			if($check !== FALSE)
			{
				$activation = $this->user_model->check_activation($email);
				if($activation  == false)
				{
					$this->response(array('status' => 'error','msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Please make sure your account is activated! You can check your email to activate the account.</b></div> '));
				}
				else
				{
					if($check->status == 'Banned')
					{
						$this->response(array('status' => 'error','msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Sorry! Your account has been banned by the administrator ! </b></div> '));
					}
					else
					{
						$newdata = array(
							   'id'  => $check->user_id,
							   'email'  => $check->email,
							   'name'  => $check->name,
							   'status' => $check->status
						 );
						 $this->response($newdata,200);
					}
				}
			}
			else
			{
				$this->response(array('status' => 'error','msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>Please make sure your username and password is correct! </b></div> '));
			}
		}
		else
			$this->response(array('status' => 'error','msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>Invalid Email Address!</b></div> '));
    }

	public function forgotPassword_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$email = $arr['txt_pwd_recovery_email'];
		$new_password = uniqid(md5(rand()), true);
		$new_password = substr(strtoupper($new_password), 0, 10);
		$result = $this->user_model->update_password($email, $new_password);
		if($result)
		{
			$subject = 'Password Reset';			
			$body = "Please use the following code to login your fresco account. <br> Here is the code : <b>".$new_password." </b>";
			
			$setting = $this->system_model->profile_setting();
			$template = $this->system_model->get_setting()->template;
			
			$sample = array("{logo}","{body}", "{company_name}", "{address}", "{website}", "{email}", "{facebook}", "{phone}");
				
			if(isset($setting) && $setting !== false)
				$real   = array('<img src="'.base_url().'assets/img/fresco_logo6.jpg" />', $body, $setting->company_name, $setting->address, $setting->website, $setting->email, '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/'.$setting->facebook.'">'.$setting->facebook .'</a>', $setting->phone);
			else
				$real = array('<img src="'.base_url().'assets/img/logo.jpg" />', $body, "Fresco, Valleverde Co.Ltd", "88 Myakhantar lane, Insein Township, Yangon. ", "www.frescomyanmar.com", "info@frescomyanmar.com",  '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/frescomyanmar">frescomyanmar</a>', '(95) 9 7922 26852, (95) 9 7922 27491');
				
			$message = str_replace($sample,$real,$template);
			
			$mail = $this->email->sent_email($email, $subject, $message);

			if($mail == true)
			{
				$this->response(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>New Password has been sent to your email. Please check your Spam box if you cannot find our mail in your Inbox. </b></div> '));
			}
			else
			{
				$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Error in Processing ! Please try again. </b></div> '));
			}	
		}
		else
		{
			$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Invalid Email ! Are you sure you\'ve registerd with this email ? </b></div> '));			
		}
	}
    
	public function updateContact_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
      
		$data['userid'] = $arr['hidID'];
		$data['name'] = $arr['txtname'];		
		$result = $this->user_model->update_contact($data);		
		if($result)
		{
			$this->response(array('status'=> 'success','msg'=> '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> Profile Detail successfully updated ! </b></div> '));
		}
		else
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Sorry for your inconvenience.  </b></div> '));
		}
	}
	
	public function updateNewPassword_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$password = $arr['txt_old_pwd'];
		$newpassword = $arr['txt_new_pwd'];
		$confirmpassword = $arr['txt_confirm_pwd'];
		$userid = $arr['hidID'];
		$result = $this->user_model->check_old_password($userid, MD5(MD5($password)));  
		if($result)
		{
			if($newpassword == $confirmpassword)
			{
				$result = $this->user_model->update_new_password($userid, MD5(MD5($newpassword)));
				if($result)
				{
					$this->response(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>You\'ve successfully changed your password! Please login with your new password next time.</b></div> '));
				}
				else
				{
					$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Sorry, there is an error encountered when processing your request. Sorry for your inconvenience.</b></div>'));
				}
			}
			else
			{
				$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> New Password and Confirm Password do not match .</b></div>'));
			}
		}
		else
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Incorrect Old Password ! Please try again ! </b></div>'));
		}
	}
	
	public function register_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$data['email'] = $arr['txt_email'];
		$data['name'] = $arr['txt_name'];
		$data['password'] = $arr['txt_password'];
		
		// generate activation code
		$data['activation_code'] = substr(strtoupper(uniqid(md5(rand()), true)),0,50);
		
		if($data['email'] == '')
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Your email address is mandatory ! </b></div> '));
		}
		
		$this->load->helper('email');
		if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)&& preg_match('/@.+\./', $data['email']) == false)
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Invalid email address! </b></div> '));
		}
		
		if($data['password'] == '')
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Password is mandatory ! </b></div> '));
		}
		
		// check email duplication
		$check =  $this->user_model->check_email_duplication($data['email']);
		
		if( ! $check)
		{
			$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Registration Failed! This email already exist!</b></div> '));
		}
		else
		{
			$data['status'] = 'New';
			
			//Send activation email to user
			$this->load->library('email');	
			$this->load->model("system_model");			
						
			$subject = 'Account Activation';
			
			$body = 'Thank you for joining FRESCO Vegy Box Home Delivery Service! Click on the button below to activate your account. After activating your account, you may start ordering the vegy boxes.<br> <center><a style="border-radius: 3px;display: inline-block;font-size: 14px;font-weight: 700;line-height: 24px;padding: 13px 35px 12px 35px;text-align: center;text-decoration: none !important;transition: opacity 0.2s ease-in;color: #fff;font-family: Cabin,Avenir,sans-serif;background-color: #4c5b6b;" href="'.base_url().'user/activate_user/'.$data['activation_code'].'" target="_blank">Activate Now</a></center>';
			
			$setting = $this->system_model->profile_setting();
			$template = $this->system_model->get_setting()->template;
			
			$sample = array("{logo}","{body}", "{company_name}", "{address}", "{website}", "{email}", "{facebook}", "{phone}");
			
			if(isset($setting) && $setting !== false)
				$real   = array('<img src="'.base_url().'assets/img/fresco_logo6.jpg" />', $body, $setting->company_name, $setting->address, $setting->website, $setting->email, '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/'.$setting->facebook.'">'.$setting->facebook .'</a>', $setting->phone);
			else
				$real = array('<img src="'.base_url().'assets/img/logo.jpg" />', $body, "Fresco, Valleverde Co.Ltd", "88 Myakhantar lane, Insein Township, Yangon. ", "www.frescomyanmar.com", "info@frescomyanmar.com",  '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/frescomyanmar">frescomyanmar</a>', '(95) 9 7922 26852, (95) 9 7922 27491');
				
			$message = str_replace($sample,$real,$template);
			
			$activation_mail = $this->email->sent_email($data['email'],$subject,$message);
		
			if($activation_mail == true)
			{
				$result = $this->user_model->register_user($data);
			
				if($result)
				{			
					
					$this->response(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>Registration Successful ! Please check your email and activate your account to enjoy our service. Please check your Spam box if you cannot find our mail in your Inbox.</b></div> '));
				}
				else
				{
					$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Registration Failed ! Please try again. </b></div> '));
				}
				
			}
			else
			{
				$this->response(array('status' => 'error', 'message' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Registration Failed ! Please try again. </b></div> '));
			}
		}
	}
	
	public function userAddresses_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$this->load->model('order_model');
		$userid = $arr['userid'];
		
		$data = $this->order_model->get_user_address($userid);	
		if($data)
		{ 
			$this->response(array('status'=> 'success','list'=> $data));
		}
		else
		{
			$this->response(array('status'=> 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Sorry for your inconvenience.  </b></div> '));
		}
	}

}
