<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">
<!--<div class="container">-->
<div class="container-fluid">
	<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 227px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 5px;">
	<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" style="float:right;"/>
	<center><b style="text-shadow: 2px 2px black; color: white; font-size: 18px; margin-top: 10px;"> <?php echo $organisation_detail['0']['org_name'];?></b> <br>
	</center>
	
</div>
</header>
<style type="text/css">
.margin-left5 {
    margin-left: 25px !important;
}
.margin-right5 {
    margin-right: 5px !important;
}
.margin-radio{
    margin-top: 0px !important;
}
</style>

<!-- Navbar here -->
<?php //$this->load->view('includes/navbar'); ?> 

<style type="text/css">
    
</style>
<base href="<?php echo base_url(); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/survey.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<div class="container-fluid"> 
    <div class="row-fluid">
        <!--Side navigator-->
        <?php //$this->load->view('survey/layout/sidebar'); ?> 
        <div class="span12">            
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <div class="row-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">                                
                                <?php
                                echo ($this->layout->navBarTitle) ? ucwords($this->layout->navBarTitle) : '';
                                ?>
                            </div>
                        </div>
                        <?php echo $content; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/tab.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/survey.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
</body>
</html>