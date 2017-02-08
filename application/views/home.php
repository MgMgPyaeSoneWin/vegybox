<?php include('common/header.php'); ?>

<style>
.rt-block {
    padding: 0px;
}
</style>
<div class="row" style="margin:0 auto;">
	<div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">
      
    
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#bs-carousel" data-slide-to="1"></li>
        <li data-target="#bs-carousel" data-slide-to="2"></li>
      </ol>
      
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
        <div class="item slides active">
          <div class="slide-1"> 
              <!-- Overlay -->
	      	<div class="overlay"></div>
          </div>         
          <div class="hero">
            <hgroup>
                <h1><?php echo $this->lang->line('app_mobile'); ?></h1>
                <h3 class="visible-md visible-lg"><?php echo $this->lang->line('download_app');?></h3>
            </hgroup>
           <!--a href="<?php echo base_url() ?>order" class="btn btn-hero btn-lg" >Download Now</a-->
		   <a href="<?php echo base_url() ?>assets/frescovegybox.cordova.android.201605250714.apk" download="FrescoVegyBox.apk" target="_blank" class="btn btn-hero btn-lg" ><?php echo $this->lang->line('download_now');?></a>
          </div>
        </div>
		
		<div class="item slides">
          <div class="slide-2">
          <!-- Overlay -->
	      	<div class="overlay"></div>
          </div>
          <div class="hero">        
            <hgroup>
                <h3><strong><?php echo $this->lang->line('tired_of_wasting_time_in_traffic');?></strong></h3>        
                <!--<h2>Sit back and relax while we do all the work. </h2> <h3 class="visible-md visible-lg"> We have started a Vegy box service to easily get to you the best of our products right infront of your door step.  </h3>-->
            </hgroup>
            <a href="<?php echo base_url() ?>order" class="btn btn-hero btn-lg" role="button"><?php echo $this->lang->line('Order_Vegy_Box_Now');?></a>
          </div>
        </div>
		
        <div class="item slides">
          <div class="slide-3">
	          <!-- Overlay -->
	      	<div class="overlay"></div>
          </div>
          <div class="hero">        
            <hgroup>
                <!--<h1>We are smart</h1>  -->      
                <h3><strong><?php echo $this->lang->line('are_you_too_busy');?></strong></h3>
            </hgroup>       
            <a href="<?php echo base_url() ?>order" class="btn btn-hero btn-lg" role="button"><?php echo $this->lang->line('Order_Vegy_Box_Now');?></a>
          </div>
        </div>
		
        <div class="item slides">
          <div class="slide-4">
          <!-- Overlay -->
	      	<div class="overlay"></div>
          </div>
          <div class="hero">        
            <hgroup>
                <!--<h1>We are amazing</h1>  -->      
                <h2><?=$this->lang->line('sitAndRelax');?></h2> <h3 class="visible-md visible-lg"> haveStartedVegyBoxService<?=$this->lang->line('haveStartedVegyBoxService');?></h3>
            </hgroup>
            <a href="<?php echo base_url() ?>order" class="btn btn-hero btn-lg" role="button"><?=$this->lang->line('OrderVegyBoxNow');?></a>
          </div>
        </div>
      </div>       
</div>


<div class="row" style="margin:0 auto; padding:15px;">
	<hr style="margin:15px 0px;">
	<h4 class="heading-green text-center"><?=$this->lang->line('vegyBoxExplain');?></h4>
	<hr style="margin:15px 0px;">
    
    <p class="text-center" style="font-size:14px;">
    	<?=$this->lang->line('servieDetail');?>
	</p>
	
    
    <div class="row" style="margin:0 auto; padding:2%;">
    	<?php 
			if(isset($boxes) && $boxes !== false)
			{
				$count = 0;
				foreach($boxes as $b)
				{						
		?>    
                    <div class="col-md-4">
                    	<div class="box">
                            <div class="box-heading">
                                <img src="<?php echo base_url()."assets/". $b->image ?>" style="width:100%;" >
                            </div>
                            <div class="box-body">
                                <h4><?php echo $b->name; ?></h4>
                                <hr style="margin:5px 0px;">
                                <p><?php echo $b->description; ?></p>
                            </div>
                            <div class="box-footer"><a href="<?php echo base_url() ?>order/"><?=$this->lang->line('OrderNow');?></a></div>
                       </div>
                    </div>
        <?php 
					$count++;
					if($count >= 3)
					{	
						echo '</div><div class="row" style="margin:0 auto; padding:2%;">';
						$count=0;
					}	
						
				}
			}
		?>          
    </div>
    <div class="row" style="margin:0 auto;">
        <div class="notice notice-success">
            <strong><?=$this->lang->line('Notice');?></strong><?=$this->lang->line('downloadManual');?><a href="<?php echo base_url() ?>assets/FrescoVegyBoxUserManual.pdf" download="FrescoVegyBoxUserManual.pdf" target="_blank" ><?=$this->lang->line('English');?></a> | <a href="<?php echo base_url() ?>assets/FrescoUserManualZawgyi.pdf" download="FrescoUserManualZawgyi.pdf" target="_blank" ><?=$this->lang->line('Myanmar');?></a>.
        </div>
    </div>
