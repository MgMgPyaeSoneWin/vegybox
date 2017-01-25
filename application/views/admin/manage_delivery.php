<?php include('header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Delivery Managment</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	 <div class="col-xs-12">
                 	<div class=" well">
                    	<form class="form-horizontal">
                        	<div class="row">
                                <div class="col-xs-12 col-sm-7">
                                    <label class="col-xs-5 col-sm-4 control-label no-padding-right" for="form-field-1"> Get Locations of : </label>
            
                                    <div class="col-xs-12 col-sm-8">
                                        <div class="input-group">
                                            <input value="07-09-2014" class="datepicker form-control" name="date" id="date" type="text" data-date-format="dd-mm-yyyy">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
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
                          <h3 class="panel-title"><i class="fa fa-map-marker"></i> Locations of 07-09-2014 (Sunday) Orders </h3>
                        </div>          
                        <div class="panel-body">  
                        	<table class="table table-user-information">                             
                            <tbody>
                              <tr>
                              	<td>Yankin</td>
                                <td>3 Orders</td>
                                <td>
                                	 <a target="_blank" href="<?php echo base_url() ?>index.php/welcome/delivery_map"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-download"></i> &nbsp; Download Map</a>
                                </td>
                              </tr>
                              <tr>
                                <td>Sanchaung</td>
                                <td>3 Orders</td>
                                <td>
                                	 <a target="_blank" href="<?php echo base_url() ?>index.php/welcome/delivery_map"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-download"></i> &nbsp; Download Map</a>
                                </td>
                              </tr>
                              <tr>
                                <td>Bahan</td>
                                <td>1 Orders</td>
                                <td>
                                	 <a target="_blank" href="<?php echo base_url() ?>index.php/welcome/delivery_map"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-download"></i> &nbsp; Download Map</a>
                                </td>
                              </tr>
                              <tr>
                                <td>Shangri-la Residence</td>
                                <td>8 Orders</td>
                                <td>
                                	 <a target="_blank" href="<?php echo base_url() ?>index.php/welcome/delivery_map"  class="btn btn-xs btn-default pull-right" style="margin-right:8px;"><i class="fa fa-download"></i> &nbsp; Download Map</a>
                                </td>
                              </tr>
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

    <!-- jQuery Version 1.11.0 -->
    <script src="<?php echo base_url() ?>assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    
  
	<script src="<?= base_url()?>assets/js/bootstrap-datepicker.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>

	<script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/sb-admin-2.js"></script>
    
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKQI6vaSJq5HTYXJAcM5vaSvhwtXqzu-I&sensor=false"></script> -->

</body>

</html>
<script>
$('.datepicker').datepicker();

var map = '';
var marker = "";

	
function initMap()
{			
	
	/*var lat =  16.806217800000000000;
	var lang = 96.134143099999960000;

			
	var myOptions = {
		zoom: 18,
		center: new google.maps.LatLng(lat, lang),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	map.setCenter(new google.maps.LatLng(lat, lang));
	
	marker = new google.maps.Marker({
				position: map.getCenter(), 
				map: map
			});	
	 marker.setPosition(new google.maps.LatLng(lat, lang));		*/
}

//google.maps.event.addDomListener(window, 'load', initMap);	

</script>