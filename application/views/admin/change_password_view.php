<?php include('common/header.php'); ?>
<div id="page-wrapper">
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> Change Password</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div> 
    <!-- /.row -->
    <div class="row">
         <div class="col-xs-12">	
	<form class="form-horizontal" role="form" id="admin_login" method="post" action="<?php echo base_url() ?>admin/admin_login/password_change">
	<div id="error_data">
	<?php
	
		if($this->session->flashdata('error_msg') != NULL)
		{
			echo $this->session->flashdata('error_msg');
		}
	?>
	</div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Old Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Old Password" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">New Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">New Confirm Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="new_cpass" name="new_cpass" placeholder="New Confirm Password" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button id="change_pass" type="submit" class="btn btn-default">Change Password</button>
    </div>
  </div>
</form>

	</div>
</div>
<!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<script type="text/javascript">
$('#change_pass').click(function(){
	new_pass = $('#new_pass').val();
	new_cpass = $('#new_cpass').val();
	if(new_pass != new_cpass)
	{
		$('#error_data').html('<div class="alert alert-danger">Please new password and confirm password must be same!!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
		return false;
	}
});
</script>
</body>
</html>