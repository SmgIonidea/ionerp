<?php
/*
 * Description	:	Tier II CO Level Attainment View

 * Created		:	December 14th, 2015

 * Author		:	 Shivaraj B

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<style>
.bold { 
      font-weight: bold;
}
.space {
    margin-left: 450px;
}
</style>
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
                          <?php echo $this->lang->line('entity_cie'); ?> - <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Attainment
                        </div>
                    </div>
                    <div class="bs-example bs-example-tabs">
				 <form  method="POST" id="add_form" name="add_form" target='_blank' class="form-inline"  action="<?php echo base_url('tier_ii/co_section_level_attainment/export_to_doc'); ?>">
					
					<!-- <form  method="POST" id="indirect_direct_attainment_form" name="indirect_direct_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/tier1_section_clo_attainment/export_to_doc'); ?>">-->
					
                        <table style="width:100%; overflow:auto;">
                            <tr>
                                <td>
                                    <p>
                                        Curriculum :<font color='red'>*</font> 
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
                                    <p >
                                        Term :<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 18); ?>   
                                        <select style="width:210px;"  id="term" name="term" class="input-medium" onchange="select_course();">
                                            <option value="">Select Term</option>
                                        </select>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        Course :<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 4); ?>
                                        <select  id="course" name="course" class="input-large" onchange="select_section();">
                                            <option value="0">Select Course</option>
                                        </select>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        Section :<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 4); ?>
                                        <select id="section_id" name="section_id" class="input-large section_id" onchange="">
                                          <!--  <option value="0">Select Section</option>-->
                                        </select>
                                    </p>
                                </td> 
                                <td>
                                         <?php echo $this->lang->line('entity_cie'); ?>  Occasion :<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 2); ?>
                                           <select id="occasion" name="occasion" class="input-large occasions_list multiselect"  multiple="multiple" > </select>
                                </td>
								<td>
									<div class="control-group span5 form-horizontal_new">
                                        <button type="button" disabled='disabled' id="export_doc" class="export_doc btn-fix btn btn-success" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
                                        <input type="hidden" name="type_id" id="type_id" value="2" />
                                        <input type="hidden" name="form_name" id="form_name" value="" />
                                    </div>
								</td>
                            </tr>
                        </table>
							
                        <div id="myTabContent" class="tab-content">
                            <!-- Tab one - Course Plan starts here -->
                            <div class="tab-pane fade in active" id="direct_attainment">
                                       <div id="export_file_content">  
							   <div id="main_div">
									<div id="before_finalized_data" >
										<div id="co_level_nav"></div>
										<div id="chart_plot_1"></div>
										<div id="co_level_attain_data"></div>
									</div>
                                    <div id="finalize_attainment"> </div>
                                    <div id="course_outcome_attainment_graph_data" style="display:none;"></div>
                                    <input type="hidden" id="course_outcome_attainment_graph_data_hidden" name="course_outcome_attainment_graph_data_hidden" />
                                    <div id="course_outcome_contribution_graph_data" style="display:none;"></div>
									
									<div id= "finalised_data" class="span12"><br/>		
										
		                                <div class="span6"> <div id="Attainment_List_nav_co"> </div> <div id="section_finalize_status_tbl"></div></div>
										
										<div class="span6"><div id="Attainment_List_nav_targets"> </div><div id="cla_data_table"> </div></div>
                                    </div>
								</div>
							</div>

                                <div id="view_ques_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Assessment details
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div id="co_questions">
                                        </div>
                                    </div>
                                    <input type="hidden" name="error_dialog" id="error_dialog" /> 
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                                    </div>
                                </div>
                                <!--Modal for CO drill down-->
                                <div  style="width: 50%;" id="view_drilldown_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) drill down
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
									    <div id="meta_data" class="span12 controls" style="margin: 0px;">
											<label class="span5">Curriculum: <font color="blue" id="clo_crclm_name"></font></label>
											<label class="span3">Term: <font color="blue" id="clo_term_name"></font></label>
											<label class="span4">Course: <font color="blue" id="clo_crs_name"></font></label>
										</div>
                                        <div id="dridown_perc"></div>
                                    </div>
                                    <input type="hidden" name="error_dialog" id="error_dialog" /> 
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                                    </div>
                                </div>
								
             

                                <div id="finalize_direct_indirect_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Finalize Attainment Confirmation
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <p> Course - <?php echo $this->lang->line('entity_cie'); ?> <?php echo $this->lang->line('entity_clo_full'); ?>(<?php echo $this->lang->line('entity_clo'); ?>) Attainment for the select Section / Division are finalized and updated successfully.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cancel cancel_button btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                    </div>
                                </div>
                            </div>
							
                            <!-- Tab one - Course Plan ends here -->	
                        </div>
								<!--<input type="hidden" name="head_data" id="head_data" value="" />-->
								<div style="display:none;" name="head_data" id="head_data" ></div>
								<input type= "hidden" name ="table_data" id="table_data" value=""/>
								<input type="hidden" name="export_data_to_doc" id="export_data_to_doc" value="" />
                                <input type="hidden" name="export_graph_data_to_doc" id="export_graph_data_to_doc" value="" />
								<input type="hidden" name="main_head" id="main_head" value="" />
								
	
						</form>
						
						                   <!--finalize-->
                                <div id="myModalfinalize" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Finalize Attainment Confirmation
                                        </div>
                                    </div>	
                                    <div class="modal-body">
                                        <p>Are you sure you want to Finalize the Overall the selected Section / Division, Course - <?php echo $this->lang->line('entity_cie'); ?>  <?php echo $this->lang->line('entity_clo_full'); ?>(<?php echo $this->lang->line('entity_clo'); ?>) Attainment for the Final Submit ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="finalize_clo_attainment btn btn-primary " id="finalize_clo_attainment"><i class="icon-ok icon-white" data-dismiss="modal"></i> Ok</button>
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div><!--Finalize Modal-->
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
                        <p>The Overall<?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Attainment are finalized and updated successfully (includes Direct & Indirect Attainment).</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel cancel_button btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
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
                        <p>Are you sure you want to Finalize the Overall <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Attainment (includes Direct & Indirect Attainment) for Final Submit to calculate PO Attainment ?</p>
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
    var entity_co_single = "<?php echo $this->lang->line('entity_clo_singular'); ?>";
    var entity_tee = "<?php echo $this->lang->line('entity_tee'); ?>";
	var entity_clo_full_singular = "<?php echo $this->lang->line('entity_clo_full_singular'); ?>";
	var entity_clo_full = "<?php echo $this->lang->line('entity_clo_full'); ?>";
    var course_outcome = "<?php echo $this->lang->line('entity_clo_full'); ?> <?php echo '('.$this->lang->line('entity_clo').')'; ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/co_section_level_attainment/co_section_level_attainment.js'); ?>"></script>

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
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js');  ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js');  ?>"></script>
