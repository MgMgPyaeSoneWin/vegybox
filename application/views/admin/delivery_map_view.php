<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php include('common/header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Delivery Locations</h1>
                </div>
                <hr>
                
                <div class="col-lg-12">
                	<ol class="breadcrumb">
                      <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
                      <li><a href="<?php echo base_url() ?>admin/delivery/delivery_locations?date=<?php echo $date; ?>">Delivery Locations</a></li>
                      <li class="active">View Map</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
            	 <div class="col-xs-12">
                 	<div class="panel panel-primary">
                        <div class="panel-heading">
                         <h3 class="panel-title"><i class="fa fa-map-marker"></i> Locations of 
			  	<?php echo $tsp.' for '; 
			  		 if(isset($date) && $orders !== false)
						  echo $date.' ('.date("D", strtotime($date)).') ';
					  else
						echo date('d-m-Y').' ('. date("D", strtotime(date('d-m-Y'))).') ';  
				?> Orders 
                          </h3>
                        </div>          
                        <div class="panel-body" id="map_canvas"  style="width: 100%; height: 500px;margin:0 auto;">  
                        <script> var markers = []; </script>
						<?php
                            if(isset($orders) && $orders != false):
                                foreach($orders as $row):
                        ?>
                        	<script>
								markers.push("<?php echo $row->lat ?>, <?php echo $row->long ?>");
							</script>
						<?php
                            	endforeach;
							endif;
                        ?>
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
console.log(markers[0].split(','));
	
	var LatLng = markers[0].split(',');
	var mk;
	
	 var map = new google.maps.Map(document.getElementById('map_canvas'), {
      zoom: 15,
      center: new google.maps.LatLng(LatLng[0], LatLng[1]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
	
	google.maps.event.trigger(map, 'resize');
	
	for (i = 0; i < markers.length; i++) 
	{		
		mk = markers[i].split(',');
		marker = new google.maps.Marker({
					position: map.getCenter(), 
					map: map
				});	
		 
		 marker.setPosition(new google.maps.LatLng(mk[0], mk[1]));
	}
	

</script>