<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('admin/summarize_model');
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
		$data['income'] = $this->summarize_model->get_incomegraph();
		$data['dorder'] = $this->summarize_model->get_deliveredordergraph();		
		$this->load->view('admin/statistics_view.php',$data);
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */