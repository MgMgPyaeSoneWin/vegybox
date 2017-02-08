<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if (IE 9)]><html class="no-js ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>FRESCO VEGY BOX</title>   

<meta name="description" content="Fresco Vegy Box" /> 

<!-- Mobile Specifics -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="HandheldFriendly" content="true"/>
<meta name="MobileOptimized" content="320"/>   

<!-- Mobile Internet Explorer ClearType Technology -->
<!--[if IEMobile]>  <meta http-equiv="cleartype" content="on">  <![endif]-->

<link rel="icon" type="image/x-icon" href="<?php echo base_url() ?>favicon.ico" />
<!-- Bootstrap -->
<link href="<?php echo base_url() ?>assets/css/bootstrap3.css" rel="stylesheet">

<!-- Main Style -->
<link href="<?php echo base_url() ?>assets/css/main.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/rt.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/rt-responsive.css" rel="stylesheet">

<!-- Font Icons -->
<link href="<?php echo base_url() ?>assets/css/fonts.css" rel="stylesheet">

<!-- Responsive -->
<link href="<?php echo base_url() ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>assets/css/responsive.css" rel="stylesheet">

<!-- Fav Icon -->
<link rel="shortcut icon" href="#">

<link rel="apple-touch-icon" href="#">
<link rel="apple-touch-icon" sizes="114x114" href="#">
<link rel="apple-touch-icon" sizes="72x72" href="#">
<link rel="apple-touch-icon" sizes="144x144" href="#">

<!-- Modernizr -->
<script src="<?php echo base_url() ?>assets/js/modernizr.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.js"></script> 
<script src="<?php echo base_url() ?>assets/js/bootstrap3.min.js"></script> <!-- Bootstrap -->
<script src="<?php echo base_url() ?>assets/js/jquery.validate.min.js"></script> 

</head>
<body>
<!-- This section is for Splash Screen -->
<div class="ole">
<section id="jSplash">
	<div id="circle"></div>
</section>
</div>
<!-- End of Splash Screen -->

<!-- Header -->
<header>
    <div class="sticky-nav">
    	<a id="mobile-nav" class="menu-nav" href="#menu-nav"></a>
        
        <div id="logo">
        	<a id="goUp" href="#home-slider" title="Brushed | Responsive One Page Template"><?=$this->lang->line('VegeBox');?></a></div>
        
        <nav id="menu">
        
        	<ul id="menu-nav">
            	<li id="nav_home"><a href="<?php echo base_url() ?>main"><?php echo $this->lang->line('home'); ?></a></li>
                 <?php if(!$this->session->userdata('userdata')): ?>
					<li id="nav_login"><a href="<?php echo base_url() ?>user"><?=$this->lang->line('Login');?></a></li>
                 <?php endif; ?>
                <li id="nav_order"><a href="<?php echo base_url() ?>order"><?=$this->lang->line('Order');?></a></li>

                <?php $sess =  $this->session->userdata('userdata');
                    if($sess):
                        if($sess['status'] !== 'New'): ?>
					        <li id="nav_myorders"><a href="<?php echo base_url() ?>order/my_order"><?=$this->lang->line('MyOrders');?></a></li>
                 <?php endif; 
                  endif; 
                 ?>
                 
                <li id="nav_faq" onClick="menu_click('main/faq')"><a><?=$this->lang->line('FAQ');?></a></li>
                
                <?php if($this->session->userdata('userdata')): ?>
	                <li id="nav_profile"><a href="<?php echo base_url() ?>user/profile"><?=$this->lang->line('Profile');?></a></li>
                    <li id="nav_about"><a href="<?php echo base_url() ?>user/log_out"><?=$this->lang->line('LogOut');?></a></li>
                 <?php endif; ?>

				<!--Jan 26: 6:02 AM
					@Author Pyae Sone
					Languge Dropdown-->
				<select onchange="javascript:window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/'+this.value;">
					<option value="english" <?php if($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
					<option value="unicode" <?php if($this->session->userdata('site_lang') == 'unicode') echo 'selected="selected"'; ?>>unicode</option>
					<option value="zawgyi" <?php if($this->session->userdata('site_lang') == 'zawgyi') echo 'selected="selected"'; ?>>zawgyi</option>
				</select>
				<p><?php echo $this->lang->line('welcome_message'); ?></p>
				<!--Jan 26: 6:02 AM
					@Author Pyae Sone
					Languge Dropdown-->
            </ul>
            <script type="text/javascript">	
				
				/* Make Current Nav */			
				var urlpath = window.location.pathname;
				
				if(urlpath.indexOf("order/my_order") != -1)
				{	
					$('#nav_myorders').addClass('current');
				}
				else if(urlpath.indexOf("order") != -1)
				{	
					$('#nav_order').addClass('current');
				}
				else if(urlpath.indexOf("main/faq") != -1)
				{	
					$('#nav_faq').addClass('current');
				}
				else if(urlpath.indexOf("user/profile") != -1)
				{	
					$('#nav_profile').addClass('current');
				}
				else if(urlpath.indexOf("user") != -1)
				{	
					$('#nav_login').addClass('current');
				}
				else if(urlpath.indexOf("main/contact_us") != -1)
				{	
					$('#nav_contact').addClass('current');
				}
				else if(urlpath.indexOf("main") != -1)
				{	
					$('#nav_home').addClass('current');
				}
				
				function menu_click(url)
				{
					window.location.href = "<?php echo base_url() ?>"+url;
				}
			</script>
        </nav>
    </div>
</header>
<br>
<div class="rt-page-container rt-container rt-dark" style="opacity:1">
	<div id="rt-drawer">
       <div class="clear"></div>
    </div>
    <div id="rt-transition">
    	<div id="rt-mainbody-surround">
        	<div id="rt-feature">
            	<div class="rt-grid-12 rt-alpha rt-omega">
                	<div class="rt-block box2 title4">
                    	<div class="module-surround">
                        	<div class="module-content">
                            
                            	