<?php
class Delivery extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/delivery_model');
		$this->load->model('admin/order_model');
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
	
	function manage_township()
	{
		//$data['day_township'] = $this->delivery_model->get_daybytownship();
		$data['delivery_day'] = $this->delivery_model->delivery_day();
		$data['townships'] = $this->order_model->get_township();
		$this->load->view('admin/delivery_township_view',$data);
	}
	
	function twonshipdata()
	{
		echo json_encode($this->delivery_model->get_daybytownship($_POST['dayid']));
	}
	
	function addtspdetails()
	{
		if(isset($_POST['townships']))
			echo $this->delivery_model->add_daybytownship($_POST['dayid'],$_POST['townships']);
		else
			echo $this->delivery_model->add_daybytownship($_POST['dayid'],"");
	}
	
	function add_deliveryday()
	{
		echo $this->delivery_model->add_deliverydayid($_POST['dayid']);
	}
	
	function add_township()
	{
		echo $this->delivery_model->add_townshipdata($_POST['townname'],$_POST['townlat'],$_POST['townlong']);
	}
	
	/*
	|--------------------------------------------------
	| DELIVERY LOCATIONS
	|--------------------------------------------------
	*/
	
	function delivery_locations()
	{        
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $array);
        $arr = array_keys($array);
        
        $data['today_date'] = '';
        if( $arr[0] != '/admin/delivery/delivery_locations')
        {
            $data['today_date'] = urldecode(mysql_real_escape_string($array['/admin/delivery/delivery_locations?date']));
        }
        
		if($data['today_date'] == '')
			$data['today_date'] = date('d-m-Y');
			
		$data['orders'] = $this->delivery_model->get_tspOrders($data['today_date']);
		$this->load->view('admin/delivery_location_view', $data);
	}
	
	function get_noti()
	{
		echo json_encode($this->order_model->noti());
	}
	
	function read_noti()
	{
		echo json_encode($this->order_model->readnoti($_POST['noti']));
	}
	
	function delivery_map()
	{       
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

		$orders = urldecode(mysql_real_escape_string($arr['/admin/delivery/delivery_map?orders']));
		$address = urldecode(mysql_real_escape_string($arr['address']));
		$date = urldecode(mysql_real_escape_string($arr['date']));
		$tsp = urldecode(mysql_real_escape_string($arr['tsp']));
		
		$this->load->library('Pdf');	
		
		$pdf = new Pdf(PDF_PAGE_FORMAT, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetKeywords('FRESCO, Vegy Box, Home Delivery');		
		
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');
		$pdf->SetPrintFooter(false);
		$pdf->SetPrintHeader(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$pdf->AddPage();	
				
		$pdf->SetFont('helvetica', '', 10);
		
		if($date == '')
			$pdf->writeHTML('No orders for today ! ', true, false, false, false, '');
		else
		{		
			$title = '<h1 style="text-align:center">Delivery Locations of '.$tsp. ' for '.$date.' Orders</h1><br>';
			$pdf->writeHTML($title, true, false, false, false, '');
			
			$orders = $this->delivery_model->get_locations($address , $date , $orders);
			if(isset($orders) && $orders!= false)
			{
				$count = count($orders);
				$data = '';
				foreach($orders as $row)
				{
					
					$order = '<h3><u>'.$row->order_ref.'</u></h3>';
					$address = '<p>'.$row->address.'</p>';
					$img = '<img border="0" src="http://maps.googleapis.com/maps/api/staticmap?zoom=16&size=600x300&maptype=roadmap&&markers=color:red|'.$row->lat.','.$row->long.'&sensor=false"><br>';		
					$pdf->writeHTML($order.$address.$img, true, false, false, false, '');		
				}
			}		
			
		}
		
		$pdf->Output('Map'.$date.'.pdf', 'I'); 
	}
	
	function view_map()
	{
        $queryString = $_SERVER['REQUEST_URI'];
        parse_str($queryString, $arr);

    	$data['order'] = urldecode(mysql_real_escape_string($arr['/admin/delivery/view_map?orders']));
		$data['address'] = urldecode(mysql_real_escape_string($arr['address']));
		$data['date'] = urldecode(mysql_real_escape_string($arr['date']));
		$data['tsp'] = urldecode(mysql_real_escape_string($arr['tsp']));
		
		$data['orders'] = $this->delivery_model->get_locations($data['address'] , $data['date'] , $data['order']);
		
		$this->load->view('admin/delivery_map_view', $data);
	}
}