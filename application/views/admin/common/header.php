<!DOCTYPE html>
<html lang="en"><head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fresco - Vege Box Management</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url() ?>assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url() ?>assets/css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url() ?>assets/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->    

	<link href="<?php echo base_url() ?>assets/css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url() ?>assets/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="<?php echo base_url() ?>assets/js/jquery-1.11.0.js"></script> 
	<script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url() ?>assets/js/sb-admin-2.js"></script>
	

	<style>
    .sidebar ul li a {
		color:#13CB13 !important;
	}
	.sidebar ul li a.active {
		color:#fff !important;
	}
	
	.sidebar ul li a.active:hover {
		background-color: #a61124 !important;
   		 color: #FFF !important;
	}

	
	.navbar-top-links li a {
		color:#13CB13 !important;
	}
	
	.nav>li>a:hover, .nav>li>a:focus 
	{
		background-color: #242424 !important;
		color: #fff !important; /*#01C701*/
	}
    </style>
    
</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url() ?>" style="padding:0px;">
                	<img src="<?php echo base_url() ?>assets/img/logo.jpg">
                </a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  Notifications <span class="badge" id="total_noti"></span> &nbsp; &nbsp; <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts" id="notidata">
                       
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
						<li><a href="<?php echo base_url().'admin/admin_login/change_password'; ?>"><i class="fa fa-key fa-fw"></i> Change Password</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url().'admin/admin_login/log_out'; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a id="nav_dashboard" href="<?php echo base_url() ?>admin/dashboard/main"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                       <li id="nav_orders">
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Orders Management<span class="fa arrow"></a>
							<ul class="nav nav-second-level">
                                <li>
                                    <a id="nav_all_order" href="<?php echo base_url() ?>admin/orders/manage_order"><i class="glyphicon glyphicon-th-list fa-fw"></i> All Orders</a>
                                </li>
                                <li>
                                    <a id="nav_del_order" href="<?php echo base_url() ?>admin/orders/delivered_order"><i class="glyphicon glyphicon-ok-sign fa-fw"></i> Delivered Orders</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a id="nav_summarize" href="<?php echo base_url() ?>admin/summarize/"><i class="fa fa-table fa-fw"></i> Summarise Orders</a>
                        </li>
                        
                        <li id="nav_delivery">
							<a href="#"><i class="fa fa-truck fa-fw"></i> Delivery Management <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a id="nav_del_tsp" href="<?php echo base_url() ?>admin/delivery/manage_township"><i class="fa fa-sitemap fa-fw"></i> Delivery Townships</a>
                                </li>
                                <li>
                                    <a id="nav_del_map" href="<?php echo base_url() ?>admin/delivery/delivery_locations"><i class="fa fa-map-marker fa-fw"></i> Delivery Locations</a>
                                </li>
                            </ul>
                        </li> 
                        <li id="nav_customer">
							<a href="#"><i class="glyphicon glyphicon-user fa-fw"></i> Customer Management <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                     <a id="nav_act_customer" href="<?php echo base_url() ?>admin/customer/mange_customer"><i class="fa fa-user fa-fw"></i> Active Customers </a>
                                </li>
                                <li>
                                    <a id="nav_inact_customer" href="<?php echo base_url() ?>admin/customer/archieve_customer"><i class="glyphicon glyphicon-list-alt fa-fw"></i> Inactive Customers</a>
                                </li>
                             </ul>
                           
                        </li>
                        
                        <li id="nav_product">
							<a href="#"><i class="glyphicon glyphicon-tags fa-fw"></i> Product Management <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a id="nav_box" href="<?php echo base_url() ?>admin/product/box_list"><i class="glyphicon glyphicon-folder-close fa-fw"></i> Vegy Boxes</a>
                                </li>
                                <li>
                                    <a id="nav_item" href="<?php echo base_url() ?>admin/product/item_list"><i class="glyphicon glyphicon-list-alt fa-fw"></i> Additional Items</a>
                                </li>
                             </ul>
                        </li>
                        
                        <li>
                            <a id="nav_faq" href="<?php echo base_url() ?>admin/system/faq_list"><i class="glyphicon glyphicon-info-sign fa-fw"></i> FAQs</a>
                        </li> 
                        
                        <li>
                            <a id="nav_statistics" href="<?php echo base_url() ?>admin/statistics"><i class="fa fa-bar-chart-o fa-fw"></i> Statistics Report</a>
                        </li>
                        
                         <li>
                            <a id="nav_faq" href="<?php echo base_url() ?>admin/system/settings"><i class="glyphicon glyphicon-cog fa-fw"></i> System Settings</a>
                        </li> 
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        
<script type="text/javascript">	
 $(document).ready(function () {
        $('.dropdown-toggle').dropdown();		
    });
	
	/* Highlight Current Nav */			
	var urlpath = window.location.pathname;
	
	if(urlpath.indexOf("dashboard") !== -1)
	{	
		$('#nav_dashboard').addClass('active');
	}	
	else if(urlpath.indexOf("manage_order") != -1)
	{	
		$('#nav_all_order').addClass('active');
		$('#nav_orders').addClass('active');
	}
	else if(urlpath.indexOf("delivered_order") != -1)
	{	
		$('#nav_del_order').addClass('active');
		$('#nav_orders').addClass('active');
	}			
	else if(urlpath.indexOf("summarize") != -1)
	{	
		$('#nav_summarize').addClass('active');
	}
	else if(urlpath.indexOf("manage_township") != -1)
	{	
		$('#nav_delivery').addClass('active');
		$('#nav_del_tsp').addClass('active');
	}
	else if(urlpath.indexOf("delivery_locations") != -1 || urlpath.indexOf("view_map") != -1 )
	{	
		$('#nav_delivery').addClass('active');
		$('#nav_del_map').addClass('active');
	}
	else if(urlpath.indexOf("delivery_locations") != -1 || urlpath.indexOf("view_map") != -1 )
	{	
		$('#nav_delivery').addClass('active');
		$('#nav_del_map').addClass('active');
	}
	else if(urlpath.indexOf("box_list") != -1 || urlpath.indexOf("edit_box") != -1 )
	{	
		$('#nav_product').addClass('active');
		$('#nav_box').addClass('active');
	}
	else if(urlpath.indexOf("item_list") != -1 || urlpath.indexOf("item_entry") != -1 )
	{	
		$('#nav_product').addClass('active');
		$('#nav_item').addClass('active');
	}
	else if(urlpath.indexOf("customer/archieve_customer") != -1)
	{	
		$('#nav_customer').addClass('active');
		$('#nav_inact_customer').addClass('active');
	}
	else if(urlpath.indexOf("mange_customer") != -1)
	{	
		$('#nav_customer').addClass('active');
		$('#nav_act_customer ').addClass('active');
	}
	else if(urlpath.indexOf("faq_list") != -1)
	{	
		$('#nav_faq').addClass('active');
	}
	else if(urlpath.indexOf("settings") != -1)
	{	
		$('#nav_setting').addClass('active');
	}
	else if(urlpath.indexOf("orders_report") != -1)
	{					
		$('#li_reports').addClass('active');
		$('#nav_order_report').addClass('active');
	}
	else if(urlpath.indexOf("customer_report") != -1)
	{	
		$('#li_reports').addClass('active');
		$('#nav_customer_report').addClass('active');
	}
	else if(urlpath.indexOf("income_report") != -1)
	{	
		$('#li_reports').addClass('active');
		$('#nav_income_report').addClass('active');
	}
	else if(urlpath.indexOf("statistics") !== -1)
	{	
		$('#nav_statistics').addClass('active');
	}
	
	function goto_read(noti_id)
	{
		$.post("<?php echo base_url(); ?>admin/Dashboard/read_noti",{ noti : noti_id},function(data){
			
		});
	}
	<?php if($this->session->userdata('userdata')) {
			$dd = $this->session->userdata('userdata');
			if($dd['user_role'] == 'Admin'){ ?>
			function noti()
			{
				$.post("<?php echo base_url(); ?>admin/Dashboard/get_noti",function(data){
					if(data)
					{	
						$('#notidata').html('');
						data = JSON.parse(data);						
						var total = data.length;
						$("#total_noti").html(total);
						var html_data = "";
						for(var i in data)
						{							
							html_data += '<li><a onclick="goto_read('+data[i]['noti_id']+')" href="<?php echo base_url(); ?>admin/customer/stop_orderreply/'+data[i]['user_id']+'"><div><i class="fa fa-hand-o-right"></i>'+data[i]['name']+'<span class="pull-right label label-danger">Order Stop</span></div></a></li><li class="divider">';					
						}	
						$('#notidata').html(html_data);			
						
					}						
				});
			}
			setInterval(function(){
				noti();
			},6000);
	<?php }  } ?>
</script>