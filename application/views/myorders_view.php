<?php include('common/header.php'); ?>
<div class="row" style="margin:0 auto;">    

    <div class="widget stacked widget-table action-table">    			
        <div class="widget-header">					
        <h2 class="heading-green" style="padding:10px;">
        	<b>MY ORDERS</b> 
            <small><a data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('listOfBox');?>"> <i class="glyphicon glyphicon-question-sign"></i> </a></small> 
        </h2>

    </div> <!-- /widget-header -->

    <div class="widget-content">	
       	<div id="no-more-tables">
        	<table class="col-md-12 table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
                    	<th><?=$this->lang->line('orderRef');?></th>
        				<th><?=$this->lang->line('orderDate');?></th>
        				<th class="text-center"><?=$this->lang->line('BoxType');?></th>
        				<th class="text-center"><?=$this->lang->line('deliveryDay');?></th>
        				<th class="text-center"><?=$this->lang->line('addtionalItem');?></th>
        				<th class="numeric"><?=$this->lang->line('Subtotal');?></th>
                        <th class="text-center"><?=$this->lang->line('weeksSubscribed');?></th>
        				<th class="text-center"><?=$this->lang->line('weeksDelivered');?></th>
                        <th class="text-center"><?=$this->lang->line('status');?></th>
        				<th></th>
        			</tr>
        		</thead>
        		<tbody>
                <?php
					if(isset($orders) && $orders !== false):
						foreach($orders as $row):
				?>
        			<tr>
                    	<td data-title="Order Ref"><?= $row->order_ref ?></td>
        				<td data-title="Order Date"><?= date('d-m-Y',strtotime($row->order_date)) ?></td>
        				<td data-title="Box Type">
                        <ul> 
                        <?php
							if($row->box_num >= 1)
							{
								$boxes = explode(",", $row->boxes);
								for($b = 0; $b < (int)$row->box_num; $b++)
								{
									$box = explode(":", $boxes[$b]);
									echo '<li>'.ucfirst(strtolower($box[0])).' &times; '.$box[1].'</li>';
								}
							}
						?>                          
                        </ul>
                        </td>
        				<td class="text-center" data-title="<?=$this->lang->line('deliveryDay');?>"><?= $row->delivery_day ?></td>
        				<td data-title="<?=$this->lang->line('addtionalItem');?>">
                        <ul>
                         <?php
							if($row->item_num >= 1)
							{
								$items = explode(",", $row->items);
								for($i = 0; $i < (int)$row->item_num; $i++)
								{
									$item = explode(":", $items[$i]);
									echo '<li>'.$item[0].'('.$item[1] .') &times; '.$item[2].'</li>';
								}
							}
						?>   
                        </ul>
                        </td>
        				<td data-title="Subtotal" class="numeric"><?= number_format($row->subtotal,0) ?> ks</td>
        				<td data-title="Weeks Subscribed" class="text-center"><?= $row->week_num ?></td>
                        <td data-title="Weeks Delivered" class="text-center">
						<?php 
							if($row->subscription == 'Yes' && $row->order_status !== 'Stop')
								echo $row->week_status;
							else
								echo '-';

						 ?>
                        </td>
                        <td data-title="Status" class="text-center">
							<?php 
								if((int)$row->week_status == (int)$row->week_num)
									echo 'Ends';
								else
								{
									switch ($row->order_status) {
									    case "Pause":
									        echo $this->lang->line('onHold');
									        break;
									    case "Stop":
									        echo $this->lang->line('Cancelled');
									        break;
									    default:
									        echo $row->order_status;
									}
									
								}
								
							?>
                        </td>
        				<td data-title="" class="text-center"> 
                        <?php  
                        	// show action buttons if the order is not stopped or ended
							if($row->order_status !== 'Stop' && ($row->week_status !== $row->week_num) ):
								
								$delivery_day = $row->delivery_day;
								$today = date('D');
								if($delivery_day !== false):			
									// Check if it is before 3 days of delivery				
									$check = false;
									if($delivery_day == 'Mon' && ($today == 'Tue' || $today == 'Wed' || $today == 'Thu'))
										$check = true;
									else if($delivery_day == 'Tue' && ($today == 'Wed' || $today == 'Thu' || $today == 'Fri'))
										$check = true;
									else if($delivery_day == 'Wed' && ($today == 'Thu' || $today == 'Fri' || $today == 'Sat'))
										$check = true;
									else if($delivery_day == 'Thu' && ($today == 'Fri' || $today == 'Sat' || $today == 'Sun'))
										$check = true;
									else if($delivery_day == 'Fri' && ($today == 'Sat' || $today == 'Sun' || $today == 'Mon'))
										$check = true;
									else if($delivery_day == 'Sat' && ($today == 'Sun' || $today == 'Mon' || $today == 'Tue'))
										$check = true;
									else if($delivery_day == 'Sun' && ($today == 'Mon' || $today == 'Tue' || $today == 'Wed'))
										$check = true;		
								
									$date = date('Y-m-d', strtotime($row->next_del));
								
									$date_diff = strtotime($date) - strtotime(date('d-m-Y'));
									$days_left = floor($date_diff/(60*60*24));
								
									if( $check == true ||  $days_left > 3):
								
						?>     
                            			<a href="<?= base_url().'order/edit_order/'. $row->order_id ?>" class="btn btn-info btn-catchy-info btn-sm" title="Edit Order"><?=$this->lang->line('editOrder');?></a>

										<?php if($row->subscription == 'Yes'): 	
                                                if($row->order_status == 'On-going'):
                                        ?>
                            						<a class="btn btn-warning btn-catchy-warning btn-sm btn_pause" data-id="<?=  $row->order_id ?>" title="On Hold Subscription"><?=$this->lang->line('onHold');?></a>
                            
                       					<?php 	else: ?>
                        
                        							<a class="btn btn-success btn-catchy-success btn-sm btn_resume" data-id="<?=  $row->order_id ?>"  title="Resume Subscription"><?=$this->lang->line('Resume');?></a>
                            
                        				<?php	endif; ?> 
                                                                  
	                            				<a class="btn btn-danger btn-catchy-danger btn-sm btn_stop" data-id="<?=  $row->order_id ?>" title="Cancel Subscription"><?=$this->lang->line('Cancel');?></a>
                        				<?php endif; 	?>
                        </td>
                       <?php 	endif; 
					  		 endif;
						 endif;
					   ?>
        			</tr>
                 <?php 
				 		endforeach;
					endif;
				 ?>
                </tbody>
            </table>
        </div>
    </div>
	<?php echo (isset($pagination) && $pagination !== '' ? $pagination : '') ?>
