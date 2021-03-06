<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('system_model');
	}
	
	function index()
	{
		$this->load->view('login_view');
	}
	
	function verify()
	{
		$this->load->library('form_validation');
		$tablename = 'fr_user';
		
		
		$this->form_validation->set_rules('txtemail','Email','trim|required|valid_email');
		$this->form_validation->set_rules('txtpassword','Password','trim|required');

		if($this->form_validation->run()!== FALSE)
		{
		    // User is valid or not
			$check	  = $this->user_model->check_user($this->input->post('txtemail'),$this->input->post('txtpassword'),$tablename);

            // User is valid
			if($check !== FALSE)
			{
			    // User is actived or not
				$activation = $this->user_model->check_activation($this->input->post('txtemail'));
		
				if($activation == false)
				{
					echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('checkActivated').'</b></div> '));
				return;
				}
				else
				{
                    // User is actived and checking status
					if($check->status == 'Banned')
					{
						echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('checkBanned').'</b></div> '));
						return;
					}
					else
					{
						// Create userdata Session
						$newdata = array(
							   'id'  => $check->user_id,
							   'email'  => $check->email,
							   'name'  => $check->name,
							   'status' => $check->status
						   );
			
						$this->session->set_userdata('userdata',$newdata);		
						echo true;					
						return;
					}				
				}
			}
			else
			{	
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('checkUsernameAndPassword').'</b></div> '));
				return;
			}
		}
		else
		{ 
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> '.$this->lang->line('checkUsernameAndPassword').'b></div> '));
			return;
		}
	}
	
	function log_out()
	{
		$this->session->unset_userdata('userdata');
		redirect(base_url().'main');
	}
	
	function check_session()
	{
		if(! $this->session->userdata('userdata'))
			echo false;
		else
			echo true;
	}
	
	function forgot_password()
	{
		$email = $this->input->post("txt_pwd_recovery_email");
		$new_password = uniqid(md5(rand()), true);
		$new_password = substr(strtoupper($new_password), 0, 10);
			
		$result = $this->user_model->update_password($email, $new_password);

		if($result)
		{
			// send verification email
			$this->load->library('email');	
			$this->load->model("system_model");
						
			$subject = $this->lang->line('passwordReset');		
			$body = $this->lang->line('codeToLogin').$new_password." </b>";
			
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
				echo json_encode(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>'.$this->lang->line('sendToEmail').'</b></div> '));
				return;
			}
			else
			{
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('errorInProcessing').'</b></div> '));
				return;
			}				
			
			
		}
		else
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('invalidEmail').'</b></div> '));
			return;
		}
	}
	
	function profile()
	{
		if(! $this->session->userdata('userdata'))
			redirect(base_url().'user');
		
		$this->load->model('order_model');
		
		$data['userdata'] = $this->session->userdata('userdata');
		$data['addresses'] = $this->order_model->get_user_address($data['userdata']['id']);
		$data['townships'] = $this->order_model->get_townships(); 
		
		$this->load->view('profile_view',$data);
	}
	
	function update_address()
	{		
        $userdata = $this->session->userdata('userdata');
		$data['userid'] = $userdata['id'];
		$data['edited_add'] = $this->input->post('hid_id');
		$data['contact_person'] = $this->input->post("txtname");
		$data['phone'] = $this->input->post("txtphone");
		$data['mobile'] = $this->input->post("txtmobile");
		$data['township'] = $this->input->post("cbotownship");
		$data['tspdetail_id'] = $this->input->post("rdo_delivery_day");		
		$data['lat'] = $this->input->post("hid_lat");
		$data['lon'] = $this->input->post("hid_long");
		$data['address'] = $this->input->post("txtaddress");	
		$data['instruction'] = $this->input->post("txtinstruction");
		
		$result = $this->user_model->update_address($data);
		
		if($result)
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> Address successfully '.($data['edited_add'] == "" ? 'added' : 'changed').' ! </b></div> ');
		}
		else
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>'.$this->lang->line('errorEncountered').'</b></div> ');
		}	
		
		redirect('user/profile');
	}
	
	function update_contact()
	{
		$userdata = $this->session->userdata('userdata');
    	$data['userid'] = $userdata['id'];
		$data['name'] = $this->input->post('txtname');
		
		$result = $this->user_model->update_contact($data);
		
		if($result)
		{
            $userdata = $this->session->userdata('userdata');
			$newdata = array(
				   'id'  => $data['userid'],
				   'email'  => $userdata['email'],
				   'name'  => $data['name'],
				   'status' => $userdata['status']
			   );

			$this->session->set_userdata('userdata',$newdata);	
			$this->session->set_flashdata('msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i>'.$this->lang->line('profileDetail').'</b></div> ');
		}
		else
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>'.$this->lang->line('errorEncountered').'</b></div> ');
		}	
		
		redirect('user/profile');
	}
	
	function update_new_password()
	{
		
		if(!$this->session->userdata('userdata'))
			redirect(base_url().'user','refresh');
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_old_pwd', 'Old Password', 'trim|required|callback_check_old_password');
		$this->form_validation->set_rules('txt_new_pwd', 'New Password', 'trim|required|matches[txt_confirm_pwd]');
		$this->form_validation->set_rules('txt_confirm_pwd', 'Confirm Password', 'trim|required');
		
		
		if($this->form_validation->run() == FALSE)
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('incorrectOldPassword').'</b></div> '));
				return;
		}
		else
		{
			
			$userdata = $this->session->userdata('userdata');
			$newpassword = $this->input->post('txt_new_pwd');
			$result = $this->user_model->update_new_password($userdata['id'], MD5(MD5($newpassword)));	
			if($result)
			{
				echo json_encode(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>'.$this->lang->line('changeSuccessfully').'</b></div> '));
				return;
			}
			else
			{
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('errorEncountered').'</b></div> '));
				return;
			}					
		}
		
		
	}
	
	function check_old_password($password)
	{
		$userdata = $this->session->userdata('userdata');
		$result = $this->user_model->check_old_password($userdata['id'], MD5(MD5($password)));  
		if($result)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_old_password', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('incorrectOldPassword').'</b></div>');
			return FALSE;
		}	
	} 
	
	
	
	/* ---------------------------------------------------
	|
	|	REGISTRATION
	|
	 ------------------------------------------------------*/
	 
	 function register()
	 {
	 	$data['email'] = $this->input->post('txt_email', TRUE);
		$data['name'] = $this->input->post('txt_name', TRUE);
		$data['password'] = $this->input->post('txt_password', TRUE);
		
		// generate activation code
		$data['activation_code'] = $this->GenerateActivationCode();
		
		if($data['email'] == '')
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('emailMandatory').'</b></div> '));
			return;
		}
		
		$this->load->helper('email');
		if(valid_email($data['email']) == false)
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('invalidEmail').'</b></div> '));
			return;
		}
		
		if($data['password'] == '')
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('passwordMandatory').'</b></div> '));
			return;
		}
		
		// check email duplication
		$check =  $this->user_model->check_email_duplication($data['email']);
		
		if( ! $check)
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>'.$this->lang->line('emailExit').'</b></div> '));
			return;
		}
		else
		{
			$data['status'] = 'New';
			
			//Send activation email to user
			$this->load->library('email');	
			$this->load->model("system_model");			
						
			$subject = $this->lang->line('accActivation');
			
			$body = $this->lang->line('thxUsing').'<br> <center><a style="border-radius: 3px;display: inline-block;font-size: 14px;font-weight: 700;line-height: 24px;padding: 13px 35px 12px 35px;text-align: center;text-decoration: none !important;transition: opacity 0.2s ease-in;color: #fff;font-family: Cabin,Avenir,sans-serif;background-color: #4c5b6b;" href="'.base_url().'user/activate_user/'.$data['activation_code'].'" target="_blank">'.$this->lang->line('activeNow').'</a></center>';
			
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
					echo json_encode(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>'.$this->lang->line('successfulReg').'</b></div> '));
					return;
				}
				else
				{
					echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('regFailed').'</b></div> '));
					return;
				}
				
				
			}
			else
			{
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>'.$this->lang->line('regFailed').'</b></div> '));
				return;
			}
		}
	 }
	 
	function GenerateActivationCode()
	{
		$code = substr(strtoupper(uniqid(md5(rand()), true)),0,50);
		return $code;
	}
	
	function activate_user($code)
	{
		$check = $this->user_model->activate_user($code);
		if($check)
			redirect(base_url().'user');
		else
			echo '<h2>'.$this->lang->line('failActivation').'</h2>';
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */	