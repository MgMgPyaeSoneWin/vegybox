<?php include('common/header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Delivery Locations</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div> 
            <!-- /.row -->
            <div class="row">
            	 <div class="col-xs-12">
                 	<div class=" well">
                    	<form class="form-horizontal" action="<?php echo base_url() ?>admin/delivery/delivery_locations" method="get">
                        	<div class="row">
                                <div class="col-xs-12">
                                    <label class="col-xs-5 col-sm-4 control-label no-padding-right" for="form-field-1"> Get Locations of : </label>
            
                                    <div class="col-xs-12 col-sm-5">
                                        <div class="input-group">
                                            <input value="<?php echo $today_date; ?>" class="datepicker form-control" name="date" id="date" type="text" data-date-format="dd-mm-yyyy">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
                                    	<button type="submit" class="btn btn-primary"> Search </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                 </div>                 
            </div>
            <div class="row">
            	 <div class="col-xs-12">
                 	<div class="panel panel-primary">
                        <div class="panel-heading">
                         <h3 class="panel-title"><i class="fa fa-map-marker"></i> Locations of 
			  	<?php //if(isset($orders[0]->order_date) && $orders !== false)
						 // echo $orders[0]->order_date.' ('.date("D", strtotime($orders[0]->order_date)).') ';
					 // else
						echo $today_date.' ('. date("D", strtotime($today_date)).') ';  
				?> Orders 
                          </h3>
                        </div>          
                        <div class="panel-body">  
                        	<table class="table table-user-information">                             
                            <tbody>
                            <?php
								if(isset($orders) && $orders !== false):
									foreach($orders as $order):
							?>
                              <tr>
                              	<td><?php echo $order->township; ?></td>
                                <td><?php echo $order->order_num; ?> Orders</td>
                                <td>
                                	<a target="_blank" href="<?php echo base_url() ?>admin/delivery/delivery_map?orders=<?php echo $order->order_id ?>&tsp=<?php echo $order->township .'&address='. $order->address_id . '&date=' .$today_date; ?>"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-download"></i> &nbsp; Download Map</a>
                                    
                                	 <a target="_blank" href="<?php echo base_url() ?>admin/delivery/view_map?orders=<?php echo $order->order_id ?>&tsp=<?php echo $order->township .'&address='. $order->address_id . '&date=' .$today_date; ?>"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-map-marker"></i> &nbsp; View Map</a>                                     
                                     
                                </td>
                              </tr>
                           <?php
							   		endforeach;
								else:
									echo '<h3>No order for today !</h3>';
								endif;
						   ?>
                            </tbody>
                          </table> 
                        </div>
                     </div>
                 </div>
            </div>
            <!-- /.row -->
           <!--<div id="map_canvas" style="width: 100%; height: 500px;margin:0 auto;"></div>  -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
  <link href="<?php echo base_url() ?>assets/css/datepicker.css" rel="stylesheet">
	<script src="<?= base_url()?>assets/js/bootstrap-datepicker.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
    
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKQI6vaSJq5HTYXJAcM5vaSvhwtXqzu-I&sensor=false"></script> -->

</body>

</html>
<script>
$('.datepicker').datepicker();

</script>