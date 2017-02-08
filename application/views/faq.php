<?php include('common/header.php'); ?>

<div class="row" style="margin:0 auto;">
	 <h2 class="heading-green" style="margin-top:0px !important;"><?=$this->lang->line('faq');?></h2> 
     <hr style="margin:15px 0px;">
     <p><?=$this->lang->line('serviceFAQ');?></p>
     <div class="row" style="margin:0 auto;">
        <div class="notice notice-success">
            <strong><?=$this->lang->line('Notice');?></strong><?=$this->lang->line('userManual');?><a href="<?php echo base_url() ?>assets/FrescoVegyBoxUserManual.pdf" download="FrescoVegyBoxUserManual.pdf" target="_blank" ><?=$this->lang->line('English');?></a> | <a href="<?php echo base_url() ?>assets/FrescoUserManualZawgyi.pdf" download="FrescoUserManualZawgyi.pdf" target="_blank" ><?=$this->lang->line('Myanmar');?></a>.
        </div>
    </div>
     	<div class="faq-panel-group" id="accordion1">
        
        <?php
			if(isset($list) && $list !== false):
				foreach($list as $row):
		?>
        
        <div class="faq-panel faq-panel-default">
          <div class="faq-panel-heading">
            <h5 class="faq-panel-title">
              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#accordion1_<?= $row->faq_id ?>"><?= $row->question ?></a>
            </h5>
          </div>
          <div id="accordion1_<?= $row->faq_id ?>" class="faq-panel-collapse collapse">
            <div class="faq-panel-body">
               <?= $row->answer ?>
            </div>
          </div>
        </div>
        
        <?php 	endforeach;
			endif;
			
			//echo '<hr style="margin:15px 0px;">';
			echo $pagination;
		?>
        
        </div>
     
     
</div>

<?php include('common/footer.php'); ?>

<script>

$('.title4').attr('style','padding:30px;');	

$('.accordion-toggle:first').click();

</script>