<?php include('common/header.php'); ?>
<style>
.rt-block { padding : 0px !important; }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6EcljSTs97GBfNXzEk6Esq1O8G1iFQc0&sensor=false"></script>
<form class="form-horizontal" id="order_form" role="form" method="post">
	<input type="hidden" name="hid_order_id" id="hid_order_id" value="<?= $orderDetails->order_id  ?>">
<div class="row" style="margin:0 auto;">
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="<?=$this->lang->line('vegeBoxDetails');?>">
                            <span class="round-tab">
                                <i class="icon-box"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="<?=$this->lang->line('additionalItems');?>">
                            <span class="round-tab">
                                <i class="icon-list-add"></i>
                            </span>
                        </a>
                    </li>
                     <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="<?=$this->lang->line('deliveryDetails');?>">
                            <span class="round-tab">
                                <i class="icon-truck"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="<?=$this->lang->line('orderConfirmation');?>">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
			
           	
                <div class="tab-content">
            		<!-- Vegebox Details -->                
                    <div class="tab-pane active" role="tabpanel" id="step1">
                       
                        <h2 class="heading-green"><?=$this->lang->line('vegyBoxDetails');?></h2>  
                       <p><?=$this->lang->line('subscribeAtLeast');?></p>
                       <p><?=$this->lang->line('subscriber');?></p>

                        <hr style="margin:5px 0px;" />
                        
                        <div class="col-md-12"> 
                        	<div id="msg"></div>
                        	<?php 	 
								if($orderDetails->subscription == 'No') {
							
							?>
                            <div class="form-group" id="div_subscription">
                                <label class="col-sm-3 control-label"><?=$this->lang->line('Subscription');?></label>
                                
                                <div class="col-sm-8" style="padding-top:0px;">
                                   <label class="radio-inline">
                                      <input type="radio" name="rdo_subscription" id="rdo_yes" value="YES"><?=$this->lang->line('yes');?> 
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="rdo_subscription" id="rdo_no" value="NO" checked><?=$this->lang->line('no');?> 
                                    </label>
                                </div>
                            </div>
                            <?php 
								} 
								
							
							?>  
       
                             <input type="hidden" name="hid_box_count" id="hid_box_count">         
                            <div class="form-group" id="div_boxes" style="display:none;">
                                <label class="col-sm-3 control-label"><?=$this->lang->line('typeOfBoxes');?></label>                    			
                                
                                <div class="col-sm-9">
                                	<div class="row" style="margin: 0 auto">
                                    <?php 
                                        if(isset($boxes) && $boxes !== false){
                                            foreach($boxes as $b){								
                                    ?>		
                                    <div class="col-sm-3" style="padding-bottom:15px;">
                                        <img class="rt-noborder img-responsive" src="<?php echo base_url() . "assets/". $b->image?>" alt="image" width="90%" >
                                        <div class="checkbox">
                                            <label>
                                              <input class="box" name="hid_box_id[]" type="checkbox" value="<?php echo $b->box_id; ?>" data-name="<?php echo $b->name; ?>"> <b><?php echo $b->name; ?></b>
                                            </label>
                                        </div>
                                    </div>
                                    <?php 													
											}
                                        }
                                    ?>
                                    </div> 
                                </div>
                            </div>    

                            <div id="div_week_num">                            
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('Numberweeksyouwanttosubscribe');?></label>
                                    
                                    <div class="col-sm-2"  style="padding:10px 0 0 30px;">                                      
    
                                        <div class="input-group number-spinner" style="width: 90%;">
                                            <span class="input-group-btn data-dwn">
                                                <button class="btn btn-default btn-danger" type="button" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                            </span>
                                            <input type="text" class="form-control text-center box"  name="txt_week_num" id="txt_week_num" value="<?php echo ($orderDetails->week_num == 0 ? 4 : $orderDetails->week_num); ?>" min="4" max="20">                                       
                                            <span class="input-group-btn data-up">
                                                <button class="btn btn-default btn-danger" type="button" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
                                            </span>
                                        </div>                              
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('Numberofboxyouwantperweek');?></label>
                                    <div class="col-sm-2" style="padding:10px 0 0 30px;">                                        
                                        <div class="input-group number-spinner" style="width: 90%;">
                                            <span class="input-group-btn data-dwn">
                                                <button class="btn btn-default btn-danger" type="button" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                            </span>
                                            <input type="text" class="form-control text-center box" name="txt_box_num" id="txt_box_num" value="<?php echo ($ordered_boxes[0]->box_num == 0 ? 1 : $ordered_boxes[0]->box_num); ?>" min="1" max="3">
                                            <span class="input-group-btn data-up">
                                                <button class="btn btn-default btn-danger" type="button" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
                                            </span>
                                        </div>                                                                     
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                	<label class="col-sm-3 control-label"><?=$this->lang->line('TypeOfBoxesAvaiableToOrder');?></label>
                                    
                                    <div class="col-sm-9" style="padding-top:0px;">
                                    	<div class="row" style="margin: 0 auto">
                                    <?php 										
                                        if(isset($boxes) && $boxes !== false){
											$count = 0;											
											foreach($boxes as $b2){	
											$count++;
																			
									?>		
                                    		<div class="col-sm-3">
                                            	<img class="rt-noborder img-responsive" src="<?php echo base_url() . "assets/". $b2->image?>" alt="image" width="90%" >
												<div class="checkbox">
													<label>
													  <input class="chk_box" name="chk_box_id[]" type="checkbox" value="<?php echo $b2->box_id; ?>" data-name="<?php echo $b2->name; ?>"> <b><?php echo $b2->name; ?></b>
													</label>
												</div>
                                             </div>
							   <?php 		
							   					if($count >= 4)
												{
													echo '</div><div class="row" style="margin: 0 auto">';		
													$count = 0;
												}										
											}
                                        }
                                    ?>
                                    	</div>
                                   </div>
                                </div>
                                
                            </div>
                            
                            
                            
                            <div class="form-group" style="clear:both;">
                                <label class="col-sm-3 control-label"><?=$this->lang->line('OtherInfo');?></label>
                                
                                <div class="col-sm-5" style="padding-top:0px;">
                                    <textarea class="form-control" name="txt_info" rows="3" placeholder="Note the vegetables you don't want in your box.."><?= $orderDetails->other_info ?></textarea>
                                </div>
                            </div>  
                            
                            <hr style="margin:5px 0px;" />
                        </div>   <!--/col-md-12--> 
                                  
                        <div class="row" style="margin:0 auto;">
                            <button type="button" id="btn_continue_1" class="btn btn-danger next-step btn-catchy-danger pull-right"><?=$this->lang->line('continue_order');?></button>
                        </div>
                    </div>
                    
                    <div class="tab-pane" role="tabpanel" id="step2">
                    	<h2 class="heading-green"><?=$this->lang->line('AdditionalItems');?></h2>  
                        <p><?=$this->lang->line('additionalProducts');?></p>                        
	     				<div class="form-group" id="div_item_sub">
                            <label class="col-sm-12 control-label" style="text-align:left;"><?=$this->lang->line('additionalItems');?></label>
                            
                            <div class="col-sm-12" style="padding-top:0px;">
                               <label class="radio-inline">
                                  <input type="radio" name="rdo_item_subscription" id="rdo_yes" value="YES" <?php echo ($orderDetails->item_subscription == 'Yes' ? 'checked' : '') ?>><?=$this->lang->line('yes');?>
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="rdo_item_subscription" id="rdo_no" value="NO" <?php echo ($orderDetails->item_subscription == 'No' ? 'checked' : '') ?>><?=$this->lang->line('no');?>
                                </label>
                            </div>
                        </div>
                        <hr style="margin:5px 0px;" />
                        
                        <!-- Items List -->
                        <?php echo $item_list_view; ?>
                            
                    	<div class="row" style="margin:0 auto;">
                        	<hr style="margin:15px 0px;">
                            <button type="button" class="btn btn-warning btn-catchy-warning  prev-step"><?=$this->lang->line('goBack');?></button>
                            <button type="button" class="btn btn-danger btn-catchy-danger next-step pull-right"><?=$this->lang->line('continue');?></button>
                        </div>
                    </div>
                    
                    <!-- Delivery Detail-->
                    <div class="tab-pane" role="tabpanel" id="step3">
                         <h2 class="heading-green"><?=$this->lang->line('deliveryDetails');?>
                           <small><a data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('addAdress');?>"> <i class="glyphicon glyphicon-question-sign"></i> </a></small> 
                         </h2>  
                         <p><?=$this->lang->line('boxAreaLive');?></p>                  
     					 <hr style="margin:15px 0px;">    
                         
                         <div id="delivery_msg"></div>
                                              
                         <?php if(isset($address) && $address !== false) { ?>
                            <div class="form-group">
                                <label class="col-md-1 control-label"> </label>
                                <div class="radio col-sm-11">
                                  <label>
                                    <input type="radio" name="rdo_address" id="rdo_old" value="old" checked>
                                    <h5 style="font-weight:bold;"><?=$this->lang->line('exitingArea');?></h5>
                                  </label>
                                  <input type="hidden" name="hid_edited_address" id="hid_edited_address">
                                  <div class="row" style="margin:0 auto;">
                                  	<div class="funkyradio">
                                    <?php 									
										$count2 = 1;
										foreach ($address as $add){
									?>
                                        <div class="col-md-5">                                        	
                                            <button type="button" class="btn btn-link btn_remove pull-right text-danger" data-id="<?= $add->address_id ?>" style="color:#a94442;"><?=$this->lang->line('rem');?></button>                                  		
                                            <button type="button" class="btn btn-link btn_edit pull-right text-info" data-id="<?= $add->address_id ?>"><?=$this->lang->line('edit');?></button>
                                            <div class="funkyradio-success">
                                                <input type="radio" name="rdo_old_address" value="<?= $add->address_id ?>" id="radio<?= $add->address_id ?>" />
                                                <label for="radio<?= $add->address_id ?>" style="margin-top:5px;"> 
                                                    <p style="margin-left:2em;word-wrap: break-word;">
                                                    	<strong><?= $add->contact_person ?></strong> <br>                                               
                                                        
                                                         <?php 
														 	if($add->ph_no == '')
																echo $add->mobile . ' <br>';
															else if($add->mobile == '')
																echo $add->ph_no . ' <br>';
															else
																echo $add->ph_no . ', '. $add->mobile . ' <br>';
												
															if($add->township_name !== 'Other'): 
                                                        		echo $add->address .','. $add->township_name . '<br> ';
                                                         	endif; 
														 ?>
                                                       <?=$this->lang->line('deliveryDay');?> <span class="badge spn_delivery_day"><?= $add->delivery_day ?></span>
                                                        
                                                    </p>
                                                </label>
                                            </div>
                                       	</div>
                                    <?php 
											$count2++;
										 }									
									?>
                                          
                                    </div>
                                  </div>                                  
                                </div>
                            </div>  
                            
                            <div class="form-group">
                                <label class="col-md-1 control-label"> </label>
                                <div class="radio col-sm-5">
                                  <label>
                                    <input type="radio" name="rdo_address" id="rdo_new" value="new">
                                    <h5 style="font-weight:bold;"><?=$this->lang->line('addressAndContact');?></h5>
                                  </label>
                                  <div style="margin-top:5px;" id="msg"></div>
                                </div>
                            </div>  
                         <?php } ?>
                            
                            <div id="holder" style="<?php echo (isset($address) && $address !== false ? 'display:none' : 'display:block' ) ?>;">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('name');?></label>
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <input type="text" class="form-control" id="txtname" name="txtname" placeholder="Contact Person Name...">
                                    </div>
                                    <span class="err"></span>
                                </div>  
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('PhoneNumber');?></label>
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <input type="text" class="form-control" id="txtphone" name="txtphone" placeholder="Contact Number...">
                                    </div>
                                    <span class="err"></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('Mobile');?></label>
                                    
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <input type="text" class="form-control" id="txtmobile" name="txtmobile" placeholder="Mobile Phone Number..." >
                                    </div>
                                    <span class="err"></span>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Township </label>
                                    
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <select class="input-sm" name="cbotownship"  id="cbotownship">
                                          <option value="">--- Choose ---</option>
                                          <?php
										  	if(isset($townships) && $townships !== false) { 
												foreach ($townships as $tsp){
										  ?>
                                          		<option value="<?= $tsp->township_id ?>" data-lat="<?= $tsp->lat ?>" data-long="<?= $tsp->long ?>"> <?= $tsp->name; ?> </option>
                                          <?php 
												}
											}
										  ?>	  
                                        </select>    
                                        <small><a data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('listOfTownships');?>"> <i class="glyphicon glyphicon-question-sign"></i> </a></small>                                     
                                    </div>
                                    <span class="err"></span>
                                </div>  
                                
                                <div class="form-group" id="div_delivery" style="display:none;">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('deliveryDay');?></label>
                                    
                                    <div class="col-sm-5" style="padding-top:0px;" id="delivery_day"></div>
                                </div>
                                
                                <div class="form-group" id="map" style="display:none;">
                                    <label class="col-sm-3 control-label"> </label>
                                    
                                    <div class="col-sm-9" style="padding-top:0px;">
                                    	<input type="hidden" id="lat" name="hid_lat">
                                        <input type="hidden" id="long" name="hid_long">
                                        <div id="map_canvas" style="width: 100%; height: 500px;margin:0 auto;"></div>  
                                    </div>
                                </div>
                                
                                <div class="form-group" id="div_address">
                                    <label class="col-sm-3 control-label">Address </label>
                                    
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <textarea class="form-control" name="txtaddress" id="txtaddress" placeholder="Building No, Floor, Nearby landmark/building etc.," rows="5"></textarea>
                                    </div>
                                    <span class="err"></span>
                                </div>
                                
                                <div class="form-group" id="div_instruction">
                                    <label class="col-sm-3 control-label"><?=$this->lang->line('DeliveryInstruction');?></label>
                                    
                                    <div class="col-sm-5" style="padding-top:0px;">
                                        <textarea class="form-control" name="txtinstruction" id="txtinstruction" placeholder="Note a place or instruction to leave your delivery in a safe place when you are not at home" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                
                            </div>
                         </form>
 						 <hr style="margin:15px 0px;">
                         
                        <div class="row" style="margin:0 auto;">                            
                            <button type="button" class="btn btn-warning btn-catchy-warning prev-step"><?=$this->lang->line('goBack');?></button>
                            <button type="button" class="btn btn-danger btn-catchy-danger next-step pull-right" id="btn_continue_3"><?=$this->lang->line('continue');?></button>
                        </div>
                    </div>
                    
                    <!-- Order Confirmation -->
                    <div class="tab-pane" role="tabpanel" id="complete">
                        <h2 class="heading-green">Order Details</h2> 
      					<hr style="margin:15px 0px;">
                          
                        <div class="col-xs-10 col-sm-10 col-md-8 col-xs-offset-1 col-sm-offset-1 col-md-offset-2">
                            <div class="row" style="margin: 0 auto">
                                <div class="col-sm-6">
                                    <address id="delivery_address">
                                        <?php 
											/*echo '<b>'.$orderDetails->contact_person.'</b><br>';
											echo $orderDetails->address.'<br>';
											if($orderDetails->ph_no == '')
												echo $orderDetails->mobile;
											else if($orderDetails->mobile == '')
												echo $orderDetails->ph_no;
											else
												echo $orderDetails->ph_no . ', '. $orderDetails->mobile;*/
										?>
                                    </address>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <p id="confirm_delivery_day">
                                    	<?php 											
											/*switch ($orderDetails->delivery_day) {
												case "Mon":
													$delivery_day = 'Monday';
													break;
												case "Tue":
													$delivery_day = 'Tuesday';
													break;
												case "Wed":
													$delivery_day = 'Wednesday';
													break;
												case "Thu":
													$delivery_day = 'Thursday';
													break;
												case "Fri":
													$delivery_day = 'Friday';
													break;
												case "Sat":
													$delivery_day = 'Saturday';
													break;
												case "Sun":
													$delivery_day = 'Sunday';
													break;
											}
												
											echo '<b>Delivery day</b> :'.$delivery_day;*/
										?>
                                    </p>
                                </div>
                            </div>
                            <div class="row" style="margin: 0 auto">
                                <div class="text-center">
                                    <h1>Receipt</h1>
                                </div>
								<div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th class="text-right">Price <small>(Ks)</small></th>
                                            <th class="text-right">Total <small>(Ks)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_receipt">
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <hr style="margin:15px 0px;">
                            
                            <div class="row" style="margin:0 auto;">
                                <center>
                                <button type="button" class="btn btn-warning btn-catchy-warning prev-step"><?=$this->lang->line('goBack');?></button>
                                <button type="button" id="btn_confirm" class="btn btn-success btn-catchy-success"><?=$this->lang->line('confirmOrder');?></button>
                                
                                </center>
                            </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            
        </div>
</div>  

 <!-- Step 1 Modal -->
<div class="modal fade" id="step1_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p><?=$this->lang->line('chooseBox');?></p>
        <div id="modal_boxes" ></div>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?=$this->lang->line('done');?></button>
      </div>
    </div>
  </div>
</div>




<!-- Success Modal -->
<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h1 style="margin-bottom:0px;"><i class="glyphicon glyphicon-ok-sign"></i> <?=$this->lang->line('orderSuccessful');?></h1>
            </div>
            <div class="modal-body">
                <p><?=$this->lang->line('editOrderSuccessful');?><a href="<?php echo base_url() ?>order/my_order"><?=$this->lang->line('here');?></a>.</p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Error Modal -->
<div class="modal fade" id="error_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h1 style="margin-bottom:0px;"><i class="glyphicon glyphicon-remove-sign"></i><?=$this->lang->line('orderFailed');?></h1>
            </div>
            <div class="modal-body">
                <p><?=$this->lang->line('errorProcessing');?></p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php include('common/footer.php'); ?>

<style>
.icon-list-add:before {
  content: "a";
}
.icon-box:before {
  content: "b";
}
.icon-truck:before {
  content: "c";
}


/* Order Form Tab Wizard */

.wizard {
    margin: 00px auto;
    /*background: #fff;*/
	width: 100%;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 0px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
		background-color:#fafafa;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 70%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #d9534f;
    
}
.wizard li.active span.round-tab i{
    color: #d9534f;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #d9534f;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #d9534f;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding: 30px;
	background-color:#fff;
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
}


