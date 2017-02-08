<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/system_model');
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
	
	/*	
	|-------------------------------------------------
	| System Settings
	|_________________________________________________
	*/
	
	function settings()
	{
		$data['setting'] = $this->system_model->get_setting();				
		$this->load->view('admin/setting_view.php',$data);
	}
	
	function edit_settings()
	{
		$data['max_box'] = $this->input->post('txtmax');
		$data['address'] = $this->input->post('txtaddress');
		$data['phone'] = $this->input->post('txtphone');
		$data['email'] = $this->input->post('txtemail');
		$data['box_price'] = $this->input->post('txtprice');
		$data['autoreply'] = $this->input->post('txtauto');
		$data['smtp_username'] = $this->input->post('txtsmtp_email');
		$data['smtp_pwd'] = $this->input->post('txtsmtp_pwd');
		
		$result = $this->system_model->update_settings($data);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
			redirect(base_url().'admin/system/settings/');
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully edited a record ! </b></div>');
			redirect(base_url().'admin/system/settings');
		}
		
	}
	
	/*
	|-------------------------------------------
	| FAQs
	|-------------------------------------------
	*/
	
	public function faq_list()
	{
		$this->load->library('pagination');	
		
		
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$data['offset'] = $this->uri->segment(4,0);

		$row_count = 0;
		$data['list'] = $this->system_model->get_faq_list($config['per_page'],$data['offset'],$row_count); 
		$config['base_url'] =  base_url().'admin/system/faq_list';
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
		$this->load->view('admin/faq_list_view.php',$data);
	}

	
	function faq_entry($id = "")
	{
		if($id != '')
		{
			$data['faq'] = $this->system_model->get_faq_details($id);
		}
		else
		{
			$data['faq'] = false;
		}
		
		$this->load->view('admin/faq_entry_view_myanmar.php',$data);
	}
	
	function insert_faq()
	{ 
		$data['id'] = $this->input->post('hidID');		
		$data['question'] = $this->input->post('txtquestion');
		$data['answer'] = $this->input->post('txtanswer');
		$data['status'] = $this->input->post('cbostatus');

		$result = $this->system_model->insert_faq($data);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
			redirect(base_url().'admin/system/faq_entry/'.$data['id']);
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully '.($data['id'] !== '' ? 'edited a' : 'added a new') .'  FAQ ! </b></div>');
			redirect(base_url().'admin/system/faq_list');
		}
		
	}

    function insert_faq_lang()
    {
        $data['id'] = $this->input->post('hidID');
        $data['question'] = $this->input->post('txtquestion');
        $data['answer'] = $this->input->post('txtanswer');
        $data['status'] = $this->input->post('cbostatus');
        $data['cbolang'] = $this->input->post('cbolang');

        $result = $this->system_model->insert_faq($data);
        if($result == false)
        {
            $this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
            redirect(base_url().'admin/system/faq_entry/'.$data['id']);
        }
        else
        {
            $this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully '.($data['id'] !== '' ? 'edited a' : 'added a new') .'  FAQ ! </b></div>');
            redirect(base_url().'admin/system/faq_list');
        }

    }
	function delete_faq($id)
	{
		$result = $this->system_model->delete_faq($id);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully deleted a record ! </b></div>');
			
		}
		redirect(base_url().'admin/system/faq_list');
	}
	
	
}


/* End of file System.php */
/* Location: ./application/controllers/admin/System.php */