</div>
<?php echo $this->lang->line('download'); ?>
<?php include('common/footer.php'); ?>

<style>
/*
Fade content bs-carousel with hero headers
Code snippet by maridlcrmn (Follow me on Twitter @maridlcrmn) for Bootsnipp.com
Image credits: unsplash.com
*/

/********************************/
/*       Fade Bs-carousel       */
/********************************/
.fade-carousel {
    position: relative;
    height: 50vh;
}
.fade-carousel .carousel-inner .item {
    height: 50vh;
}
.fade-carousel .carousel-indicators > li {
    margin: 0 2px;
    background-color: #f39c12;
    border-color: #f39c12;
    opacity: .7;
}
.fade-carousel .carousel-indicators > li.active {
  width: 10px;
  height: 10px;
  opacity: 1;
}

/********************************/
/*          Hero Headers        */
/********************************/
.hero {
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 3;
    color: #fff;
    text-align: center;
    text-transform: uppercase;
    text-shadow: 1px 1px 0 rgba(0,0,0,.75);
      -webkit-transform: translate3d(-50%,-50%,0);
         -moz-transform: translate3d(-50%,-50%,0);
          -ms-transform: translate3d(-50%,-50%,0);
           -o-transform: translate3d(-50%,-50%,0);
              transform: translate3d(-50%,-50%,0);
}
.hero h1 {
    font-size: 6em;    
    font-weight: bold;
    margin: 0;
    padding: 0;
}

.fade-carousel .carousel-inner .item .hero {
    opacity: 0;
    -webkit-transition: 2s all ease-in-out .1s;
       -moz-transition: 2s all ease-in-out .1s; 
        -ms-transition: 2s all ease-in-out .1s; 
         -o-transition: 2s all ease-in-out .1s; 
            transition: 2s all ease-in-out .1s; 
}
.fade-carousel .carousel-inner .item.active .hero {
    opacity: 1;
    -webkit-transition: 2s all ease-in-out .1s;
       -moz-transition: 2s all ease-in-out .1s; 
        -ms-transition: 2s all ease-in-out .1s; 
         -o-transition: 2s all ease-in-out .1s; 
            transition: 2s all ease-in-out .1s;    
}

/********************************/
/*            Overlay           */
/********************************/
.overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-color: #080d15;
    opacity: .7;
}

/********************************/
/*          Custom Buttons      */
/********************************/
.btn.btn-lg {padding: 10px 40px;}
.btn.btn-hero,
.btn.btn-hero:hover,
.btn.btn-hero:focus {
    color: #f5f5f5;
    background-color: #1abc9c;
    border-color: #1abc9c;
    outline: none;
    margin: 20px auto;
}

/********************************/
/*       Slides backgrounds     */
/********************************/
.fade-carousel .slides .slide-1, 
.fade-carousel .slides .slide-2,
.fade-carousel .slides .slide-3,
.fade-carousel .slides .slide-4 {
  height: 50vh;
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
}
.fade-carousel .slides .slide-1 {
  background-image: url(assets/img/banner.jpg); 
}
.fade-carousel .slides .slide-2 {
  background-image: url(assets/img/tomato.jpg);
}
.fade-carousel .slides .slide-3 {
  background-image: url(assets/img/bsket3.jpg);
}
.fade-carousel .slides .slide-4 {
  background-image: url(assets/img/DSC04758.JPG);
}

/********************************/
/*          Media Queries       */
/********************************/
@media screen and (min-width: 980px){
    .hero { width: 980px; }    
}
@media screen and (max-width: 640px){
    .hero h1 { font-size: 4em; }  
</style>
