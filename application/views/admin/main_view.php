<?php include('common/header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Delivery List for 
                    <?php if(isset($day) && $day != false)
					{
						echo $day;
					}?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($view) && $view != false ) echo $view[0]->total_cus ?></div>
                                    <div>Customer Registration</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url(); ?>admin/customer/mange_customer">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-dollar fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"> 
                                    <?php if(isset($view) && $view != false && $view[0]->subtotal != 0 ) {
                                 	   $sub = (($view[0]->subtotal - $view[0]->f_total)/$view[0]->subtotal)*100; 
                                  	  echo number_format($sub, 0).'%';
                                   }else{ echo '0%';}  ?></div>
                                    <div>This Month Income</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url(); ?>admin/statistics">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 pull-right">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-automobile fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($total_deliver)) echo $total_deliver ?></div>
                                    <div>Today Deliveries</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url(); ?>admin/delivery/delivery_locations">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div> 
                <div class="col-lg-3 col-md-6 pull-right">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($view) && $view != false ) echo $view[0]->tm_order; ?></div>
                                    <div>New Orders!</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url(); ?>admin/statistics">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
               
            </div>
            
            <hr>
            
            <!-- /.row -->
             <?php if(isset($order) && $order!= false ) { ?>
            <div class="row">
              <div class="col-lg-12">
            	<div class="panel panel-green">
                <div class="panel-heading">
                     <i class="fa fa-bell fa-fw"></i> <?php if(isset($total_deliver) && $total_deliver != 0 ) echo "Today"; else echo "Comming" ?> Deliveries
                     <span class="pull-right text-muted small"><span class="label label-primary"></span>
                            </span>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
							<?php foreach($order as $or) {?>
                                <tr>
                                    <td><i class="fa fa-hand-o-right fa-fw"></i></td>
                                    <td width="16%"><?php echo $or->name; ?></td>
                                    <td width="16%"><?php echo $or->email; ?></td>
									<td width="13%"><?php echo $or->township; ?></td>
                                    <td width="20%">
                                    	<?php $type = $or->Type; $type = explode(",",$type); foreach($type as $t){echo $t."<br />"; } ?>
                                    	<?php $add = $or->additional; $add = explode(",",$add); foreach($add as $t){echo $t."<br />"; } ?>
                                    </td>                                   
                                    <td width="50%">                                         	
                                            	<a target="_blank" href="<?php echo base_url() ?>admin/dashboard/invoice/<?php echo $or->order_id; ?>/<?php echo $date ?>"  class="btn btn-xs btn-default pull-right"><i class="fa fa-cloud-download"></i> &nbsp;Download Invoice</a>											
                                                <a target="_blank" href="<?php echo base_url() ?>admin/dashboard/customer_details/<?php echo $or->order_id; ?>"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-user"></i> &nbsp; Customer Details</a>
                                            </td>
                                </tr>
								<?php } ?>                               
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
				<div class="row text-center">
					<?php if(isset($pagination))echo $pagination;	?>
				</div>
            </div>
            <?php } else{ echo "No Order Found"; } ?>
                     
            
           
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
	
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<?php
function total_sundays($month,$year)
{
$satdays=0;
$total_days=cal_days_in_month(CAL_GREGORIAN, $month, $year);
for($i=1;$i<=$total_days;$i++)
if(date('N',strtotime($year.'-'.$month.'-'.$i))==2)
$satdays++;
return $satdays;
}
//echo total_sundays(1,2016); 

?>
</body>

</html>
<script>
$('#normalBox').click(function(){
	$('#normalDelivery').modal('show');
});
</script>