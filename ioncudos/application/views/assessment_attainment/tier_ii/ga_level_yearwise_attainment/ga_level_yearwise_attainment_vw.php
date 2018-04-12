<?php
	/*
		* Description	:	View for Tier II GA Level yearwise Attainment
		
		* Created		:	December 31st, 2015
		
		* Author		:	 Shivaraj B
		
		* Modification History:
		* Date				Modified By				Description
		
	----------------------------------------------------------------------------------------------*/
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
							Graduate Attributes (GAs) Attainment (Year wise)
						</div>
					</div>
					
					<form class="form-horizontal" method="POST" target="_blank" id="ga_yearwise_add_form" name="ga_yearwise_add_form" action="<?php echo base_url('tier_ii/ga_level_yearwise_attainment/export_to_pdf');  ?>">
						
						<div class="row-fluid">
							<table style="width:100%; overflow:auto;">
								<tr>
									<td>
										<p>
											<label>Department :<font color='red'>*</font> 
												<?php
													foreach ($department_list as $listitem2) {
														$select_options1[$listitem2['dept_id']] = $listitem2['dept_name']; 
														//group name column index
													}
													if(!isset($select_options1))
													$select_options1['0'] = 'No Department to display';
													echo form_dropdown('dept_id', array('' => 'Select Department') + $select_options1, set_value('dept_id', '0'), '"class="required target" id="dept_id" autofocus = "autofocus"');
												?>	
											</label>
										</p>
									</td>
									<td>
										<p>
											<label>Program :<font color='red'>*</font> 
												<select id="pgm_id" name="pgm_id" class="pgm_id required">
													<option value="">Select Program </option>
												</select>
											</label>
										</p>
									</td>
									<td>
										<p>
											<label>Academic End Year :<font color='red'>*</font> 
												<select id="year" name="year" class="year required">
													<option value="">Select Year</option>
												</select>
											</label>
											
										</p>
									</td>
									
								</tr>
							</table>
						</div>
						<div class="row-fluid">
							<div id="ga_attainment_chart"></div>
							<div id="ga_year_wise_data"></div>
							<!--For graph use-->
							<div id="ga_attainment_graph_data" style="display:none"></div>
							<input type="hidden" name="ga_attainment_graph_data_hidden" id="ga_attainment_graph_data_hidden" />
						</div>
					</form>
					<div id="drilldown_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Graduate Attributes (GAs) Drill Down
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div id="ga_drill_down_data">
							</div>
						</div>
						<input type="hidden" name="error_dialog" id="error_dialog" /> 
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
						</div>
					</div>
					<div id="crclm_drilldown_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Curriculum Drill Down
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div id="crclm_drill_down_data">
							</div>
						</div>
						<input type="hidden" name="error_dialog" id="error_dialog" /> 
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
						</div>
					</div>
				</div>
				
				
				
			</section>
		</div>
	</div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/ga_level_yearwise_attainment/ga_level_yearwise_attainment.js'); ?>"></script>

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