</style>


<script>

var map; var marker;
var box_count = 0; 
var b3_qty = '';
var edit_stage = 0;
$(document).ready(function(){	

	if('<?php echo $orderDetails->subscription ?>' == 'No')
	{
		$('#div_boxes').show();
		$('#div_week_num').hide();
		$('#div_item_sub').hide();
	}
	
	<?php  if(isset($ordered_boxes) && $ordered_boxes !== false){														
				foreach($ordered_boxes as $ob){	 
					if($orderDetails->subscription == 'Yes'){
	?>
						
						$( ".chk_box" ).each(function() { 
						   if( $(this).val() == <?php  echo $ob->box_id ?> )
						   { 
							   box_count++;
							   $(this).prop('checked', true); 
							   $(this).prop('disabled', false);
							   
							   if(<?php echo $ordered_boxes[0]->box_num  ?> == 1 && <?php echo count($ordered_boxes) ?> == 1)
							   		$(this).next().append(' <span class="badge qty">1</span>');
									
							   if(<?php echo $ordered_boxes[0]->box_num  ?> == 2 && <?php echo count($ordered_boxes) ?> == 2)
							   		$(this).next().append(' <span class="badge qty">1</span>');
								
							   if(<?php echo $ordered_boxes[0]->box_num  ?> == 2 && <?php echo count($ordered_boxes) ?> == 1)
							   		$(this).next().append(' <span class="badge qty">2</span>');
							  
							   if(<?php echo $ordered_boxes[0]->box_num  ?> == 3 && <?php echo count($ordered_boxes) ?> == 2)
							   {
								  if(b3_qty == '')
									   b3_qty += <?php  echo $ob->box_qty ?> ;
								   else
										b3_qty += ','+ <?php  echo $ob->box_qty ?> ;
								   
								   $(this).next().append('<select class="input-sm cbo_box_qty" name="cbo_box_qty_'+$(this).val()+'"><option value="1">1</option><option value="2">2</option></select>');
								   
								   $('[name="cbo_box_qty_'+$(this).val()+'"]').val(<?php  echo $ob->box_qty ?>);
							   }
						   }						   
						});
						
						
					   if(parseInt($('#txt_box_num').val()) == box_count)
					   {
						   $( ".chk_box" ).each(function() { 
							if( $(this).prop('checked') == false )
							{
								$(this).prop('disabled', true);
							}
						   });
					   }
	<?php 			}
					else
					{
	?>
						$( ".box" ).each(function() {
							 if( $(this).val() == <?php  echo $ob->box_id ?> )
						   	 {
								  //$(this).val(<?php  echo $ob->box_qty ?>);
								  $(this).prop('checked', true);
							 }
						});
	<?php					
					}
				}
			}
	?>
	
	
	$('#txtname').focus(function(){
		$(this).closest('.form-group')
		.removeClass('has-error');
		$(this).closest('.form-group').find('.err').empty();
	});
	
	$('#txtphone').focus(function(){
		$(this).closest('.form-group')
		.removeClass('has-error');
		$(this).closest('.form-group').find('.err').empty();
	});
	
	$('#txtmobile').focus(function(){
		$(this).closest('.form-group')
		.removeClass('has-error');
		$(this).closest('.form-group').find('.err').empty();
	});
	
	$('#cbotownship').focus(function(){
		$(this).closest('.form-group')
		.removeClass('has-error');
		$(this).closest('.form-group').find('.err').empty();
	});
	
	$('#txtaddress').focus(function(){
		$(this).closest('.form-group')
		.removeClass('has-error');
		$(this).closest('.form-group').find('.err').empty();
	});
	
	$('[name="rdo_subscription"]').change(function(){
		box_count = 0;
		if($(this).val() == 'YES' )
		{
			$('#div_week_num').show();
			$('#div_boxes').hide();
			$('#div_item_sub').show();
		}
		else
		{	
			$('#div_boxes').show();
			$('#div_week_num').hide();
			$('#div_item_sub').hide();
		}
	});
	
		
	$('.chk_box').click(function(){
		
		if( $(this).prop('checked') == true )
		{			
			box_count ++;
			
			if($('#txt_box_num').val() == 1 && box_count == 1)
			{				
				$(this).next().append(' <span class="badge qty">1</span>');
				$( ".chk_box" ).each(function() { //console.log($(this).prop('checked'));
				   if( $(this).prop('checked') == false )
				   {
					   $(this).prop('disabled', true);
				   }
				});
			}
			
			if($('#txt_box_num').val() == 2 && box_count == 2)
			{	
				$(this).next().append(' <span class="badge qty">1</span>');					
				$( ".qty:first" ).html('1');
							
				$( ".chk_box" ).each(function() { //console.log($(this).prop('checked'));
				   if( $(this).prop('checked') == false )
				   {
					   $(this).prop('disabled', true);
				   }
				});
			}
			
			if($('#txt_box_num').val() == 2 && box_count == 1)
			{
				$(this).next().append(' <span class="badge qty">2</span>');				
			}

			
			if($('#txt_box_num').val() == 3 && box_count == 1)
			{
				$(this).next().append(' <span class="badge qty">3</span>');	
			}
			
			if($('#txt_box_num').val() == 3 && box_count == 2)
			{
				$( ".chk_box" ).each(function() { 
					$(this).next().find('.qty').remove();
					if( $(this).prop('checked') == true )
				    {
						$(this).next().append('<select class="input-sm cbo_box_qty" name="cbo_box_qty_'+$(this).val()+'"><option value="1">1</option><option value="2">2</option></select>');
						$('.cbo_box_qty:first').val('2');
					}
					
				});
			}
			
			if($('#txt_box_num').val() == 3 && box_count == 3)
			{				
				$( ".chk_box" ).each(function() { 
				   $(this).next().find('.cbo_box_qty').remove();
				   if( $(this).prop('checked') == false )
				   {
					   $(this).prop('disabled', true);
					  // $(this).parent().next().hide();
				   }
				   else
				   {
					  $(this).next().append(' <span class="badge qty">1</span>');	 				   
				   }
				    
				});
			}
			
		}
		else
		{
			box_count --;
			$( ".chk_box" ).each(function() { 				  
				   $(this).prop('disabled', false);
				   $(this).next().find('.qty').remove();
			});
			
			if($('#txt_box_num').val() == 3 && box_count == 2)
			{
				$( ".chk_box" ).each(function() { 
					$(this).next().find('.qty').remove();
					if( $(this).prop('checked') == true )
				    {
						$(this).next().append('<select class="input-sm cbo_box_qty" name="cbo_box_qty_'+$(this).val()+'"><option value="1">1</option><option value="2">2</option></select>');
						$('.cbo_box_qty:first').val('2');
					}
					
				});
			}
		}		
		
	});
	
	$('#txt_box_num').change(function(){ //alert('here');
		box_count = 0;
		edit_stage = 1;
		$(this).closest('.form-group').removeClass('has-error');
		$( ".chk_box" ).each(function() { 				  
		   $(this).prop('checked', false);	
		   $(this).prop('disabled', false);			   
			     
		});
		
	});
		
	
	$('#btn_confirm').click(function(e){
		e.preventDefault;		
	
		$('#hid_box_count').val(box_count);
		
		//do submission 
		var submit_url = "<?= base_url(); ?>order/add_order";		
		var formData = $('#order_form').serialize();
		
		//disable the button to prevent multiple clicks
		$(this)
			.addClass('loading')
			.prop('disabled', true)
			.html('Processing....');
			
		$.ajax({
			url: submit_url,  
			type: 'POST',
			data: formData
		})
		.done(function( response) {			
			data = JSON.parse(response);	
			
			$('#btn_confirm')
					.removeClass('loading')
					.prop('disabled', false)
					.html('Confirm order');
							
			if(data.status == 'error')
			{				
				$('#step1_modal').modal('hide');
				$('#error_modal').modal('show');
			}
			else
			{
				$('#step1_modal').modal('hide');
				$('#success_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
			}
		})
		.fail(function() {
			$('#btn_confirm')
				.removeClass('loading')
				.prop('disabled', false)
				.html('Confirm order');
			
			$('#error_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
		});

	});
	
	
	// Tabs
	//Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {
        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);
		check_session();

    });
    $(".prev-step").click(function (e) {
        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);
		check_session();

    });
	
	
	/* Delivery 
   	   -------------------------------------------------------
	*/
	$('[name="rdo_old_address"]').each(function(){
		if(<?php echo $orderDetails->address_id ?> == $(this).val())
		{
			$(this).attr('checked', true);
		}
	});

	
	
	$('[name="rdo_address"]').change(function(){
		
		if($(this).val() == "new")
		{
			// reset form
			$('#txtname').val('');
			$('#txtphone').val('');
			$('#txtmobile').val('');
			$('#txtaddress').val('');
			$('#txtinstruction').val('');
			$('#cbotownship').val('');
			$('#div_delivery').hide();
			$('#map').hide();
			
			
			$("#holder").show();
			$('[name="rdo_old_address"]').attr('checked', false); // un-checked the option of old address
		}
		else
		{
			$("#holder").hide();
			$('[name="rdo_old_address"]:first').prop('checked', true); // auto check the first option of existing address
		}
	});
	
	
	$('#cbotownship').change(function(){		
		var tsp_id = $(this).val();
		//if($(this).val() !== '1'){ 
			// Mapping			
			var lat = $(this).find(':selected').data('lat');
			var long = $(this).find(':selected').data('long');
			mapping(lat, long);
			// map.setCenter(marker.getPosition());
			
			google.maps.event.trigger(map, 'resize');
			$('#map').show();	
			$('#div_address').show();
			$('#div_instruction').show();
			
			
			//var delivery_day = $(this).find(':selected').data('day');
			
			//var option = '<label class="radio-inline"><input type="radio" name="rdo_delivery_day" value="'+delivery_day+'" checked> '+delivery_day+'</label><label class="radio-inline"><input type="radio" name="rdo_delivery_day" value="Sat"> Sat (Pick-Up)</label>';
			
			var option = '';
			// get delivery day for chosen tsp
			$.ajax({
				url: '<?php echo base_url() ?>order/get_delivery_days',  
				type: 'POST',
				data: {'tsp_id' : tsp_id}
			})
			.done(function( response ) {		 
				data = JSON.parse(response);			
				if(data.status == 'error')
				{
					bootbox.alert("Sorry, an error encountered while processing your request. Please refresh the page and try again.", function() {
					  location.reload(); 
					});
				}
				else
				{				
					var count = data.length; 
					if(count == 1)
					{
						option = '<span class="badge" id="lbl_delivery">' + data[0].delivery_day + '</span><input type="hidden" name="rdo_delivery_day" value="'+ data[0].tspdetail_id +'">';
						$('#delivery_day').html(option);
					}
					else
					{
						for(var i in data)
						{
							option += '<label class="radio-inline"><input type="radio" name="rdo_delivery_day" data-day="'+data[i].delivery_day+'" value="'+data[i].tspdetail_id+'" checked> '+data[i].delivery_day+'</label>';
						}
						$('#delivery_day').html(option);
					}
					
				}
				
			});
			
			//$('#delivery_day').html(option);
		/*}
		else
		{
		
			var delivery_day = $(this).find(':selected').data('day');			
			$('#delivery_day').html('<span class="badge">' + delivery_day + '</span>');
			
			$('#map').hide();
			$('#div_address').hide();
			$('#div_instruction').hide();
			
		}*/
		$('#div_delivery').show();
	});	

	/* Number Input */
	var action;
    $(".number-spinner button").mousedown(function () {
        btn = $(this);
        input = btn.closest('.number-spinner').find('input');
        btn.closest('.number-spinner').find('button').prop("disabled", false);
		
		/* program logic */
		box_count = 0;
		edit_stage = 1;
		$(this).closest('.form-group').removeClass('has-error');
		$( ".chk_box" ).each(function() { 				  
		   $(this).prop('checked', false);	
		   $(this).prop('disabled', false);		
		   $(this).next().find('.qty').remove();	 	   
				 
		});
		
    	if (btn.attr('data-dir') == 'up') 
		{			
            //action = setInterval(function(){
                if ( input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max')) ) {
                    input.val(parseInt(input.val())+1);
                }else{
                    btn.prop("disabled", true);
                    clearInterval(action);
                }
            //}, 50);
			
			
    	} else {
            //action = setInterval(function(){
                if ( input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min')) ) {
                    input.val(parseInt(input.val())-1);
                }else{
                    btn.prop("disabled", true);
                    clearInterval(action);
                }
            //}, 50);
    	}		
    }).mouseup(function(){
       // clearInterval(action);
    });	
	
	
});	


