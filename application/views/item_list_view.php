<div  class="scroll">
	<div id="item_list" >
    <div class="row" style="margin:0">

        <?php
            if(isset($items) && $items !== false)
            { 
                $box_count = 1; // col count => for row break after every 2 col                
                $count = 0;
                foreach ($items as $row)
                {
                    
					$count++;					
					if($count <= 6){
						$box_count++;
        ?>
        <div class="col-xs-12 col-md-4 col-sm-6" style="padding:0px;">
            <div class="panel panel-success" style="margin:5px;">
                <div class="panel-heading">
                    <b><?php echo $row->name . ($row->type !== null ? ' ( '.$row->type . ' )' : '');  ?></b>
                    <br><small class="text-muted"><?php echo ($row->description !== null ? $row->description : '&nbsp;'); ?></small>
                </div>
                <div class="panel-body" style="padding:5px;">
                    <div class="col-sm-6" style="float:left;padding:0px 5px;">
                        <img src="<?php echo base_url() ?>assets/img/<?php echo $row->image;  ?>" alt="<?php echo $row->name ?>" style="width:100%"/>
                    </div>	
                <?php			
    
                    if($row->number > 1)
                    {												
                        $amt = explode(",", $row->net_weight);
                        $price = explode(",", $row->price);
                        $ids = explode(",", $row->ids);
                    }
                    else
                    {
                        $amt = $row->net_weight;
                        $price = $row->price;
                        $ids = $row->ids;
                    }
                        for($i=0;$i < count($ids); $i++)
                        {
                ?>
                            <div class="col-sm-6" style="padding:0px 5px;margin-top:10px;">                                            		
                                <span style="font-weight:bold"> 
                                    <?php echo ($row->number > 1 ? $amt[$i] : $amt) . ' &rArr; <label class="badge">' . number_format( ($row->number > 1 ? $price[$i] : $price)) . ' ks </label>'; ?>
                                </span>
                                <br />
                                <input type="hidden" name="hid_itemID[]" value="<?php echo ($row->number > 1 ? $ids[$i] : $ids ) ?>">
                                <input type="number" name="txt_itemQty[]" class="input-sm items" min="0" value="0" style="width:90%;float:left;" data-name="<?php echo $row->name ?>" data-weight="<?php echo ($row->number > 1 ? $amt[$i] : $amt) ?>" data-price="<?php echo ($row->number > 1 ? $price[$i] : $price);?>" data-type="<?php echo ($row->type !== null ? $row->type : '&nbsp;'); ?>">
                            </div>
                <?php
            
                        }
                    
                ?>
    
                </div>
             </div>
        </div>
        <?php		   
						if($box_count > 3)
						{
							$box_count = 1; // rest col count to 0 
							echo '</div><div class="row" style="margin:0">';
						}
					}					
					
                } // end foreach              
                
            } // end if isset
        ?>
    </div>
    </div>
    <?php 
	if($count < $row_count)
	{
		echo '<div class="row" style="margin:0;padding: 5px;"><button class="btn btn-block btn-default" type="button" id="btn_more">Show More</button></div>';
		echo '<div class="row" style="margin:0;padding: 5px;"><button class="btn btn-block btn-default" type="button" id="btn_less" style=" display:none;">Show Less</button></div>';
	}
	?>
</div>

