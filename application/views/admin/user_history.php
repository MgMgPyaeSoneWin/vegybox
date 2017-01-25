<?php include('header.php'); ?>		

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">User History <a href="<?php echo base_url() ?>index.php/orders/customer_manage" class="btn btn-default pull-right">Back to List</a></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
           
        
            <!-- /.row -->
            
            
             <div class="row">
             	<div class="col-lg-12">
                    <div class="well">
                        <div class="row">			
                        	<form class="form-horizontal">
                                <div class="col-xs-12 col-sm-5">
                                    <label class="col-xs-5 col-sm-4 control-label"> Type of Box : </label>
            
                                    <div class="col-xs-12 col-sm-8">
                                        <select class="form-control">
                                            <option>All</option>
                                            <option>Normal Box</option>
                                            <option>Salad Box</option>
                                            <option>Vegetable Box</option>
                                        </select>
                                    </div>
                                </div>
                                        
                                <div class="col-xs-12 col-sm-5">
                                    <label class="col-xs-5 col-sm-5 control-label"> Day of Delivery : </label>
            
                                    <div class="col-xs-12 col-sm-7">
                                        <div class="input-group">
                                            <input class="datepicker form-control" name="date" id="date" type="text" data-date-format="dd-mm-yyyy">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12 col-sm-2">
                                	<button class="btn btn-primary">Filter</button>
                                </div> 
                             </form>                           
                         </div>
                    </div>
                    <div class="panel panel-info filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">John S. Schwab</h3>
                            <div class="pull-right">
                               <!-- <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>-->
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr class="filters">
                                    <th><input type="text" class="form-control" placeholder="Type" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Date / Day" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Additional Items" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="Remarks" disabled></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   
                                    <td>Normal Box <small>&times;1</small></td>
                                    <td>07/09/2014 (Sunday)</td>
                                    <td>Wild Honey <small>&times;2</small></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                   
                                    <td>Salad Box <small>&times;2</small></td>
                                    <td>02/09/2014 / (Tuesday)</td>
                                    <td>-</td>
                                    <td>No carrot</td>
                                </tr>
                                <tr>
                                   
                                    <td>Vegetable Box <small>&times;1</small> </td>
                                    <td>06/09/2014 (Sunday)</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                 </div>
             </div>
            <!-- /.row -->           
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    


    <!-- jQuery Version 1.11.0 -->
    <script src="<?php echo base_url() ?>assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    
    <script src="<?= base_url()?>assets/js/bootstrap-datepicker.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>

	<script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/sb-admin-2.js"></script>
	<style>
		.filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}


	</style>

</body>

</html>
<script>
$('.datepicker').datepicker();
</script>