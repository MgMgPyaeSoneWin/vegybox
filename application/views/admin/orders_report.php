<?php include('header.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Orders Report</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Half Year Report of Vege Box Orders
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">This Month Report</a>
                                        </li>
                                        <li><a href="#">Monthly Report</a>
                                        </li>
                                        <li><a href="#">Yearly Report</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="test"></div>
                        </div>
                        <!-- /.panel-body -->
                     </div>
                </div>      
            </div>
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


    <link href="<?php echo base_url() ?>assets/css/plugins/morris.css" rel="stylesheet">

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>

	<script src="<?php echo base_url() ?>assets/js/jquery-ui-1.10.3.custom.min.js"></script>
   <!-- Morris Charts JavaScript -->
    <script src="<?php echo base_url() ?>assets/js/plugins/morris/raphael.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/morris/morris.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/morris/morris-data.js"></script>

    

</body>

</html>
<script>
Morris.Bar({
        element: 'test',
        data: [{
            y: '2014-03',
            a: 100,
            b: 90,
			c : 110
        }, {
            y: '2014-04',
            a: 75,
            b: 65,
			c : 80
        }, {
            y: '2014-05',
            a: 150,
            b: 90,
			c : 100
        }, {
            y: '2014-06',
            a: 75,
            b: 65,
			c : 110
        }, {
            y: '2014-07',
            a: 130,
            b: 120,
			c : 50
        }, {
            y: '2014-08',
            a: 75,
            b: 65,
			c : 110
        
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
		barColors: ['#e3001d', '#fdff00', '#4da74d'],
        labels: ['Normal Box', 'Salad Box', 'Vegetable Box'],
        hideHover: 'auto',
        resize: true
    });
</script>
