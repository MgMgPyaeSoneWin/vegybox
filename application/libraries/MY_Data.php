<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Data
{
	private $CI;
	
	function __construct()
    {
        $this->CI =& get_instance();
    }

	
	public function database_table()
	{		
		$table_name = array(
			'user_table' => 'fr_user',
			'township_table' => 'fr_township',
			'tspdetails_table' => 'fr_township_detail',
			'dday_table' => 'fr_delivery_day',
			'system_table' => 'fr_system',
			'order_details_table' => 'fr_order_details',
			'order_table' => 'fr_order',
			'notification_table' => 'fr_notification',
			'faq_table' => 'fr_faq',
			'delivery_info_table' => 'fr_delivery_info',
			'box_table' => 'fr_box',
			'addresses_table' => 'fr_addresses',
			'additional_order_table' => 'fr_additional_order',
			'additional_items_table' => 'fr_additional_items',
			'delivery_log_table' => 'fr_delivery_log'
		);
		return $table_name;
	}
	
	public function err_message()
	{		
		$err = array(
			'admin_login_error' => '<div class="alert alert-danger">Please make sure your email and password is correct!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
			'admin_login_validate_error' => '<div class="alert alert-danger">Make sure your email and password format!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
			'admin_login_require' => '<div class="alert alert-danger">Please login first.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
			'admin_login_not' => '<div class="alert alert-danger">Invalid user ! Only administrator can access this.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
			'admin_oldpass_wrong' => '<div class="alert alert-danger">Incorrect Old Password ! Please try again !<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
			'cusmange_serchempty' => '<div class="alert alert-danger">Please enter what you want to search for!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>'
		);
		return $err;
	}
	
	public function sucess_message()
	{		
		$err = array(
			'admin_pass_change' => '<div class="alert alert-success">You\'ve successfully changed your password! Please login with your new password next time.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>',
		);
		return $err;
	}
	
}

/* End of file MY_DATA.php */
/* Location: ./application/libraries/MY_DATA.php */