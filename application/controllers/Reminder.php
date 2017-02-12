<?php
class Reminder extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
			
		$this->load->library('email');	
		$this->load->model("system_model");
	}
	
	function index()
	{
		if(!$this->input->is_cli_request())
		{
		  echo "This script can only be accessed via the command line" . PHP_EOL;
		  return;
		}
		
		$data = $this->system_model->get_dueOrder_userDetails();
		if($data !== false)
		{
            error_log("------------------ Reminder Initiated ------------------ ", 0);
			$subject = 'Subscription Reminder';
			$setting = $this->system_model->profile_setting();
			$template = $this->system_model->get_setting()->template;			
			
			foreach($data as $row)
			{			
                error_log("Reminder List : ".$row->name.' | '.$row->email, 0);
				$body = "<b>Dear ".$row->name.",</b> <br> <p>".$this->lang->line('remainder')."</p>";
				
				$sample = array("{logo}","{body}", "{company_name}", "{address}", "{website}", "{email}", "{facebook}", "{phone}");
				
				if(isset($setting) && $setting !== false)
					$real   = array('<img src="'.base_url().'assets/img/fresco_logo6.jpg" />', $body, $setting->company_name, $setting->address, $setting->website, $setting->email, '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/'.$setting->facebook.'">'.$setting->facebook .'</a>', $setting->phone);
				else
					$real = array('<img src="'.base_url().'assets/img/logo.jpg" />', $body, "Fresco, Valleverde Co.Ltd", "88 Myakhantar lane, Insein Township, Yangon. ", "www.frescomyanmar.com", "info@frescomyanmar.com",  '<img src="'.base_url().'assets/img/fb.png" /> <a target="_blank" href="https://www.facebook.com/frescomyanmar">frescomyanmar</a>', '(95) 9 7922 26852, (95) 9 7922 27491');
					
				$message = str_replace($sample,$real,$template);

				$mail = $this->email->sent_email($row->email, $subject, $message);
				if($mail)
				{
                     error_log("Reminder Sent : ".$row->name.' | '.$row->email, 0);
					 $this->system_model->update_reminder($row->order_id);
				}
			}			
			
		}
	  
	}
}

/* End of file Reminder.php */
/* Location: ./application/controllers/Reminder.php */