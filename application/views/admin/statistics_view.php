<?php include('common/header.php'); ?>	
<script src="<?php echo base_url() ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
<div id="page-wrapper">
<div class="row">
<div class="col-lg-12">
    <h1 class="page-header">Statistics</h1>
</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->  
<div class="row">
<div class="col-md-12">
	<label>Statistics for Income by Month</label>
</div>
<div class="col-lg-12">
	<div id ='chartincome'></div>
</div>
</div>
<hr />
<div class="row">
<div class="col-md-12">
	<label>Statistics for Delivered Order by Month</label>
</div>
<div class="col-lg-12">
	<div id ='chartorder'></div>
</div>
</div>
<script src="<?php echo base_url() ?>assets/zingchart/zingchart.min.js"></script>

<!--Chart Placement[2]-->
<div id ='chartDiv'></div>
<script>
var month_label = [];
var in_value = [];
<?php if(isset($income) && $income != false)
	foreach($income as $in)
	{ ?>
	month_label.push('<?php echo $in->MONTH; ?>');
	in_value.push(<?php echo $in->income; ?>);
<?php	}
?>
  var chartData={
    "type":"line",  // Specify your chart type here.
    "scale-x": {
	    "label":{ /* Add a scale title with a label object. */
	      "text":"Month",
	    },
	    /* Add your scale labels with a labels array. */
	    "labels":month_label
	  },
    "scale-y": {
	    "label":{ /* Add a scale title with a label object. */
	      "text":"Income",
	    }},
    "series": [ 
    {"values":in_value}
  ] 
  };
  zingchart.render({ // Render Method[3]
    id:'chartincome',
    data:chartData,
    height:400,
    width:800
  });
  var month_label1 = [];
var in_value1 = [];
<?php if(isset($dorder) && $dorder!= false)
	foreach($dorder as $del)
	{ ?>
	month_label1.push('<?php echo $del->MONTH; ?>');
	in_value1.push(<?php echo $del->dorder; ?>);
<?php	}
?>
  
  var chartData1={
    "type":"line",  // Specify your chart type here.
    "scale-x": {
	    "label":{ /* Add a scale title with a label object. */
	      "text":"Month",
	    },
	    /* Add your scale labels with a labels array. */
	    "labels":month_label1
	  },
    "scale-y": {
	    "label":{ /* Add a scale title with a label object. */
	      "text":"Orders",
	    }},
    "series": [ 
    {"values":in_value1}
  ] 
  };
  zingchart.render({ // Render Method[3]
    id:'chartorder',
    data:chartData1,
    height:400,
    width:800
  });
</script>


</div>