$(document).on('change', '[name="rdo_delivery_day"]', function() {
	 /* var delivery_day  = $(this).val();
	  if(delivery_day == "Sat")
	  {
		  $('#map').hide();
		  $('#div_address').hide();
		  $('#div_instruction').hide();
	  }
	  else
	  {		  
		  $('#map').show();
		  $('#div_address').show();
		  $('#div_instruction').show();
	  }*/
});



$(document).on('click', '[name="rdo_old_address"]', function() { 
	
	$("#holder").hide();
	$('[name="rdo_address"]').prop('checked', false);
	$('[name="rdo_address"]:first').prop('checked', true);
});

$(document).on('click', '.btn_edit', function() { 
	var add_id = $(this).data('id');
	$('#hid_edited_address').val(add_id);

	show_address_details(add_id);
	
	/*$("#txtname").val('<?php echo $orderDetails->contact_person ?>');
	$("#txtphone").val('<?php echo $orderDetails->ph_no ?>');
	$("#txtmobile").val('<?php echo $orderDetails->mobile ?>');

	$('#cbotownship').val('<?php echo $orderDetails->township_id ?>');
	$("#txtaddress").val('<?php echo $orderDetails->address ?>');
	$("#txtinstruction").val('<?php echo $orderDetails->delivery_instruction ?>');
	
	
	//if('<?php echo $orderDetails->township_id ?>' !== '1' || '<?php echo $orderDetails->delivery_day ?>' !== 'Sat')
	//{
		//var delivery_day = '<?php echo $orderDetails->delivery_day ?>';
		var option = '';
		// get delivery day for chosen tsp
		$.ajax({
			url: '<?php echo base_url() ?>order/get_delivery_days',  
			type: 'POST',
			data: {'tsp_id' : '<?php echo $orderDetails->township_id ?>'}
		})
		.done(function( response ) {		 
			data = JSON.parse(response);			
			if(data.status == 'error')
			{
				bootbox.alert("Sorry, an error encountered while processing your request. Please refresh the page and try again.", function() {
				  location.reload(); 
				});
			}
			else
			{				
				var count = data.length; 
				if(count == 1)
				{
					option = '<span class="badge" id="lbl_delivery">' + data[0].delivery_day + '</span><input type="hidden" name="rdo_delivery_day" value="'+ data[0].tspdetail_id +'">';
					$('#delivery_day').html(option);
				}
				else
				{
					for(var i in data)
					{
						option += '<label class="radio-inline"><input type="radio" name="rdo_delivery_day" data-day="'+data[i].delivery_day+'" value="'+data[i].tspdetail_id+'"> '+data[i].delivery_day+'</label>';
					}
							
					$('#delivery_day').html(option);
					$('[value="<?php echo $orderDetails->delivery_day ?>"]').prop('checked', true);
				}
				
			}
			
		});
			
		//var option = '<label class="radio-inline"><input type="radio" name="rdo_delivery_day" value="'+delivery_day+'" checked> '+delivery_day+'</label><label class="radio-inline"><input type="radio" name="rdo_delivery_day" value="Sat"> Sat (Pick-Up)</label>';
		//$('#delivery_day').html(option);
			
		$('#div_delivery').show();
		$("#lat").val('<?php echo $orderDetails->lat ?>');
		$("#long").val('<?php echo $orderDetails->long ?>');
		
		mapping('<?php echo $orderDetails->lat ?>', '<?php echo $orderDetails->long ?>');
    	$('#map').show();
	//}
	
	$("#holder").show();*/
});

