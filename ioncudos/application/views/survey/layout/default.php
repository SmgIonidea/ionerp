<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 

<style type="text/css">
    
</style>
<base href="<?php echo base_url(); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/survey.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/survey_progress_bar.css'); ?>" />
<div class="container-fluid"> 
    <div class="row-fluid">
        <!--Side navigator-->
        <?php $this->load->view('survey/layout/sidebar'); ?> 
        <div class="span10">            
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
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisLabelRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.sizes.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/excanvas.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/tab.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery-ui-p.js'); ?>"></script>

<script src="<?php echo base_url('twitterbootstrap/js/custom/survey.js'); ?>"></script>   
  
<script src="<?php echo base_url('twitterbootstrap/js/custom/survey_dashboard.js'); ?>"></script>     
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
</body>
</html>	  
<style>
 .ui-widget-header {
	background: #6c9cdd;
	border: 1px solid #DDDDDD;
	color: #0276FD;
	font-weight: bold;
 }
</style>