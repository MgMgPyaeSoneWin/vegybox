<?php include('common/header.php'); ?>		
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Customer Management</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
           
        
            <!-- /.row -->
			
            
            
             <div class="row">
                <div class="col-lg-12">
				<div id="error">
					<?php
						if($this->session->flashdata('error_msg') != NULL)
						{
							echo $this->session->flashdata('error_msg');
						}
					?>
				</div>
                	<div class="well">					
                    	<div class="row">							
                            <form class="form-horizontal" method="get" action="<?php echo base_url() ?>admin/customer/search_customer">
                                <div class="col-xs-12 col-sm-5">
                                    <label class="col-xs-5 col-sm-4 control-label"> Name</label>
            
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" name="name" id="name" value="<?php if(isset($name)) echo $name;  ?>" class="form-control">
										<input type="hidden" name="type" id="type" value="<?php if(isset($type)) echo $type;  ?>">
                                    </div>
                                </div>
								
                                <div class="col-xs-12 col-sm-5">
                                    <label class="col-xs-5 col-sm-4 control-label"> Email</label>
            
                                    <div class="col-xs-12 col-sm-8">
                                        <input type="email" name="email" id="email" value="<?php if(isset($email)) echo $email;  ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <button type="submit" id="search_filter" class="btn btn-primary pull-right">Filter</button>
                                </div>
                             </form>  
                          </div>					  
                    </div>
					<div>
						<a href="<?php echo base_url() ?>admin/customer/mange_customer" class="btn btn-default btn-sm pull-right" style="margin-bottom:5px">Reset Filter</a>						
					</div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->           
            <div class="widget-content">
		<div id="no-more-tables">
		<?php if(isset($customer) && $customer != false) { ?>
        	<table class="col-md-12 table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
                    	<!--<th class="text-center">ID</th> -->
        				<th class="text-center">Name</th>
        				<th class="text-center">Email</th>
        				<th class="text-center">Status</th>
        				<th class="text-center">Action</th>
        			</tr>
        		</thead>
        		<tbody>
                <?php foreach($customer as $cus){ 
					 if($type == 'current' && $cus->status != 'Banned' &&  $cus->order_status == 0 && $cus->activation_date != NULL){ ?>
					 <tr>
						<td data-title="Name"><a href="<?php echo base_url() ?>admin/customer/view_history/<?php echo $cus->user_id; ?>"><?php echo $cus->name; ?></a></td>
						<td data-title="Email"><a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=<?php echo $cus->email; ?>" target="_blank"><?php echo $cus->email; ?></a></td>
						<td data-title="Status">
					<div id="<?php echo $cus->user_id; ?>status">
					<?php if($cus->activation_date != NULL ) {
							if( $cus->status == 'Banned') { ?>
								<span class="label label-danger">Banned</span>
					<?php	} else { ?>
								<span class="label label-success">Active</span>
					<?php 	}
						  }
						  else { ?>
								<span class="label label-warning">Inactive</span>
					<?php } ?> 
					</div>
					<?php  if($cus->order_status != 0) {	?>								
						<span class="label label-danger">Order Cancelled</span>	
                       						 <?php  if($cus->reply != 'Yes'){ ?>
									<a href="<?php echo base_url() ?>admin/customer/stop_orderreply/<?php echo $cus->user_id; ?>" >Send Email</a>							
					<?php 		}
							} 
					?>										
					</td>
					<td data-title="Action" id="<?php echo $cus->user_id; ?>action">
					<a href="<?php echo base_url() ?>admin/customer/view_history/<?php echo $cus->user_id; ?>" class="btn btn-info">View History</a>
					<?php if($cus->activation_date != NULL ) {
							if( $cus->status == 'Banned') { ?>
								<a href="#" onclick="unbanned('<?php echo $cus->user_id ?>')" class="btn btn-success">Unbanned</a>
					<?php	} else { ?>
								<a href="#" onclick="banned('<?php echo $cus->user_id ?>')"  class="btn btn-danger">Banned</a>
					<?php 	}
						  }
						  else { ?>
								<a href="#" onclick="activate('<?php echo $cus->user_id ?>')" class="btn btn-success">Activate</a>
					<?php } ?> 								
					</td>
				</tr>
					 <?php }else if($type != 'current' && ($cus->status == 'Banned' ||  $cus->order_status != 0 || $cus->activation_date == NULL)){ ?>
					 <tr>
					<!--<td data-title="ID"><?php //echo $cus->user_id; ?></td> -->
					<td data-title="Name"><a href="<?php echo base_url() ?>admin/customer/view_history/<?php echo $cus->user_id; ?>"><?php echo $cus->name; ?></a></td>
					<td data-title="Email"><a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=<?php echo $cus->email; ?>" target="_blank"><?php echo $cus->email; ?></a></td>
					<td data-title="Status">
					<div id="<?php echo $cus->user_id; ?>status">
					<?php if($cus->activation_date != NULL ) {
							if( $cus->status == 'Banned') { ?>
								<span class="label label-danger">Banned</span>
					<?php	} else { ?>
								<span class="label label-success">Active</span>
					<?php 	}
						  }
						  else { ?>
								<span class="label label-warning">Inactive</span>
					<?php } ?> 
					</div>
					<?php  if($cus->order_status != 0) {	?>								
						<span class="label label-danger">Order Cancelled</span>	
                       						 <?php  if($cus->reply != 'Yes'){ ?>
									<a href="<?php echo base_url() ?>admin/customer/stop_orderreply/<?php echo $cus->user_id; ?>" >Send Email</a>							
					<?php 		}
							} 
					?>										
					</td>
					<td data-title="Action" id="<?php echo $cus->user_id; ?>action">
					<a href="<?php echo base_url() ?>admin/customer/view_history/<?php echo $cus->user_id; ?>" class="btn btn-info">View History</a>
					<?php if($cus->activation_date != NULL ) {
							if( $cus->status == 'Banned') { ?>
								<a href="#" onclick="unbanned('<?php echo $cus->user_id ?>')" class="btn btn-success">Unbanned</a>
					<?php	} else { ?>
								<a href="#" onclick="banned('<?php echo $cus->user_id ?>')"  class="btn btn-danger">Banned</a>
					<?php 	}
						  }
						  else { ?>
								<a href="#" onclick="activate('<?php echo $cus->user_id ?>')" class="btn btn-success">Activate</a>
					<?php } ?> 								
					</td>
				</tr>
					 <?php }?>
				
			<?php } ?>	           
                </tbody>
            </table>
			<?php } else{ ?>
						No Customer Found.
					<?php } ?>
        </div>
		</div>
			<div class="row text-center">
				<?php if(isset($pagination))echo $pagination;	?>
			</div>
			<div style="clear:both"></div>
			<br/><br/>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
   
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
</body>

