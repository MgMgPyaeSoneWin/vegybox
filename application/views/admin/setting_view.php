<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> System Settings</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li class="active">Settings</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <div class="row">
         <div class="col-xs-12"> 
         	<?php
				if($this->session->flashdata('error_msg') != NULL)
				{
					echo $this->session->flashdata('error_msg');
				}
			?>
         	<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/system/edit_settings">
         		<fieldset title="Settings">
                	<div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Maximun Box Limit</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="text" class="form-control" name="txtmax" id="txtmax" placeholder="Maximum box limit" value="<?php echo ($setting !== false ? $setting->max_box_limit : '') ?>" required>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Box Price</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="number" min="0" class="form-control" name="txtprice" id="txtprice" placeholder="Box price" value="<?php echo ($setting !== false ? $setting->box_price : '') ?>" required>
                        </div>
                      </div>
                </fieldset>
                
                <fieldset title="Settings">
                	<div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Company Address</label>
                        <div class="col-xs-12 col-sm-8">
                           <textarea class="form-control" name="txtaddress" rows="4" placeholder="Company address" ><?php echo ($setting !== false ? $setting->address : '') ?></textarea>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Company Phone</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="text" class="form-control" name="txtphone" id="txtphone" placeholder="Company Phone" value="<?php echo ($setting !== false ? $setting->phone : '') ?>" required>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Company Email</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="email" class="form-control" name="txtemail" id="txtemail" placeholder="Company Email" value="<?php echo ($setting !== false ? $setting->email : '') ?>" required>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Auto Reply Email</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="email" class="form-control" name="txtauto" id="txtauto" placeholder="Auto Reply Email" value="<?php echo ($setting !== false ? $setting->autoreply_email : '') ?>" required>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">SMTP Email</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="email" class="form-control" name="txtsmtp_email" id="txtsmtp_email" placeholder="SMTP Email" value="<?php echo ($setting !== false ? $setting->smtp_username : '') ?>" required>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">SMTP Password</label>
                        <div class="col-xs-12 col-sm-8">
                          <input type="password" class="form-control" name="txtsmtp_pwd" id="txtsmtp_pwd" placeholder="SMTP Password" value="<?php echo ($setting !== false ? $setting->smtp_password : '') ?>" required>
                        </div>
                      </div>
                </fieldset>
              
              <hr>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
              </div>
            </form>
         </div>
    </div>
</div>    
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/file-upload.js"></script>