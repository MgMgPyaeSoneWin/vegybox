<?php include('common/header.php'); ?>

        <div id="page-wrapper">
		            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">All Orders</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->  
            
            <form class="form-horizontal">
            <div class="row">
            	<div class="well">
                	<div class="row">					
                        <div class="col-xs-12 col-sm-4">
                            <label class="col-xs-5 col-sm-4 control-label">Customer Name</label>
    
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="name" id="name" value="<?php echo (isset($name)  && $name != 'NULL' ? $name:"") ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <label class="col-xs-5 col-sm-4 control-label"> Order Ref.</label>
    
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" name="ref" id="ref" value="<?php echo (isset($ref) && $ref != 'NULL' ? $ref:"") ?>" class="form-control">
                            </div>
                        </div>
                        
                     </div>
                     <hr>
                     <div class="row">					
                        <div class="col-xs-12 col-sm-4">
                            <label class="col-xs-5 col-sm-4 control-label"> Delivery Day</label>    
                            <div class="col-xs-12 col-sm-8">
                            
                               <select class="form-control" name="delivery" id="delivery">
                               <option value="">--- Choose ---</option>
    						   	<?php if(isset($days) &&  $days!= false) { 
									foreach($days as $d) {?>
									<option value="<?php echo $d->day_id ?>" <?php if(isset($days) && $delivery != 'NULL' && $delivery == $d->day_id) echo 'selected'; ?> ><?php echo $d->name ?></option>
								<?php } } ?>                                      
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <label class="col-xs-5 col-sm-4 control-label"> Township</label>    
                            <div class="col-xs-12 col-sm-8">
                              <select class="chosen-select col-xs-14 col-sm-12" name="township" id="township" data-placeholder="Choose township ...">
                                    <option value=""></option>
									<?php if(isset($townships) &&  $townships!= false) { 
										foreach($townships as $town1) {?>
										<option value="<?php echo $town1->name ?>" <?php if(isset($town) && $town != 'NULL' && $town == $town1->name) echo 'selected'; ?>><?php echo $town1->name ?></option>
									<?php } } ?>                               
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                        	<button type="button" id="btn_search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        </div>
                     </div>
                </div>
            </div>
            </form>
                <div>                
			<a href="<?php echo base_url() ?>admin/orders/manage_order" class="btn btn-default btn-sm pull-right" style="margin-bottom:5px">Reset Searching</a>								
                </div>   
				<div style="clear:both"></div>
            
            
        <!-- /#page-wrapper -->
		<div class="widget-content">
		<div id="no-more-tables" style="font-size:13px;">
		<?php if(isset($order) && $order != false ) { ?>
        	<table class="col-md-12 table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
                    	<th class="text-center">Order Ref</th>
        				<th class="text-center">Customer</th>
        				<th class="text-center">Box Type</th>
        				<th class="text-center">Addition item</th>
        				<th class="text-center">Order Date</th>
        				<th class="numeric text-center">Delivery Day</th>
        				<th class="text-center">Order Status</th>
						<th class="text-center">Weeks Subscribed</th>
                        <th class="text-center">Weeks Delivered</th>
						<th class="text-center">Township</th>
						<th class="text-center">&nbsp;</th>
        			</tr>
        		</thead>
        		<tbody>
                <?php foreach($order as $or){ ?>
					<tr>
						<td data-title="Order Ref" class="text-center"><a href="<?php echo base_url().'admin/orders/order_details/'.$or->order_id; ?>"><?php echo $or->order_ref; ?></a></td>
						<td data-title="Customer Name" class="text-center"><?php echo $or->name; ?></td>
						<td data-title="Box Type">
						<ul style="padding-left:15px;">
                         <?php
							$items = explode(",", $or->Type);
							foreach($items as $itm)
								echo '<li>'.$itm.'</li>';
						?>   
                        </ul>
						</td>
						<td data-title="Addition item">
						<ul style="padding-left:15px;">
                         <?php
						 if( $or->additional != "")
						 {
							$items = explode(",", $or->additional);
							foreach($items as $itm)
								echo '<li>'.$itm.'</li>';
						}
						?>   
                        </ul>
						</td>
						<td data-title="Order Date" class="text-center"><?php echo $or->order_date; ?></td>
						<td data-title="Delivery Day" class="text-center"><?php echo $or->Day; ?></td>
                        
						<td data-title="Order Status" class="text-center">
                           <?php 
    							if((int)$or->week_status == (int)$or->week_num)
									echo 'Ended';
								else
								{
									switch ($or->order_status) {
									    case "Pause":
									        echo 'On Hold';
									        break;
									    case "Stop":
									        echo 'Cancelled';
									        break;
									    default:
									        echo $or->order_status;
									}
									
								}
								
							?>
                        </td>
                        
						<td data-title="Weeks Subscribed" class="text-center"><?php echo $or->week_num; ?></td>						
						<td data-title="Weeks Delivered" class="text-center" id="<?php echo $or->order_id; ?>_week"><?php echo $or->week_status; ?></td>						
						<td data-title="Township" class="text-center"><?php echo $or->township; ?></td>
						<td data-title="&nbsp;">
                        
                        <?php if($or->week_status !== $or->week_num): ?>
                        <?php if($or->order_status == 'On-going'): ?>
                            <?php if($or->order_status !== "Stop"):  ?>
    							<a href="#" data-id="<?= $or->order_id ?>" data-user="<?= $or->user_id ?>" class="btn btn-danger btn-sm btn_cancel">Cancel Order</a><br/><br>
                                <a href="#" data-id="<?=  $or->order_id ?>" data-user="<?= $or->user_id ?>" class="btn btn-warning btn-sm btn_pause" title="On Hold Subscription"> On Hold</a><br><br>
                            <?php endif; ?>
                        <?php elseif($or->order_status == 'Pause'): ?>
                                <a class="btn btn-success btn-sm btn_resume" data-id="<?=  $or->order_id ?>" data-user="<?= $or->user_id ?>"  title="Resume Subscription"> Resume</a><br><br>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                            <a target="_blank" href="<?php echo base_url() ?>admin/dashboard/invoice/<?php echo $or->order_id; ?>" class="btn btn-primary btn-sm">Download Invoice</a><br/><br>
                            <a target="_blank" href="<?php echo base_url() ?>admin/dashboard/customer_details/<?php echo $or->order_id; ?>" class="btn btn-info btn-sm">Customer Details</a>
                            
						</td>							
					</tr>
				<?php } ?>	           
                </tbody>
            </table>
			<?php } else { echo "No Order Found"; } ?>
        </div>
		</div>
			<div class="row text-center">
				<?php if(isset($pagination))echo $pagination;	?>
			</div>
			<div style="clear:both"></div>
			<br/><br/><br/>
    </div>
    <!-- /#wrapper -->
    <!-- start table here -->
		
       	
    

	<!-- end table here -->
    <!-- Modal -->