$(document).on('click', '.btn_remove', function() { 
	var add_id = $(this).data('id');
	var ele = $(this);
	bootbox.confirm("Are you sure you want to remove this address ?", function(result) {
	  	if(result == true)
		{
			$.ajax({
				url: '<?php echo base_url() ?>order/remove_address',  
				type: 'POST',
				data: {'address_id' : add_id}
			})
			.done(function( response) {			
				data = JSON.parse(response);			
				if(data.status == 'error')
				{
					$('#delivery_msg').html(data.msg);
				}
				else
				{	ele.parent().hide();				
					$('#delivery_msg').html(data.msg);
				}
			})
			.fail(function() {	
				$('#delivery_msg').html('Internal server error occured. Please refresh the page and try again. Sorry for your inconvenience.');
			});
		}
	});
});


var b_count_2 = 0;
$(document).on('change', '.cbo_box_qty', function() {
	
	$('.cbo_box_qty').removeClass('chosen');	
	b_count_2 = parseInt($(this).val());
	var rest_b =0;

	if(b_count_2 == 2)
		rest_b = 1;
	else if(b_count_2 == 1)
		rest_b = 2;
	
	$(this).addClass('chosen');	
	
	$( '.cbo_box_qty' ).each(function() {
		if ($(this).hasClass('chosen') === false) 
		{
			$(this).html('<option value="'+rest_b+'">'+rest_b+'</option>');
		}
	});	
	
});