</html>
<style>
/* Resiable table */

@media only screen and (max-width: 800px) {
    
    /* Force table to not be like tables anymore */
	#no-more-tables table, 
	#no-more-tables thead, 
	#no-more-tables tbody, 
	#no-more-tables th, 
	#no-more-tables td, 
	#no-more-tables tr { 
		display: block; 
	}
 
	/* Hide table headers (but not display: none;, for accessibility) */
	#no-more-tables thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
 
	#no-more-tables tr { border: 1px solid #ccc; }
 
	#no-more-tables td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
		white-space: normal;
		text-align:left;
	}
	
	#no-more-tables td.tbl_img
	{
		padding-left: 1% !important; 
	}
	
	#no-more-tables td.tbl_btn
	{
		padding-left: 1% !important; 
	}
 
	#no-more-tables td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		text-align:left;
		font-weight: bold;
	}
 
	/*
	Label the data
	*/
	#no-more-tables td:before { content: attr(data-title); }
}
</style>
<script>
$('#normalBox').click(function(){
	$('#normalDelivery').modal('show');
});
function activate(id)
{
	var r = confirm("Are you sure you want to activate this user!");
	if (r == true)
	{
		$.post('<?php echo base_url() ?>admin/customer/activate_customer',{ userid : id },function(data){
			if(data == 1)
			{
				$('#'+id+'status').html('<span class="label label-success">active</span>');
				$('#'+id+'action').html('<a href="#" onclick="banned(\''+id+'\')" class="btn btn-danger">Banned</a>');
			}
		});
	}
}

function banned(id)
{
	var r = confirm("Are you sure you want to banned this user!");
	if (r == true)
	{
		$.post('<?php echo base_url() ?>admin/customer/ban_customer',{ userid : id },function(data){
			if(data == 1)
			{
				$('#'+id+'status').html('<span class="label label-danger">Banned</span>');
				$('#'+id+'action').html('<a href="#" onclick="unbanned(\''+id+'\')" class="btn btn-success">Un Banned</a>');
			}
		});
	}
}

function unbanned(id)
{
	var r = confirm("Are you sure you want to banned this user!");
	if (r == true)
	{
		$.post('<?php echo base_url() ?>admin/customer/unban_customer',{ userid : id },function(data){
			if(data == 1)
			{
				$('#'+id+'status').html('<span class="label label-success">active</span>');
				$('#'+id+'action').html('<a href="#" onclick="banned(\''+id+'\')" class="btn btn-danger">Banned</a>');
			}
		});
	}
}

$('#search_filter').click(function(){
	/*var name = $('#name').val();
	var email = $('#email').val();
	if(name == "" && email == "")
	{
		$('#error').html('<div class="alert alert-danger">Please Enter the search value!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		return false;
	}
	else
		return true;*/
});

</script>