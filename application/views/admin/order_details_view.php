<?php include('common/header.php'); ?>	
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
 <div id="page-wrapper">
  <div class="row">
  	 <div class="col-lg-12">
            <h1 class="page-header"> Order Details</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li><a href="<?php echo base_url() ?>admin/orders/manage_order">Order Management</a></li>
              <li class="active">Order Details</li>
            </ol>
        </div>
	<div class="col-lg-12">
	
	<?php if(isset($orders) && $orders !=false ) { ?>
	
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label class="">Order Ref</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->order_ref; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label class="">Order Date</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo date('d-m-Y', strtotime($orders[0]->order_date)); ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Order Subscription</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->subscription; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Order Status</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;
            <?php 
                if((int)$orders[0]->week_status == (int)$orders[0]->week_num)
					echo 'Ended';
				else
				{
					switch ($orders[0]->order_status) {
					    case "Pause":
					        echo 'On Hold';
					        break;
					    case "Stop":
					        echo 'Cancelled';
					        break;
					    default:
					        echo $orders[0]->order_status;
					}
					
				}
            ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Weeks Subscribed</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->week_num; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Weeks Delivered</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->week_status; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Type of Box</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->box_name; ?></div><br /><br /><br />
		</div>
		<?php if($orders[0]->additional_item_status != 'No') { ?>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Item Subscription</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->item_subscription; ?></div><br /><br /><br />
		</div>		
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Additional Item</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->additional; ?></div><br /><br /><br />
		</div>
		<?php } ?>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Order info:</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo ($orders[0]->other_info == '' ? '-' :$orders[0]->other_info); ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Delivery Day</label></div>
			<?php
				$delivery_day = $orders[0]->day_id;
				switch ($delivery_day) {
					case "2":
						$delivery_day = 'Monday';
						break;
					case "3":
						$delivery_day = 'Tuesday';
						break;
					case "4":
						$delivery_day = 'Wednesday';
						break;
					case "5":
						$delivery_day = 'Thursday';
						break;
					case "6":
						$delivery_day = 'Friday';
						break;
					case "7":
						$delivery_day = 'Saturday';
						break;
					case "1":
						$delivery_day = 'Sunday';
						break;
				}
			?>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $delivery_day; ?></div><br /><br /><br />
		</div>
		<?php if($orders[0]->order_status !== 'Stop'): ?>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Next delivery is on:</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;
            <?php 
                if($orders[0]->order_status == 'On-going'):
                    echo date('d-m-Y', strtotime($orders[0]->next_delivery)); 
                else:
                    echo '-';
                endif;
            ?></div><br /><br /><br />
		</div>
		<?php endif; ?>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Myanmar Contact Person</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->contact_person; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Contact Number</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->ph_no . ', ' . $orders[0]->mobile; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Township</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->township; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Address</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo $orders[0]->address; ?></div><br /><br /><br />
		</div>
		<div class="row" style="margin:0 auto">
			<div class="col-md-3 col-xs-6 text-right"><label>Delivery Instruction</label></div>
			<div class="col-md-9 col-xs-6">:&nbsp;&nbsp;&nbsp;<?php echo ($orders[0]->delivery_instruction == '' ? '-' :$orders[0]->delivery_instruction); ?></div><br /><br /><br />
		</div>
	<?php } ?>
	
	</div>
 </div>
 </div>