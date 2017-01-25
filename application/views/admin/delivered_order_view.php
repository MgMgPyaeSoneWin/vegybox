<?php include('common/header.php'); ?>

<div id="page-wrapper">
	<div class="row">
	<div class="col-lg-12">
	<h1 class="page-header">Delivered Orders</h1>
	</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->      

<form class="form-horizontal" id="searchForm">
    <div class="row">
    	<div class="well">
        	<div class="row">					
                <div class="col-xs-12 col-sm-4">
                    <label class="col-xs-5 col-sm-4 control-label">Customer Name</label>

                    <div class="col-xs-12 col-sm-8">
                        <input type="text" name="name"  id="name" value="<?php echo (isset($customer) && $customer != 'NULL' ? $customer:"") ?>" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <label class="col-xs-5 col-sm-4 control-label"> Order Ref.</label>

                    <div class="col-xs-12 col-sm-8">
                        <input type="text" name="ref" id="ref" value="<?php echo (isset($ref) && $ref != 'NULL' ? $ref:"") ?>" class="form-control">
                    </div>
                </div>
				
                <div class="col-xs-12 col-sm-3">
                    <label class="col-xs-5 col-sm-5 control-label"> Delivery Day</label>    
                    <div class="col-xs-12 col-sm-7">
                    
                       <select class="form-control" name="delivery" id="delivery">
                       <option value="">- Choose -</option>
					   	<?php if(isset($days) &&  $days!= false) { 
							foreach($days as $d) {?>
							<option value="<?php echo $d->day_id ?>" <?php if(isset($days) && $delivery != 'NULL' && $delivery == $d->day_id) echo 'selected'; ?> ><?php echo $d->name ?></option>
						<?php } } ?>                                    
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-1">
                	<button type="button" id="btn_search" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
				       

<div>                
	<a href="<?php echo base_url() ?>admin/orders/delivered_order" class="btn btn-default btn-sm pull-right" style="margin-bottom:5px">Reset Searching </a>								
</div>   
<div style="clear:both"></div>
            
            
        <!-- /#page-wrapper -->
		<div class="widget-content">
		<div id="no-more-tables">
		<?php if(isset($order) && $order != false ) { ?>
        	<table class="col-md-12 table-bordered table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
                    			<th class="text-center">Order Ref</th>
        				<th class="text-center">Customer Name</th>
        				<!--<th class="text-center">Box Type x Qty</th>-->
        				<!--<th class="text-center">Addition item x Qty</th>-->
        				<!--<th class="text-center">Order Date</th>-->
        				<th class="numeric">Delivery Day</th>
						<th class="text-center">Delivery Date</th>
        				<!--<th class="text-center">Order Status</th>-->
					<th class="text-center">Number of Week</th>
                        <th class="text-center">Weeks Delivered</th>
					<!--<th class="text-center">Township</th>-->
					<th class="text-center">Action</th>
        			</tr>
        		</thead>
        		<tbody>
                <?php foreach($order as $or){ ?>
					<tr>
						<td data-title="Order Ref"><?php echo $or->order_ref; ?></td>
						<td data-title="Customer Name"><?php echo $or->name; ?></td>
						<!--<td data-title="Box Type x Qty">
						<ul>
                         <?php
							//$items = explode(",", $or->Type);
							//foreach($items as $itm)
							//	echo '<li>'.$itm.'</li>';
						?>   
                        </ul>
						</td>-->
						<!--<td data-title="Addition item x Qty">
						<ul>
                         <?php
						 //if( $or->additional != "")
						 //{
						//	$items = explode(",", $or->additional);
						//	foreach($items as $itm)
						//		echo '<li>'.$itm.'</li>';
						//}
						?>   
                        </ul>
						</td>-->
						<!--<td data-title="Order Date"><?php //echo $or->order_date; ?></td>-->
						<td data-title="Delivery Day"><?php echo $or->Day; ?></td>
						<td data-title="Delivery Date"><?php echo $or->order_delivery_date; ?></td>
						<!--<td data-title="Order Status"><?php //echo $or->order_status; ?></td>-->
						<td data-title="Number of Week"><?php echo $or->week_num; ?></td>						
						<td data-title="Weeks Delivered"id="<?php echo $or->order_id; ?>_week"><?php echo $or->week_status; ?></td>						
						<!--<td data-title="Township"><?php //echo $or->township; ?></td>-->	
						<td data-title="Action" id="<?php echo $or->order_id; ?>_action"><!--<a href="#" class="btn btn-info">View</a><br/><br/> -->
						<?php if(($or->week_status >= $or->week_num) || ($or->order_status == "Pause")) { ?>
							<a href="#" class="btn btn-info" disabled>Deliver</a>
							<?php } else { ?>
							<a href="#" class="btn btn-info" onclick="deliver(<?php echo $or->order_id.','.$or->week_status.',\''.$or->order_delivery_date.'\''; ?>)">Delivered</a>
							<?php } ?> 
						<!--&nbsp;&nbsp;<a href="#" onclick="change_next(<?php echo $or->order_id.',\''.$or->order_delivery_date.'\''; ?>)" class="btn btn-success">Change Next Week</a>-->
						</td>
					</tr>
				<?php } ?>	           
                </tbody>
            </table>
			<?php } else { echo "All Order already Delivered"; } ?>
        </div>
		</div>
			<div class="row text-center">
				<?php if(isset($pagination))echo $pagination;	?>
			</div>
			<div style="clear:both"></div>
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
<script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>
<script>
$(".chosen-select").chosen({
	allow_single_deselect: true
});
$(document).on('click', '#btn_search', function() {
    var page = "<?php echo ($page != '' ? $page : 0) ?>";
    var name = $('#name').val();
    var ref = $('#ref').val();
    var delivery = $('#delivery').val();
    
    var url = "<?php echo base_url() ?>admin/orders/delivered_order";
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
    
    url += "/0";
        
    window.location.href = url;
});

function deliver(id,week,ddate)
{	
	
	var r = confirm("Are you sure you already deliver the order!");
	if (r == true)
	{		
		$.post('<?php echo base_url() ?>admin/orders/deliver_order',{ order_id: id, order_date: ddate },function(data){
			if(data == 1)
			{
				$('#'+id+'_week').parent().remove();
			}
		});
	}
}
function change_next(id,ddate)
{
	bootbox.confirm("Are you sure you want to change this order to next week ?", function(r) {
		if (r == true)
		{		
			$.post('<?php echo base_url() ?>admin/orders/change_week',{ order_id: id, order_date: ddate },function(data){
				if(data == 1)
				{
					$('#'+id+'_week').parent().remove();
				}
			});
		}
	});
}
</script>