<div class="modal fade" id="normalDelivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Normal Box Deliveries</h4>
      </div>
      <div class="modal-body">
       		<table>
            	<tr>
                	<td></td>
                </tr>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

    <!-- Custom Theme JavaScript -->
    <link href="<?php echo base_url() ?>assets/css/chosen.min.css" rel="stylesheet">
    <script src="<?= base_url()?>assets/js/chosen.jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>
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
$(".chosen-select").chosen({
	allow_single_deselect: true
});

$(document).on('click', '#btn_search', function() {
    var page = "<?php echo ($page != '' ? $page : 0) ?>";
    var name = $('#name').val();
    var ref = $('#ref').val();
    var delivery = $('#delivery').val();
    var tsp = $('#township').val();
    
    
    var url = "<?php echo base_url() ?>admin/orders/search_order";
    if(name != '')
        url += "/"+name;
    else
        url += "/NULL";
        
    if(ref != '')
        url += "/"+ref;
    else
        url += "/NULL";
    
    if(delivery != '')
        url += "/"+delivery;
    else
        url += "/NULL";
    
    if(tsp != '')
        url += "/"+tsp;
    else
        url += "/NULL";
    
    url += "/0";
        
    window.location.href = url;
});

$(document).on('click', '.btn_pause', function() {
    var id = $(this).data('id');
    var userid = $(this).data('user');
	var ele = $(this);	
	pause_subscription(id, ele, userid);
	
});


