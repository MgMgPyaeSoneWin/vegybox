<?php
class Orders extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
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
	
	function manage_order()
	{
        $this->load->model('admin/order_model');
		$this->load->library('pagination');
        
        $data['name'] = ''; $data['ref'] = ''; $data['delivery'] = ''; $data['town'] = ''; $data['page'] = $this->uri->segment(4);
        
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['order'] = $this->order_model->get_order($config['per_page'],$this->uri->segment(4), $row_count);
		$config['base_url'] =  base_url().'admin/orders/manage_order';
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
		
		$this->pagination->cur_page = $this->uri->segment(4);
		$this->pagination->initialize($config);
		
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
        $data['days'] = $this->delivery_model->get_delivery_day();
		$data['boxes'] = $this->order_model->get_box();
		$data['townships'] = $this->order_model->get_township();
		$this->load->view('admin/order_manage_view',$data);
	}
	
	function search_order($name = '', $ref = '', $delivery = '', $tsp = '', $page = '')
	{
        $this->load->model('admin/order_model');
        $this->load->model('admin/delivery_model');
        
        $this->load->library('pagination');
        
        /*$queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
        $data['name'] = ''; $data['ref'] = ''; $data['delivery'] = ''; $data['town'] = ''; $page = '';
        if(!isset($arr['/admin/orders/manage_order']))
        {
    		$data['name'] = $arr['/admin/orders/search_order?name'];
    		$data['ref'] = $arr['ref'];
    		$data['delivery'] = $arr['delivery'];
    		$data['town'] = $arr['township'];
            
    		if(isset($arr['per_page']))
				$page = $arr['per_page'];
		}*/
        
        $data['page'] = ($page == '' ? 0 : $page);
        $data['name'] = ($name == '' ? 'NULL' : urldecode(mysql_real_escape_string($name)));
        $data['ref'] = ($ref == '' ? 'NULL' : urldecode(mysql_real_escape_string($ref)));
        $data['delivery'] = ($delivery == '' ? 'NULL' : $delivery);
        $data['town'] = ($tsp == '' ? 'NULL' : $tsp);
        
        $error = $this->my_data->err_message();
                
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['order'] = $this->order_model->search_order($data, $config['per_page'], $data['page'], $row_count);
		$config['base_url'] =  base_url().'admin/orders/search_order/'.$data['name'].'/'.$data['ref'].'/'.$data['delivery'].'/'.$data['town'];
        
		//$data['order'] = $this->order_model->search_order($data, $config['per_page'], $page, $row_count);			
		//$config['base_url'] =  base_url().'admin/orders/search_order?name='.$data['name'].'&ref='.$data['ref'].'&township='.$data['town'].'&delivery='.$data['delivery'];
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
		//$config['page_query_string'] = TRUE;			
		
        $this->pagination->cur_page = $data['page'];
		$this->pagination->initialize($config);
        
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
        
        $data['days'] = $this->delivery_model->get_delivery_day();
		$data['boxes'] = $this->order_model->get_box();
		$data['townships'] = $this->order_model->get_township();
		$this->load->view('admin/order_manage_view',$data);
	}
	
	function deliver_order()
	{
        $this->load->model('admin/order_model');
		echo $this->order_model->deliver_order($_POST['order_id'],$_POST['order_date']);
	}
	
	function change_week()
	{
        $this->load->model('admin/order_model');
		echo $this->order_model->change_week($_POST['order_id'],$_POST['order_date']);
	}
	
	function delivered_order( $customer = '', $ref = '', $delivery = '', $page = '')
	{        
        $this->load->model('admin/order_model');
        $this->load->model('admin/delivery_model');
        
    	$this->load->library('pagination');
        
        $data['page'] = ($page == '' ? 0 : $page);
        $data['customer'] = ($customer == '' ? 'NULL' : urldecode(mysql_real_escape_string($customer)));
        $data['ref'] = ($ref == '' ? 'NULL' : urldecode(mysql_real_escape_string($ref)));
    	$data['delivery'] = ($delivery == '' ? 'NULL' : $delivery);
                
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['order'] = $this->order_model->get_deliver_order($data['customer'], $data['ref'], $data['delivery'],$config['per_page'],$data['page'], $row_count);
		$config['base_url'] =  base_url().'admin/orders/delivered_order/'.$data['customer'].'/'.$data['ref'].'/'.$data['delivery'];
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
		
		$this->pagination->cur_page = $page;
		$this->pagination->initialize($config);
		
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
        
		$data['days'] = $this->delivery_model->get_delivery_day();
    	$data['townships'] = $this->order_model->get_township();
		$this->load->view('admin/delivered_order_view',$data);
        
	}
    
    function cancel_order()
    {        
        $this->load->model('admin/order_model');
        
        $order_id = $this->input->post('order_id');
        $userid = $this->input->post('user_id');
        echo $this->order_model->update_order_status($userid, $order_id, 'Stop');
    }
    
    function pause_subscription()
    {
        $this->load->model('order_model');
        
		$userid = $this->input->post('user_id');
		$order_id = $this->input->post('order_id');
		$status = 'Pause';
		$result = $this->order_model->update_order_status($userid, $order_id, $status);
		if(!$result)
		{
			echo json_encode(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> On Hold Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
			return;
		}
		else
		{
			echo json_encode(array('status' => 'success', 'title' => '<i class="glyphicon glyphicon-ok-sign"></i> On Hold Subscription Successful! ', 'msg' => 'Your order subscription has been successfully on hold!'));
			return;
		}
	}
	
	function resume_subscription()
	{
        $this->load->model('order_model');
        
		$userid = $this->input->post('user_id');
		$order_id = $this->input->post('order_id');
		$status = 'On-going';
		
		$delivery_day = $this->order_model->get_order_delivery_day($order_id);
		$box_count = $this->order_model->get_ordered_boxes_count($order_id);
		
		if($delivery_day !== false && $box_count !== false)
		{
			$limit = $this->check_box_limit($delivery_day, $box_count);
		
			if( $limit == false)
			{
				echo json_encode(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'The maximum box limit for "'.strtoupper($delivery_day).'" has exceeded. Please try again with the different delivery date. Thank you! '));
				return;
			}
			else
			{
				$result = $this->order_model->update_order_status($userid, $order_id, $status);
				if(!$result)
				{
					echo json_encode(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
					return;
				}
				else
				{
					switch ($delivery_day) {
						case "2":
							$delivery_day = 'Monday';
							break;
						case "3":
							$delivery_day = 'Tuesday';
							break;
						case "4":
							$delivery_day = 'Wednesday';
							break;
						case "5":
							$delivery_day = 'Thursday';
							break;
						case "6":
							$delivery_day = 'Friday';
							break;
						case "7":
							$delivery_day = 'Saturday';
							break;
						case "1":
							$delivery_day = 'Sunday';
							break;
					}
					
					echo json_encode(array('status' => 'success', 'title' => '<i class="glyphicon glyphicon-ok-sign"></i> Resuming Subscription Successful ! ', 'msg' => 'Order has been successfully resumed! Customer will get the order start from coming "'.strtoupper($delivery_day).'".'));
					return;
				}
			}
		}
		else
		{
			echo json_encode(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
			return;
		}
		
	}
    
    function check_box_limit($delivery_day, $box_count)
    {		
        $this->load->model('system_model');
        $this->load->model('order_model');
		$current_box = $this->order_model->check_box_limit($delivery_day);
		$max_box_limit =$this->system_model->get_box_limit()->max_box;		
		
		if( ($current_box + $box_count) > $max_box_limit)
		{
			return false;
		}
		else
		{
			return true;
		}

	}
    
    //Add order details function by Ei Mon Kyaw 22 Apr 2016
    function order_details($order_id)
	{
        $this->load->model('admin/order_model');
		$data["orders"] = $this->order_model->get_orderdetails($order_id);
		$this->load->view('admin/order_details_view',$data);
	}
}