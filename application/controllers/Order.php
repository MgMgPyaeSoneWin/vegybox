<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		if(! $this->session->userdata('userdata'))
			redirect(base_url().'user');
			
		$this->load->model("order_model");
		$this->load->model("system_model");
	}

	public function index()
	{
		$data['show'] = false;	
		$data['userdata'] = $this->session->userdata('userdata');		
		
		// check if his previous order is delivered	
		if($data['userdata']['status'] == 'Old' || $data['userdata']['status'] == 'Pending') 
		{
			$delivery_status = $this->order_model->check_order_status($data['userdata']['id']);
			if($delivery_status == false)
			{
				redirect(base_url().'order/my_order');
			}			
		}		
			
		// check user status to show/hide subscription option
		if($data['userdata']['status'] == 'New')
			$data['show'] = true;	

		
		$data['townships'] = $this->order_model->get_townships();
		$data['boxes'] = $this->order_model->get_boxes();		
		$data['address'] = $this->order_model->get_user_address($data['userdata']['id']);
		$data['box_price'] = $this->system_model->get_setting()->box_price;
		
		$data['row_count'] = 0;
		$data['per_page'] = 6;
		$offset = 0;
		$data['items'] = $this->order_model->get_extra_items($data['per_page'],$offset,$data['row_count']);

		$data['item_list_view'] = $this->load->view('item_list_view', $data, true);
		
		$this->load->view('order_view', $data);
	}
	
	function ajax_item_list()
	{
		$userdata = $this->session->userdata('userdata');
		
		$offset = $this->input->post('offset');
		$per_page = 6;

		$row_count = 0;
		$data = $this->order_model->get_extra_items($per_page,$offset,$row_count);

		if($data == false){
				echo 'false';
		}
		else
		{ 
			$json = ""; 
			$output = array(); 
			foreach($data as $row)
			{
				$items  = array(
							'item_id' => $row->item_id,
							'name' => $row->name,
							'description' => $row->description,
							'type' => $row->type,
							'net_weight' => $row->net_weight,
							'price' => $row->price,
							'image' => $row->image,
							'ids' => $row->ids,
							'number' => $row->number
							);
				array_push($output,$items);
			}

			$json .= json_encode($output);
			echo $json;
		}
	}
	
	
	
	public function edit_order($order_id='')
	{
		if($order_id != '')
		{
			$data['userdata'] = $this->session->userdata('userdata');	
			
			// Check if it is current user order
			$order_check = $this->order_model->check_user_order($data['userdata']['id'], $order_id);
			if($order_check === true)
			{
				$data['orderDetails'] = $this->order_model->get_order_details($order_id);
				$delivery_day = $this->order_model->check_weekDay($order_id);
				$today = date('l'); 
				if($delivery_day !== false)
				{				
					// Check if it is before 3 days of delivery				
					$check = false;
					if($delivery_day == 'Monday' && ($today == 'Tuesday' || $today == 'Wednesday' || $today == 'Thursday'))
						$check = true;
					else if($delivery_day == 'Tuesday' && ($today == 'Wednesday' || $today == 'Thursday' || $today == 'Friday'))
						$check = true;
					else if($delivery_day == 'Wednesday' && ($today == 'Thursday' || $today == 'Friday' || $today == 'Saturday'))
						$check = true;
					else if($delivery_day == 'Thursday' && ($today == 'Friday' || $today == 'Saturday' || $today == 'Sunday'))
						$check = true;
					else if($delivery_day == 'Friday' && ($today == 'Saturday' || $today == 'Sunday' || $today == 'Monday'))
						$check = true;
					else if($delivery_day == 'Saturday' && ($today == 'Sunday' || $today == 'Monday' || $today == 'Tuesday'))
						$check = true;
					else if($delivery_day == 'Sunday' && ($today == 'Monday' || $today == 'Tuesday' || $today == 'Wednesday'))
						$check = true;			
					
					$next_del = $this->order_model->get_next_delivery($order_id);
					$date = date('Y-m-d', strtotime($next_del));	
					$date_diff = strtotime($date) - strtotime(date('d-m-Y'));
					$days_left = floor($date_diff/(60*60*24));
			
					if( $check == true  ||  $days_left > 3)
					{
						$data['ordered_boxes'] = $this->order_model->get_ordered_boxes($order_id);
						$data['ordered_items'] = $this->order_model->get_ordered_items($order_id);
						
						$data['townships'] = $this->order_model->get_townships(); 
						$data['boxes'] = $this->order_model->get_boxes();		
						$data['address'] = $this->order_model->get_user_address($data['userdata']['id']);
						$data['box_price'] = $this->system_model->get_setting()->box_price;
						
						$data['row_count'] = 0;
						$data['per_page'] = 6;
						$offset = 0;
						$data['items'] = $this->order_model->get_extra_items($data['per_page'],$offset,$data['row_count']);
				
						$data['item_list_view'] = $this->load->view('item_list_view', $data, true);
						
						$this->load->view('edit_order_view', $data);
					}
					else
					{
						redirect(base_url().'main/error/edit');
					}				
					
				}
				else
				{
					redirect(base_url().'main/error/500');
				}	
			}
			else
			{
				redirect(base_url().'main/error/deny');
			}		
			
		}
		else
		{
			redirect(base_url().'main/error/404');
		}
	}
	
	public function add_order()
	{	
		$data['order_id'] = $this->input->post('hid_order_id');
		if($data['order_id'] !== '')
			$stage = 'edit';
		else
			$stage = 'add';
			
		// check box_limit			
		if($this->input->post("rdo_address") == 'new' || ($this->input->post("rdo_address") == 'old' && $this->input->post('hid_edited_address') != ''))		
		{ 
			$tspdetail_id = $this->input->post("rdo_delivery_day");	
			$data['delivery_day'] = $this->order_model->get_tsp_delivery_day($tspdetail_id);			
		}
		else if($this->input->post("rdo_address") == 'old')
		{
			$data['delivery_day'] = $this->order_model->get_address_delivery_day($this->input->post("rdo_old_address"));
		}
        else
    	{
			$tspdetail_id = $this->input->post("rdo_delivery_day");	
			$data['delivery_day'] = $this->order_model->get_tsp_delivery_day($tspdetail_id);	
		}
			
		$limit = $this->check_box_limit($data['delivery_day'], (int)$this->input->post("txt_box_qty"));
		
		if( $limit == false)
		{
            switch ($data['delivery_day']) 
            {
    		    case "1":
    		        $delivery_day = 'Sunday';
    		        break;
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
    		}
            
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>The maximum box limit for "'.strtoupper($delivery_day).'" has exceeded. Please try again with the different delivery date. Thank you!</b></div> '));
			return;
		}
		
				
		$subtotal = 0;
		$data['userdata'] = $this->session->userdata('userdata');
		$box_price = $this->system_model->get_setting()->box_price;	
		
		// Box details
		$data['subscription'] = $this->input->post("rdo_subscription");	
		
		if($data['subscription'] == 'NO')
		{
			$data['week_status'] = 1;
			$box_id = array();
			$box_num = array();
			$boxes = $this->input->post("hid_box_id");
			$count =0;
			foreach ($boxes as $box => $id) {				
				$count++;
				array_push($box_id, $id);
				array_push($box_num, 1);
				
			}
			
			$data['boxes'] = array_combine($box_id, $box_num);	
			$subtotal += ($count * $box_price); 
		}
		else
		{
			$data['week_status'] = $this->input->post("txt_week_num");
			$box_count = $this->input->post("hid_box_count");
			$box_num = $this->input->post("txt_box_num");
			$box_id = $this->input->post("chk_box_id");
			$box_qty = 0;			
			
			if($box_num == $box_count)
			{
				$box_qty = $box_num / $box_count;
			}
			else if($box_num > $box_count)
			{
				if($box_count == '1')
					$box_qty = $box_num / $box_count;			
			}
		
			$arr_box_id = array();
			$arr_box_num = array();
			
			for($i = 0; $i < count($box_id); $i++)
			{
				array_push($arr_box_id, $box_id[$i]);
				
				if($box_count == 2 && $box_num == 3)
				{					
					$box_qty = $this->input->post("cbo_box_qty_".$box_id[$i]);
					array_push($arr_box_num, $box_qty);
				}
				else
					array_push($arr_box_num, $box_qty);
			}
			$data['boxes'] = array_combine($arr_box_id, $arr_box_num);
			
			
			$subtotal += ((int)$box_num * $box_price) * (int)$data['week_status'];			
		}
		
		
		$data['info'] = $this->input->post("txt_info");
		
		/* Additional Items */
		
		$data['item_subscription'] = $this->input->post("rdo_item_subscription"); 
		$arr_items = array_combine($this->input->post("hid_itemID"), $this->input->post("txt_itemQty"));
		
		$arr_item_id = array();
		$arr_item_qty = array();
		
    	$data['items'] = ''; 
		foreach ($arr_items as $id => $qty) {
    		if($qty != 0)
			{
				array_push($arr_item_id, $id);
				array_push($arr_item_qty, $qty);
				
				// add to subtotal
				$price = $this->order_model->get_item_price($id);
				if($price !== false)
				{
					if($data['item_subscription'] == 'YES')
						$subtotal += ((int)$price * (int)$qty) * (int)$data['week_status'];
					else
						$subtotal += ((int)$price * (int)$qty);
				}				
			}
		}	
		$data['items'] = array_combine($arr_item_id, $arr_item_qty);
      
        $data['subtotal'] = $subtotal;
		
		// Delivery details
		$data['edited_add'] = $this->input->post('hid_edited_address');
		$data['selected_address'] = $this->input->post("rdo_address"); 		
		$data['address_id'] = $this->input->post("rdo_old_address"); 
	
		$data['contact_person'] = $this->input->post("txtname");
		$data['phone'] = $this->input->post("txtphone");
		$data['mobile'] = $this->input->post("txtmobile");
		$data['township'] = $this->input->post("cbotownship");
		$data['tspdetail_id'] = $this->input->post("rdo_delivery_day");		
		$data['lat'] = $this->input->post("hid_lat");
		$data['lon'] = $this->input->post("hid_long");
		$data['address'] = $this->input->post("txtaddress");	
		$data['instruction'] = $this->input->post("txtinstruction");
		
		if($data['order_id'] == '')
			$result = $this->order_model->insert_order($data);
		else
			$result = $this->order_model->edit_order($data);
			
		if($result)
		{
			echo json_encode(array('status' => 'success'));
			return;
		}
		else
		{
			echo json_encode(array('status' => 'error'));
			return;
		}		
		
	}
	
	function remove_address()
	{
		$address_id = $this->input->post('address_id');
		
		if($address_id !== "")
		{
			$result = $this->order_model->remove_address($address_id);
			if(!$result)
			{
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.  </b></div> '));
				return;
			}
			else
			{
				echo json_encode(array('status' => 'success', 'msg' => ' '));
				return;
			}
		}
		else
		{
			echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.  </b></div> '));
			return;
		}
	}
	
	function get_selected_address()
	{
		$address_id = $this->input->post('address_id');
		
		if($address_id !== "")
		{
			$address = $this->order_model->get_selected_address($address_id);
			if(!$address)
			{
				echo false;
				return;
			}
			else
			{
				$output = array();
				$address  = array(
								'contact_person' => $address->contact_person,
								'phone' => $address->ph_no,
								'mobile' => $address->mobile,
								'address' => $address->address,
								'township' => $address->township,
								'township_id' => $address->tsp_id,
								'day_id' => $address->township_id,
								'lat' => $address->lat,
								'long' => $address->long,
								'delivery_instruction' => $address->delivery_instruction
								);
				array_push($output,$address);
	
				echo json_encode($output);
				return;
			}
		}
		else
		{
			echo false;
			return;
		}
		
	}
	
	function get_pickup_address()
	{
		$pickup_address = $this->order_model->get_pickup_address();
		if($pickup_address)
		{
			$address  = array(
							'drop_off_point' => $pickup_address->drop_off_point,
							'pick_up_address' => $pickup_address->pick_up_address
						);
			echo json_encode($address);
		}
		else
			echo false;
		
		return;
			
	}
	
	function ajax_check_box_limit()
	{
		$day = $this->input->post('delivery_day');
		
		$delivery_day = $this->change_day_to_number($day);
		if($delivery_day == false)
		{
			echo json_encode(array('status' => 'error'));
			return;
		}
		
		$box_count = $this->input->post('box_count');
		
		$current_box = $this->order_model->check_box_limit($delivery_day);
		$max_box_limit =$this->system_model->get_box_limit()->max_box;		
		//echo $delivery_day.' | ' . $current_box . ' | ' .$box_count;exit();
		if( ($current_box + $box_count) > $max_box_limit)
		{
			echo json_encode(array('status' => 'error'));
			return;
		}
		else
		{
			echo json_encode(array('status' => 'success'));
			return;
		}

	}
	
	function check_box_limit($delivery_day, $box_count)
	{		
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
	
	function get_delivery_days()
	{
		$tsp_id = $this->input->post('tsp_id');
		if($tsp_id != '')
		{
			$data = $this->order_model->get_delivery_days($tsp_id);
			if($data !== false)
			{
				$json = ""; 
				$output = array(); 
				foreach($data as $row)
				{
					$days  = array(
								'day_id' => $row->day_id,
								'tspdetail_id' => $row->tspdetail_id,
								'delivery_day' => $row->delivery_day
								);
					array_push($output,$days);
				}
	
				$json .= json_encode($output);
				echo $json;
			}
			else
			{
				echo json_encode(array('status' => 'error'));
				return;
			}
		}
		else
		{
			echo json_encode(array('status' => 'error'));
			return;
		}
	}

	function change_day_to_number($day)
	{
		$result = $this->order_model->change_day_to_number($day);
		if($result)
			return $result;
		else
			return false;
	}
	
	
	/*
	|-----------------------------------------------
	| MY ORDERS
	|-----------------------------------------------
	*/
	
	public function my_order()
	{
		$data['userdata'] = $this->session->userdata('userdata');	
		$this->load->library('pagination');
		
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$data['offset'] = $this->uri->segment(2,0);
		
		$row_count = 0;
		$data['orders'] = $this->order_model->get_user_orders($data['userdata']['id'], $config['per_page'],$data['offset'],$row_count);
		
		$config['base_url'] =  base_url().'order/my_order';
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
		$this->load->view('myorders_view', $data);
	}
	
	function pause_subscription()
	{
		$userdata = $this->session->userdata('userdata');	
		$order_id = $this->input->post('order_id');
		$status = 'Pause';
		$result = $this->order_model-> update_order_status($userdata['id'], $order_id, $status);
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
		$userdata = $this->session->userdata('userdata');	
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
				$result = $this->order_model-> update_order_status($userdata['id'], $order_id, $status);
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
                    
					echo json_encode(array('status' => 'success', 'title' => '<i class="glyphicon glyphicon-ok-sign"></i> Resuming Subscription Successful ! ', 'msg' => 'Your order subscription has been successfully resumed! You will get your order start from coming "'.strtoupper($delivery_day).'".'));
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
	
	function stop_subscription()
	{		
		$userdata = $this->session->userdata('userdata');	
		$order_id = $this->input->post('hid_order_id');
		$reason = $this->input->post('txtreason');
		$status = 'Stop';
		if($order_id !== '' && $reason != '')
		{
			$userdata = $this->session->userdata('userdata');	
			$update = $this->order_model->update_order_status($userdata['id'], $order_id, $status);
			if(!$update)
			{
				echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience. </b></div> '));
				return;
			}
			else
			{
				$result = $this->system_model->send_notification($userdata['id'], $reason);
				
				if($result)
				{
					echo json_encode(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>Your order subscription has been successfully cancelled! Administrator will contact you shortly and your money will be refunded only after reviewing your reason. Thank you.</b></div> '));
					return;
				}
				else
				{
					echo json_encode(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.</b></div> '));
					return;
				}
			}
		}
		else
		{
			redirect(base_url().'main/error/deny');
		}
	}
	

}

/* End of file order.php */
/* Location: ./application/controllers/order.php */
