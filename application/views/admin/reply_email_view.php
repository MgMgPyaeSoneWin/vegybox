<?php include('common/header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Order Cancelling Response</h1>
                </div>
                <hr>
                
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                      <li><a href="<?php echo base_url() ?>admin/dashboard/main">Dashboard</a></li>
                      <li><a href="<?php echo base_url() ?>admin/customer/mange_customer">Customer Management</a></li>
                      <li class="active">Email Reply For Order Cancelling</li>
                    </ol>
                </div>

                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->  
            
            <form class="form-horizontal" method="post" action="<?php echo base_url() ?>admin/customer/send_email">
            <div class="row" style="margin:0 auto">
            	<div class="well">
				<?php
					if($this->session->flashdata('error_msg') != NULL)
					{
						echo $this->session->flashdata('error_msg');
					}
				?>
                	<div class="row">		
                  		<div class="col-lg-2">
							<label class="pull-right">Email :</label>
                    	</div>
						<div class="col-lg-10">
							<div class="col-lg-8">
								<input type="text" class="form-control box" value="<?php echo $email; ?>" disabled="disabled" / >
							 </div>
                    	</div>
                    </div>
					<hr />
					<div class="row">		
                  		<div class="col-lg-2">
							<label class="pull-right">Stop Message :</label>
                    	</div>
						<div class="col-lg-10">
							<div class="col-lg-8">
								<input type="text" class="form-control box" name="stop_msg" id="stop_msg" value="<?php echo $order_msg; ?>" disabled="disabled" / >
							 </div>
                    	</div>
                    </div>
					<hr />
					<div class="row">		
                  		<div class="col-lg-2">
							<label class="pull-right">Message :</label>
                    	</div>
						<div class="col-lg-10">
							<div class="col-lg-12">
								<textarea class="form-control" name="email_msg" id="email_msg" placeholder="Enter the text that you want to send." rows="15"></textarea>								
								
								<input type="hidden" value="<?php echo $email; ?>" id="email" name="email"/>
								<input type="hidden" value="<?php echo $id; ?>" id="userid" name="userid"/>
							 </div> 
                    	</div>
                    </div>
					<hr />
					<div class="row">		
                  		<div class="col-lg-2">
							<label class="pull-right"></label>
                    	</div>
						<div class="col-lg-10">
							<div class="col-lg-8">
								<button type="submit" class="btn btn-primary "><i class="glyphicon glyphicon-send"></i>  Send Email</button>
							 </div>
                    	</div>
                    </div>
                </div>
            </div>
            </form>
                      
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <script src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
   <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="<?= base_url()?>assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
	CKEDITOR.replace( 'email_msg',
	 {
		 filebrowserBrowseUrl : '/assets/ckfinder/ckfinder.html',
		 filebrowserImageBrowseUrl : '/assets/ckfinder/ckfinder.html?type=Images',
		 filebrowserFlashBrowseUrl : '/assets/ckfinder/ckfinder.html?type=Flash',
		 filebrowserUploadUrl : '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		 filebrowserImageUploadUrl : '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		 filebrowserFlashUploadUrl : '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	 } 
	 );
</script>
</body>

</html>