<script>
$(document).ready(function(){
	var order_id = $('#hid_order_id').val();
	// Edit
	if(order_id !== '')
	{
		<?php  if(isset($ordered_items) && $ordered_items !== false){														
					foreach($ordered_items as $oi){	 ?>
					
						$( ".items" ).each(function() { 
						   if( $(this).prev().val() == <?php  echo $oi->item_id ?> )
						   {
							    $(this).val(<?php  echo $oi->item_qty ?>);
						   }
						});
		<?php 		}
				}
		?>
	}
	
	var c = 0;
	var count = 0;
	var row_count = <?php echo $row_count ?>;
	$('#btn_more').click(function(){
		
		var ele = $(this);
		
		// show loading icon
		$('#item_list').append('<div id="loading"><center><img src="<?php echo base_url() ?>assets/img/ajax-loader-circle.gif"></center></div>');		
		count += 6;
        c = 0;
		console.log('count :' + count + " | " + row_count);
		if((count+6) >= row_count)
		{
			ele.hide();		
			$('#btn_less').show();
		}
		$.post('<?php echo base_url() ?>order/ajax_item_list',	{ offset : count },
		function(data){ 
			if(data != 'false')
			{			
				
				data = JSON.parse(data); 
				//$('#item_list').empty(); 
				var row1 = $('<div class="row" style="margin:0 auto;"></div>');
				var row2 = $('<div class="row" style="margin:0 auto;"></div>');
				for(var i in data)
				{
					c++;
			
					if(data[i].number > 1)
					{												
						amt = data[i].net_weight.split(','); 
						price = data[i].price.split(','); 
						ids =  data[i].ids.split(',');
					}
					else
					{
						amt = data[i].net_weight;
						price = data[i].price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
						ids = data[i].ids;
					}
					
					
					var desc = data[i].description;					
					var type = data[i].type;
					
					
					if(type !== null)
						type = ' (' + type + ')';
					else
						type = '';
					
					if(desc == null)
						desc = '&nbsp;';
					
					
					
					var box = $('<div class="col-xs-12 col-md-4 col-sm-6" style="padding:0px;"></div>');
					var heading = $('<div class="panel-heading"></div>').append( '<b>' + data[i].name + type + '</b><br><small class="text-muted">'+ desc +'</small>');				
					
					var left = $('<div class="col-sm-6" style="float:left;padding:0px 5px;"><img src="<?php echo base_url() ?>assets/img/'+ data[i].image +'" alt="'+ data[i].name +'" style="width:100%"/></div>');
					
					var right = $('<div class="col-sm-6" style="padding:0px 5px;margin-top:10px;"></div>');
					
					for(var x=0; x < data[i].number; x++)
					{					
						right.append('<span style="font-weight:bold">'+(data[i].number > 1 ? amt[x] : amt) + ' &rArr; <label class="badge">' + (data[i].number > 1 ? price[x].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : data[i].price) + ' ks </label></span><br /><input type="hidden" name="hid_itemID[]" value="'+(data[i].number > 1 ? ids[x] : data[i].ids )+'"><input type="number" name="txt_itemQty[]" class="input-sm items" min="0" value="0" style="width:90%;float:left;" data-name="' + data[i].name + '" data-weight="'+(data[i].number > 1 ? amt[x] : amt) + '" data-price="' + (data[i].number > 1 ? price[x] : data[i].price) + '" data-type="'+ type +'">');
						
						
					}
					
					var p_body = $('<div class="panel-body" style="padding:5px;"></div>').append(left, right);
					var panel = $('<div class="panel panel-success" style="margin:5px;"></div>').append(heading, p_body);
					box.append(panel);	
					
					if(c > 3)
    				{ 
						c = 0;
						row1.append('</div><div class="row" style="margin:0 auto;">');
						row1.append(box);
					}
					else								
					{ 
						row1.append(box);
					}
					
					
					// remove loading icon
					$('#loading').remove();
					
					$('#item_list').append(row1);
					//$('#item_list').append(row2);
					
				   // Edit
					if(order_id !== '')
					{
						<?php  if(isset($ordered_items) && $ordered_items !== false){														
									foreach($ordered_items as $oi){	 ?>
									
										$( ".items" ).each(function() { 
										   if( $(this).prev().val() == <?php  echo $oi->item_id ?> )
										   {
												$(this).val(<?php  echo $oi->item_qty ?>);
										   }
										});
						<?php 		}
								}
						?>
					}
				}
				//$('html,body').animate({scrollTop: $("#item_list").offset().top},	'fast');
			}
			else
			{
				$('#item_list').append('<h3 class="text-danger text-center">Error in loading items! Please refresh the page and try again !</h3>');
			}
		});	
		
		
	});
	
	
	$('#btn_less').click(function(){
		count -= 6;		
		$(this).hide();
		$('#btn_more').show();
		
		$('#item_list').children().last().remove();
		//$('#item_list').children().last().remove();

	});
});

</script>