<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("order_model");
	}


	public function index()
	{ 
		$data['boxes'] = $this->order_model->get_boxes();
		$this->load->view('home', $data);
	}
	
	function check_status()
	{
		$userdata = $this->session->userdata('userdata');		
		
		// check if his previous order is delivered	
		if(! $userdata || $userdata['status'] == 'New')
		{
			echo json_encode(array('status' => 'show'));
			return;
		}
		else 
		{
			$delivery_status = $this->order_model->check_order_status($userdata['id']);
			
			if($delivery_status == false)
			{
				echo json_encode(array('status' => 'hide'));
				return;
			}
			else
			{
				echo json_encode(array('status' => 'show'));
				return;
			}
		}		
	}
	
	public function faq()
	{
		$this->load->model("faq_model");
		$this->load->library('pagination');
		
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$data['offset'] = $this->uri->segment(3,0);
	
		$row_count = 0;
		$data['list'] = $this->faq_model->get_faq_list($config['per_page'],$data['offset'],$row_count);

		$config['base_url'] =  base_url().'main/faq';
		$config['total_rows'] = $row_count;
		$config['full_tag_open'] = '<div class="col-xs-12"><center><ul class="pagination">';
		$config['full_tag_close'] = '</ul></center></div>';
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
		
		
		$this->pagination->cur_page = $data['offset'];
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();
		$this->load->view('faq', $data);
	}
	
	public function contact_us()
	{
		$this->load->view('contact_view');
	}
	
	public function error($type)
	{
		if($type == 'max_box')
		{
			$data['title'] = '<i class="glyphicon glyphicon-warning-sign"></i> Sorry, Orders closed!';
			$data['msg'] = 'The maximum box limit for this delivery date has exceeded. Please try again with the different delivery date. Thank you! <br> <center><a href="'.base_url().'order" class="btn btn-success">Go back to order page</a></center>';
		}
		else if($type == 404)
		{
			$data['title'] = '<i class="glyphicon glyphicon-remove-sign"></i> Page not found !';
			$data['msg'] = 'Sorry, the page you are trying to access is not available. Click <a href="'.base_url().'main" class="btn btn-link" style="color: #337ab7; text-decoration: underline;">here</a> to go back to home page ';
		}
		else if($type == 500)
		{
			$data['title'] = '<i class="glyphicon glyphicon-wrench"></i> Something went wrong !';
			$data['msg'] = 'Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. ';
		}
		else if($type == 'deny')
		{
			$data['title'] = '<i class="glyphicon glyphicon-remove-sign"></i> Access Denied!';
			$data['msg'] = 'You are not allowed to access this page ! ';
		}
		else if($type == 'edit')
		{
			$data['title'] = '<i class="glyphicon glyphicon-remove-sign"></i> Invalid Request!';
			$data['msg'] = 'Sorry, you can only edit your order not later than 3 days before the delivery is to be made. Click <a href="'.base_url().'main/faq" class="btn btn-link" style="color: #337ab7; text-decoration: underline;">here</a> to view some FAQ for your reference. ';
		}
		$this->load->view('error_view', $data);
	}
	
	function mail_template()
	{
		$this->load->model("system_model");
		$data['setting'] = $this->system_model->profile_setting();
		$this->load->view('template', $data);
	}
	
	function test_email()
	{
	
	      /* $msg = "First line of text\nSecond line of text";
    $msg = wordwrap($msg,70);
    $headers = "From: unique.angel.paradise@gmail.com";
   echo mail("shweyee.myawin@gmail.com", "My subject", $msg, $headers);
   
   		$to='shweyee.myawin@gmail.com';
$subject='Application Form ';
$message='testing';

 $headers  = 'MIME-Version: 1.0' . "\r\n";
 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 $headers .= 'From: admin <unique.angel.paradise@gmail.com>' . "\r\n";
 if(mail($to,$subject,$message,$headers))
 {
  echo "Mail Successfully Sent..";
//  exit;
 }*/
	
		// send verification email
		$this->load->library('email');		
		$this->load->model("system_model");
				
		$subject = 'Password Reset';
		
		$body = '<p>Please use the following code to login your fresco account. </p> <br> Here is the code : <b> a$1tQ90 </b><br> <center><a style="border-radius: 3px;display: inline-block;font-size: 14px;font-weight: 700;line-height: 24px;padding: 13px 35px 12px 35px;text-align: center;text-decoration: none !important;transition: opacity 0.2s ease-in;color: #fff;font-family: Cabin,Avenir,sans-serif;background-color: #4c5b6b;" href="http://test.com">Activate Now</a></center>';
		
		$setting = $this->system_model->profile_setting();
		$template = $this->system_model->get_setting()->template;
		
		$sample = array("{logo}","{body}", "{address}", "{website}", "{email}", "{facebook}", "{phone}", "{company_name}");
		$real   = array('<img src="'.base_url().'assets/img/fresco_logo6.jpg" />', $body, $setting->address, $setting->website, $setting->email, '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/'.$setting->facebook.'">'.$setting->facebook .'</a>', $setting->phone, 'Fresco');


		$message = str_replace($sample,$real,$template);
		$email = 'shweyee.myawin@gmail.com';
		
		echo $this->email->sent_email($email, $subject, $message);
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */