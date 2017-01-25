<?php include('common/header.php'); ?>	
<div id="page-wrapper">
	<div class="row">	
		<div class="col-lg-12">
			<h1>Delivery Days and Township List</h1>
		<hr />
		</div>		
		
		<div class="col-lg-12">
		<div id="msg_data"></div>
			<div class="col-lg-5">
				<div class="col-md-4">Delivery Day</div>
				<div class="col-md-8">
					<select class="form-control" id="delivery_day" >
						<option>Select Delivery Day</option>
						<?php if(isset($delivery_day) && $delivery_day != false) {
									foreach($delivery_day as $del) { 
										if($del->delivery == "YES") { ?>
											<option value="<?php echo $del->day_id; ?>"><?php echo $del->name ?></option>
						<?php } } }?>
					
					</select>
				</div>
				<br /><br /><br />
				<div class="col-md-4" id="label_townships"></div>
				<div class="col-md-8" id="check_townships"></div>
			</div>
			<div class="col-lg-5">
				<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsedeliveryday" aria-expanded="false" aria-controls="collapsedeliveryday">
				  Change Delivery Days
				</button>
				<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsetownships" aria-expanded="false" aria-controls="collapsetownships">
				  Add New Township
				</button>
				<br /><br />
				<div class="collapse" id="collapsedeliveryday">
				  <div class="well">
						<?php if(isset($delivery_day) && $delivery_day != false) {
									foreach($delivery_day as $del) { 
										if($del->delivery == "YES") { ?>
											<input type="checkbox" class="dday" value="<?php echo $del->day_id; ?>" data_name="<?php echo $del->name; ?>" checked="checked" />&nbsp;&nbsp;&nbsp;<?php echo $del->name; ?><br />
									<?php } else { ?>
										<input type="checkbox" class="dday" value="<?php echo $del->day_id; ?>"  data_name="<?php echo $del->name; ?>" />&nbsp;&nbsp;&nbsp;<?php echo $del->name; ?><br />
									<?php }?> 
						<?php } }?>	
						<br />	
						<button class="btn btn-success pull-right" onclick="delivery_day()" type="button">Save</button>
						<br /><br />		
				  </div>
				</div>
				<div class="collapse" id="collapsetownships">
				  <div class="well">
					<label>Township Name :	</label><input type="text" class="form-control" id="tsp_name" />
					<br />
					<!-- Button trigger modal -->
					<button type="button" onclick="initmap()" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
					  Find Lat & Long
					</button>
					
					<!-- Modal -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Find The Location on Map</h4>
						  </div>
						  <div class="modal-body">
						  	<input type="hidden" id="lat"/>
						  	<input type="hidden" id="lag"/>
							<div id="map_canvas" style="width: 90%; height: 500px;margin:0 auto;"></div>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="savelatlag()">Save</button>
						  </div>
						</div>
					  </div>
					</div>
					<!--Model end-->
					<div style="clear:both"></div>
					<br />
					<label>Township Latitude :	</label><input type="text" class="form-control" id="tsp_lat" disabled /><br />
					<label>Township Longitude :	</label><input type="text" class="form-control" id="tsp_lang" disabled /><br />
					<button class="btn btn-success pull-right" onclick="save_town()" type="button">Save</button>
					<br /><br />		 
				  </div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
 
<script type="text/javascript">
$('#delivery_day').change(function(){ 
	var day = $('#delivery_day').val();	
	$.post('<?php echo base_url() ?>admin/delivery/twonshipdata',{ dayid : day },function(data){
			if(data != 0)
			{
				$('#label_townships').html('');
				$('#check_townships').html('');
				$('#label_townships').html('Townships');
				var townships = JSON.parse(data);
				var checkdata = "";
				for(var n in townships)
				{
					if(townships[n]['name'] != 'Other')
					{
						if(townships[n]['select'] == 'YES')
							checkdata += '<input type="checkbox" class="town" value="'+townships[n]['township_id']+'" checked="checked" />&nbsp;&nbsp;&nbsp;'+townships[n]['name']+'<br />';
						else
							checkdata += '<input type="checkbox" class="town" value="'+townships[n]['township_id']+'" />&nbsp;&nbsp;&nbsp;'+townships[n]['name']+'<br />';
					}
				}
				checkdata += '<br /><button class="btn btn-success pull-right" onclick="township_details()" type="button">Save</button>';
				
				$('#check_townships').html(checkdata);
			}
		});
});	

