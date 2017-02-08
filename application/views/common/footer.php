                               
                                <div class="clear"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <p class="text-center"><?=$this->lang->line('company');?></p>
        <div class="clear"></div>
    </div>
    
    <div class="panel-r panel-default-r">
      <div class="panel-leftheading">
		  <h3 class="panel-lefttitle" data-stage="close">Get Mobile App <i class="glyphicon glyphicon-chevron-down"></i></h3>
	  </div>
	  <div class="panel-rightbody">
		<br>
		  <p><a href="<?php echo base_url() ?>assets/frescovegybox.cordova.android.201605250714.apk" download="FrescoVegyBox.apk" target="_blank" class="btn btn-lg btn-default" style="padding:10px 20px"><i class="glyphicon glyphicon-save"></i> Download Now !</a></p>
		  <p><a href="#"><img src="<?= base_url() ?>assets/img/apk-download.png" width="196px"></a></p>
	  </div>
		<div class="clearfix">
		</div>
	</div>

    <script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/supersized.3.2.7.min.js"></script> <!-- Slider -->
	<script src="<?php echo base_url() ?>assets/js/waypoints.js"></script> <!-- WayPoints -->
    <script src="<?php echo base_url() ?>assets/js/waypoints-sticky.js"></script> <!-- Waypoints for Header -->
    <script src="<?php echo base_url() ?>assets/js/plugins.js"></script> 
    <script src="<?php echo base_url() ?>assets/js/main.js"></script> <!-- Default JS -->

</body>
</html>

<script>
$(document).ready(function(){
	$.ajax({
			url: '<?php echo base_url() ?>main/check_status',  
			type: 'POST'
		})
		.done(function(response) {	
			
			data = JSON.parse(response);					
			if(data.status == 'show')
			{				
				$('#nav_order').show();
			}
			else
			{
				$('#nav_order').hide();
			}
		});
        
        $('.panel-leftheading').click(function(){ 
        	var stage = $('.panel-lefttitle').data('stage');
    
    		if(stage == 'close')
    		{
    			$(this).attr('style','right:0px');
    			$('.panel-rightbody').attr('style','right:0px');
    			$('.panel-lefttitle')
    			.data('stage', 'open')
    			.html('Get Mobile App ! <i class="glyphicon glyphicon-chevron-up"></i>');
    		}
    		else
    		{
    			$(this).attr('style','right:-250px');
    			$('.panel-rightbody').attr('style','right:-250px');
    			$('.panel-lefttitle')
    			.data('stage', 'close')
    			.html('Get Mobile App ! <i class="glyphicon glyphicon-chevron-down"></i>');
    		}
    	});
});
</script>