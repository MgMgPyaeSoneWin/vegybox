<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> FAQs    
            <a href="<?php echo base_url() ?>admin/system/faq_entry" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add New</a>
            </h1>
            <select class="pull-right" onchange="javascript:window.location.href='<?php echo base_url(); ?>LanguageSwitcher/switchLang/'+this.value;">
					<option value="english" <?php if($this->session->userdata('site_lang') == 'english') echo 'selected="selected"'; ?>>English</option>
					<option value="unicode" <?php if($this->session->userdata('site_lang') == 'unicode') echo 'selected="selected"'; ?>>unicode</option>
					<option value="zawgyi" <?php if($this->session->userdata('site_lang') == 'zawgyi') echo 'selected="selected"'; ?>>zawgyi</option>
			</select>
            
        </div>
        <hr>
    </div>
    <!-- /.row -->
    
    
        <div id="err_msg"></div>
        <?php
            if($this->session->flashdata('error_msg') != NULL)
            {
                echo $this->session->flashdata('error_msg');
            }
        ?>
       <div class="row">
		 <?php
            if(isset($list) && $list !== false):
				$count=0;
                foreach($list as $row):
        ?>
        		<div class="col-sm-6">
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h3 class="panel-title"><?= $row->question ?>
                        <small>
                        <?php 
							if ($row->status == 'enabled')
								echo '<label class="label label-success">Enabled</label>';
							else
								echo '<label class="label label-danger">Disabled</label>';
						?>
                        </small>
                        </h3>
                      </div>
                      <div class="panel-body">
                        <?= $row->answer ?> 
                      </div>
                      <div class="panel-footer" style="min-height:34px;padding:0px;">
                         <a onclick="delete_faq(<?= $row->faq_id?>)"  class="btn btn-link pull-right"><i class="glyphicon glyphicon-remove"></i> Delete</a>
                         <a href="<?php echo base_url() ?>admin/system/faq_entry/<?= $row->faq_id?>"  class="btn btn-link pull-right"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                      </div>
                    </div>
                </div>
        <?php 	$count++;
				if($count == 2)
				{
					echo '</div><div class="row">';
					$count = 0;
				}
                endforeach;
            endif;
        ?>

    </div>
    <div class="row">
    	<?php echo $pagination; ?>
    </div>
</div>    
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>
<script>
function delete_faq(id)
{
	bootbox.confirm('Are you sure you want to delete this record?', function(result) {
		if(result == true)
		{ 
			window.location.href = '<?php echo base_url() ?>admin/system/delete_faq/'+id;
		}
	
	});
}
</script>