function initmap()
{
	var myLatLng = {lat: 16.8660694, lng: 96.195132};
	var lat = 16.8660694;
	var long  = 96.195132;

	  // Create a map object and specify the DOM element for display.
	  var map = new google.maps.Map(document.getElementById('map_canvas'), {
	    center: myLatLng,
	    scrollwheel: false,
	    zoom: 13
	  });
	
	map.setCenter(new google.maps.LatLng(lat, long));
	marker = new google.maps.Marker({
				position: map.getCenter(), 
				map: map
			});	
	 
	 marker.setPosition(new google.maps.LatLng(lat, long));
	 
	 // must define the Listener right after creating the Marker 
	 google.maps.event.addListener(
		map,
		'click',
		function(event) {
			if(marker)
				marker.setMap(null);
				
			marker = new google.maps.Marker({
				position: event.latLng, 
				map: map
			});
		
			map.setCenter(marker.getPosition());
	
			$('#lat').val(marker.position.lat());
			$('#lag').val(marker.position.lng());
		}
	);

	
	google.maps.event.trigger(map, 'resize');
}

function savelatlag()
{
	var lat = $('#lat').val();
	var lag = $('#lag').val();
	$('#tsp_lat').val(lat);
	$('#tsp_lang').val(lag);
}

function township_details()
{
	var day = $('#delivery_day').val();
	var town = [];
	$('.town').each(function(){
		if(this.checked == 1)
			town.push($(this).val());
	});
	$.post('<?php echo base_url() ?>admin/delivery/addtspdetails',{ dayid : day , townships : town },function(data){
		if(data != 0)
		{
			$('#msg_data').html('<div class="alert alert-success">Successfully added township(s) for delivery!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		}
		else
			$('#msg_data').html('<div class="alert alert-danger">Error in adding delivery township. Please refresh the page and try again!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
	});
}

function delivery_day()
{
	var dday = [];
	$('.dday').each(function(){
		if(this.checked == 1)
			dday.push($(this).val());
	});
	$.post('<?php echo base_url() ?>admin/delivery/add_deliveryday',{ dayid : dday },function(data){
		if(data != 0)
		{
			$('#delivery_day').html('');
			var dayappend = "<option>Select Delivery Day</option>"
			$('.dday').each(function(){
				if(this.checked == 1)
					dayappend += "<option value="+$(this).val()+" >"+$(this).attr('data_name')+"</option>";dday.push();
			});
			$('#delivery_day').html(dayappend);
			$('#msg_data').html('<div class="alert alert-success">Successfully addded delivery day!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		}
		else
			$('#msg_data').html('<div class="alert alert-danger">Error in adding delivery day. Please refresh the page and try again!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
	});
}

function save_town()
{
	var town_name = $('#tsp_name').val();
	if(town_name == "")
	{
		$('#msg_data').html('<div class="alert alert-danger">Township is Required!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		return false;
	}
	var town_lat = $('#tsp_lat').val();
	if(town_lat == "")
	{
		$('#msg_data').html('<div class="alert alert-danger">Township Latitude is Required!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		return false;
	} 
	var town_lang = $('#tsp_lang').val();
	if(town_lang == "")
	{
		$('#msg_data').html('<div class="alert alert-danger">Township Longitude is Required!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		return false;
	}
	var townships = <?php echo json_encode($townships); ?>;
	for(var i in townships)
	{
		if(town_name == townships[i]['name'])
		{
			$('#msg_data').html('<div class="alert alert-danger">This township is already in the township list!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
			return false;
		}
	}
	$.post('<?php echo base_url() ?>admin/delivery/add_township',{ townname : town_name, townlat : town_lang , townlong : town_lang },function(data){
		if(data != 0)
		{		
			 $('#tsp_name').val('');
			 $('#tsp_lat').val('');
			 $('#tsp_lang').val('');
			$('#msg_data').html('<div class="alert alert-success">Successfully added a new township!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		}
		else
			$('#msg_data').html('<div class="alert alert-danger">Error in adding new townships. Please refresh the page and try again!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
	});
}



</script>
</body>
</html>