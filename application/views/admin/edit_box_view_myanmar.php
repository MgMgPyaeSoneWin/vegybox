<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> Vegy Boxes</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li><a href="<?php echo base_url() ?>admin/product/box_list">Vegy Boxes</a></li>
              <li class="active">Edit Vegy Box</li>
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
         	<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/product/insert_box_lang">
              <input type="hidden" name="hidID" id="hidID" value="<?php echo ($box !== false ? $box->box_id : '') ?>">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Box Name</label>
                <div class="col-xs-12 col-sm-8">
                  <input type="text" class="form-control" name="txtname" id="txtname" placeholder="Box name" value="<?php echo ($box !== false ? $box->name : '') ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Description</label>
                <div class="col-xs-12 col-sm-8">
                  <textarea class="form-control" name="txtdesc" rows="4" placeholder="Box description..." required><?php echo ($box !== false ? $box->description : '') ?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label  class="col-xs-12 col-sm-2 control-label">Price</label>
                <div class="col-xs-12 col-sm-8">
                  <input type="text" class="form-control" name="txtprice" id="txtprice" placeholder="Price" value="<?php echo ($box !== false ? $box->price : '') ?>" required>
                  <span id="err2" class="text-danger"></span>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-xs-12 col-sm-2 control-label">Image</label>
                
                <div class="col-xs-12 col-sm-8" id="img_holder" style="display:none">
	                <img id="img" src="<?php echo ($box !== false ? base_url().'assets/'.$box->image : '') ?>" class="img-thumbnail"> <br>
                    <a id="btn_change" class="btn btn-link text-center"> Change Image </a>
                </div>
                
                <div class="col-xs-12 col-sm-8" id="fileupload" style="display:none">
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span>
                    <span class="fileupload-exists">Change</span>         <input type="file" name="file" id="file" /></span>
                    <span class="fileupload-preview"></span>
                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                    <small><a data-toggle="tooltip" data-placement="right" title="Only JPG, GIF or PNG file formats are supported and the maximum file size is 1MB. "> <i class="glyphicon glyphicon-question-sign"></i> </a></small> 
                  </div>
                   <span id="err" class="text-danger"></span>
                   
                </div>
                
              </div>
              
               <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Status</label>
                <div class="col-xs-12 col-sm-8">
                  <select name="cbostatus" id="cbostatus" class="input-sm" >
                  	<option value="enabled">Enabled</option>
                    <option value="disabled">Diabled</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Language</label>
                <div class="col-xs-12 col-sm-8">
                  <select name="cbolang" id="cbolang" class="input-sm" >
                  	<option value="english">English</option>
                    <option value="unicode">Unicode</option>
                    <option value="zawgyi">Zawgyi</option>
                  </select>
                </div>
              </div>
              
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

<script>
$(document).ready(function(){

  $('[data-toggle="tooltip"]').tooltip();

	<?php if($box !== false):?> 
		$('#cbostatus').val('<?php echo $box->status ?>');
		$('#img_holder').show();
	<?php else: ?>
		$('#fileupload').show();
		$('#img_holder').hide();
		$('[name="file"]').prop('required',true);
	<?php endif; ?>
	
	$('#btn_change').click(function(){
		$('#img').remove();
		$(this).remove();
		$('#fileupload').show();
		$('#img_holder').hide();
		$('[name="file"]').prop('required',true);
	});
	
	$('#txtprice').change(function () { 
		$('#err2').empty();
		if($.isNumeric($(this).val()) == false)
		{
			$(this).val('');
			$('#err2').html('This should only be number value !');
		}
	});
	
	$('INPUT[type="file"]').change(function () { 
		var size = this.files[0].size; //console.log(size);
		$('#err').empty();
		var ext = $('#file').val().split('.').pop().toLowerCase();
		 
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			//alert('invalid extension!');
			$(this).val('');
			$('#err').html('The file type you are trying to upload is not allowed !');
		}
		
		if(( size / 1000) > 1000)
		{
			$(this).val('');
			$('#err').html('The uploaded file must not larger than 1MB (1000KB) !');
		}
		
	});
	
});
</script>