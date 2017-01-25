<?php include('common/header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"> Additional Items
            <a href="<?php echo base_url() ?>admin/product/item_entry" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Add New</a>
            </h1>
        </div>
        <hr>
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
         	<table class="table table-bordered"> 
            	<tr>
                	<th>No.</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Net Weight &rArr; Price </th>
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
                        <td><?= $row->type ?></td>
                        <td>
                   <?php			
						if($row->number > 1)
						{												
							$amt = explode(",", $row->net_weight);
							$price = explode(",", $row->price);
							$ids = explode(",", $row->ids);
							$status = explode(",", $row->status);
						}
						else
						{
							$amt = $row->net_weight;
							$price = $row->price;
							$ids = $row->ids;
							$status = $row->status;
						}
						echo '<ul>';
						for($x=0;$x < count($ids); $x++)
						{
							 echo '<li>'. ($row->number > 1 ? $amt[$x] : $amt) . ' &rArr; ' . number_format( ($row->number > 1 ? $price[$x] : $price)) . ' ks &nbsp;'.($status[$x] == 'enabled' || $status == 'enabled'  ? '<small><label class="label label-success">Enabled</label></small>': '<small><label class="label label-danger">Disabled</label></small>').'</li>'; 
						}
						echo '</ul>';
					?>
                    	</td>
                        <td class="text-center">
                        	<a href="<?php echo base_url() ?>admin/product/item_entry/<?= $row->item_id?>" class="btn btn-warning btn-catchy-warning">Edit </a>
                            <a href="#" onclick="delete_item('<?= str_replace(',','-',$row->ids) ?>')" class="btn btn-danger btn-catchy-danger">Delete </a>
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
function delete_item(id)
{ 
	bootbox.confirm('Are you sure you want to delete this item?', function(result) {
		if(result == true)
		{ 
			window.location.href = '<?php echo base_url() ?>admin/product/delete_item/'+id;
		}
	
	});
}
</script>