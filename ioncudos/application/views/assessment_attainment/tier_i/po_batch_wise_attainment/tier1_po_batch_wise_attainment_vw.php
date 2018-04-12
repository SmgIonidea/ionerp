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
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                           <?php echo $title; ?>
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <form method="post" action="<?php echo base_url('assessment_attainment/tier1_po_bacth_wise_attainment/export_doc'); ?>">
                    <input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
                    <input type="hidden" name="batchwise_attainmetn_graph" id="batchwise_attainmetn_graph" />
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
                                <button type="submit" id="export_doc" disabled="disabled" class="export btn-fix btn pull-right btn-success" abbr="0">
                                    <i class="icon-book icon-white"></i> Export .doc
                                </button>
                            </td>
                        </tr>
                    </table>
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
                                        Curriculum (Within a Batch)  <?php echo $this->lang->line('so'); ?> Report.  
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
                    </form>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_i/tier1_po_batch_wise_attainment.js'); ?>" type="text/javascript"></script>
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