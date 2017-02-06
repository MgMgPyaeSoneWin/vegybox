<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> Additional Items</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li><a href="<?php echo base_url() ?>admin/product/item_list">Additional Items</a></li>
              <li class="active">Edit Additional Item</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/product/insert_item_myanmar">
    <div class="row">
         <div class="col-xs-12"> 
         	<div id="msg">
			<?php
				if($this->session->flashdata('error_msg') != NULL)
				{
					echo $this->session->flashdata('error_msg');
				} 
			?>
         	</div>
              <input type="hidden" name="hidID" id="hidID" value="<?php echo ($item !== false ? $item->item_id : '') ?>">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Item Name</label>
                <div class="col-xs-12 col-sm-8">
                  <input type="text" class="form-control" name="txtname" id="txtname" placeholder="Item name" value="<?php echo ($item !== false ? $item->name : '') ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Description</label>
                <div class="col-xs-12 col-sm-8">
                  <textarea class="form-control" name="txtdesc" rows="4" placeholder="Item description..." ><?php echo ($item !== false ? $item->description : '') ?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Type</label>
                <div class="col-xs-12 col-sm-8">
                   <input type="text" class="form-control" name="txtype" id="txtype" placeholder="Item type" value="<?php echo ($item !== false ? $item->type : '') ?>" >
                </div>
              </div>

              <div class="row">
                <div class="form-group">
                <label for="inputLanguage" class="col-xs-12 col-sm-2 control-label">Language</label>
                <div class="col-xs-12 col-sm-8">
                  <select name="cbolang" id="cbolang" class="input-sm" >
                  	<option value="english">English</option>
                    <option value="unicode">Unicode</option>
                    <option value="zawgyi">Zawgyi</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-xs-12 col-sm-2 control-label" for="form-field-2"> Image </label>
                <div class="col-xs-12 col-sm-8" id="img_holder" style="display:none">
	                <img id="img" src="<?php echo ($item !== false ? base_url().'assets/img/'.$item->image : '') ?>" class="img-thumbnail"> <br>
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

              

            </div>
              
         </div>
    </div>
    
    <div class="row">
    	<div class="col-sm-6 col-xs-12">
		    <h3>Sub Items</h3>
        </div>
        
    	<div class="col-sm-6 col-xs-12">
		    <button class="btn btn-primary pull-right" type="button" id="btn_add" style="margin-top:20px;"><i class="glyphicon glyphicon-plus-sign"></i> &nbsp; Add Item</button>
        </div>    
    </div>
    
    <hr>  
                
  	<div id="display">   
   <?php			
		if($item !== false)
		{ 
			if($item->number > 1)
			{												
				$amt = explode(",", $item->net_weight);
				$price = explode(",", $item->price);
				$ids = explode(",", $item->ids);		
				$status = explode(",", $item->status);			
			}
			else
			{
				$amt = $item->net_weight;
				$price = $item->price;
				$ids = $item->ids;
				$status = $item->status;		
			}
			
			for($x=0;$x < count($ids); $x++)
			{
	?>
				<div class="row">    
					<div class="col-md-2"><input type="hidden" name="hid_subID[]" value="<?php echo ($item->number > 1 ? $ids[$x] : $ids) ?>"></div>          
					<div class="col-sm-6 col-md-2">
						<label class="control-label" style="text-align:left" for="form-field-2"> Net Weight  </label>
						<div class="clearfix">
							<input type="text" name="txtweight[]" class="txtweight form-control" value="<?php echo ($item->number > 1 ? $amt[$x] : $amt) ?>" required>
						</div>
					</div>
						
					<div class="col-sm-6 col-md-2">
						<label class="control-label" style="text-align:left" for="form-field-2"> Price  </label>
						<div class="clearfix">
							<input type="text" name="txtprice[]" class="txtprice form-control" value="<?php echo ($item->number > 1 ? $price[$x] : $price) ?>"required>
						</div>
					</div>
                    
					<div class="col-sm-6 col-md-2">
						<label class="control-label" style="text-align:left" for="form-field-2"> Status  </label>
						<div class="clearfix">
							<select name="cbostatus[]" class="input-sm cbostatus" >
								<option value="enabled" <?php echo ($status[$x] == 'enabled' || $status == 'enabled'  ? 'selected="selected"' : '') ?>>Enabled</option>
								<option value="disabled" <?php echo ($status[$x] == 'disabled' || $status == 'disabled'  ? 'selected="selected"' : '') ?>>Disabled</option>
							  </select>
						</div>
					</div>
                    
                    <div class="col-sm-6 col-md-2">
                    <?php if($item->number > 1): ?>
                    	<br>
                    	<button type="button" data-id="<?php echo ($item->number > 1 ? $ids[$x] : $ids) ?>" class="btn btn-danger btn-sm btn_remove pull-right"> <i class="glyphicon glyphicon-remove-sign"></i> Remove</button>
                    <?php endif; ?>
                    </div>
				 </div>
<?php		}
		}
		else
		{
  	?>              
            
       <div class="row">    
            <div class="col-md-2"></div>          
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Net Weight  </label>
                <div class="clearfix">
                    <input type="text" name="txtweight[]" class="txtweight form-control" required>
                </div>
            </div>
                
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Price  </label>
                <div class="clearfix">
                    <input type="text" name="txtprice[]" class="txtprice form-control" required>
                </div>
            </div>
                
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Status  </label>
                <div class="clearfix">
                    <select name="cbostatus[]" class="input-sm cbostatus" >
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                      </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-2">
                <br> <button data-id="" class="btn btn-danger btn-sm btn_remove pull-right"><i class="glyphicon glyphicon-remove-sign"></i> Remove</button>
            </div>
         </div>
         
         <div class="row">    
            <div class="col-md-2"></div>          
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Net Weight  </label>
                <div class="clearfix">
                    <input type="text" name="txtweight[]" class="txtweight form-control">
                </div>
            </div>
                
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Price  </label>
                <div class="clearfix">
                    <input type="text" name="txtprice[]" class="txtprice form-control">
                </div>
            </div>
                
            <div class="col-sm-6 col-md-2">
                <label class="control-label" style="text-align:left" for="form-field-2"> Status  </label>
                <div class="clearfix">
                    <select name="cbostatus[]" class="input-sm cbostatus" >
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                      </select>
                </div>
            </div>
             <div class="col-sm-6 col-md-2">
                <br><button type="button" data-id="" class="btn btn-danger btn-sm btn_remove pull-right"> <i class="glyphicon glyphicon-remove-sign"></i> Remove</button>
            </div>
         </div>              
     
      <?php } ?>
       </div>
              
     <hr>
      
      <div class="row">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-lg btn-success">Save</button>
          <button type="reset" class="btn btn-lg btn-default">Cancel</button>
        </div>
      </div>
    
    
    <br>
    
 
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/file-upload.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>

