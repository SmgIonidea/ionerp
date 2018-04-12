<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:
 * Date				Modified By				Description
 * 2-08-2016                    Mritunjay B S                	        Course  Attainemnt Data view.
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
                                Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Level Attainment (Course <?php echo $this->lang->line('entity_see'); ?> & <?php echo $this->lang->line('entity_cie'); ?>)
                            </div>
                        </div>
                        <div class="bs-example bs-example-tabs">
                             <form  method="POST" target="_blank" id="indirect_direct_attainment_form" name="indirect_direct_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/course_level_assessment_data/export_to_pdf_direct_indirect_attainment'); ?>">
                                 <div class="row-fluid">
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Curriculum:</label>
                                       <div class="controls">
                                         <?php
                                            foreach ($crclm_data as $listitem2) {
                                                $select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name']; //group name column index
                                            }
                                            if (!isset($select_options1))
                                                $select_options1['0'] = 'No Curriculum to display';
                                            echo form_dropdown('tier1_crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'class="required input-large" id="tier1_crclm_id" autofocus = "autofocus"');
                                            ?>
                                       </div>
                                   </div>
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Term:</label>
                                       <div class="controls">
                                         <select id="tier1_term" name="tier1_term" class="input-large" >
                                                <option value="">Select Term</option>
                                            </select>
                                       </div>
                                   </div>
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Course:</label>
                                       <div class="controls">
                                            <select id="tier1_course" name="tier1_course" class="input-large">
                                                <option value="0">Select Course</option>
                                            </select>
                                       </div>
                                   </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Section:</label>
                                       <div class="controls">
                                         <select id="tier1_section" name="tier1_section" class="input-large tier1_section">
                                                <option value="0">Select Section</option>
                                            </select>
                                       </div>
                                   </div>
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Type:</label>
                                       <div class="controls">
                                         <select id="type_data" name="type_data" class="input-large">	
                                                <option>Select Type</option>
                                            </select>
                                       </div>
                                   </div>
                                    <div class="control-group span4 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Occasion:</label>
                                       <div class="controls">
                                            <select id="occasion" name="occasion" class="input-large">	
                                                    <option>Select Occasion</option>
                                                </select>
                                       </div>
                                   </div>
                                </div>
                                 <br>
                                 <div id="clo_finalize_data_div" style="display:none;">
                                     <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment finalized 
                                        </div>
                                    </div>
                                     <div id="co_finalized_tbl_div">
                                         
                                     </div>
                                      <br>
                                 </div>
                                 
                                 
                                 <div id="po_attainment_div" style="display:none;">
                                     <div class="navbar">
                                        <div class="navbar-inner-custom">
                                          <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Attainment Through CIA By This Section.
                                        </div>
                                    </div>
                                     <div id="po_attainment">
                                         
                                     </div>
                                      <br>
                                 </div>
                                
                                 
                                 <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment
                                        </div>
                                </div>
                                 <div class="row-fluid" id="chart1"   class="chart1">
                                 </div>
                                 <div class="row-fluid" id="course_clo_attaiment_div">
                                 </div>
                                 <br>
                                 <div style="display: none;" class="pull-right" id="finalize_div">
                                     <button name="clo_attainment_finalize" id="clo_attainment_finalize" type="button" class="btn btn-success"><i class="icon-ok icon-white"></i> Finalize Attainment</button>
                                 </div>
                                 <br>
                                 <br>
                            </form>
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
                    
                    <!--finalize-->
                                <div id="section_attainment_finalize" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Finalize Attainment Confirmation
                                        </div>
                                    </div>	
                                    <div class="modal-body">
                                        <p>Are you sure you want to Finalize the Overall Course Outcomes (COs) Attainment for Final Submit to calculate <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Attainment ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="finalize_clo_attainment btn btn-primary " id="finalize_clo_attainment"><i class="icon-ok icon-white" data-dismiss="modal"></i> Ok</button>
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div><!--Finalize Modal-->
                    
                                <div id="clo_attainment_final_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Finalize Attainment Confirmation
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <p>The Course Outcomes (COs) Attainment for the Section are finalized and updated successfully.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                    </div>
                                </div>
                                
                                <div id="clo_attainment_final_unsuccess" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Finalize Attainment Confirmation
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <p>The finalization of Course Outcomes (COs) Attainment for the Section are unsuccessful.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                    </div>
                                </div>
                    <!--Modal to display the "CLO Details "-->
                    <div id="clo_details_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Course Outcome (CO) Assessment Details.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Curriculum: <font color="blue" id="crclm_name"></font></label>
                                <label class="span5">Term: <font color="blue" id="term_name"></font></label>
                            </div>
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Course: <font color="blue" id="crs_name"></font></label>
                                <label class="span5">Section: <font color="blue" id="sec_name"></font></label>
                            </div>
                            <div id="clo_statement_table">
                                
                            </div>
                                                    </div>
                        <input type="hidden" name="error_dialog" id="error_dialog" /> 
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                        </div>
                    </div>
                    
                     <!--Modal to display the "Attainment occasion wise "-->
                    <div id="attainmnet_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    CO Attainment Occasion Wise.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Curriculum: <font color="blue" id="drill_crclm_name"></font></label>
                                <label class="span5">Term: <font color="blue" id="drill_term_name"></font></label>
                            </div>
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Course: <font color="blue" id="drill_crs_name"></font></label>
                                <label class="span5">Section: <font color="blue" id="drill_sec_name"></font></label>
                            </div>
                            <div id="clo_statement">
                                
                            </div>
                            <div id="cia_weight" class="span5">
                                <b> CIA Weightage: </b> <font id="cia"></font>
                            </div>
                            <div id="tee_weight" class="span5">
                                <b> TEE Weightage: </b> <font id="tee"></font>                                 
                            </div>
                            <hr>
                            <div id="display_attainment_div">
                                
                            </div>
                        </div>
                        <input type="hidden" name="error_dialog" id="error_dialog" /> 
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
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
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_i/tier1_clo_overall_attainment.js'); ?>"></script>

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
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
