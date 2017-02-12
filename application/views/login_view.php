<?php include('common/header.php'); ?>

    <div class="row" style="margin:0 auto;padding:5px;">
        <div style="width:100%;">
        	<h3 class="heading-red" style="margin-top:5px;"><?=$this->lang->line('HaveanAccount');?></h3>
            <h4 class="heading-green" style="margin-top:5px;"><?=$this->lang->line('needToSign');?><small><a data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('needInfo');?>"> <i class="glyphicon glyphicon-question-sign"></i> </a></small></h4>
            
            <div class="well">
                <ul id="myTab" class="nav nav-tabs">
                  <li class="active"><a href="#signin" data-toggle="tab"><?=$this->lang->line('signIn');?></a></li>
                  <li class=""><a href="#signup" data-toggle="tab"><?=$this->lang->line('register');?></a></li>
                </ul> 
                
                <div id="myTabContent" class="tab-content">                    
                    <!-- Login Tab -->
                    <div class="tab-pane fade active in" id="signin">
                       <form id="login_form" class="form-horizontal" role="form" action="<?php echo base_url() ?>user/verify" method="post">
                       	  <div style="margin-top:5px;" id="msg"></div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label" ><?=$this->lang->line('email');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="Email">
                            </div>
                          </div>
            
                          <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('password');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="password" class="form-control" id="txtpassword" name="txtpassword"  placeholder="Password">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-4 col-xs-10">
                              <button type="submit" id="btn_login" class="btn btn-success"><?=$this->lang->line('signIn');?></button>
                              <a class="pull-right" data-toggle="modal" data-target="#forgot_pwd_modal"><?=$this->lang->line('forgetPwd');?></a>
                            </div>
                          </div>
                        </form>
                    </div>
                    
                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="signup">
                    	  <div id="message" style="margin-top:5px;"></div>
                         <form class="form-horizontal" id="register_form" role="form" action="<?php echo base_url() ?>user/register">
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"><?=$this->lang->line('email');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="email" class="form-control" id="txt_email" name="txt_email" placeholder="johndoe@mail.com">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"><?=$this->lang->line('Name');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="text" class="form-control" id="txt_name" name="txt_name" placeholder="John Doe">
                            </div>
                          </div>
            
                          <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('password');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="password" class="form-control" id="txt_password" name="txt_password" placeholder="******" >
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label"><?=$this->lang->line('Re-EnterPassword');?></label>
                            <div class="col-sm-4 col-xs-10">
                              <input type="password" class="form-control" id="txtcpassword" name="txtcpassword" placeholder="******">
                            </div>
                          </div>
                          
                          
                          <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-4">
                              <button type="submit" id="btn_register" class="btn btn-success"><?=$this->lang->line('register');?></button>
                            </div>
                          </div>
                        </form>                       
                  </div>
                </div>               
            </div>
        </div>
     </div>
     
     
     <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgot_pwd_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
          </div>
          <form id="forgot_form" action="<?php echo base_url() ?>user/forgot_password">
          <div class="modal-body"> 
          	<div id="forgot_msg"></div>
            <p>To reset your password, enter the email address you use to sign in. We will send you a new password to this email.</p>
            <div class="form-group" style="margin: 0 auto 5px 0;">
                <div class="input-group" style="margin:0 auto;width:100%;">
                    <input type="email" name="txt_pwd_recovery_email" id="txt_pwd_recovery_email" class="form-control" placeholder="Enter your email.." required autofocus>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>               
                </div>
            </div>            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-info" id="btn_ok">OK</button>
          </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php include('common/footer.php'); ?>
<script>
$(document).ready(function(){

	$('#btn_login').click(function(e){		
	
		//disable the button to prevent multiple clicks
		$(this)
			.prop('disabled', true)
			.html('Signing In....');
		//do submission 
		var submit_url = "<?= base_url(); ?>user/verify";		
		var formData = $('#login_form').serialize();

		$.ajax({
			url: submit_url,  
			type: 'POST',
			data: formData
		})
		.done(function( response ) {
			response = JSON.parse(response);

			if(response.status == 'error')
			{
				$("#msg").empty();
				$("#msg").html( response.msg );
				$("#msg").show();
				
				$('#login_form')[0].reset();			
				
			}
			else
				window.location.href = '<?php echo base_url() ?>order';
			
			$("#btn_login")
					.removeAttr("disabled")
					.html('<?=$this->lang->line('signIn');?>');
			
			
		});
		
		
		// return false to disable form submission
		return false;
					
	});
	
	
	// Validate Registration Form
	$('#register_form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			txt_email: {
				email:true,
				required : true
			},
			txt_password: {
				minlength: 5,
				required : true
			},	
			txtcpassword:{
				required: true,
				minlength: 5,
				equalTo: "#txt_password"
			}
		},

		invalidHandler: function (event, validator) { //display error alert on form submit   
			$('.alert-danger', $('.login-form')).show();
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
			else error.insertAfter(element);
		}
	});
	
	// Registration
	$('#btn_register').click(function(e){
		
		e.preventDefault;
		if ($("#register_form").valid() == false){			
			return;
		}

		bootbox.confirm("<?=$this->lang->line('sureEmail');?>"+$("#txt_email").val()+" ?", function(result) {
			if(result == true)
			{	
				//disable the button to prevent multiple clicks
				$(this)
					.prop('disabled', true)
					.html('Processing....');
				//do submission 
				var submit_url = "<?= base_url(); ?>user/register";		
				var formData = $('#register_form').serialize();
		
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
						$('#register_form')[0].reset();
					
					$("#btn_register")
						.removeAttr("disabled")
						.html('Register');
				});
			}
		});
		
		
		// return false to disable form submission
		return false;
					
	});
	
	
	// Forgot Password
	$('#btn_ok').click(function(e){
		e.preventDefault;
		//do submission 
		var submit_url = "<?= base_url(); ?>user/forgot_password";		
		var formData = $('#forgot_form').serialize();
		
		//disable the button to prevent multiple clicks
		$(this)
			.prop('disabled', true)
			.html('Processing....');
			
		$.ajax({
			url: submit_url,  
			type: 'POST',
			data: formData
		})
		.done(function( response ) {
	
			response = JSON.parse(response);
			$("#forgot_msg").empty();
			$("#forgot_msg").html( response.msg );
			$("#forgot_msg").show();
			
			
			$('#forgot_form')[0].reset();
				
			$("#btn_ok")
				.removeAttr("disabled")
				.html('OK');
		});
		
		
		// return false to disable form submission
		return false;
					
	});
	
});
</script>