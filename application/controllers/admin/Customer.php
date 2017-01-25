<?php
class customer extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/customer_model');
		$this->load->library('MY_Data');
		$error = $this->my_data->err_message();
		
        $userdata = $this->session->userdata('userdata');
        if(!$this->session->userdata('userdata'))
    	{
			$this->session->set_flashdata('error_msg', $error['admin_login_require']);
			redirect('admin/admin_login');
		}
		else if($userdata['user_role'] != "Admin")
		{
			$this->session->set_flashdata('error_msg', $error['admin_login_not']);
			redirect('admin/admin_login');
		}
	}
	
	function mange_customer()
	{
		$this->load->library('pagination');
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['customer'] = $this->customer_model->get_customer($config['per_page'],$this->uri->segment(4), $row_count);
		$config['base_url'] =  base_url().'admin/customer/mange_customer';
		$config['total_rows'] = $row_count;
		$config['full_tag_open'] = '<div class="col-md-11 pull-right"><ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul></div>';
		$config['cur_tag_open'] = '<li><a href=# style="color:#ffffff; background-color:#258BB5;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo; '; 
		$data['type'] = 'current';
		
		$this->pagination->cur_page = $this->uri->segment(4);
		$this->pagination->initialize($config);
		
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
		$this->load->view('admin/customer_manage',$data);		
	}
	
	function archieve_customer()
	{
		$this->load->library('pagination');
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['customer'] = $this->customer_model->get_archievecustomer($config['per_page'],$this->uri->segment(4), $row_count);
		$config['base_url'] =  base_url().'admin/customer/archieve_customer';
		$config['total_rows'] = $row_count;
		$config['full_tag_open'] = '<div class="col-md-11 pull-right"><ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul></div>';
		$config['cur_tag_open'] = '<li><a href=# style="color:#ffffff; background-color:#258BB5;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo; '; 
		$data['type'] = 'archieve';
		
		$this->pagination->cur_page = $this->uri->segment(4);
		$this->pagination->initialize($config);
		
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
		$this->load->view('admin/customer_manage',$data);		
	}
	
	function activate_customer()
	{
		echo $this->customer_model->activate_customer($_POST['userid']);
		//redirect('admin/customer');
	}
	
	function ban_customer()
	{
		echo $this->customer_model->ban_customer($_POST['userid']);
		//redirect('admin/customer');
	}
	
	function unban_customer()
	{
		echo $this->customer_model->unban_customer($_POST['userid']);
		//redirect('admin/customer');
	}
	
	function search_customer()
	{
		$queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
        $name = $arr['/admin/customer/search_customer?name'];
        $email = $arr['email'];
        $data['type'] = $arr['type'];
		
        $error = $this->my_data->err_message();
        
		if($name == "" && $email == "")
		{
			$this->session->set_flashdata('error_msg', $error['cusmange_serchempty']);
			redirect(base_url().'admin/customer/mange_customer');
		}
		if($name == NULL && $email == NULL)
		{
			$this->session->set_flashdata('error_msg', $error['cusmange_serchempty']);
			redirect(base_url().'admin/customer/mange_customer');	
		}
		else
		{
			$this->load->library('pagination');
			$page = $this->input->get('per_page');	
			$data['per_page'] = 10;
			$config['per_page'] = $data['per_page'];
			$row_count = 0; 
			$data['customer'] = $this->customer_model->search_customer($name, $email, $config['per_page'], $page, $row_count);			
			$config['base_url'] =  base_url().'admin/customer/search_customer?name='.$name.'&email='.$email;
			$config['total_rows'] = $row_count;
			$config['full_tag_open'] = '<div class="col-md-11 pull-right"><ul class="pagination pull-right">';
			$config['full_tag_close'] = '</ul></div>';
			$config['cur_tag_open'] = '<li><a href=# style="color:#ffffff; background-color:#258BB5;">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '&raquo;';
			$config['prev_link'] = '&laquo; '; 
			$config['page_query_string'] = TRUE;			
			$this->pagination->cur_page = $page;
			$this->pagination->initialize($config);
			$data['name'] = $name;
			$data['email'] = $email;
			$data['icon'] = 'fa-fa-sort';
			$data['pagination'] = $this->pagination->create_links();
			$this->load->view('admin/customer_manage',$data);
		}
	}
	
	function stop_orderreply($id)
	{
		$data['id'] = $id;
		$user = $this->customer_model->get_email($id);
		$data['email'] = $user[0]->email;
		$data['order_msg'] = $user[0]->message;		
		$this->load->view('admin/reply_email_view',$data);
	}
	
	function send_email()
	{
		$email = $this->input->post('email');
		$message = $_POST['email_msg'];
		
		$data['id'] = $this->input->post('userid');
		$data['order_msg'] = $this->input->post('stop_msg');		
		$data['email'] = $email;

		// send email
		$this->load->library('email');	
		$this->load->model("system_model");
					
		$subject = 'Order Cancelling Response';
		
		$setting = $this->system_model->profile_setting();
		$template = $this->system_model->get_setting()->template;
		
		$sample = array("{logo}","{body}", "{company_name}", "{address}", "{website}", "{email}", "{facebook}", "{phone}");
			
		if(isset($setting) && $setting !== false)
			$real   = array('<img src="'.base_url().'assets/img/fresco_logo6.jpg" />', $message, $setting->company_name, $setting->address, $setting->website, $setting->email, '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/'.$setting->facebook.'">'.$setting->facebook .'</a>', $setting->phone);
		else
			$real = array('<img src="'.base_url().'assets/img/logo.jpg" />', $message, "Fresco, Valleverde Co.Ltd", "88 Myakhantar lane, Insein Township, Yangon. ", "www.frescomyanmar.com", "info@frescomyanmar.com",  '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/frescomyanmar">frescomyanmar</a>', '(95) 9 7922 26852, (95) 9 7922 27491');
			
		$message = str_replace($sample,$real,$template);
		
		
		$mail = $this->email->sent_email($email, $subject, $message);
		if($mail == true)
		{
			//update db
			$this->customer_model->update_noti_status($data['id']);
			$this->session->set_flashdata('error_msg','<div class="alert alert-success">Email successfully send !<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
			$this->load->view('admin/reply_email_view',$data);
		}
		else
		{
			$this->session->set_flashdata('error_msg','<div class="alert alert-danger">Sorry, an error encountered when sending your email. Please refresh the page and try again. Sorry for your inconvinence. <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
			$this->load->view('admin/reply_email_view',$data);
		}
	}
	
	function view_history($id)
	{
		$data['user_info'] = $this->customer_model->get_user($id);
		$this->load->view('admin/customer_history_view',$data);
	}
	
}