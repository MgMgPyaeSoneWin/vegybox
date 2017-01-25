<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> Vegy Boxes
            <a href="<?php echo base_url() ?>admin/product/edit_box" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add New</a>
            </h1>
        </div>
        <hr>
    </div>
    <!-- /.row -->
    
    <div class="row">
         <div class="col-xs-12"> 
         	<div id="err_msg"></div>
         	<?php
				if($this->session->flashdata('error_msg') != NULL)
				{
					echo $this->session->flashdata('error_msg');
				}
			?>
         	<table class="table table-bordered"> 
            	<tr>
                	<th>No.</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                <tbody>
                 <?php
					if(isset($list) && $list !== false):
						$currentpage = ($this->uri->segment(4) != false) ? $this->uri->segment(4) : 0 ;
						$itemsperpage = 10;
						$offset = $currentpage;// $itemsperpage * ($currentpage - 1);
						$i = 1;
						foreach($list as $row):
				?>
                	<tr>
                    	<td><?php echo $offset+$i; ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= number_format($row->price,0) ?> Ks.</td>
                        <td>
						<?php 
							if ($row->status == 'enabled')
								echo '<label class="label label-success">Enabled</label>';
							else
								echo '<label class="label label-danger">Disabled</label>';
						?>
                        </td>
                        <td class="text-center">
                        	<a href="<?php echo base_url() ?>admin/product/edit_box/<?= $row->box_id?>" class="btn btn-warning btn-catchy-warning">Edit </a>
                            <a href="#" onclick="delete_box(<?= $row->box_id?>)" class="btn btn-danger btn-catchy-danger">Delete </a>
                       </td>
                    </tr>
                <?php 	$i++;
						endforeach;
					endif;
				?>
                </tbody>
            </table>
         </div>
    </div>
    <div class="row">
    	<?php echo $pagination; ?>
    </div>
</div>    
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/bootbox.min.js"></script>
<script>
function delete_box(id)
{
	bootbox.confirm('Are you sure you want to delete this box?', function(result) {
		if(result == true)
		{ 
			window.location.href = '<?php echo base_url() ?>admin/product/delete_box/'+id;
		}
	
	});
}
</script>