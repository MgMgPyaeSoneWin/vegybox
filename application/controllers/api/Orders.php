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
class Orders extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->model('user_model');
		$this->load->model("system_model");
		$this->load->model("order_model");

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	public function faq_get()
	{
		$this->load->model("faq_model");
		$this->load->library('pagination');
		
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$per_page = $arr['total_row'];		
		$offset = $arr['offset'];	
		
		$row_count = 0;
		$result = $this->faq_model->get_faq_list($per_page,$offset,$row_count);
		
		$config['per_page'] = $per_page;
		$config['total_rows'] = $row_count;
		$config['full_tag_open'] = '<div class="col-xs-12"><center><ul class="pagination">';
		$config['full_tag_close'] = '</ul></center></div>';
		$config['cur_tag_open'] = '<li><a href=# class="pagination_link" style="color:#ffffff; background-color:#258BB5;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['first_link'] = FALSE;
		$config['last_link'] = FALSE;
		$config['prev_link'] = FALSE;
		$config['next_link'] = FALSE;
		$config['attributes'] = array('class' => 'pagination_link');
		$config['num_links'] = $config['total_rows'] / $config['per_page'];
		
		$this->pagination->cur_page = $offset;
		$this->pagination->initialize($config);
		
		$pagination = $this->pagination->create_links();
		
		if($result != FALSE)
			$this->response(array('status' => 'success','faq_list' => $result,'rowcount'=>$row_count, 'pagination' => $pagination));
		else
			$this->response(array('status' => 'error','msg' => 'Error Occur'));
	}

    public function boxlist_get()
	{   
		$box = $this->order_model->get_boxes();		
		if($box != FALSE)
			$this->response(array('status' => 'success','box_list' => $box));
		else
			$this->response(array('status' => 'error','msg' => 'Error Occur'));
	}
	
	public function myorder_get()
	{
		$this->load->library('pagination');
		
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$id = $arr['userid'];
		$per_page = $arr['total_row'];
		$offset = $arr['offset'];
		$row_count = 0;
		
		$config['per_page'] = $per_page;
		$config['total_rows'] = $row_count;
		$config['full_tag_open'] = '<div class="col-xs-12"><center><ul class="pagination">';
		$config['full_tag_close'] = '</ul></center></div>';
		$config['cur_tag_open'] = '<li><a href=# class="pagination_link" style="color:#ffffff; background-color:#258BB5;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['first_link'] = FALSE;
		$config['last_link'] = FALSE;
		$config['prev_link'] = FALSE;
		$config['next_link'] = FALSE;
		$config['attributes'] = array('class' => 'pagination_link');
		$config['num_links'] = $config['total_rows'] / $config['per_page'];
		
		$this->pagination->cur_page = $offset;
		$this->pagination->initialize($config);
		
		$pagination = $this->pagination->create_links();
		
		$order = $this->order_model->get_user_orders($id, $per_page,$offset,$row_count);
		if($order != FALSE)
			$this->response(array('status' => 'success','order_list' => $order,'rowcount'=>$row_count, 'pagination' => $pagination));
		else
			$this->response(array('status' => 'error','msg' => 'Error Occur'));
		
	}
	
	public function pauseSubscription_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$userid = $arr['user_id'];
		$order_id = $arr['order_id'];
		$status = 'Pause';
		$result = $this->order_model-> update_order_status($userid, $order_id, $status);
		if(!$result)
		{
			$this->response(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> On Hold Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
		}
		else
		{
			$this->response(array('status' => 'success', 'title' => '<i class="glyphicon glyphicon-ok-sign"></i> On Hold Subscription Successful! ', 'msg' => 'Your order subscription has been successfully on hold!'));
		}
	}
	
	
	public function resumeSubscription_get()
	{	
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$user_id = $arr['user_id'];
		$order_id = $arr['order_id'];
		$status = 'On-going';
		
		$delivery_day = $this->order_model->get_order_delivery_day($order_id);
		$box_count = $this->order_model->get_ordered_boxes_count($order_id);
		
		if($delivery_day !== false && $box_count !== false)
		{
			$limit = $this->check_box_limit($delivery_day, $box_count);
			
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
		
			if( $limit == false)
			{
				$this->response(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'The maximum box limit for "'.strtoupper($delivery_day).'" has exceeded. Please try again with the different delivery date. Thank you! '));
			}
			else
			{
				$result = $this->order_model-> update_order_status($user_id, $order_id, $status);
				if(!$result)
				{
					$this->response(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
				}
				else
				{
					$this->response(array('status' => 'success', 'title' => '<i class="glyphicon glyphicon-ok-sign"></i> Resuming Subscription Successful ! ', 'msg' => 'Your order subscription has been successfully resumed! You will get your order start from coming "'.strtoupper($delivery_day).'".'));
				}
			}
		}
		else
		{
			$this->response(array('status' => 'error', 'title' => '<i class="glyphicon glyphicon-remove-sign"></i> Resuming Subscription Failed !', 'msg' => 'Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.'));
		}
	}

	
	function check_box_limit($delivery_day, $box_count)
	{
		$this->load->model("order_model");
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
	
	public function stopSubscription_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$user_id = $arr['hid_user_id'];	
		$order_id = $arr['hid_order_id'];
		$reason = $arr['txtreason'];
		$status = 'Stop';
		if($order_id !== '' && $reason != '')
		{
			$update = $this->order_model->update_order_status($user_id, $order_id, $status);
			if(!$update)
			{
				$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i> Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience. </b></div> '));
			}
			else
			{
				$result = $this->system_model->send_notification($user_id, $reason);
				
				if($result)
				{
					$this->response(array('status' => 'success', 'msg' => '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="glyphicon glyphicon-remove"></i></button> <i class="glyphicon glyphicon-ok"></i> <b>Your order subscription has been successfully cancelled! Administrator will contact you shortly and your money will be refunded only after reviewing your reason. Thank you.</b></div> '));
				}
				else
				{
					$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>Sorry, there is an error encountered when processing your request. Please refresh the page and try again. Sorry for your inconvenience.</b></div> '));
				}
			}
		}
		else
		{
			$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>Sorry, Your input value is not valid. Please refresh the page and try again. Sorry for your inconvenience.</b></div> '));
		}
	}
	
	function ajaxItemList_get()
	{
		$queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
        
		$offset = ($arr['offset'] - 1);
		$per_page = 6;
//echo $offset;
		$row_count = 0;
                $data = $this->order_model->get_extra_items($per_page,$offset,$row_count);
        //$data = $this->order_model->get_extra_items($per_page,((int)$offset - 1),$row_count);


		if($data == false){
			$this->response(array('status' => 'error'));
		}
		else
		{
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
							'number' => $row->number,
							'row_count' => $row_count
							
							);
				array_push($output,$items);
			}

			$this->response(array('status' => 'success','items' => $output));
		}
	}
	
	function getOrderDetails_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$order_id = $arr['order_id'];
		$details = $this->order_model->get_order_details($order_id);	
		$boxes = $this->order_model->get_ordered_boxes($order_id);
		$items = $this->order_model->get_ordered_items($order_id);
		if($details != FALSE)
			$this->response(array('status' => 'success','details' => $details, 'boxes' => $boxes, 'items' => $items));
		else
			$this->response(array('status' => 'error','msg' => 'Error Occur'));
	}
	
	function remove_address_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$address_id = $arr['address_id'];
		
		if($address_id !== "")
		{
			$result = $this->order_model->remove_address($address_id);
			if($result == 'in use')
			{
				$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> You cannot delete the address that is being used.  </b></div> '));
			}
			else if(!$result)
			{
				$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Sorry for your inconvenience.  </b></div> '));
			}
			else
			{
				$this->response(array('status' => 'success', 'msg' => ' '));
			}
		}
		else
		{
			$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Sorry, there is an error encountered when processing your request. Sorry for your inconvenience.  </b></div> '));
		}
	}
	
	
	
	/* -----------------------
	* Add Order
	*------------------------ */
	
	function check_order_status_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$user_status =  $arr['user_status'];
		$user_id =  $arr['user_id'];
		
		// check if his previous order is delivered	
		if($user_status == 'Old' || $user_status == 'Pending') 
		{
			$delivery_status = $this->order_model->check_order_status($user_id);
			if($delivery_status == false)
			{
				$this->response(array('status' => 'error', 'result' => $delivery_status));
			}
		}		
	}
	
	function check_box_limit_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$day = $arr['delivery_day'];
		
		$delivery_day = $this->change_day_to_number($day);
		if($delivery_day == false)
		{
			$this->response(array('status' => 'error'));
		}

		$box_count = $arr['box_count'];
		
		$current_box = $this->order_model->check_box_limit($delivery_day);
		$max_box_limit =$this->system_model->get_box_limit()->max_box;		
		
		if( ($current_box + $box_count) > $max_box_limit)
		{
			$this->response(array('status' => 'error'));
		}
		else
		{
			$this->response(array('status' => 'success'));
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
	
	function get_selected_address_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$address_id = $arr['address_id'];
		
		if($address_id !== "")
		{  
			$address = $this->order_model->get_selected_address($address_id);
			if(!$address)
			{
				$this->response(array('status' => false));
			}
			else
			{
				$data  = array(
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
				//array_push($output,$address);
	
				$this->response(array('status' => 'success', 'address' => $data));
			}
		}
		else
		{
			$this->response(array('status' => false));
		}
		
	}
	
	function townshipList_get()
	{
		$data = $this->order_model->get_townships();
		if(!$data)
		{
			$this->response(array('status' => false));
		}
		else
		{			
			$this->response(array('status' => 'success', 'list' => $data));
		}
	}
	
	function get_delivery_days_get()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$tsp_id = $arr['tsp_id']; 
		if($tsp_id != '')
		{
			$data = $this->order_model->get_delivery_days($tsp_id); 
			if($data !== false)
			{
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
	
				$this->response(array('status' => 'success', 'list' => $output));
			}
			else
			{
				$this->response(array('status' => 'error'));
			}
		}
		else
		{
			$this->response(array('status' => 'error'));
		}
	}
	
	function getBoxPrice_get()
	{
		$data= $this->system_model->get_setting()->box_price;
		if(!$data)
		{
			$this->response(array('status' => false));
		}
		else
		{			
			$this->response(array('status' => 'success', 'price' => $data));
		}
	}
	
	public function add_order_get()
	{	
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);
       
		$data['userdata']['id'] = $arr['hid_userId'];
		$data['userdata']['status'] = $arr['hid_userStatus'];
		$data['userdata']['email'] = $arr['hid_userEmail'];
		
		if(isset($arr['hid_order_id']))
		{
            $data['order_id'] = $arr['hid_order_id'];
            $stage = 'edit';
		}
        else
		{
            $data['order_id'] = '';
            $stage = 'add';
		}
			
		// check box_limit			
		if($arr["rdo_address"] == 'new' || ($arr["rdo_address"] == 'old' && $arr['hid_edited_address'] != ''))		
		{ 
			$tspdetail_id = $arr["rdo_delivery_day"];	
			$data['delivery_day'] = $this->order_model->get_tsp_delivery_day($tspdetail_id);	
		}
		else if($arr["rdo_address"] == 'old')
		{
			$data['delivery_day'] = $this->order_model->get_address_delivery_day($arr["rdo_old_address"]);
		}
		else
		{
			$tspdetail_id = $arr["rdo_delivery_day"];	
			$data['delivery_day'] = $this->order_model->get_tsp_delivery_day($tspdetail_id);	
		}
			
		$limit = $this->check_box_limit($data['delivery_day'], (int)$arr["txt_box_num"]);
		
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
			$this->response(array('status' => 'error', 'msg' => '<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>The maximum box limit for "'.strtoupper($delivery_day).'" has exceeded. Please try again with the different delivery date. Thank you!</b></div> '));
		}
		
				
		$subtotal = 0;
		$box_price = $this->system_model->get_setting()->box_price;	
		
		// Box details
		$data['subscription'] = $arr["rdo_subscription"];	
		
		if($data['subscription'] == 'NO')
		{
			$data['week_status'] = 1;
			$box_id = array();
			$box_num = array();
			$boxes = $arr["hid_box_id"];
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
			$data['week_status'] = $arr["txt_week_num"];
			$box_count = $arr["hid_box_count"];
			$box_num = $arr["txt_box_num"];
			$box_id = $arr["chk_box_id"];
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
					$box_qty = $arr["cbo_box_qty_".$box_id[$i]];
					array_push($arr_box_num, $box_qty);
				}
				else
					array_push($arr_box_num, $box_qty);
			}
			$data['boxes'] = array_combine($arr_box_id, $arr_box_num);
			
			
			$subtotal += ((int)$box_num * $box_price) * (int)$data['week_status'];			
		}
		
		
		$data['info'] = $arr["txt_info"];
		
		/* Additional Items */
		
		$data['item_subscription'] = $arr["rdo_item_subscription"]; 
		$arr_items = array_combine($arr["hid_itemID"], $arr["txt_itemQty"]);
		
		$arr_item_id = array();
		$arr_item_qty = array();
		
		$data['items'] = NULL;		
		
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
		
		if(! empty($arr_item_id) && ! empty($arr_item_qty))
			$data['items'] = array_combine($arr_item_id, $arr_item_qty);
		
		$data['subtotal'] = $subtotal;
		// Delivery details
		$data['edited_add'] = $arr['hid_edited_address'];
		$data['selected_address'] = $arr["rdo_address"]; 	
        
        if(isset($arr["rdo_old_address"]))
		    $data['address_id'] = $arr["rdo_old_address"]; 
        else
            $data['address_id'] = ''; 
	
		$data['contact_person'] = $arr["txtname"];
		$data['phone'] = $arr["txtphone"];
		$data['mobile'] = $arr["txtmobile"];
		$data['township'] = $arr["cbotownship"];
        
		if(isset($arr["rdo_delivery_day"]))
            $data['tspdetail_id'] = $arr["rdo_delivery_day"];		
        else
            $data['tspdetail_id'] = '';
        
		$data['lat'] = $arr["hid_lat"];
		$data['lon'] = $arr["hid_long"];
		$data['address'] = $arr["txtaddress"];	
		$data['instruction'] = $arr["txtinstruction"];
		
		if($data['order_id'] == '')
			$result = $this->order_model->insert_order($data);
		else
			$result = $this->order_model->edit_order($data);
			
		if($result)
		{
			$this->response(array('status' => 'success'));
		}
		else
		{
			$this->response(array('status' => 'error' ));
		}		
		
	}
}