var check_modal = false;
var shown = 'no';

$(document).on('click', '[href="#complete"]', function() {
	show_chosen_delivery_address().done( show_selected_items());
});

$(document).on('click', '[href="#step1"]', function() {
	shown = 'no';
});

$(document).on('click', '[href="#step2"]', function() {
	step1_validation();
});

function show_address_details(add_id)
{
	// get selected address details
	$.ajax({
		url: '<?php echo base_url() ?>order/get_selected_address',  
		type: 'POST',
		data: {'address_id' : add_id}
	})
	.done(function( response ) {		 
				
		if(response == false)
		{
			bootbox.alert("Sorry, an error encountered while processing your request. Please try again after the page being refreshed.", function() {
			  location.reload(); 
			});
		}
		else
		{		
			data = JSON.parse(response);
			//console.log(data);			
			$("#txtname").val(data[0].contact_person);
			$("#txtphone").val(data[0].phone);
			$("#txtmobile").val(data[0].mobile);			
			$('#cbotownship').val(data[0].township_id);
			$("#txtaddress").val(data[0].address);
			$("#txtinstruction").val(data[0].delivery_instruction);	

			mapping(data[0].lat, data[0].long);	
			$("#lat").val(data[0].lat);
			$("#long").val(data[0].long);
			$('#map').show();

			get_delivery_details(data[0].day_id, data[0].township_id);
			
			$('#div_delivery').show();			
			$("#holder").show();
		}
	});
}

function get_delivery_details(delivery_day, tsp_id)
{
	var option = '';
			
	// get delivery day for chosen tsp
	$.ajax({
		url: '<?php echo base_url() ?>order/get_delivery_days',  
		type: 'POST',
		data: {'tsp_id' : tsp_id}
	})
	.done(function( response ) {		 
		data = JSON.parse(response);			
		if(data.status == 'error')
		{
			bootbox.alert("Sorry, an error encountered while processing your request. Please try again after the page being refreshed.", function() {
			  location.reload(); 
			});
		}
		else
		{				
			var count = data.length; 
			if(count == 1)
			{
				option = '<span class="badge" id="lbl_delivery">' + data[0].delivery_day + '</span><input type="hidden" name="rdo_delivery_day" value="'+ data[0].day_id +'">';
				$('#delivery_day').html(option);
			}
			else
			{
				for(var i in data)
				{
					option += '<label class="radio-inline"><input type="radio" name="rdo_delivery_day" data-day="'+data[i].delivery_day+'" value="'+data[i].day_id+'"> '+data[i].delivery_day+'</label>';
				}

				$('#delivery_day').html(option);
				$('[value="'+data[i].day_id+'"]').prop('checked', true);
			}
			
		}
		
	});
}

function nextTab(elem) {

	if($('.nav-tabs li.active').children().attr('href') == '#step1')
	{
		if(step1_validation() == 'ok')
			$(elem).next().find('a[data-toggle="tab"]').click();
		
	}
	if($('.nav-tabs li.active').children().attr('href') == '#step2')
	{
		$(elem).next().find('a[data-toggle="tab"]').click();
	}
	else if($('.nav-tabs li.active').children().attr('href') == '#step3')
	{
		/* Delviery info Validation */
		var validation = true;
		var address = $('[name="rdo_address"]:checked').val();
		
		if(address == 'new' || address === undefined)	
		{
			if($('#txtname').val() == '')
			{
				$('#txtname').closest('.form-group')
				.addClass('has-error')
				.find('.err')
				.html('<b class="err text-danger">Please enter contact person name !</b>');
				
				validation = false;
			}
			
			if($('#txtphone').val() == '')
			{
				$('#txtphone').closest('.form-group')
				.addClass('has-error')
				.find('.err')
				.html('<b class="err text-danger">Please enter contact number !</b>');
				
				validation = false;
			}
			
			if($('#txtmobile').val() == '')
			{
				$('#txtmobile').closest('.form-group')
				.addClass('has-error')
				.find('.err')
				.html('<b class="err text-danger">Please enter mobile number !</b>');
				validation = false;
			}
			
			if($('#cbotownship').val() == '')
			{
				$('#cbotownship').closest('.form-group')
				.addClass('has-error')
				.find('.err')
				.html('<b class="err text-danger">Please choose your township !</b>');
				
				validation = false;
			}
			
			//if($('#cbotownship').val() !== '1')
			//{
				//if($('[name="rdo_delivery_day"]:checked').val() !== 'Sat')
				//{
					if($('#txtaddress').val() == '')
					{
						$('#txtaddress').closest('.form-group')
						.addClass('has-error')
						.find('.err')
						.html('<b class="err text-danger">Please enter your address !</b>');
						
						validation = false;
					}
				//}
			//}
		}
		 
		if(validation == true)
		{		
			
			var delivery_day;
			var address = $('[name="rdo_address"]:checked').val();		
			if(address == 'old')			
				delivery_day = $('[name="rdo_old_address"]:checked').next().find('.spn_delivery_day').html();
			else
			{
				if($('#lbl_delivery').length == 0)
					 delivery_day = $('[name="rdo_delivery_day"]:checked').data('day');
				else
					 delivery_day = $('#lbl_delivery').html();
			}
			
			var box_count = $('#txt_box_num').val();
			
			/* Check available box limit when user increases box number or change delivery day*/
			if(parseInt(box_count) > <?php echo $ordered_boxes[0]->box_num ?>  || '<?php echo $orderDetails->delivery_day ?>' !==  delivery_day)
			{
				box_count = parseInt(box_count) - parseInt(<?php echo $ordered_boxes[0]->box_num ?>);
				
				$.ajax({
					url: '<?php echo base_url() ?>order/ajax_check_box_limit',  
					type: 'POST',
					data: {'delivery_day' : delivery_day, 'box_count' : box_count}
				})
				.done(function( response ) {		 
					data = JSON.parse(response);	
	
					if(data.status == 'error')
					{
						window.location.href = "<?php echo base_url() ?>main/error/max_box";
					}
					else
					{					
						$(elem).next().find('a[data-toggle="tab"]').click();
						
						/*$('#btn_continue_3')
							.html('Processing...')
							.attr('disabled', true);*/
							
						/* 	Order Confirmation 	*/	
						show_chosen_delivery_address().done( show_selected_items());
						
					}
					
				})
				.fail(function() {
					console.log('here');
				});
				
			}
			else
			{
				$(elem).next().find('a[data-toggle="tab"]').click();
				show_chosen_delivery_address().done( show_selected_items());
			}
		}
	}
	
	
}