</div>


<!-- Cancel Subscription Modal -->
<div class="modal fade" id="stop_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 style="margin-bottom:0px;"><?=$this->lang->line('cancelSubscription');?></h2>
            </div>
            <div class="modal-body">
            	<div id="msg"></div>
                <form id="stop_form">
                	<input type="hidden" id="hid_order_id" name="hid_order_id" value="">
                    <div class="form-group">
                       <label><?=$this->lang->line('Reason');?></label>
                       <textarea class="form-control" name="txtreason" id="txtreason" rows="5"></textarea>
                      </div>
                </form>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('Close');?></button>
            	<button class="btn btn-primary" id="btn_send"><?=$this->lang->line('Send');?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Success Modal -->
<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 style="margin-bottom:0px;" id="success_title"></h2>
            </div>
            <div class="modal-body">
                <p id="success_msg"></p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Error Modal -->
<div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 style="margin-bottom:0px;" id="error_title"> </h2>
            </div>
            <div class="modal-body">
                <p id="err_msg"></p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php include('common/footer.php'); ?>
<style>
	a.btn:hover{
		color:#fff !important;
		/*font-weight:bold;*/
	}
</style>
<script>

$('.title4').attr('style','padding:0px;');	

$(document).on('click', '.btn_pause', function() {
	var id = $(this).data('id');
	var ele = $(this);	
	pause_subscription(id, ele);
	
});


$(document).on('click', '.btn_resume', function() {
	var id = $(this).data('id');
	var ele = $(this);		
	resume_subscription(id, ele);
	
});
var stop_ele;
$(document).on('click', '.btn_stop', function() {
	stop_ele = $(this);
	var id = $(this).data('id');
	$('#hid_order_id').val(id);
	
	$('#stop_modal').modal('show');
});

$(document).on('click', '#btn_send', function() {
	
	var reason = $('#txtreason').val();	
	if(reason == '')
	{
		$('#msg').html('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Please enter your reason for cancelling the subscription ! </b></div>');
		$('#txtreason').closest('.form-group').addClass('has-error');
	}
	else
	{
		$('#btn_send')
			.prop('disabled', true)
			.html('Processing....');
			
		var formData = $('#stop_form').serialize();
		$.ajax({
				url: '<?php echo base_url() ?>order/stop_subscription',  
				type: 'POST',
				data: formData
			})
			.done(function( response ) {			
				data = JSON.parse(response);			
				if(data.status == 'error')
				{
					$('#msg').html(data.msg);
				}
				else
				{
					// show success msg
					$('#msg').html(data.msg);
					
					// disable the reason textarea
					$('#txtreason').prop('disabled', true);
					
					// hide the send button to prevent user from clicking it again ang again
					$('#btn_send').hide();
					
					// change order status to stop
					stop_ele.parent().prev().html('Cancelled');
					
					// remove all the action buttons from the list
					stop_ele.parent().empty();
					
					
				}
			})
			.fail(function() {	
				$('#msg').html('Internal server error occured. Please refresh the page and try again. Sorry for your inconvenience.');
			});
	}
});

$(document).on('focus', '#txtreason', function() {
	$(this).closest('.form-group')
	.removeClass('has-error');
	$(".alert").fadeTo(500, 0).slideUp(500, function(){
		$(this).remove(); 
	});
});


function pause_subscription(id, ele)
{
	bootbox.confirm("Are you sure you want to On Hold this order subscrption ?", function(result) {
	  	if(result == true)
		{
			$.ajax({
				url: '<?php echo base_url() ?>order/pause_subscription',  
				type: 'POST',
				data: {'order_id' : id}
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
						.parent().prev().html('On Holdd');
					
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

function resume_subscription(id, ele)
{
	bootbox.confirm("Are you sure you want to resume this order subscrption ?", function(result) {
	  	if(result == true)
		{
			$.ajax({
				url: '<?php echo base_url() ?>order/resume_subscription',  
				type: 'POST',
				data: {'order_id' : id}
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
						.html('On Hold')
						.attr('title', 'On Hold Subscription')
						.attr('class', 'btn btn-warning btn-catchy-warning btn-sm btn_pause')
						.parent().prev().html('On-going');
					
					$('#success_title').html(data.title);
					$('#success_msg').html(data.msg);
					$('#success_modal').modal('show');
				}
			})
			.fail(function() {
				$('#error_title').html('<i class="glyphicon glyphicon-remove-sign"></i> Resume Subscription Failed');
				$('#err_msg').html('There is an internal server error occured while resuming your subscription. Please refresh the page and try again. Sorry for your inconvenience.');
				$('#error_modal').modal('show');
			});
		}
	});
	
}

</script>