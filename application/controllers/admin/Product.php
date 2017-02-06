<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/product_model');
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
	|-------------------------------------------
	| VEGY BOX
	|-------------------------------------------
	*/
	
	public function box_list()
	{
		$this->load->library('pagination');	
		
		
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$data['offset'] = $this->uri->segment(4,0);

		$row_count = 0;
		$data['list'] = $this->product_model->get_product_list($config['per_page'],$data['offset'],$row_count);
		$config['base_url'] =  base_url().'admin/product/box_list';
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
		$this->load->view('admin/box_list.php',$data);
	}
	
	function edit_box($id = "")
	{
		if($id != '')
		{
			$data['box'] = $this->product_model->get_box_details($id);
			
		}
		else
		{
			$data['box'] = false;
			//$this->session->set_flashdata('error_msg', '<div class="alert alert-warning alert-dismissible" role="alert">  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>  An error encountered while processing. Please refresh the page and try again.</div>');
			//redirect(base_url().'admin/product/box_list');
		}
		
		$this->load->view('admin/edit_box_view_myanmar.php',$data);
	}
	
	function insert_box()
	{ 
		$data['id'] = $this->input->post('hidID');		
		$data['name'] = $this->input->post('txtname');
		$data['price'] = $this->input->post('txtprice');
		$data['description'] = $this->input->post('txtdesc');
		$data['status'] = $this->input->post('cbostatus');
		
		if($_FILES["file"]["name"] != "") 
		{		
			// File Upload	
			$config['upload_path']          = getcwd().'/assets/img/'; 
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 1000;
			$config['max_width']            = 1024;
			$config['max_height']           = 1024;
	
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload('file'))
			{
				$err = $this->upload->display_errors('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>', '</b></div>');
				$this->session->set_flashdata('error_msg', $err);
				redirect(base_url().'admin/product/edit_box/'.$data['id']);
			}
			else
			{
				$upload = $this->upload->data();
				$data['img'] = 'img/'.$upload['file_name'];
			}
		}
		
		$result = $this->product_model->insert_box($data);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
			redirect(base_url().'admin/product/edit_box/'.$data['id']);
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully '.($data['id'] !== '' ? 'edited a' : 'added a new') .'  box ! </b></div>');
			redirect(base_url().'admin/product/box_list');
		}
		
	}

	function insert_box_lang()
	{ 
		$data['id'] = $this->input->post('hidID');		
		$data['name'] = $this->input->post('txtname');
		$data['price'] = $this->input->post('txtprice');
		$data['description'] = $this->input->post('txtdesc');
		$data['status'] = $this->input->post('cbostatus');
		$data['lang'] = $this->input->post('cbolang');
		
		if($_FILES["file"]["name"] != "") 
		{		
			// File Upload	
			$config['upload_path']          = getcwd().'/assets/img/'; 
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 1000;
			$config['max_width']            = 1024;
			$config['max_height']           = 1024;
	
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload('file'))
			{
				$err = $this->upload->display_errors('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>', '</b></div>');
				$this->session->set_flashdata('error_msg', $err);
				redirect(base_url().'admin/product/edit_box/'.$data['id']);
			}
			else
			{
				$upload = $this->upload->data();
				$data['img'] = 'img/'.$upload['file_name'];
			}
		}
		
		$result = $this->product_model->insert_box($data);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
			redirect(base_url().'admin/product/edit_box/'.$data['id']);
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully '.($data['id'] !== '' ? 'edited a' : 'added a new') .'  box ! </b></div>');
			redirect(base_url().'admin/product/box_list');
		}
		
	}
	
	function delete_box($box_id)
	{
		$result = $this->product_model->delete_box($box_id);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully deleted a  box ! </b></div>');
			
		}
		redirect(base_url().'admin/product/box_list');
	}
	
	/*
	|-------------------------------------------
	| ADDITIONAL ITEMS
	|-------------------------------------------
	*/
	
	public function item_list()
	{
		$this->load->library('pagination');	
		
		
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$data['offset'] = $this->uri->segment(4,0);

		$row_count = 0;
		$data['list'] = $this->product_model->get_item_list($config['per_page'],$data['offset'],$row_count);
		$config['base_url'] =  base_url().'admin/product/item_list';
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
		$this->load->view('admin/item_list_view.php',$data);
	}
	
	function item_entry($id = "")
	{
		if($id != '')
		{
			$data['item'] = $this->product_model->get_item_details($id);
			
		}
		else
		{
			$data['item'] = false;
		}
		
		$this->load->view('admin/item_entry_view.php',$data);
	}
	
	function insert_item()
	{
		$data['id'] = $this->input->post('hidID');
		$data['name'] = $this->input->post('txtname');
		$data['description'] = $this->input->post('txtdesc');
		$data['type'] = $this->input->post('txtype');
		
		$sub['subID'] = $this->input->post('hid_subID');
		$sub['weight'] = $this->input->post('txtweight');
		$sub['price'] = $this->input->post('txtprice');
		$sub['status'] = $this->input->post('cbostatus');
		
		// File Upload	
		if($_FILES["file"]["name"] != "") 
		{		
			// File Upload	
			$config['upload_path']          = getcwd().'/assets/img/'; 
			$config['allowed_types']        = 'gif|jpg|png|jpeg';
			$config['max_size']             = 1000;
			$config['max_width']            = 1024;
			$config['max_height']           = 1024;
	
			$this->load->library('upload', $config);
	
			if ( ! $this->upload->do_upload('file'))
			{
				$err = $this->upload->display_errors('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i>', '</b></div>');
				$this->session->set_flashdata('error_msg', $err);
				redirect(base_url().'admin/product/edit_box/'.$data['id']);
			}
			else
			{
				$upload = $this->upload->data();
				$data['img'] = $upload['file_name'];
			}
		}
		
		$result = $this->product_model->insert_item($data, $sub);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
			redirect(base_url().'admin/product/edit_item/'.$data['id']);
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully '.($data['id'] !== '' ? 'edited a' : 'added a new') .'  item ! </b></div>');
			redirect(base_url().'admin/product/item_list');
		}
		
	}
	
	function remove_sub_item()
	{
		$item_id = $this->input->post('item_id');
		$result = $this->product_model->remove_sub_item($item_id);
		if(!$result)
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>There is an internal server error occured while deleting the sub item. Please refresh the page and try again. Sorry for your inconvenience.</b></div> '));
			return;
		}
		else
		{
			echo json_encode(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i>Sub Item successfully removed !</b></div> '));
			return;
		}
	}
	
	function delete_item($item_id)
	{
		$item_id = str_replace('-',',',$item_id);
        //$item_id = $this->input->request('id'); 
        
		
		$result = $this->product_model->delete_item($item_id);
		if($result == false)
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b> Sorry, there is some internal server occur when processing your request. Please refresh the page and try again. Sorry for your inconvinence. </b></div>');
		}
		else
		{
			$this->session->set_flashdata('error_msg', '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-ok"></i> You have successfully deleted an item ! </b></div>');
			
		}
		redirect(base_url().'admin/product/item_list');
	}
	
}


/* End of file product.php */
/* Location: ./application/controllers/admin/product.php */