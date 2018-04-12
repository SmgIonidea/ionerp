<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:
 * Date				Modified By				Description
 * 25-08-2014		Jevi V G     	        Added help entity.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
    <!--branding here-->
    <?php $this->load->view('includes/branding'); ?>
    <!-- Navbar here -->
    <?php $this->load->view('includes/navbar'); ?> 
    <div class="container-fluid">
        <div class="row-fluid">
            <!--sidenav.php-->
            <?php $this->load->view('includes/sidenav_5'); ?>
            <div class="span10">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <!-- Contents -->
                <section id="contents">
                    <div class="bs-docs-example" >
                        <!--content goes here-->
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment (Course <?php echo $this->lang->line('entity_see'); ?> & <?php echo $this->lang->line('entity_cie'); ?>)
                            </div>
                        </div>
                        <div class="bs-example bs-example-tabs">
                            <table style="width:100%; overflow:auto;">
                                <tr>
                                    <td>
                                        <p>
                                            Curriculum:<font color='red'>*</font> 
                                            <?php
                                            foreach ($crclm_data as $listitem2) {
                                                $select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name']; //group name column index
                                            }
                                            if (!isset($select_options1))
                                                $select_options1['0'] = 'No Curriculum to display';
                                            echo form_dropdown('crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'onchange="select_term();" class="required input-large" id="crclm_id" autofocus = "autofocus"');
                                            ?>

                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Term:<font color='red'>*</font> 
                                            <select id="term" name="term" class="input-medium" onchange="select_course();">
                                                <option value="">Select Term</option>
                                            </select>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Course:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 6); ?>
                                            <select id="course" name="course" class="input-large" onchange="select_type();">
                                                <option value="0">Select Course</option>
                                            </select>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#direct_attainment" data-toggle="tab"> Direct Attainment </a></li>
                                <li><a href="#indirect_attainment" data-toggle="tab"> Indirect Attainment </a></li>
                                <li><a href="#chapter_wise_plan" data-toggle="tab"> Direct and Indirect Attainment </a></li>

                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <!-- Tab one - Course Plan starts here -->
                                <div class="tab-pane fade in active" id="direct_attainment">
                                    <form  method="POST" target="_blank" id="add_form" name="add_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/course_level_assessment_data/export_to_pdf'); ?>">

                                        <label>
                                            Type:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 5); ?>
                                            <select id="type_data" name="type_data" class="input-medium">	
                                                <option>Select Type</option>
                                            </select>
                                        </label><?php echo str_repeat('&nbsp;', 9); ?>
                                        <div id="occasion_div" style="display:none;">
                                            <label>
                                                Occasion:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 2); ?>
                                                <select id="occasion" name="occasion" class="input-large">	
                                                    <option>Select Occasion</option>
                                                </select>
                                            </label></div>
                                        <div id="actual_data_div"></div>
                                        <div id="co_level_nav"></div>
                                        <div id="chart_plot_1"></div>
                                        <div id="co_level_student_threshold_nav"></div>
                                        <div id="chart_plot_5"></div>
                                        <div id="course_attainment_nav"></div>

                                        <div id="chart_plot_2"></div>
                                        <br />
                                        <div id="bloom_level_nav">                                           
                                        </div>

                                        <div id="chart_plot_3"></div>
                                        <br />
                                        <div id="cumulative_performance_nav">                                           
                                        </div>								

                                        <div id="chart_plot_4">
                                        </div>

                                        <div id="CLO_PO_attainment_nav">
                                        </div>

                                        <div id="PO_ref_nav">
                                        </div>

                                        <div id="chart_plot" ></div>

                                        <!--<div id="CLO_PO_attainment_note" class="span12">
                                        </div> -->
                                        <div id="course_outcome_attainment_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="course_outcome_attainment_graph_data_hidden" name="course_outcome_attainment_graph_data_hidden" />
                                        <div id="course_outcome_contribution_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="co_contribution_graph_data_hidden" name="co_contribution_graph_data_hidden" />
                                        <div id="bloom_level_distribution_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="bloom_level_distribution_hidden" name="bloom_level_distribution_hidden" />
                                        <div id="bloom_level_cumm_perf_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="bloom_level_cumm_perf_graph_hidden" name="bloom_level_cumm_perf_graph_hidden" />				
                                        <!--<div id="co_po_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="co_po_graph_hidden" name="co_po_graph_hidden" /> -->

                                    </form>
                                </div>
                                <!-- Tab one - Course Plan ends here -->
                                <!-- Tab two - Course Content starts here -->
                                <div class="tab-pane fade" id="indirect_attainment">
                                    <form  method="POST" target="_blank" id="indirect_attainment_form" name="indirect_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/course_level_assessment_data/export_to_pdf_indirect_attainment'); ?>">
                                        <label>
                                            Survey:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 6); ?>
                                            <select id="survey_id" name="survey_id" class="input-xlarge" >
                                                <option value="0">Select Survey</option>
                                            </select>
                                        </label><?php echo str_repeat('&nbsp;', 13); ?>
                                        <div id="co_level_indirect_nav" style="height:auto; width:100%;">

                                        </div>
                                        <div id="chart_plot_indirect_attain"></div>
                                        <div id="graph_val" class="center-block" style="height:auto; width:100%;margin:0 auto;">

                                        </div>
                                        <div id="course_outcome_indirect_attainment_graph_data" style="display:none;"></div>
                                        <input type="hidden" id="course_outcome_indirect_attainment_graph_data_hidden" name="course_outcome_indirect_attainment_graph_data_hidden" />


                                    </form>

                                </div>
                                <!-- Tab two - Course Content ends here -->
                                <!-- Tab three - Course wise Plan starts here -->
                                <div class="tab-pane fade" id="chapter_wise_plan">
                                    <form  method="POST" target="_blank" id="indirect_direct_attainment_form" name="indirect_direct_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/course_level_assessment_data/export_to_pdf_direct_indirect_attainment'); ?>">
                                        <table class="table" style="text-align: center; border:0px;">
                                            <tr>
                                                <td style="text-align: center; border:0px;">
                                                    <p>
                                                        Type:<font color='red'>*</font> 
                                                        <select id="type_data_survey" name="type_data_survey" class="input-small" onchange="select_survey_comparison();" >
                                                            <option>Select Type</option>
                                                        </select>
                                                    </p>
                                                </td>
                                                <td style="text-align: center; border:0px;">
                                                    <p id="survey_occasion_div" style="display:none;">
                                                        Occasion:<font color='red'>*</font> 
                                                            <select id="survey_occasion" name="survey_occasion" class="input-small">	
                                                                <option>Select Occasion</option>
                                                            </select>
                                                    
                                                    </p>
                                                </td>
                                                <td style="text-align: center; border:0px;">
                                                    <p>
                                                        Survey:<font color='red'></font> 
                                                        <select id="comparison_survey_id" name="comparison_survey_id" class="input-large" >
                                                            <option value="0">Select Survey</option>
                                                        </select>
                                                    </p>
                                                </td>
                                                <td style="text-align: center; border:0px;">
                                                    <p><?php echo str_repeat('&nbsp;', 6); ?>Direct :<font color="red"><b>*</b></font>
                                                        <input type="text" name="direct_attainment_val" value="00" style="text-align: right;" id="direct_attainment_val" class="input-mini required " placeholder="Enter Direct Attainment">
                                                        <label>%</label>
                                                    </p>
                                                </td>
                                                <td style="text-align: center; border:0px;">
                                                    <p><?php echo str_repeat('&nbsp;', 6); ?>Indirect :<font color="red"><b>*</b></font>
                                                        <input type="text" name="indirect_attainment_val" value="00" style="text-align: right;" id="indirect_attainment_val" class="input-mini required" placeholder="Enter Indirect Attainment">
                                                        <label>%</label>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
										
                                        <a id="direct_indirect_submit" href="#" disabled="disabled" class="pull-right btn btn-primary direct_indirect_submit"><i class="icon-ok icon-white"></i> Submit </a>
										
										<div class="clearfix"></div> 

                                        <div id="survey_occasion_actual_data_div">
                                        </div>
                                        <div id="co_level_comparison_nav">                                           
                                        </div>
                                        <div id="chart_plot_7"></div>					
										<div class="clearfix"></div> 
                                        <div id="finalize_direct_indirect_div" style="visibility:hidden">								
                                            <a id="finalize_direct_indirect" href="#" class="pull-right btn btn-success finalize_direct_indirect"><i class="icon-ok icon-white"></i> Finalize Attainment </a>
                                        </div>
										<div class="clearfix"></div> <br />
										<div id="co_attain_data_finalized_div"></div>
										<div id="po_attain_data_div"></div>
                                        <!--Div for graph -->
                                        <div id="direct_indirect_attain_data" style="display:none"></div>
                                        <input  type="hidden" name="direct_indirect_attain_data_hidden" id="direct_indirect_attain_data_hidden"/> 
                                    </form>
                                </div>
                                <!-- Tab three - Course wise Plan ends here -->

                            </div>
                        </div>

                    </div>

                    <!--Modal to display the message "Curriculum not selected needs your attention"-->
                    <div id="error_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Make sure that you select all the drop-downs before proceeding. </p>
                        </div>
                        <input type="hidden" name="error_dialog" id="error_dialog" /> 
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                        </div>
                    </div>
                    <!--Modal to Finalize Attainment confirmation-->
                    <div id="finalize_direct_indirect_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Finalize Attainment Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>The Overall Course Outcomes (COs) Attainment are finalized and updated successfully (includes Direct & Indirect Attainment).</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <!--Modal to Finalize Attainment confirmation-->
                    <div id="finalize_direct_indirect_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="finalize_direct_indirect_confirmation" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Finalize Attainment Confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to Finalize the Overall Course Outcomes (COs) Attainment (includes Direct & Indirect Attainment) for Final Submit to calculate <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Attainment ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="finalize_direct_indirect_confrim" class="finalize_direct_indirect_confrim btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>		
                </section>
            </div>
        </div>
    </div>
    <!--</div>-->
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script>
        var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
        var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
        var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
        var course_outcome = "<?php echo $this->lang->line('entity_clo_full'); ?> <?php echo '('.$this->lang->line('entity_clo').')'; ?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/course_attainment.js'); ?>"></script>

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
