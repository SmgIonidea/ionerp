<?php $this->load->view('includes/head'); ?>
<!--css for animated message display-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
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
						 <form class="form-horizontal" method="POST" target="_blank" id="po_attainment_within_batch_form" name="po_attainment_within_batch_form" action="<?php echo base_url('tier_ii/tier2_po_bacth_wise_attainment/export_to_doc'); ?>">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                           <?php echo $title; ?>
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
                    <table style="width:100%;">
                        <tr>
                            <td>
                                <label>Curriculum: <span style="color:red;">*</span>
                                    <select name="curriculum_data" id="curriculum_data" class="curriculum_name form-control">
                                        <option value="">Select Curriculum</option>
                                        <?php echo $options; ?>

                                    </select></label>
                            </td>
							
									<td>
                                        <p> 
											<button type="button"  disabled="disabled" id="export_doc" class="export_doc btn-fix btn btn-success" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
											<input type="hidden" name="type_id" id="type_id" value="2" />
											<input type="hidden" name="form_name" id="form_name" value="" />
									    </p>
                                    </td>
                        </tr>
                    </table>
						<input type="hidden" name="export_data_to_doc" id="export_data_to_doc" value="" />				
									<input type="hidden" name="export_graph_data_to_doc" id="export_graph_data_to_doc" value="" />
									<input type="hidden" name="file_name" id="file_name" value="" />
									<input type="hidden" name="main_head" id="main_head" value="" />
									<!--<input type="hidden" name="po_attainment_graph_data" id="po_attainment_graph_data" value="" />	-->
									<div style="display:none" id="po_attainment_graph_data"></div>
                    <div class="bs-example bs-example-tabs">
                        <span id="status_msg"></span>
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active"><a href="#crclm_batch_wise" data-toggle="tab"><?php echo $tab1_title; ?></a></li>
                           <!-- <li id="crclm_div_data"><a href="#across_batch_crclm" data-toggle="tab"><?php echo $tab2_title; ?></a></li> -->
                            <!-- <li id="perf_div_data"><a href="#year_wise" data-toggle="tab"><?php echo $tab3_title; ?></a></li> -->
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            
                             <!-- Tab one - Curriculum wise PO report tab starts here -->
                            <div class="tab-pane fade active in" id="crclm_batch_wise">
                                <div id="po_populate_div">
                                     <label><?php echo $this->lang->line('student_outcome_full'); ?>: <span style="color:red;">*</span>
                                    <select name="po_data" id="po_data" class="po_data form-control input-medium">
                                        <option value="">Select <?php echo $this->lang->line('so'); ?></option>
                                        <?php //echo $po_options; ?>

                                    </select></label>
                                </div>
                                <br>
                                <!-- display Curriculum Level Assessment -->
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Curriculum (Within Batch)  <?php echo $this->lang->line('so'); ?> Report.  
                                    </div>
                                </div>
                                <div style="display: block; position: relative; height: 300px;" class="row-fluid jqplot-target" id="po_attain_chart1">
                                    
                                </div>
                                <br>
                                <div id="po_attain_table_plot_div" class="po_attain_table_plot_div">
                                    
                                </div>
                            </div><!--tab1 main div ends -->
                            
                            <!-- Tab two - Across Curriculum Level PO report starts here
                            <div class="tab-pane fade" id="across_batch_crclm">
                                <div id="po_populate_div_one">
                                     <label><?php echo $this->lang->line('student_outcome_full'); ?>: <span style="color:red;">*</span>
                                    <select name="po_data_one" id="po_data_one" class="po_data_one form-control input-medium">
                                        <option value="">Select <?php echo $this->lang->line('so'); ?></option>
                                        <?php //echo $po_options; ?>

                                    </select></label>
                                </div>
                                <br>
                             Comment  display Curriculum Level Assessment 
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Across Curriculum (Batch Wise ) <?php echo $this->lang->line('so'); ?> Report.
                                    </div>
                                </div>
                                
                            </div>  tab2 main div ends -->
                            <!-- tab two ends here -->
                            <!-- Tab three - Year wise PO report 
                            <div class="tab-pane fade" id="year_wise">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        
                                    </div>
                                </div>
                                
                            </div>
                            <!--Tab three ends here -- >
                          
                            <!--Tab four ends -->
                        </div>
                    </div>
					<div>
                </form>
                <br/>
        </div>
    </div><!--End of span10-->
</div><!--row-fluid-->
</div><!--container-fluid-->


<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/po_batch_wise_attainment/tier2_po_batch_wise_attainment.js'); ?>" type="text/javascript"></script>
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
	<script>
	var po = "<?php echo $this->lang->line('so'); ?>";
	</script>