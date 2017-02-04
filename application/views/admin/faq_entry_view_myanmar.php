<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> FAQs</h1>
        </div>
        <hr>
        
        <div class="col-lg-12">
            <ol class="breadcrumb">
              <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
              <li><a href="<?php echo base_url() ?>admin/system/faq_list">FAQs</a></li>
              <li class="active">Edit FAQ</li>
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
         	<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url() ?>admin/system/insert_faq_lang">
              <input type="hidden" name="hidID" id="hidID" value="<?php echo ($faq !== false ? $faq->faq_id : '') ?>">
              <div class="form-group">
                <label for="inputEmail3" class="col-xs-12 col-sm-2 control-label">Question</label>
                <div class="col-xs-12 col-sm-8">
                  <input type="text" class="form-control" name="txtquestion" id="txtquestion" placeholder="Question....." value="<?php echo ($faq !== false ? $faq->question : '') ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Answer</label>
                <div class="col-xs-12 col-sm-8">
                  <textarea class="form-control" name="txtanswer" rows="10" placeholder="Answer..." required><?php echo ($faq !== false ? $faq->answer : '') ?></textarea>
                </div>
              </div>
              
               <div class="form-group">
                <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Status</label>
                <div class="col-xs-12 col-sm-8">
                  <select name="cbostatus" id="cbostatus" class="input-sm" >
                  	<option value="enabled" <?php echo ($faq !== false && $faq->status == 'enabled'  ? 'selected="selected"' : '') ?>>Enabled</option>
					<option value="disabled" <?php echo ($faq !== false && $faq->status == 'disabled'  ? 'selected="selected"' : '') ?>>Disabled</option>
                  </select>
                </div>
              </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-xs-12 col-sm-2 control-label">Input Type</label>
                    <div class="col-xs-12 col-sm-8">
                        <select name="cbolang" id="cbolang" class="input-sm" >
                            <option value="english" >English</option>
                            <option value="unicode" >Unicode</option>
                            <option value="zawgyi" >Zawgyi</option>
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

});
</script>