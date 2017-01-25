<?php include('common/header.php'); ?>      

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Orders Summary</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                 <div class="col-xs-12">
                    <div class=" well">
                        <form class="form-horizontal" method="post" action="<?php echo base_url().'admin/summarize/summarize_order'; ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-7">
                                    <label class="col-xs-5 col-sm-5 control-label no-padding-right" for="form-field-1"> Summarized Orders of : </label>                                 
                                    <div class="col-xs-12 col-sm-7">
                                    <div class="input-group date" data-provide="datepicker">
                                        <input value="<?php echo $day; ?>" name="date" class="datepicker form-control" data-date-format="yyyy-mm-dd">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>                                        
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Get Summarize Order" />
                            </div>
                        </form>
                    </div>
                 </div>                 
            </div>

            <!-- SY : 22/8/2016 -->
            <?php $b_total = 0; $i_total = 0; ?>
            <div class="col-md-9"> </div>

            <?php if(isset($total['data']) && $total['data'] != false) {  ?>
                 <div class="col-md-12"><a target="_blank" href="<?php echo base_url() ?>admin/summarize/print_summarizeorder/<?php echo $day; ?>" class="btn btn-success pull-right"><i class="glyphicon glyphicon-download"></i> Download</a></div>
            <?php } else { ?>
                <div  class="col-md-12"><a target="_blank" href="<?php echo base_url() ?>admin/summarize/print_summarizeorder/<?php echo $day; ?>" class="btn btn-success pull-right" disabled><i class="glyphicon glyphicon-download"></i> Download</a></div>
             <?php } ?>



             <br/><br/>
            <div class="row">
                 <div class="col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title"><?php echo $day.$longday ?></h3>
                        </div>          
                        <div class="panel-body"> 
                        <table class="table table-user-information">                             
                            <tbody id="tb_summarize">
                            <?php 
                             if(isset($total['data']) && $total['data'] != false) {
                              foreach($total['data'] as $tot) {
                               if($tot->name != NULL) { 
                                $b_total += $tot->b_total;
                                $i_total += $tot->i_total;
                            ?>
                            <tr>
                                <td>
                                <?php 
                                    $detail = array();
                                    $i = 0;
                                    if(isset($details) && $details != false) {
                                         foreach($details as $det){
                                            if($det->name == $tot->name)
                                            {
                                                $detail[$i] = array( 'key' => $det->info , 'value' => $det->total, 'ref' => $det->orders );
                                                $i++;
                                            }
                                         }
                                    }
                                ?>
                                    
                                    <div style="width:100%;float:left;">
                                        <div style="width:50%;float:left;">
                                         <h4 style="margin-top:5px;"><?php echo $tot->name; ?></h4>
                                        </div>
                                        <div style="width:50%;float:right;text-align:right;">
                                         <h4 style="margin-top:5px;"><?php echo "&times; ".$tot->total; ?></h4>
                                        </div>
                                    </div>
                                    <div style="width:100%;float:left;">
                                    <?php if(isset($detail) && (! empty($detail)) ) {?>
                                     <ul>                                       
                                        <?php foreach($detail as $det){ 
                                                if($det['key'] == 'Default') { ?>
                                            <li><b> Default </b> <span class='pull-right'>&times; <?php echo $det['value'] . '</span><br>(' .$det['ref'] . ')<br>'; ?></li>
                                        <?php } } ?>
                                        <?php foreach($detail as $det){ 
                                                if($det['key'] != 'Default') 
                                                { 
                                                    echo "<li><b>".$det['key']. "</b> <span class='pull-right'>&times; ".$det['value']."</span><br>(" . $det['ref'] .")</li>"; 
                                                } 
                                            } ?>                                      
                                    </ul>
                                    <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                              } 
                                   echo '<h4 class="text-center">Total Orders : <span class="label label-warning">'.$total['count'].'</span> &nbsp; | &nbsp;Total Boxes : <span class="label label-warning">'.  $b_total .'</span>Total Items : <span class="label label-warning">'.$i_total.'</span> </h4>';
                            }
                            else 
                            {   
                                echo 'No order for this day.';
                            } 
                            ?>
                            </tbody>
                        </table>                             
                        </div>
                     </div>
                 </div>
            </div>
            <!-- /.row -->
            
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
    <link href="<?php echo base_url() ?>assets/css/datepicker.css" rel="stylesheet">
    <script src="<?= base_url()?>assets/js/bootstrap-datepicker.min.js"></script>
<script>
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    /*startDate: '-30d',*/
    showOnFocus : true
})
</script>
</body>

</html>