<script>

var itemCount = <?php echo ($item !== false ? $item->number : 0) ?>;
$(document).ready(function(){
	
	
<?php if($item !== false):?> 
	$('#img_holder').show();
<?php else: ?>
	$('#fileupload').show();
	$('#img_holder').hide();
	$('[name="file"]').prop('required',true);
<?php endif; ?>
	
  $('[data-toggle="tooltip"]').tooltip();
	
	$('#btn_change').click(function(){
		$(this).parent().hide();
		$(this).parent().next().show();
	});
	
	$('#txtprice').change(function () { 
		$('#err2').empty();
		if($.isNumeric($(this).val()) == false)
		{
			$(this).val('');
			$('#err2').html('This should only be number value !');
		}
	});
	
});

$(document).on("change", '.file', function(event) { 
	var size = this.files[0].size; 
	$(this).closest('#err').empty();
	var ext = $(this).val().split('.').pop().toLowerCase();

	if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
		$(this).val('');
		$(this).parent().next().html('The file type you are trying to upload is not allowed !');
	}
	
	if(( size / 1000) > 1000)
	{
		$(this).val('');
		$(this).closest('.err').html('The uploaded file must not larger than 1MB (1000KB) !');
	}
});

$(document).on("click", '#btn_add', function(event) { 
		var _weight = '<div class="col-sm-6 col-md-2"><label class="control-label" style="text-align:left" for="form-field-2"> Net Weight  </label><div class="clearfix"><input type="text" name="txtweight[]" class="txtweight form-control"></div></div>';

		var _price = '<div class="col-sm-6 col-md-2"><label class="control-label" style="text-align:left" for="form-field-2"> Price  </label><div class="clearfix"><input type="text" name="txtprice[]" class="txtprice form-control"></div></div>';
		
		
		var _status = '<div class="col-sm-6 col-md-2"><label class="control-label" style="text-align:left" for="form-field-2"> Status  </label><div class="clearfix"><select name="cbostatus[]" class="input-sm cbostatus" ><option value="enabled">Enabled</option><option value="disabled">Disabled</option></select></div></div>';
		
		var _btn = '<div class="col-sm-6 col-md-2"><br><button type="button" data-id="" class="btn btn-danger btn-sm btn_remove pull-right"> <i class="glyphicon glyphicon-remove-sign"></i> Remove</button></div>';
		
		$('#display').append('<div class="row"><div class="col-md-2"><input type="hidden" name="hid_subID[]" value=""></div>' + _weight + _price +  _status + _btn + '</div>');
});

$(document).on("click", '.btn_remove', function(event) { 
	var id = $(this).data('id');
	var ele = $(this);
	if(id == '')
	{
		$(this).parent().parent().remove();
	}
	else
	{
		bootbox.confirm("Are you sure you want to delete this sub item ?", function(result) {
			if(result == true)
			{				
				$.ajax({
					url: '<?php echo base_url() ?>admin/product/remove_sub_item',  
					type: 'POST',
					data: {'item_id' : id}
				})
				.done(function( response) {			
					data = JSON.parse(response);			
					$('#msg').html(data.msg);
					ele.parent().parent().remove();
					itemCount--;
					if(itemCount == 1)
					{
						$('.btn_remove').remove();
					}
					
				})
				.fail(function() {
					$('#msg').html('<div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-remove"></i>There is an internal server error occured while deleting the sub item. Please refresh the page and try again. Sorry for your inconvenience.</b></div>');
				});
			}
		});
	}
});

</script>
</form>
</div>   