<?php
/*
------------------------------------------------------------------------------------------------------------------------
* Description	: Topic list view page, provides the fecility to view the Topic Contents.
* Modification History:
* Date				Modified By				Description
* 
------------------------------------------------------------------------------------------------------------------------
*/
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
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
				<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
			</div>
			<!-- Contents -->
			<section id="contents">
				<div class="bs-docs-example" >
					<!--content goes here-->
					<div class="navbar">
						<div class="navbar-inner-custom">
							Student <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Attainment (<?php echo $this->lang->line('entity_cie'); ?> & <?php echo $this->lang->line('entity_see'); ?>)
						</div>
					</div>
					
					<form class="form-horizontal" method="POST" target="_blank" id="add_form" name="add_form" action="<?php echo base_url('assessment_attainment/po_level_assessment_data/export_to_pdf');  ?>">
						
						
						<div class="row-fluid">
							<table style="width:100%; overflow:auto;">
								<tr>
									<td>
										<p><label>Curriculum :<font color='red'>*</font> 
											<?php
    											foreach ($crclmlist as $listitem2) {
    												$select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name']; 
  													//group name column index
												}
    											if(!isset($select_options1))
												$select_options1['0'] = 'No Curriculum to display';
    											echo form_dropdown('crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'onchange="select_term();" "class="required target" id="crclm_id" autofocus = "autofocus"');
											?>	
										</label>
										<input type="hidden" name="po_attainment_type" id="po_attainment_type" value="<?php echo $po_attainment_type[0]['value']; ?>"/> </p>
									</td>
									<!-- <td>
										<p>
											<input type="checkbox" name="core_course" id="core_course" onChange= "setToggleStatusValues();" value="core" ><b>&nbsp;&nbsp;Core Courses </b>
										</p>
									</td> -->
									<td>
										<p>
											<label>Term :<font color='red'>*</font> 
												<select id="term" name="term[]" class="example-getting-started term_id required" onchange="select_type();" style="margin-top:8px;" multiple="multiple">
													<option value="">Select Term</option>
												</select>
											</label>
											<input type="hidden" name="core_crs_id" id="core_crs_id" value="0" size="1" class="required input-mini"/>
										</p>
									</td>
									<td>
										<p><label>Type :<font color='red'>*</font> 
											<select id="type_data" name="type_data" class="input-medium" onchange="select_usn();">	
												<option>Select Type</option>
											</select>
										</label></p>
									</td>	
									<td>
										<p><label>Student :<font color='red'>*</font> 
											<select id="stud_usn" name="stud_usn" class="input-medium cursor_pointer" onchange="display();">	
												<option>Select USN</option>
											</select>
										</label></p>
									</td>
								</tr>
							</table>
						</div>
						<ul id="myTab" class="nav nav-tabs">
							<li class="active"><a href="#po_direct_attainment" data-toggle="tab"> Direct Attainment </a></li>
							</ul>								
						<div id="myTabContent" class="tab-content">
							<!-- Tab one - Course Plan starts here -->
							<div class="tab-pane fade in active" id="po_direct_attainment">
								<div id="po_attainment_nav">
								</div>
								<div id="po_attainment_no_data" ></div>  
								<div id="po_attainment_chart_1"></div>
								</br>
								<div id="po_attainment_graph_data" style="display:none;"></div>
								<input type="hidden" id="po_attainment_graph_data_hidden" name="po_attainment_graph_data_hidden" />
								<div id="course_attainment_graph_data" style="display:none;"></div>
								<input type="hidden" id="course_attainment_graph_data_hidden" name="course_attainment_graph_data_hidden" />
							</form>
						</div>
						<!-- Tab one - Course Plan ends here -->
						<!-- Tab two - Course Content starts here -->
						<div class="tab-pane fade" id="po_indirect_attainment">
							<form  method="POST" target="_blank" id="indirect_attainment_form" name="indirect_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/po_level_assessment_data/export_to_pdf_indirect_attainment');  ?>">
								<label>
									Survey :<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 6); ?>
									<select id="survey_id" name="survey_id" class="input-xlarge" >
										<option value="0">Select Survey</option>
									</select>
								</label><?php echo str_repeat('&nbsp;', 13); ?>
								<div id="po_level_indirect_div" style="height:auto;">
								</div>
								<div id="chart_plot_po_indirect_attain"></div>
								<div id="graph_val">
								</div>
								<div id="po_indirect_attainment_graph_data" style="display:none"></div>
								<input type="hidden" name="po_indirect_attainment_graph_data_hidden" id="po_indirect_attainment_graph_data_hidden" />
							</form>
						</div>
						<!-- Tab two - Course Content ends here -->
						
						<!-- Tab three - Course wise Plan starts here -->
						<div class="tab-pane fade" id="po_direct_indirect_report">
							<form  method="POST" target="_blank" id="po_indirect_direct_attainment_form" name="po_indirect_direct_attainment_form" action="<?php echo base_url('assessment_attainment/po_level_assessment_data/export_to_pdf_direct_indirect_attainment');  ?>">
								<table>
									<tr>
										<td>
											<p><?php echo str_repeat('&nbsp;', 6); ?>Direct Attainment :<font color="red"><b>*</b></font><?php echo str_repeat('&nbsp;', 6); ?>
												<input type="text" name="direct_attainment_val" value="0" style="text-align: right;" id="direct_attainment_val" class=" input-mini required " placeholder="Enter Direct Attainment">&nbsp; %
												
											</p>
										</td>
										<td>
											<p for="indirect_attainment_val"><?php echo str_repeat('&nbsp;', 6); ?>Indirect Attainment :<font color="red"><b>*</b></font><?php echo str_repeat('&nbsp;', 6); ?>
												<input type="text" name="indirect_attainment_val" value="0" style="text-align: right;" id="indirect_attainment_val" class=" input-mini required" placeholder="Enter Indirect Attainment"/>&nbsp; %
											</p>
										</td>
										<td>
											<p><?php echo str_repeat('&nbsp;', 6); ?>
																						</p>
										</td>
									</tr>
								</table>

								<div id="direct_indirect_attain_data">
									<div id="msg"></div>
									<div id="err_msg"></div>
									<div id="add_more_err"></div>
									<table class="table table-bordered"  id="add_more_survey_rows">
										<thead>
											<tr>
												<th><center>Sl No.</center></th>
												<th><center>Survey Name : <span class='err_msg'>*</span></center></th>
												<th><center>Weightage % : <span class='err_msg'>*</span></center></th>												
												<th><center>Delete</center></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><center>1.</center></td>
												<td>
													<center>
														<select name="survey_title_shv_1" id="survey_title_shv_1" 
														class="survey_title_shv">
															<option value="">Select Survey</option>
															<?php
																if(!empty($survey_data)){
																	foreach ($survey_data as $value) {
																		echo "<option value='".$value['survey_id']."'>".$value['name']."</option>";
																	}
																	}else{
																	echo "<option>No survey found</option>";
																}
															?>
														</select> 
													</center>
												</td>	
												<td><center><input type="text" style="text-align: right;" autocomplete="off" name="survey_wgt_perc_1"  id="survey_wgt_perc_1"  class="onlyDigit max_wgt input-large" />&nbsp;%</center></td>						
												<td><center><i id="tooltip" class="icon icon-remove disabled" title="Delete disabled" data-toggle="tooltip" data-placement="top"></i></center></td>
											</tr>
										</tbody>
										
									</table>
									
									<table>
										<tr>
											<td width="85%" colspan="2">&nbsp;</td><td><center>Total: <input type="text" name="total_perc" class="total_wgt required input-mini" readonly='readonly' id="total_perc" value="100"/></center></td><td>&nbsp;</td>
										</tr>
										
									</table>
									<input type="hidden" name="survey_counter" id="survey_counter" class="survey_counter" value="1"/>
									<input type="hidden" name="counter" id="counter" class="counter" value="1"/>
									
									<input type="hidden" name="direct_attainment" id="direct_attainment" value="0"/>
									<input type="hidden" name="indirect_attainment" id="indirect_attainment" value="0"/>
									
									<div class="pull-right">
										<a id="add_survery_field" class="btn btn-primary add_survery_field" name="add_survery_field"><i class="icon icon-plus icon-white"></i> Add more rows </a>
										<button id="save_po_attainment" name="save_po_attainment" class="save_po_attainment btn btn-primary"><i class="icon icon-ok icon-white"></i>&nbsp;Submit</button>
									</div>
									<br /><br />
									<!-- Div for Attainment Analysis table-->
									<div id="po_level_comparison_nav">                                           
									</div>
									<br>
									<div id="chart_plot_7"></div>								
									<div id="po_attainment_no_data_survey"></div>								
									<br>
								</div><!-- direct indirect attain div ends-->
								
								<div id="po_direct_indirect_attainment_graph_data" style="display:none"></div>
								<input type="hidden" name="po_direct_indirect_attainment_graph_data_hidden" id="po_direct_indirect_attainment_graph_data_hidden" />
								
							</form>
						</div><!--Div ends-->
						
					</div>
					<!-- Tab three - Course wise Plan ends here -->
				</div>
				<div id="myModalQPdisplay_paper_modal_2" class="modal hide fade myModalQPdisplay_paper_modal_2 modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
				 data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
					<div class="modal-header">
						<div class="navbar">
							<div class="navbar-inner-custom">
								<?php echo $this->lang->line('student_outcome_full'); ?> Attainment by individual Courses.
							</div>
						</div>
						<div id="po_statement"></div>
						<div class="modal-body" id="drilldown_content_display" width="100%" height="auto">
							<div id="course_po_attainment_chart"></div>
							
							
						</div>
						<div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
						</div>
					</div>														
					<div class="modal-footer">
						<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
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
			
		</section>
	</div>
</div>
</div>
<!--</div>-->
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/stud_po_attainment.js'); ?>"></script>

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
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"> </script>
<script>
	var student_outcome = "<?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>";
	var student_outcome_short = "<?php echo $this->lang->line('student_outcome'); ?>";
	var so = "<?php echo $this->lang->line('so'); ?>";
	var student_outcome_full = "<?php echo $this->lang->line('student_outcome_full'); ?>";
	var student_outcomes = "<?php echo $this->lang->line('student_outcomes_full'); ?><?php echo $this->lang->line('student_outcomes'); ?>";
	var student_outcomes_short = "<?php echo $this->lang->line('student_outcomes'); ?>";
	var sos = "<?php echo $this->lang->line('sos'); ?>";
	var student_outcomes_full = "<?php echo $this->lang->line('student_outcome_full'); ?>";
	var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
	var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
	var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
	var student_outcomes_val = "<?php echo $this->lang->line('student_outcomes'); ?>";
	var student_outcome_val = "<?php echo $this->lang->line('student_outcome'); ?>";
</script>
