<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description				: Student Threshold 
 * Modification History		:
 * Date				Author				Description
 * 04-08-2015	 Arihant Prasad		Set student threshold, list student USN
									display improvement plan table, facility to
									insert improvement plan details, view improvement plan
  ---------------------------------------------------------------------------------------------------------------------------------
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
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
				
            </div>
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Student Threshold
                        </div>
                    </div>

                    <form method="POST" id="add_form" name="add_form" class="form-horizontal" action="<?php echo base_url('assessment_attainment/improvement_plan'); ?>">
						<input type="hidden" id="entity_id" name="entity_id" value="11" />
						<input type="hidden" id="student_usn" name="student_usn" value="">
                        <div class="row-fluid">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 5%;">
                                            Curriculum:<font color='red'>*</font>
                                            <?php
                                            foreach ($crclm_data as $listitem2) {
                                                $select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name'];
                                            }
                                            
											if (!isset($select_options1)) {
                                                $select_options1['0'] = 'No Curriculum to display';
											}
											
                                            echo form_dropdown('crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'onchange="select_term();" "class="required input-medium" id="crclm_id" name="crclm_id" autofocus = "autofocus"'); ?>
                                        </td>
                                        <td style="width: 5%;">
                                            Term:<font color='red'>*</font>
                                            <select id="term" name="term" class="input-medium" onchange="select_course();">
                                                <option value="">Select Term</option>
                                            </select>
                                        </td>
                                        <td style="width: 5%;">
                                            Course:<font color='red'>*</font>
                                            <select id="course" name="course" class="input-medium" onChange="select_type();">
                                                <option value="0">Select Course</option>
                                            </select>
                                        </td>
										<td style="width: 5%;">
											Type:<font color='red'>*</font>
											<select id="type_data" name="type_data" class="input-medium">
												<option>Select Type</option>
											</select>
										</td>
                                    </tr>
								</tbody>
                            </table>
                            <table>
								<tbody>
									<tr>
										<td class="pull-left">
											<div id="occasion_div" style="display:none;">
												Occasion &nbsp;&nbsp;:<font color='red'>*</font>
												<select id="occasion" name="occasion" class="input-medium">	
													<option>Select Occasion</option>
												</select><?php echo str_repeat('&nbsp', 8); ?>
											</div>
										</td>
										<td class="pull-left">
											<div id="threshold_div" style="display:none;">
												<div id="space"></div>Threshold:<font color='red'>*</font>
												<select id="threshold_level" name="threshold_level" class="input-medium" onchange="getCOs();">	
													<option>Select Threshold Level</option>
												</select><?php echo str_repeat('&nbsp', 8); ?>
												<input type="text" value="" id="clo_thold" class="clo_thold" style="display: none; width:30px;" value="" maxlength="3" onkeypress="return isNumber(event);" />
											</div><?php echo str_repeat('&nbsp', 8); ?>
										</td>
									</tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right">
                            <input type="hidden" id="tee_qpd_id" name="tee_qpd_id" value="0" />
                            <input type="hidden" name="clo_ids" id="clo_ids" /> 
                            <button class="btn btn-success" data-dismiss="modal" aria-hidden="true" id="submit_clo" disabled="disabled"><i class="icon-ok icon-white"></i> Submit </button>
                        </div>

                        <div id="clo_list_div">
                        </div>

                        <div id="cia_error_msg">
                        </div>
						
						<div id="student_dataAnalysis">
                        </div>
                    </form>
				
					<!-- modal to confirm before deleting improvement plan -->
					<div id="myModal_select_dropdown" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_select_dropdown" data-backdrop="static" data-keyboard="false">
						<div class="modal-header">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning
								</div>
							</div>
						</div>
						<div class="modal-body">
							<p> Select all drop-downs before proceeding. </p>
						</div>
						<div class="modal-footer">
							<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
						</div>
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
<script>
	var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
	var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
	var entity_cie_full = "<?php echo $this->lang->line('entity_cie_full'); ?>";
	var entity_see_full = "<?php echo $this->lang->line('entity_see_full'); ?>";
</script>
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/student_threshold.js'); ?>"></script>
