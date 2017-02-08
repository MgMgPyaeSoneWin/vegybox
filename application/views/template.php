<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
	<div style="99%; background-color:#000; padding: 0 0 15px 15px;">
    	<img src="<?php echo base_url() ?>assets/img/logo.jpg" />
    </div>
    
    <div style="99%; padding-left:15px;font-family: sans-serif;font-size:14px">
    <p><?=$this->lang->line('thx');?></p>
    
    <center><a style="border-radius: 3px;display: inline-block;font-size: 14px;font-weight: 700;line-height: 24px;padding: 13px 35px 12px 35px;text-align: center;text-decoration: none !important;transition: opacity 0.2s ease-in;color: #fff;font-family: Cabin,Avenir,sans-serif;background-color: #4c5b6b;" href="http://test.com"><?=$this->lang->line('activeNow');?></a></center>
    
    <p><?=$this->lang->line('tku');?><br /> <?php echo (isset($setting->company_name) ? $setting->company_name : 'Fresco, Valleverde Co.Ltd'); ?>
    </p>
    
    </div>
    
    <div style="width:100%; background-color:#000; color:#999; font-size:12px">
    	<p style="text-align:center">
        <?php
			if(isset($setting) && $setting !== false):
				echo $setting->address . '<br>'; 
				echo $setting->website . ' | '. $setting->email.' | <img src="'.base_url().'assets/img/fb.png"> '. $setting->facebook . '<br>';
				echo $setting->phone;
	 		else:
				echo '88 Myakhantar lane, Insein Township, Yangon. <br>';
				echo 'www.frescomyanmar.com | info@frescomyanmar.com | <img src="'.base_url().'assets/img/fb.png"> frescomyanmar ';
				echo '(95) 9 7922 26852, (95) 9 7922 27491';
			endif; 
		?>
        </p>
    </div>
</body>
</html>