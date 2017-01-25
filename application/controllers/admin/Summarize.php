<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Summarize extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/summarize_model');
		$this->load->model('admin/delivery_model');
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
	
	public function index()
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
		//$day = date('Y-m-d', strtotime("+".$addday." days"));
		//$week =  array ('Sun','Mon', 'Tue', 'Wed','Thu','Fri','Sat');
		$day = date('Y-m-d', strtotime("+".$addday." days"));		
		$week = date('l',strtotime($day));
		$data['day'] = 	$day;
		$data['longday'] = " (".$week.")";

		//Add by wday by Ei Mon Kyaw for day_id	
		$wday = date('w',strtotime($day))+1;
		$data['total'] = $this->summarize_model->get_summarizebyday($day,$wday);
		$data['details'] = $this->summarize_model->get_detailssummarizebyday($day,$wday);
 		$this->load->view('admin/summarize_order.php',$data);
	}
	
	function summarize_order()
	{
		$getdata = $_POST['date'];
		$today = getdate(strtotime($getdata));
		
		$day = $getdata;
		$week = date('l',strtotime($day));
		$data['day'] = 	$day;
		$data['longday'] = " (".$week.")";	

		//Add by wday by Ei Mon Kyaw for day_id		
		$wday = date('w',strtotime($day))+1;
		$data['total'] = $this->summarize_model->get_summarizebyday($day,$wday);
		$data['details'] = $this->summarize_model->get_detailssummarizebyday($day,$wday); 
 		$this->load->view('admin/summarize_order.php',$data);
	}
	
	function print_summarizeorder($date)
	{
		$this->load->library('Pdf');	
		$count = 0;
		$subtotal = 0;
		$table = '<br><hr><br><table width="100%"><tbody>';
		$heading = '';
		//$day = $date;
		
		// SY : 22/8/16
		$today = getdate(strtotime($date));		
		$day = $date;
		$week = date('l',strtotime($day));
		$data['day'] = 	$day;
		$data['longday'] = " (".$week.")";
		$wday = date('w',strtotime($day))+1;

		$total = $this->summarize_model->get_summarizebyday($day,$wday);
		$details = $this->summarize_model->get_detailssummarizebyday($day,$wday);
		$b_total = 0; $i_total = 0; 
		if(isset($total['data']) && $total['data'] != false) 
		{ 
			foreach($total['data'] as $tot) 
			{
				if($tot->name != NULL) 
				{ 
					$b_total += $tot->b_total;
                	$i_total += $tot->i_total;

                	$heading = '<br> <hr> <br> <h4>Total Orders : '.$total['count'].'  &nbsp; &nbsp; | &nbsp; &nbsp;  Total Boxes : '.$b_total.'   &nbsp; &nbsp; | &nbsp; &nbsp;  Total items : '.$i_total.' </h4> <br> <hr> '; 

					$table .= '<tr><td>';
					$detail = array();
					$i = 0;
					if(isset($details) && $details != false) 
					{
						foreach($details as $det)
						{
							if($det->name == $tot->name)
							{
								$detail[$i] = array( 'key' => $det->info , 'value' => $det->total , 'ref' => $det->orders );
								$i++;
							}
						}
					}
					$table .= '<br><h4 style="margin-top:5px;">'.$tot->name.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &times; '.$tot->total.'</h4>';
					if(isset($detail) && !empty($detail)) 
					{
						$table .= '<ul>';
						foreach($detail as $det)
						{ 
							if($det['key'] == 'Default') 
							{
								$table .= "<li> <b> Default </b> &nbsp;  &times; ". $det['value'] . "<br>(" .$det['ref'] . ")<br></li>";
							} 
						}
					foreach($detail as $det)
					{ 
						if($det['key'] != 'Default') 
						{ 
							$table .= "<li><b> ".$det['key']. "</b> &nbsp; &times; ".$det['value']."<br>(" . $det['ref'] .")</li>";
						} 
					}										
                    $table .= '</ul>';
				} 
                //$table .= '</td><td align="right"><h4 style="margin-top:5px;"> x '.$tot->total.'</h4>';
                /*foreach($detail as $det)
				{ 
					if($det['key'] == 'Default') 
						$table .= ' x '.$det['value'] .'<br />';
				} 				
				foreach($detail as $det)
				{ 
					if($det['key'] != 'Default') 
					{ 
						$table .= ' x '.$det['value'] .'<br />';
				 	} 
				}*/
				$table .= '</td></tr>';
			}
		}
		} 
		$table .= '</table>';
		

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
		$pdf->writeHTML($heading . $table, true, false, false, false, '');
	//	ob_end_clean();
		$pdf->Output($date.'.pdf', 'I'); 	
	}
	
	
}

/* End of file summarize.php */
/* Location: ./application/controllers/admin/summarize.php */