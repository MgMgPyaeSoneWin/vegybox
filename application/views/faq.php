<?php include('common/header.php'); ?>

<div class="row" style="margin:0 auto;">
	 <h2 class="heading-green" style="margin-top:0px !important;">FAQs of Vege Box delivery service </h2> 
     <hr style="margin:15px 0px;">
     <p> &nbsp; &nbsp; &nbsp; At Fresco, we have a “Vegy Box” service to easily get to you the best or our products. We source only the highest-quality and freshest vegetables and herbs from our farms because "Fresh" is not only our name, but also our promise! We deliver a broad range of assorted vegetables, lettuces and herbs that we grow in our farms in Shan State.</p>
     <div class="row" style="margin:0 auto;">
        <div class="notice notice-success">
            <strong>Notice : </strong> Click on the link beside to download the user manual: <a href="<?php echo base_url() ?>assets/FrescoVegyBoxUserManual.pdf" download="FrescoVegyBoxUserManual.pdf" target="_blank" >English</a> | <a href="<?php echo base_url() ?>assets/FrescoUserManualZawgyi.pdf" download="FrescoUserManualZawgyi.pdf" target="_blank" >Myanmar</a>.
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