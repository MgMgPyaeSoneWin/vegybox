<?php include('common/header.php'); ?>	
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
 <div id="page-wrapper">
  <div class="row">
  	 <div class="col-lg-12">
            <h1 class="page-header"> Customer History</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li><a href="<?php echo base_url() ?>admin/customer/mange_customer">Customer Management</a></li>
              <li class="active">View History</li>
            </ol>
        </div>
	<div class="col-lg-12">
	
	<?php if(isset($user_info) && $user_info !=false ) { ?>
	
		<div class="row">
			<div class="col-md-3"><label style="margin-left:20%">Customer Name</label></div>
			<div class="col-md-4">:&nbsp;&nbsp;&nbsp;<?php echo $user_info[0]->name; ?></div><br /><br /><br />
		</div>
		<div class="row">
			<div class="col-md-3"><label style="margin-left:20%">Customer Email</label></div>
			<div class="col-md-4">:&nbsp;&nbsp;&nbsp;<?php echo $user_info[0]->email; ?></div><br /><br /><br />
		</div>
		<div class="row">
			<div class="col-md-3"><label style="margin-left:20%">Order Status</label></div>
			<div class="col-md-4">:&nbsp;&nbsp;&nbsp;
			<?php
			$count =0;
			foreach($user_info as $st)
			{
				if($count !=0)
					echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

				
				if((int)$st->week_status == (int)$st->week_num)
					echo '<span class="label label-default">Ends</span>';
				else
				{
					switch ($st->order_status) {
						case "Pause":
							echo '<span class="label label-warning">On Hold</span>';
							break;
						case "Stop":
							echo '<span class="label label-danger">Cancelled</span>';
							break;
						default:
							echo '<span class="label label-success">'.$st->order_status.'</span>';
					}
				}
				if($st->order_stop_date != '')
					echo ' ('.$st->order_stop_date.')';
				$count ++;
			}
			?>
		</div><br /><br /><br />
		</div>
		<div class="row">
			<div class="col-md-3"><label style="margin-left:20%">Total Order</label></div>
			<div class="col-md-3">:&nbsp;&nbsp;&nbsp;<?php echo $user_info[0]->total_order; ?></div>
			<div class="col-md-2"><!--<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseorder" aria-expanded="false" aria-controls="collapsedeliveryday">View Details</button> --></div><br /><br /><br />
		</div>

		<!--div class="row">
			<div class="col-md-3"><label style="margin-left:20%">Total Delivered Order</label></div>
			<div class="col-md-3">:&nbsp;&nbsp;&nbsp;<?php echo $user_info[0]->total_deliver; ?></div>
			<br /><br /><br />
		</div-->
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-9">
				<div class="collapse" id="collapsedeliver">
					  <div class="well">
					  deliver
					  </div>
				</div>
			</div>
		
		</div>		
		
	<?php } ?>
	
	</div>
 </div>
 </div>