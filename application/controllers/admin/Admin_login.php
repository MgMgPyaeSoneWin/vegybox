<?php
class Admin_login extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/admin_model');
		$this->load->library('MY_Data');
	}
	
	function index(){
		$error = $this->my_data->err_message();
        $userdata = $this->session->userdata('userdata');
		if(!$this->session->userdata('userdata'))
		{
			$this->load->view('admin/admin_login_view');
		}
		else if($userdata['user_role'] != "Admin")
		{
			$this->session->set_flashdata('error_msg', $error['admin_login_not']);
			redirect('admin/admin_login');
		}
		else
		{
            redirect('admin/dashboard/main');
		}
		
	}
	
	function login()
	{
		try{
			$this->load->library('form_validation');
			$error = $this->my_data->err_message();
			$this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('admin_password', 'Password', 'trim|required');
			if($this->form_validation->run() != FALSE)
			{
				
				$email = $this->input->post('admin_email');
				$password = $this->input->post('admin_password');
				$result = $this->admin_model->check_login($email, $password);
				if($result != false)
				{
					
					$newdata = array(
						'id'  => $result->user_id,
						'email'  => $result->email,
						'status' => $result->status,
						'user_role' => $result->user_role
					);
					$this->session->set_userdata('userdata',$newdata);
					redirect('admin/dashboard/main');
				}
				else
				{
					$this->session->set_flashdata('error_msg', $error['admin_login_error']);
					$this->load->view('admin/admin_login_view');
				}
			}
			else
			{
				$this->session->set_flashdata('error_msg', $error['admin_login_validate_error']);
				$this->load->view('admin/admin_login_view');
			}
		}
		catch(Exeception $ex)
		{
			//error here
		}
	}
	
	function change_password()
	{
		$this->load->view('admin/change_password_view');
	} 
	
	function password_change()
	{
		try{
			$error = $this->my_data->err_message();
			$success = $this->my_data->sucess_message();
			$old_pass = $this->input->post('old_pass');
			$new_pass = $this->input->post('new_pass');
			$user_data = $this->session->userdata('userdata');
			$check_old = $this->admin_model->check_oldpass($user_data['email'],$old_pass);
			if($check_old == false)
			{
				$this->session->set_flashdata('error_msg', $error['admin_oldpass_wrong']);
				$this->load->view('admin/change_password_view');
			}
			else
			{
				$change_pass = $this->admin_model->change_password($user_data['email'],$new_pass);
				$this->session->set_flashdata('error_msg', $success['admin_pass_change']);
				$this->load->view('admin/change_password_view');
			}
		}
		catch(Exeception $ex)
		{
			//error here
		}
	}
	
	function log_out()
	{
		$this->session->unset_userdata('userdata');
		$this->session->sess_destroy();
		redirect('admin/admin_login');
	}
}
?>