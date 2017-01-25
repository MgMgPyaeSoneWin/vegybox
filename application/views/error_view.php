<?php include('common/header.php'); ?>
	<div class="row" style="margin:0 auto; padding:15px;">
        <h1 class="heading-red" style="margin-top:0px !important; font-size:200%!important">
           <?php echo (isset($title) && $title != '' ? $title : ''); ?>
        </h1> 
    	<hr style="margin:15px 0px;">
        <h5> <?php echo (isset($msg) && $msg != '' ? $msg : ''); ?> </h5>
     </div>

<?php include('common/footer.php'); ?>