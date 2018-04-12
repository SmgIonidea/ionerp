<?php $this->load->view('includes/head'); ?>
<!--css for animated message display-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/html_to_word/jquery_html_to_word.css'); ?>" media="screen" />

<style type="text/css">
    .large_modal{
        width:70%;
        margin-left: -35%; 
    }
</style>

<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_5'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <?php if ((!$this->ion_auth->in_group('Course Owner')) || $this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
                    <input type="hidden" name="logged_in_user" id="logged_in_user" value="2" />
                <?php } else if ($this->ion_auth->in_group('Course Owner')) { ?>
                    <input type="hidden" name="logged_in_user" id="logged_in_user" value="1" />
                <?php } ?>

                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
							Program Level Course - <?php echo $this->lang->line('so'); ?> & <?php echo $this->lang->line('entity_pso'); ?> Attainment Matrices Report.  
						</div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <form action="<?php //echo base_url('assessment_attainment/course_po_attainment_matrix/export_doc'); ?>" method="post">
                    <input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
                    <input type="hidden" name="doc_data" id="doc_data" />
                    <table style="width:100%;">
                        <tr>
                            <td style="width: 340px;">
                                <label>Curriculum: <span style="color:red;">*</span>
                                    <select name="curriculum_data" id="curriculum_data" class="curriculum_name form-control">
                                        <option value="">Select Curriculum</option>
                                        <?php echo $options; ?>

                                    </select></label>
                            </td>
                            <td>
                                <label>Type: <span style="color:red;">*</span>
                                    <select name="occasion_type" id="occasion_type" class="occasion_type form-control input-medium">
                                        <option value="">Select</option>

                                    </select></label>
                            </td>
                            <td>
                                <button type="button" id="export_doc" disabled="disabled" class="export btn-fix btn pull-right btn-success" abbr="0">
                                    <i class="icon-book icon-white"></i> Export .doc
                                </button>
                            </td>
                        </tr>
                    </table>
			</form>		<br>
					 <div id="po_attainment_report_div">
					 </div>
                                        
                                        <br><br><br><br>
                </div>
                <br/>
        </div>
    </div><!--End of span10-->
</div><!--row-fluid-->
</div><!--container-fluid-->


<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/course_po_attainment_matrix.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/html_to_word/FileSaver.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/html_to_word/jquery.wordexport.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisLabelRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js');  ?>"></script>
    <script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js');  ?>"></script>