$(document).on('click', '.btn_resume', function() {
	var id = $(this).data('id');
    var userid = $(this).data('user');
	var ele = $(this);		
	resume_subscription(id, ele, userid);
	
});

$(document).on('click', '.btn_cancel', function() {
    var ele = $(this);
    var id = ele.data('id');
     var userid = ele.data('user');
     bootbox.confirm("Are you sure you want to canel this order?", function(r) {
        if (r == true)
    	{
            $.post('<?php echo base_url() ?>admin/orders/cancel_order',{ order_id: id, user_id : userid },function(data){    	
    			if(data == 1)
    			{
    				//ele.hide();
                    window.location.reload();
    			}
    		});
    	}
     });
});

function pause_subscription(id, ele, userid)
{
    bootbox.confirm("Are you sure you want to On Hold this order ?", function(result) {
	  	if(result == true)
		{
			$.ajax({
				url: '<?php echo base_url() ?>admin/orders/pause_subscription',  
				type: 'POST',
				data: {'order_id' : id, 'user_id' : userid}
			})
			.done(function( response) {			
				data = JSON.parse(response);			
				if(data.status == 'error')
				{
					$('#error_title').html(data.title);
					$('#err_msg').html(data.msg);
					$('#error_modal').modal('show');
				}
				else
				{
					ele
						.html('Resume')
						.attr('title' , 'Resume Subscription')
						.attr('class', 'btn btn-success btn-catchy-success btn-sm btn_resume')
						.parent().prev().prev().prev().prev().html('On Hold');
					
					$('#success_title').html(data.title);
					$('#success_msg').html(data.msg);
					$('#success_modal').modal('show');
				}
			})
			.fail(function() {
				$('#error_title').html('<i class="glyphicon glyphicon-remove-sign"></i> On Hold Subscription Failed');
				$('#err_msg').html('There is an internal server error occured while pausing your order subscription. Please refresh the page and try again. Sorry for your inconvenience. ');
				$('#error_modal').modal('show');
			});
		}
	}); 
}

function resume_subscription(id, ele, userid)
{
	bootbox.confirm("Are you sure you want to resume this order ?", function(result) {
	  	if(result == true)
		{
			$.ajax({
				url: '<?php echo base_url() ?>admin/orders/resume_subscription',  
				type: 'POST',
				data: {'order_id' : id, 'user_id' : userid}
			})
			.done(function( response) {			
				data = JSON.parse(response);			
				if(data.status == 'error')
				{
                    bootbox.alert('There is an internal server error occured while resuming your subscription. Please refresh the page and try again. Sorry for your inconvenience.');
				}
				else
				{
					ele
						.html('On Hold')
						.attr('title', 'On Hold Subscription')
						.attr('class', 'btn btn-warning btn-catchy-warning btn-sm btn_pause')
						.parent().prev().prev().prev().prev().html('On-going');
					
					//bootbox.alert('data.msg');
				}
			})
			.fail(function() {
                bootbox.alert('There is an internal server error occured while resuming your subscription. Please refresh the page and try again. Sorry for your inconvenience.');
			});
		}
	});
	
}

function deliver(id,week)
{	
	bootbox.confirm("Are you sure you already deliver this order?", function(r) {
    	if (r == true)
    	{		
    		$.post('<?php echo base_url() ?>admin/orders/deliver_order',{ order_id: id },function(data){		
    			if(data == 1)
    			{
    				$('#'+id+'_week').html(4-(week-1));
    				var latest = week-1;
    				if(latest != 0)
    					$('#'+id+'_action').html('<a href="#" class="btn btn-info" onclick="deliver('+id+','+latest +')">Deliver</a>');
    				else
    					$('#'+id+'_action').html('<a href="#" class="btn btn-info" onclick="deliver('+id+','+latest +')" disabled>Delivered</a>');
    			}
    		});
    	}
    }); 
}
</script>