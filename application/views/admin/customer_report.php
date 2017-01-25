<?php include('header.php'); ?>		

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Customers Report</h1>
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
                            <div id="customer-grid"></div>
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
    

    

</body>

</html>
<script>
Morris.Area({
        element: 'customer-grid',
        data: [{
            period: '2014-03',
            customer: 15
        }, {
            period: '2014-04',
            customer: 25
        }, {
            period: '2014-05',
            customer: 30
        }, {
            period: '2014-06',
            customer: 35
        }, {
            period: '2014-07',
            customer: 28
        }, {
            period: '2014-08',
            customer: 40
        /* }, {
            period: '2011 Q3',
            normal: 4820,
            ipad: 3795,
            itouch: 1588
        }, {
            period: '2011 Q4',
            normal: 15073,
            ipad: 5967,
            itouch: 5175
        }, {
            period: '2012 Q1',
            normal: 10687,
            ipad: 4460,
            itouch: 2028
       }, {
            period: '2012 Q2',
            normal: 8432,
            ipad: 5713,
            itouch: 1791*/
        }],
        xkey: 'period',
        ykeys: ['customer'],
        labels: ['Customer'],
        pointSize: 2,
        hideHover: 'auto',
		smooth: false,
        resize: true
    });
</script>
