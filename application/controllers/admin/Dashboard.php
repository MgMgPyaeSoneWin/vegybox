<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/order_model');
		$this->load->model('admin/delivery_model');
		$this->load->model('admin/customer_model');
		$this->load->library('MY_Data');
		date_default_timezone_set('Asia/Rangoon');
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
	
	public function main()
	{
		$today = getdate();		
		$dday = $this->delivery_model->delivery_day();
		$first;
		$addday;
		$check = false;
		$count = 0;
		foreach($dday as $dd)
		{
			if($dd->delivery == 'YES')
			{
				if($dd->day_id == $today['wday']+1)
				{
					$check = true;
					$addday = 0;
					break;
				}
				else
				{
					if($count == 0)
						$first = $dd->day_id;
					if($today['wday']+1 < $dd->day_id)
					{
						$check = true;
						$addday = $dd->day_id - ($today['wday']+1);
						break;
					}
					$count++;
				}
			}
		}
		if($check == false)
		{
			$addday = (7 + ($today['wday']+1) ) - $first;
		}
		$day = date('dS M Y', strtotime("+".$addday." days"));
		$data['date'] = date('Y-m-d',strtotime($day));
		$week = date('l',strtotime($day));
		$data['day'] = 	$day." (".$week.")";
		$this->load->library('pagination');
		$data['per_page'] = 10;
		$config['per_page'] = $data['per_page'];
		$row_count = 0;		
		$data['order'] = $this->order_model->get_comingorder($config['per_page'],$this->uri->segment(4), $row_count);
		$config['base_url'] =  base_url().'admin/dashboard/main';
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
		$data['total_deliver'] = 0; 		
		foreach($dday as $dd)
		{
			if($dd->delivery == 'YES')
			{
				if($dd->day_id == $today['wday']+1)
				{					
					$data['total_deliver'] = $row_count;
					break;
				}
			}
		}
		
		$this->pagination->cur_page = $this->uri->segment(4);
		$this->pagination->initialize($config);
		
		$data['icon'] = 'fa-fa-sort';
		$data['pagination'] = $this->pagination->create_links();
		$data['view'] = $this->order_model->get_viewdata();
		$this->load->view('admin/main_view',$data);
		
	}
	
	function invoice($id,$day = '')
	{
			
		$box = $this->order_model->pdf_box($id);
		if($day == '')
		{
			$date = $this->order_model->get_date($id); 
            if($date != false)
			    $day = $date[0]->order_date;
            else
                 $day = '';
		}		
		$additional = $this->order_model->pdf_addition($id,$day);
               
        $this->load->library('Pdf');
		$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetKeywords('FRESCO, Vegy Box, Home Delivery');
		
		
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');
		//$pdf->SetPrintFooter(false);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$pdf->AddPage();	
				
		$pdf->SetFont('helvetica', '', 10);
		$count = 0;
		$subtotal = 0;
		$table = '<br><hr/><br/>
				<div>
				<table cellspacing="5px">
				<tr>
					<td><label><strong>Ref No.</strong>&nbsp;:&nbsp;'.$box[0]->order_ref.'</label></td>
					<td><label><strong>Customer Name</strong>&nbsp;:&nbsp;'.$box[0]->cname.'</label></td>
				</tr>
				<tr>
					<td><label><strong>Date</strong>&nbsp;:&nbsp;'.date('d/m/Y').'</label></td>
					<td><label><strong>Address</strong>&nbsp;:&nbsp;'.$box[0]->address.'</label></td>
				</tr>
				</table>
				<br><br>
				<table border="0.5" >
					<thead>
					<tr>
						<th width="5%" height="15" align="center"><strong>NO.</strong></th>
						<th width="50%" align="center"><strong>ITEM DESCRIPTION</strong></th>
						<th width="15%" align="center"><strong>QUANTITY</strong></th>
						<th width="15%" align="center"><strong>UNIT PRICE</strong></th>
						<th width="15%" align="center"><strong>AMOUNT</strong></th>
					</tr>
					</thead>
					<tbody>';
			if(isset($box) && $box!= false)
			{
				
				foreach($box as $b)
				{
					$count++;
					$subtotal += $b->amount;
			    	$table .= '<tr>
    							<td width="5%" align="center">&nbsp;'.$count.'</td>
								<td width="50%" align="left">&nbsp;'.$b->name.'</td>
								<td width="15%" align="right">'.$b->week_num.'X'.$b->qty.'&nbsp;&nbsp;&nbsp;</td>
								<td width="15%" align="right">'.number_format($b->price,0).'&nbsp;&nbsp;&nbsp;</td>
								<td width="15%" align="right">'.number_format($b->amount,0).'&nbsp;&nbsp;&nbsp;</td>
								</tr>';
				}
			}
			if(isset($additional) && $additional!= false)
			{
				
				foreach($additional as $ad)
				{
					$count++;
                    if($ad->item_subscription == 'Yes')
                    {
                        $data = $ad->week_num.'X';
                        $adtotal = $ad->amount * $ad->week_num;
                    }
                    else
                    {
                        $data = '';
                        $adtotal = $ad->amount;
                    }
                    $subtotal += $adtotal;
					$table .= '<tr>
								<td width="5%" align="center">&nbsp;'.$count.'</td>
								<td width="50%" align="left">&nbsp;'.$ad->name.'</td>
								<td width="15%" align="right">'.$data.''.$ad->item_qty.'&nbsp;&nbsp;&nbsp;</td>
								<td width="15%" align="right">'.number_format($ad->price,0).'&nbsp;&nbsp;&nbsp;</td>
								<td width="15%" align="right">'.number_format($adtotal,0).'&nbsp;&nbsp;&nbsp;</td>
								</tr>';
				}
			}
						
		$table .= '</tbody>
					<tfoot>
					<tr>
						<td width="5%" height="15">&nbsp;</td>
						<td colspan="3" width="80%" align="center"><strong>GRAND TOTAL</strong></td>
						<td width="15%" align="right">'.number_format($subtotal,0).'&nbsp;&nbsp;&nbsp;</td>
					</tr>
					</tfoot>					
				</table><br/>
				<p><strong>Received By</strong> &nbsp; : _________________________</p>
				<div>';
		$pdf->writeHTML($table, true, false, false, false, '');
        $pdf->writeHTML($table, true, false, false, false, '');
		$pdf->Output($box[0]->order_ref.'.pdf', 'I'); 		
	}
	
	function get_noti()
	{
		echo json_encode($this->order_model->noti());
	}
	
	function read_noti()
	{
		echo json_encode($this->order_model->readnoti($_POST['noti']));
	}
	
	function customer_details($id)
	{
		$this->load->library('Pdf');	
		$details = $this->customer_model->pdf_customerdetails($id);
		//$box = $this->order_model->pdf_box($id);
		//$additional = $this->order_model->pdf_addition($id);
		$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetKeywords('FRESCO, Vegy Box, Home Delivery');
		
		
		$pdf->SetHeaderData(PDF_HEADER_LOGO, 300, '', '');
		$pdf->SetPrintFooter(false);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$pdf->AddPage('L');	
				
		$pdf->SetFont('helvetica', '', 13);
		$count = 0;
		$subtotal = 0;
		$table = '<br><hr/><br/>
				<div>				
				<br><br>
				<table border="0.5" >					
					<tbody>';
			if(isset($details) && $details!= false)
			{				
				$table .= '<tr>
								<td width="30%" height="25"> &nbsp;<strong>Date</strong>:</td>
								<td width="70%"> &nbsp;'.date('d-m-Y').'</td>
							</tr>
                            <tr>
    							<td width="30%" height="25"> &nbsp;<strong>Order Ref</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->order_ref.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Name</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->user_name.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Phone Number</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->ph_no.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Mobile</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->mobile.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Email Address</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->email.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Myanmar Contact Person</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->contact_person.'</td>
							</tr>
							<tr>
								<td width="30%" height="35"> &nbsp;<strong>Address</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->address.'</td>
							</tr>
							<tr>
								<td width="30%" height="25"> &nbsp;<strong>Township</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->township_name.'</td>
							</tr>
							<tr>
								<td width="30%" height="35"> &nbsp;<strong>Delivery Instruction</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->delivery_instruction.'</td>
							</tr>
                            <tr>
    							<td width="30%" height="35"> &nbsp;<strong>Type of Box</strong>:</td>
								<td width="70%">&nbsp;'.$details[0]->box_type.'</td>
							</tr>
                            <tr>
        						<td width="30%" height="30"> &nbsp;<strong>Additional Items</strong>:</td>
								<td width="70%">&nbsp;'.$details[0]->add_type.'</td>
							</tr>
							<tr>
        						<td width="30%" height="35"> &nbsp;<strong>Additional Item Subscription</strong>:</td>
								<td width="70%">&nbsp;'.$details[0]->item_subscription.'</td>
							</tr>
							<tr>
								<td width="30%" height="35"> &nbsp;<strong>Never Send</strong>:</td>
								<td width="70%"> &nbsp;'.$details[0]->other_info.'</td>
							</tr>';
				
			}
			
						
		$table .= '</tbody>										
				</table><br/>				
				<div>';
		
		$pdf->writeHTML($table, true, false, false, false, '');
		$pdf->Output($details[0]->user_name.'.pdf', 'I'); 		
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */