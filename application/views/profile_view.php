<?php include('common/header.php'); ?>
	<div class="row" style="margin:0 auto; padding:15px;">
        <h2 class="heading-green" style="margin-top:0px !important;">
            <i class="glyphicon glyphicon-user"></i> MY PROFILE
        </h2> 
    	<!--<hr style="margin:15px 0px;">-->
        
        <div style="margin-top:5px;" id="message"><?php echo ($this->session->flashdata('msg') !== '' ? $this->session->flashdata('msg') : ''); ?></div> 
        <br>
        <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab"><b><?=$this->lang->line('ContactInfo');?></b></a></li>
            <li role="presentation"><a href="#password" aria-controls="password" role="tab" data-toggle="tab"><b><?=$this->lang->line('ChangePassword');?></b></a></li>
          </ul>
  
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="contact">
            	<br> 
                <form class="form-horizontal" id="form1" method="post" action="<?php echo base_url() ?>user/update_contact">
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label"><?=$this->lang->line('email');?></label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" value="<?php echo (isset($userdata) ? $userdata['email'] :'' ) ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label"><?=$this->lang->line('Name');?></label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="txtname" name="txtname" value="<?php echo (isset($userdata) ? $userdata['name'] :'' ) ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5">
                          <button type="submit" class="btn btn-success"><?=$this->lang->line('UpdateProfile');?></button>
                        </div>
                    </div>
                      
                </form>          	
            </div>
            <div role="tabpanel" class="tab-pane" id="password">
            <br>
                    
                <form class="form-horizontal" id="form" method="post">
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('OldPassword');?></label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" id="txt_old_pwd" name="txt_old_pwd" placeholder="Old Password">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('NewPassword');?></label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" id="txt_new_pwd" name="txt_new_pwd" placeholder="New Password">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('ConfirmPassword');?></label>
                        <div class="col-sm-5">
                          <input type="password" class="form-control" id="txt_confirm_pwd" name="txt_confirm_pwd" placeholder="Confirm Password">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5">
                          <button type="submit" class="btn btn-success" id="btn_change"><?=$this->lang->line('Change');?></button>
                        </div>
                    </div>
                      
                </form>
            </div>
          </div>
        
        
       
     </div>
     
     
<?php include('common/footer.php'); ?>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">		
	$('#form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			txt_old_pwd: {
				required: true
			},
			txt_new_pwd: {
				required: true,
			},
			txt_confirm_pwd: {
				required: true,
				equalTo: "#txt_new_pwd"
			}			
		},

		messages: {
			txt_old_pwd: {
				required: "Please specify your old password.",
			},
			txt_new_pwd: {
				required: "Please specify your new password.",
			},
			txt_confirm_pwd: {
				required: "Please specify a confirm password.",
				equalTo: "Please confirm your password again."
			}
		},

		invalidHandler: function (event, validator) { //display error alert on form submit   
			$('.alert-danger', $('#form')).show();
		},

		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
			$(e).remove();
		},

		errorPlacement: function (error, element) {
			if(element.is(':checkbox') || element.is(':radio')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else error.insertAfter(element.parent());
		}
	});
	
	
	$('#btn_change').click(function(e){
		e.preventDefault;
		if ($("#form").valid() == false){			
			return;
		}
		
		//disable the button to prevent multiple clicks
		$(this)
			.prop('disabled', true)
			.html('Processing....');
			
		//do form submission 
		var submit_url = "<?= base_url(); ?>user/update_new_password";		
		var formData = $('#form').serialize();

		$.ajax({
			url: submit_url,  
			type: 'POST',
			data: formData
		})
		.done(function( response ) {
			response = JSON.parse(response);
			$("#message").empty();
			$("#message").html( response.msg );
			$("#message").show();
			
			if(response.status == 'success')
				$('#form')[0].reset();
			
			$("#btn_change")
				.removeAttr("disabled")
				.html('Register');
		});
		
		
		// return false to disable form submission
		return false;
					
	});
	

</script>	