function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
	selected_boxes = '';
	shown = 'no';
}


var show_chosen_delivery_address = function ()
{
	var selected_boxes = '';
	var r = $.Deferred();
	/*
	*	Showing the chosen / entered delivery address of the order
	*	--------------------------------------------------------------------------
	*/
	var address = $('[name="rdo_address"]:checked').val();		
	// When user has selected to use old address
	if( (address == 'old' && $('#hid_edited_address').val() !== '') || address == 'new' || address == undefined)
	{
		//show the address detail entered on the form
		var name = $('#txtname').val();
		var ph = $('#txtphone').val();
		var mobile = $('#txtmobile').val();
		
		if(ph != '' && mobile != '')
			ph = ph + ', ' + mobile;
		else if(ph == '' && mobile != '')
			ph = mobile;
		else
			ph = ph;
		
		
		var tsp = $('#cbotownship').val();
		
		var selected_delivery_day;// = $('[name="rdo_delivery_day"]:checked').val();
		if($('#lbl_delivery').length == 0)
			 selected_delivery_day = $('[name="rdo_delivery_day"]:checked').data('day');
		else
			 selected_delivery_day = $('#lbl_delivery').html();
			
		
		//if(tsp !== '1' && selected_delivery_day !== 'Sat')	{
			
			var address =  $('#txtaddress').val();
			
			if(selected_delivery_day == 'Mon')
				selected_delivery_day = 'Monday';
			else if(selected_delivery_day == 'Tue')
				selected_delivery_day = 'Tuesday';
			else if(selected_delivery_day == 'Wed')
				selected_delivery_day = 'Wednesday';
			else if(selected_delivery_day == 'Thu')
				selected_delivery_day = 'Thursday';
			else if(selected_delivery_day == 'Fri')
				selected_delivery_day = 'Friday';
			else if(selected_delivery_day == 'Sat')
				selected_delivery_day = 'Saturday';					
			else if(selected_delivery_day == 'Sun')
				selected_delivery_day = 'Sunday';
			
			
			$('#delivery_address').html('<b>' + name + '</b><br>' + address + '<br>' + ph);
			$('#confirm_delivery_day').html('<b>Delivery day</b> : '+ selected_delivery_day);
			
		/*}
		else
		{
			$.ajax({
				url: '<?php echo base_url() ?>order/get_pickup_address',  
				type: 'POST'
			})
			.done(function( response ) {
				if(response !== false)
				{
					data = JSON.parse(response);
					var drop_off_point = data.drop_off_point;
					var pick_up_address = data.pick_up_address;
					$('#delivery_address').html('<b>' + name + '</b><br>' + ph);
					$('#confirm_delivery_day').html('<b>Drop off point :</b>' + drop_off_point + '<br>' + pick_up_address + '<br><b>Pick-up day</b> : Saturday');
				}
			});	
		}*/
	}
	else 
	{
		// check if the user delivery day is "SAT"			
		var selected_delivery_day = $('[name="rdo_old_address"]:checked').next().find('.spn_delivery_day').html();
		//if(selected_delivery_day !== 'Sat')	{
			
			var selected_address = $('[name="rdo_old_address"]:checked').val();
			$.ajax({
				url: '<?php echo base_url() ?>order/get_selected_address',  
				type: 'POST',
				data: {'address_id' : selected_address}
			})
			.done(function( response ) {
				if(response !== false)
				{
					data = JSON.parse(response);
					var contact = '<b>' + data[0].contact_person + '</b> <br>';
					var address = data[0].address + '<br>';
					var ph;
					if(data[0].phone != '' && data[0].mobile != '')
						ph = data[0].phone + ', ' + data[0].mobile;
					else if(data[0].phone == '' && data[0].mobile != '')
						ph = data[0].mobile;
					else
						ph = data[0].phone;
					
					if(selected_delivery_day == 'Mon')
						selected_delivery_day = 'Monday';
					else if(selected_delivery_day == 'Tue')
						selected_delivery_day = 'Tuesday';
					else if(selected_delivery_day == 'Wed')
						selected_delivery_day = 'Wednesday';
					else if(selected_delivery_day == 'Thu')
						selected_delivery_day = 'Thursday';
					else if(selected_delivery_day == 'Fri')
						selected_delivery_day = 'Friday';
					else if(selected_delivery_day == 'Sat')
						selected_delivery_day = 'Saturday';					
					else if(selected_delivery_day == 'Sun')
						selected_delivery_day = 'Sunday';
						
					$('#delivery_address').html(contact + address + ph);
					$('#confirm_delivery_day').html('<b>Delivery day</b> : '+ selected_delivery_day);
				}
			});	
		/*}
		else
		{
			$.ajax({
				url: '<?php echo base_url() ?>order/get_pickup_address',  
				type: 'POST'
			})
			.done(function( response ) {
				if(response !== false)
				{
					data = JSON.parse(response);
					var drop_off_point = '<b>' + data.drop_off_point + '</b><br>';
					var pick_up_address = data.pick_up_address;
					$('#delivery_address').html(drop_off_point + pick_up_address);
					$('#confirm_delivery_day').html('<b>Pick-up day</b> : Saturday');
				}
			});	
		}*/
		
	}
	
	return r;
}

