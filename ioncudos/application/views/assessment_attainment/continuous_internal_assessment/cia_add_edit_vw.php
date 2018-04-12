<?php
/* -----------------------------------------------------------------------------------------------------------------------------
 * Description	: Manage CIA Occasions add, edit - view
 * Created By   : Arihant Prasad
 * Created Date : 10-09-2015
 * Date						Modified By					Description
 * 10-11-2015		      Shayista Mulla		Hard code(entities) change by Language file labels.
 * 12-01-2016		      Neha Kulkarni			Added validation for the AO Description.
 ------------------------------------------------------------------------------------------------------------------------------
 */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--css for animated message display-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />

<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <!-- Contents -->
			<div id="loading" class="ui-widget-overlay ui-front">
				<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
			</div>
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add / Edit <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>) Occasions
                        </div>
					</div>
					
					<div id="ao_add_update_form" name="ao_add_update_form">
						<div class="row-fluid">
							<div class="span12">
								<div class="span3"><br>
									<div class="control-group">
										<label class="control-label cursor_default" for="crclm" style="padding-top:0px;">Curriculum :</label>
										<div class="controls">
											<b><?php echo $crclm_term_crs_type[0]['crclm_name']; ?></b>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label cursor_default" style="padding-top:0px;" for="course">Course: </label>
										<div class="controls">
											<b><?php echo $crclm_term_crs_type[0]['crs_title'] . ' ('. $crclm_term_crs_type[0]['crs_code'] .')'; ?></b>
										</div>
									</div>
									
                                    <div class="control-group">
										<label class="control-label cursor_default" style="padding-top:0px;" for="course">Section/Division: </label>
										<div class="controls">
											<b><?php echo $crclm_term_crs_type[0]['section_name']; ?></b>
										</div>
									</div>
								</div>
							 
								<div class="span3"><br>
									<div class="control-group">
										<label class="control-label cursor_default" for="term" style="padding-top:0px;">Term: </label>
										<div class="controls">
											<b><?php echo $crclm_term_crs_type[0]['term_name']; ?></b>
										</div>
									</div>
								
									<div class="control-group">
										<label class="control-label cursor_default" for="course_type" style="padding-top:0px;">Course Type: </label>
										<div class="controls">
											<b><?php echo $crclm_term_crs_type[0]['crs_type_name']; ?></b>
										 </div>
									</div>
								</div>
								
								<input type="hidden" name="pgm_id" id="pgm_id" value="<?php echo $crclm_term_crs_type[0]['pgm_id']; ?>" />
								<input type="hidden" name="crs_type_id" id="crs_type_id" value="<?php echo $crclm_term_crs_type[0]['crs_type_id']; ?>" >
								<input type = "hidden" name="cia_curriculum_id" id="cia_curriculum_id" value ="<?php echo $crclm_term_crs_type[0]['crclm_id']?>" />
								<input type = "hidden" name="cia_term_id" id="cia_term_id" value ="<?php echo $crclm_term_crs_type[0]['crclm_term_id']?>" />
								<input type = "hidden" name="cia_course_id" id="cia_course_id" value ="<?php echo $crclm_term_crs_type[0]['crs_id']?>" />
								<input type = "hidden" name="section_id" id="section_id" value ="<?php echo $crclm_term_crs_type[0]['section_id']?>" />

								<div class="span6">
									<div class="bs-docs-example">
										<div class="navbar">
											<ol class="breadcrumb breadcrumbstyle" style="font-size:12px;" name="pgm_title_heading" id="pgm_title_heading">
												Individual Course <?php if($data[0]['cia_flag'] == 1) { echo "(" . $this->lang->line('entity_cie') .")";}  
																		if($data[0]['mte_flag'] == 1) { echo " (" . $this->lang->line('entity_mte') .")";} 
																		if($data[0]['tee_flag'] == 1) { echo " (" . $this->lang->line('entity_tee') .")";} ?>  Weightage Distribution
											</ol>
										</div>
										
										<table class="table table-bordered">
											<thead>
												<tr>
													<?php if($data[0]['cia_flag'] == 1) { ?>												
														<th><?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>)</th>
													<?php } ?>
													<?php if($data[0]['mte_flag'] == 1) {?>
														<?php if($mte_flag_org == 1) {?>
														<th><?php echo $this->lang->line('entity_mte_full'); ?> (<?php echo $this->lang->line('entity_mte'); ?>)</th>
														<?php } ?>
													<?php } ?>
													<?php if($data[0]['tee_flag'] == 1) { ?>	
														<th><?php echo $this->lang->line('entity_see_full'); ?> (<?php echo $this->lang->line('entity_see'); ?>)</th>
													<?php } ?>
												</tr>
											</thead>
									  
											<tbody>
												<tr>
													<form id="save_weightage"  name="save_weightage" method="POST"  action="<?php echo base_url('assessment_attainment/cia/save_weightage'); ?>" >
														<?php if($data[0]['cia_flag'] == 1) {?>														
														<td>
															<input type="text" name="cia_total_weightage" value="<?php echo $data[0]['total_cia_weightage']; ?>" id="cia_total_weightage" class="qp_types required input-mini rightJustified"> % <font color= "red"> <span id="msg"> </span>
														</td>
														<?php } ?>
														<?php if($data[0]['mte_flag'] == 1) {?>	
															<?php if($mte_flag_org == 1) {?>
																<td>
																	<input type="text" name="mte_total_weightage" value="<?php echo $data[0]['total_mte_weightage']; ?>" id="mte_total_weightage" class="qp_types required input-mini rightJustified"> % <font color= "red"> <span id="msg"> </span>
																</td>
															<?php } ?>
														<?php } ?>
														<?php if($data[0]['tee_flag'] == 1) {?>		
														<td>
															<input type="text" name="tee_total_weightage" value="<?php echo $data[0]['total_tee_weightage']; ?>" id="tee_total_weightage" class="qp_types required input-mini rightJustified" > % <font color= "red"> <span id="disp"> </span>
														</td>
														<?php } ?>
														<span id="error_type" style="color:red"></span>
														<input type="hidden" class="type_flag" id="cia_flag" name="cia_flag" value = "<?php echo $data[0]['cia_flag']; ?>" />
														<input type="hidden" class="type_flag" id="mte_flag" name="mte_flag" value = "<?php echo $data[0]['mte_flag']; ?>" />
														<input type="hidden" class="type_flag" id="tee_flag" name="tee_flag" value = "<?php echo $data[0]['tee_flag']; ?>"  />
													</form>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div><br>
						
                        <div class="navbar-inner-custom">
                            Assessment Occasions (AO) for <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>)
                        </div>
						
						<!-- facility to add AO details and save them -->
						<table id="ao_add_table" class="table table-bordered table-hover">
							<form id="ao_add_form" name="ao_add_form">
								<tr>
									<td class="align_center"><b>Sl No.<font color="red"> * </font></b></td>
									<td class="align_center"><b>AO Name<font color="red"> * </font></b></td>
									<td class="align_center"><b>AO Method<font color="red"> * </font></b></td>
									<td class="align_center"><b>Assessment Type<font color="red"> * </font></b></td>
									<td class="align_center"><b>Maximum Marks<font color="red"> * </font></b></td>
								</tr>
								
								<tr>
									<td class="align_center"><input class="text_align_right ao required input-mini" type="text" id="ao_name" name="ao_name"  onkeypress="return isNumber(event);" maxlength = 3  value = "<?php echo $ao_count; ?>" /></td>
									
									<td class="align_center"><input class="ao_description required input-large" type="text" id="ao_description" name="ao_description" autofocus="autofocus" > <font color= "red" /> <span id="msg1"> </span></td>
								   
									<!--AO Method dropdown-->
									<td class="align_center">
										<select name="ao_list" id="ao_list" abbr="ao_list">
											<option value=""> Select AO Method </option>
											<?php foreach ($ao_method_data as $ao_method) { ?>
												<option value="<?php echo $ao_method['ao_method_id']; ?>">
													<?php echo $ao_method['ao_method_name']; ?>
												</option>
											<?php } ?>
										</select>
									</td>
									
									<!--Assessment Type dropdown-->
									<td class="align_center">
										<select name="mt_list" id="mt_list" abbr="mt_list">
											<option value=""> Select Assessment Type </option>
											<?php foreach ($mt_details_data as $mt_details) { ?>
												<option value="<?php echo $mt_details['mt_details_id']; ?>" id="<?php echo $mt_details['mt_details_id']; ?>">
													<?php echo $mt_details['mt_details_name']; ?>
												</option>
											<?php } ?>
										</select>
									</td>
									
									<td class="align_center"><input class="max_marks required rightJustified input-mini allownumericwithdecimal" type="text" maxlength="3" name="max_marks" id="max_marks" ></td>
								</tr>
								
								<!-- QP link -->
								<tr id="qp_link_row" style="display:none;">
									<td class="align_center" colspan="6"><b> Linked to QP </b></td>
								</tr>
								
								<!-- individual drop-downs -->
								<tr id="individual_row" style="display:none;">
									<td></td>
									
									<!--CO dropdown-->
									<td class="align_center">
										<select name="co_list[]" id="co_list" abbr="co_list" class="co_list" multiple="multiple">
											<?php foreach ($co_details_data as $co_details) { ?>
												<option title="<?php echo $co_details['clo_statement']; ?>" value="<?php echo $co_details['clo_id']; ?>" id="<?php echo $co_details['clo_id']; ?>">
													<?php echo $co_details['clo_code']; ?>
												</option>
											<?php } ?>
										</select><font color="red"> * </font>
									</td>
									
									<!--Bloom's Level dropdown-->
									<td class="align_center">
										<?php if($crclm_term_crs_type[0]['clo_bl_flag'] == 1 ) { ?> 
										<select name="bl_list" id="bl_list" abbr="bl_list" class="bl_list" multiple="multiple" >
										</select><font color="red"> * </font>
										<?php } else { ?>
											<select name="bl_list" id="bl_list" abbr="bl_list" class="bl_list" multiple="multiple" >
												<?php foreach ($bl_details_data as $bl_details) { ?>
													<option title="<?php echo $bl_details['description'] .' - ' . $bl_details['bloom_actionverbs']; ?>" value="<?php echo $bl_details['bloom_id']; ?>" id="<?php echo $bl_details['bloom_id']; ?>">
														<?php echo $bl_details['level']; ?>
													</option>
												<?php } ?>
											</select><font color="red"> * </font> 
										<?php } ?>
									</td>
									<td colspan=2 class="individual_bloom_error"></td>
								</tr>
							</form>
						</table>
						
						<div class="pull-right">
							<button id="ao_details_add" class="btn btn-primary ao_details_add"><i class="icon-file icon-white"></i> Save </button>
							<a href= "<?php echo base_url('assessment_attainment/cia'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close </a>
						</div><br><br>
						
						<!-- modal to edit assessment occasion -->
						<div id="ao_edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:60%; left:41%;" data-controls-modal="ao_edit_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Edit Assessment Occasion
									</div>
								</div>
							</div>
							<div class="modal-body" style="padding-bottom: 55px;">
								<table id="edit_ao_add_table" class="table table-bordered table-hover">
									<tr>
										<td class="align_center"><b>Sl No.<font color="red"> * </font></b></td>
										<td class="align_center" style="white-space:nowrap;"><b>AO Name<font color="red"> * </font></b></td>
										<td class="align_center"><b>AO Method<font color="red"> * </font></b></td>
										<td class="align_center"><b>Assessment Type<font color="red"> * </font></b></td>
										<td class="align_center" style="white-space:nowrap;"><b>Maximum Marks<font color="red"> * </font></b></td>
									</tr>
									<tr>
										<input class="modal_ao_id required input-mini" type="hidden" id="modal_ao_id" name="modal_ao_id">
										<input class="modal_qpd_id required input-mini" type="hidden" id="modal_qpd_id" name="modal_qpd_id">
										<input class="modal_qpd_id required input-mini" type="hidden" id="ao_method_id" name="ao_method_id">
										
										<!--AO name-->
										<td class="align_center"><input class="text_align_right modal_ao_name required input-mini loginRegex" type="text" id="modal_ao_name" name="modal_ao_name" onkeypress="return isNumber(event);" maxlength = 3></td>
										
										<!--AO description-->
										<td class="align_center"><input class="modal_ao_description required input-large loginRegex" type="text" id="modal_ao_description" name="modal_ao_description"> <font color= "red"> <span id="msg2"> </span> </td>
										
										<!--AO Method dropdown-->
										<td class="align_center">
											<select id="modal_ao_method_id" name="modal_ao_method_id">
												<option>Select AO Method</option>
											</select>
										</td>
										
										<!--Assessment Type dropdown-->
										<td class="align_center">
											<select id="modal_mt_details_id" name="modal_mt_details_id" class="modal_mt_details_id">
												<option>Select Assessment Type</option>
											</select>
										</td>
										
										<!--max marks-->
										<td class="align_center"><input class="modal_max_marks required rightJustified input-mini" type="text" maxlength="3" name="modal_max_marks" id="modal_max_marks" onkeypress="return isNumber(event);"></td>
									</tr>
									
									<!-- QP link -->
									<tr id="modal_qp_link_row" style="display:none;">
										<td class="align_center" colspan="6"><b> Linked to QP </b></td>
									</tr>
									
									<!-- individual drop-downs -->
									<tr id="modal_individual_row" style="display:none;">
										<td></td>
										<!--modal co dropdown-->
										<td class="align_center" style="white-space:nowrap;">
											<select name="modal_co_list[]" id="modal_co_list" abbr="modal_co_list" class="modal_co_list" multiple="multiple">
											</select><font color="red"> * </font>
										</td>
										
										<!--modal bl dropdown-->
										<td class="align_center" style="white-space:nowrap;">
											<select name="modal_bl_list" id="modal_bl_list" abbr="modal_bl_list" multiple="multiple" class="modal_bl_list">
												<option value=""> Select Bloom's Level </option>
											</select><font color="red"> * </font>
										</td>
										
										<td colspan="3"></td>
									</tr>
								</table><br/><br/>
							</div>
							<div class="modal-footer">
								<button class="btn btn-primary ao_details_update"><i class="icon-file icon-white"></i> Update </button>
								<button class="btn btn-danger edit_question" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>	
							</div>
						</div>
						
						<!-- modal to confirm before deleting AO details -->
						<div id="ao_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="ao_delete_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Delete Confirmation
									</div>
								</div>
							</div>
							<div class="modal-body">
								<p> 
									If question paper is defined and studentwise marks are imported, all data will be erased.<br>
									Once you delete this <?php echo $this->lang->line('entity_cie'); ?> Occasion, it cannot be undone.
								</p>
								<p>
									Do you wish to continue?
								</p>
							</div>
							
							<input type="hidden" id="modal_delete_crs_id" />
							<input type="hidden" id="modal_delete_ao_id" />
							<input type="hidden" id="modal_delete_qpd_id" />
							
							<div class="modal-footer">
								<button id="delete_ao_confirm" class="delete_ao_confirm btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
								<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
							</div>
						</div>
                                                <!-- modal to confirm before deleting AO details -->
						<div id="check_assessment_method_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="ao_delete_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Warning!!!
									</div>
								</div>
							</div>
							<div class="modal-body">
								<p> 
									If Rubrics is defined or studentwise marks are uploaded for Rubrics then all data will be erased.<br>
								</p>
							</div>
							
							<div class="modal-footer">
								<!--<button id="delete_ao_confirm" class="delete_ao_confirm btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>-->
								<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
							</div>
						</div>
						
						<!--modal to display rubrics-->
						<div id="rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:120%;" data-controls-modal="rubrics_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar-inner-custom">
									Assessment Method & its Rubrics
								</div>
							</div>
							
							<form target="_blank" name="form_rubrics" id="form_rubrics" method="POST" action="<?php echo base_url('assessment_attainment/cia/export_pdf'); ?>">
								<div class="modal-body">
									<div id="rubrics_data"></div>
								</div>
								<input type="hidden" name="pdf" id="pdf" value="" />
							</form>
							
							<div class="modal-footer">
								<a href="#" id="rubrics_pdf" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
								<button id="cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>
							</div>
						</div>
						
						<!-- table to display, edit and delete AO occasion details -->
						<table id="ao_data_table" class="table table-bordered table-hover" class="dataTables_wrapper container-fluid" role="grid">
							<thead>
								<tr role="row">
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">Sl No. </th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">AO Name</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">AO Method</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">Assessment Type</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">Maximum Marks</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">Edit</th>
									<th class="header" role="columnheader" tabindex="0" aria-controls="display_portion">Delete</th>
								</tr>
							</thead>
							
							<tbody id="ao_data_table_body">
							</tbody>
						</table><br>
					</div><!--modal to display rubrics-->
						<div id="rubrics_qp_exist_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="rubrics_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar-inner-custom">
									Warning!!!
								</div>
							</div>
								<div class="modal-body">
                                                                    <p>A question paper is already defined for this Assessment occasion of type Rubrics if you Edit this Occasion All the data will be deleted which already have been defined.</p>
								</div>
							
							
							<div class="modal-footer">
								<button id="qp_existance_modal_ok" class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
								<button id="cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
							</div>
						</div>
                                        
                                        <div id="rubrics_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="rubrics_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar-inner-custom">
									Warning!!!
								</div>
							</div>
								<div class="modal-body">
                                                                    <p>All Rubrics Criteria will be deleted for this occasion.</p>
								</div>
							
							
							<div class="modal-footer">
								<button id="rubrics_modal_ok" class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
								<button id="cancel" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
							</div>
						</div>
				</div>
			</section>
		</div>
	</div>
</div>
<script>
	var cia_lang="<?php echo $this->lang->line('entity_cie'); ?>";
	var co_lang="<?php echo $this->lang->line('entity_clo'); ?>";
	var crclm = "<?php echo $crclm_term_crs_type[0]['crclm_id']; ?>";
	var crs = "<?php echo $crclm_term_crs_type[0]['crs_title']; ?>";
</script>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/cia.js'); ?>"></script>
<!--multi select js-->
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<!--success and error message animated display instead of modal-->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>