var box_price = <?php echo (isset($box_price) && $box_price !== false ? $box_price : 23000) ?>;
var show_selected_items = function ()
{
	/*
	*   Showing the ordered boxes & additional items
	*   ------------------------------------------------------------------
	*/
	var subtotal = 0;
	$('#tbl_receipt').empty();
	if($('[name="rdo_subscription"]:checked').val() == 'NO')
	{			
		var qty;
		var total = 0;
		
		$( ".box" ).each(function() { 				  
			if($(this).prop('checked') == true)			  
			{
				tr = $('<tr></tr>');
				selected_boxes = $(this).data('name');
				subtotal += box_price;
				tr.append('<td class="col-md-7">'+selected_boxes+'</td><td class="col-md-2">1 </td><td class="col-md-2 text-right">'+box_price.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td class="col-md-2 text-right">23,000</td>');
				$('#tbl_receipt').append(tr);
			}
		});
		
	}
	else
	{		
		var qty = 0;
		var week_num = parseInt($('#txt_week_num').val()); 
		var box_num = parseInt($('#txt_box_num').val());
		
		if(box_num == box_count)
		{
			qty = box_num / box_count;
		}
		else if(box_num > box_count)
		{
			if(box_count == 1)
				qty = box_num / box_count;			
		}
		
		t_qty = qty +' x '+ week_num +' weeks';
		qty = qty * week_num;
		total = (qty * box_price);
		subtotal += (parseInt($('#txt_box_num').val()) * box_price) * week_num;
		
		count = 0;
		$( ".chk_box" ).each(function() { 				  
			if($(this).prop('checked') == true )			  
			{
				count++;
				
				if(box_count == 2 && $('#txt_box_num').val() == '3')
				{
					qty = $('[name="cbo_box_qty_'+$(this).val()+'"]').val();
					t_qty = qty +' x 4 weeks';
					total = (qty * box_price);
				}
				
				tr = $('<tr></tr>');
				selected_boxes = $(this).data('name');				
				
				tr.append('<td class="col-md-7">'+selected_boxes+'</td><td class="col-md-2">'+t_qty+'</td><td class="col-md-2 text-right">'+box_price.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td class="col-md-2 text-right">'+total.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td>');
				$('#tbl_receipt').append(tr);
			}
		});		
		
	}
	
	var qty; var t_qty;	
	$( ".items" ).each(function() { 			  
		if($(this).val() > 0)			  
		{
			if($('[name="rdo_item_subscription"]:checked').val() == 'YES')
			{
				qty = parseInt($(this).val()) * week_num;
				t_qty = $(this).val() +' x '+ week_num +' weeks';
			}
			else
			{
				qty = $(this).val();
				t_qty = $(this).val();
			}
	
			tr = $('<tr></tr>');
			weight = $(this).data('weight');
			type = $(this).data('type');
			selected_items = $(this).data('name') + ' - ' + type +' (' + weight + ')';
			
			price = parseInt($(this).data('price'));			
			total = (qty * price);
			subtotal += total;
			
			tr.append('<td class="col-md-7">'+selected_items+'</td><td class="col-md-2">'+t_qty+'</td><td class="col-md-2 text-right">'+price.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td><td class="col-md-2 text-right">'+total.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td>');
			$('#tbl_receipt').append(tr);
		}
	});
	
	
	var total_tr = $('<tr></tr>');
	total_tr.append('<td>&nbsp;</td><td>&nbsp;</td><td class="text-right"><h5><b>Subtotal: </b></h5></td><td class="text-right"><h5 class="text-danger"><b>'+subtotal.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</b></h5></td>');
	$('#tbl_receipt').append(total_tr);		
}

function step1_validation()
{
	var b_count = 0;
	var status = '';
	if($('[name="rdo_subscription"]:checked').val() == 'YES')
		status = 'check';
	else if(!$('#div_subscription').is(":visible"))
		status = 'check';
	
	/* Validation */
	var check = '';
	
	if($('[name="rdo_subscription"]:checked').val() == 'NO')
	{
		$( ".box" ).each(function() {
			//check += $(this).val();
			if($(this).prop('checked') == true )			  
			{
				check += '1';
			}
		});
	}
	else
	{		
		var label = ''; 
		var b_qty = '';			
		
		$( ".chk_box" ).each(function() {
			if($(this).prop('checked') == true )			  
			{
				check += '1';	
				
				if($('#txt_box_num').val() == '3')										
				{
					b_count++;	
					
				}

			}
		});
	}
	
	if (check.indexOf("1") == -1)
	{
		$('#msg').html('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Please choose one of the box you want to order ! </b></div>');
	}
	else if(status == 'check' && $('#txt_box_num').val() > 3)
	{
		$('#msg').html('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> You can only order up to 3 boxes a week !  </b></div>');
		$('#txt_box_num').closest('.form-group').addClass('has-error');
	}
	else if(status == 'check'  && $('#txt_box_num').val() < 1)
	{
		$('#msg').html('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> You should order at least 1 boxes a week !  </b></div>');
		$('#txt_box_num').closest('.form-group').addClass('has-error');
	}
	else if(status == 'check'  && $('#txt_box_num').val() == '3' && b_count == 2)
	{
		qty = b3_qty.split(',');
		var b3_count = 0;
		//$('#modal_boxes').empty();
		$( ".chk_box" ).each(function() {
			if($(this).prop('checked') == true )			  
			{					
				//$('#modal_boxes').append('<div class="row" style="margin:0 auto;padding:5px;"><lable class="col-md-4">' +$(this).data('name') + '</lable><div class="col-md-8"><select class="input-sm cbo_box_qty" name="cbo_box_qty_'+$(this).val()+'"><option value="1">1</option><option value="2">2</option></select></div></div>');			
				b3_count += parseInt($(this).val());	
				
			}
		});
		
		if(edit_stage == 0)
		{
			$('.cbo_box_qty:first').val(qty[0]);
			$('.cbo_box_qty:last').val(qty[1]);
		}
		else
		{
			$('.cbo_box_qty:first').val('2');
		}
		
		if(b3_count < 3)
		{
			$('#msg').html('<div class="alert alert-block alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button> <b><i class="glyphicon glyphicon-warning-sign"></i> Please choose 1 more box.  </b></div>');
		}
		else
			return 'ok';
		
		/*check_modal = true;
		shown = 'yes';
		$('#step1_modal').modal();
		edit_stage = 1;*/
	}
	else
	{
		return 'ok';
	}
}

function check_session()
{	
		$.ajax({
			url: '<?php echo base_url() ?>user/check_session',  
			type: 'POST'
		})
		.done(function( response ) {
			
			if(response === false)
			{
				window.location.href = "<?php echo base_url() ?>user";
			}
		});	
}

function mapping(lat, long)
{
	var zoom = 17;
			
	if(lat == '' || long == '')
	{
		lat = 16.8660694;
		long = 96.195132;
		zoom = 13;
	}			
	
	var myOptions = {
		zoom: zoom,
		center: new google.maps.LatLng(lat, long),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	map.setCenter(new google.maps.LatLng(lat, long));
	marker = new google.maps.Marker({
				position: map.getCenter(), 
				map: map
			});	
	 
	 marker.setPosition(new google.maps.LatLng(lat, long));
	 
	 // must define the Listener right after creating the Marker 
	 google.maps.event.addListener(
		map,
		'click',
		function(event) {
			if(marker)
				marker.setMap(null);
				
     		marker = new google.maps.Marker({
				position: event.latLng, 
				map: map
			});
		
			map.setCenter(marker.getPosition());
	
			$('#lat').val(marker.position.lat());
			$('#long').val(marker.position.lng());
		}
	);
	
}

window.setTimeout(function() {
	$(".alert").fadeTo(500, 0).slideUp(500, function(){
		$(this).remove(); 
	});
}, 6000);


</script>

</form>