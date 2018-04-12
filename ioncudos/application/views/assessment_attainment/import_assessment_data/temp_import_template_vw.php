<?php
/**
* Description	:	Import Assessment Data List View
* Created		:	30-09-2014. 
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 01-10-2014	   Arihant Prasad		Import and Export features,
										Permission setting, Added file headers, function headers, 
										indentations, comments, variable naming, 
										function naming & Code cleaning
-------------------------------------------------------------------------------------------------
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
                <div class="span12">
					<!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
							<!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
									<?php if($ref_id == "ref_12") { ?>
										<?php echo $this->lang->line('entity_cie_full'); ?> Data Import - Student(s) Marks
									<?php } else if($ref_id == "ref_29"){
										echo $this->lang->line('entity_mte_full'); ?> Data Import - Student(s) Marks
									<?php }else { ?>
										<?php echo $this->lang->line('entity_see_full'); ?> Assessment Data Import - Student(s) Marks
									<?php } ?>
                                </div>
                            </div>
							
							<!-- to display loading image when mail is being sent -->
							<div id="loading" class="ui-widget-overlay ui-front">
								<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
							</div>
							
							<table>
								<tbody>
									<tr>
										<td>
											<label class="cursor_default"> 
												Department: <font color="blue"><?php echo $department; ?></font>
												<?php echo str_repeat('&nbsp;', 13); ?>
											</label>
										</td>
										<td>
											<label class="cursor_default">
												Program: <font color="blue"><?php echo $program; ?></font>
												<?php echo str_repeat('&nbsp;', 13); ?>
											</label>
										</td>
										<td>
											<label class="cursor_default">
												Curriculum: <font color="blue"><?php echo $curriculum; ?></font>
												<?php echo str_repeat('&nbsp;', 13); ?>
											</label>
										</td>
										<td>
											<label class="cursor_default">
												Term: <font color="blue"><?php echo $term; ?></font>
											</label>
										</td>
									</tr>
									<tr>
										<td>
											<label class="cursor_default">
												Course: <font color="blue"><?php echo $course; ?></font>
											</label>
										</td>
										<?php if($ref_id == "ref_12") { ?>
											<td>
												<label class="cursor_default">
													Section: <font color="blue"><?php echo $section_name; ?></font>
												</label>
											</td>   
                                        <?php } ?>
										<td>
											<label class="cursor_default">
												File Name: <font color="blue"><?php echo $file_name.'.csv'; ?></font>
											</label>
										</td>
									</tr>
								</tbody>
							</table>
							
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td>
											<b>Steps:</b>
										</td>
									</tr>
									<tr>
										<td>
											1) Click on <font color="#8E2727"><b>"Upload"</b></font> button to upload the .csv file. Make sure that the <font color="#8E2727"><b>file name</b></font> and <font color="#8E2727"><b>file headers</b></font> are not altered.</br>
											<?php echo str_repeat('&nbsp', 3); ?>
											(Note: <font color="#8E2727"><b>Discard previous downloaded file from your system</b></font> before downloading new file)
										</td>
									</tr>
									<tr>
										<td>
											2) Upon upload, student USN, sub questions with their marks and remarks will be displayed.
										</td>
									</tr>
									<tr>
										<td>
											3) Click on <font color="#8E2727"><b>"Accept"</b></font> button to save the student data and return back to list page. Make sure that all the <font color="#8E2727"><b>remarks are resolved</b></font> before proceeding.
										</td>
									</tr>
									<tr>
										<td>
											4) Click on <font color="#8E2727"><b>"Cancel"</b></font> button to discard (if any file has been uploaded) and return back to list page.
										</td>
									</tr>
									<tr>
										<td>
											5) To replace students' data follow step 1.
										</td>
									</tr>
								</tbody>
							</table>
							
							<input type="hidden" id="dept_id" name="dept_id" value="<?php echo $department_id; ?>">
							<input type="hidden" id="prog_id" name="prog_id" value="<?php echo $program_id; ?>">
							<input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $curriculum_id; ?>">
							<input type="hidden" id="term_id" name="term_id" value="<?php echo $crclm_term_id; ?>">
							<input type="hidden" id="crs_id" name="crs_id" value="<?php echo $course_id; ?>">
							<input type="hidden" id="qpd_id" name="qpd_id" value="<?php echo $qpd_id; ?>">
							<input type="hidden" id="ao_id" name="ao_id" value="<?php echo $ao_id; ?>">
							<input type="hidden" id="section_id" name="section_id" value="<?php echo @$section_id; ?>">
							<input type="hidden" id="ref_id" name="ref_id" value="<?php echo $ref_id; ?>">
							
							<!--Discard-->
							<?php if($ref_id == "ref_12") { ?>
								<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="cia_drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel </button>
							<?php } else if($ref_id == "ref_29"){?> 
								<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="mte_drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel </button>
							<?php }else { ?>
								<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel </button>
							<?php } ?>
							
							<!--Accept-->
							<button id="accept" value="accept" class="btn btn-success pull-right" style="margin-right: 2px;" onclick="insert_into_main_table();"><i class="icon-ok icon-white"></i> Accept .csv </button>
							
							<!--import-->
							<button type="submit" id="uploader" value="Upload" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Upload .csv </button><br><br>
						</div>
						<!--display table with student usn and secured marks-->
						<div id="student_marks">
							
						</div>
						
						<!-- Modal to display the import success status  -->
						<div id="insert_complete" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="insert_complete" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Import status
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Student assessment data has been successfully uploaded.  </p>
							</div>

							<?php  if($ref_id == "ref_12") { ?>
								<!-- intenral exam -->
								<div class="modal-footer">
									<a href="<?php echo site_url('assessment_attainment/import_assessment_data/fetch_student_marks/'.$department_id.'/'.$program_id.'/'.$curriculum_id.'/'.$crclm_term_id.'/'.$course_id.'/'.$qpd_id.'/'.$ao_id.'/'.$ref_id); ?>" class="btn btn-success"><i class="icon-eye-open icon-white"></i> View Marks </a>
									
									<button class="btn btn-primary" data-dismiss="modal" onclick="cia_add_page();"><i class="icon-ok icon-white"></i> Ok </button> 
								</div>
							<?php } else if($ref_id == "ref_29") {  ?>
								<div class="modal-footer">
									<a href="<?php echo site_url('assessment_attainment/import_assessment_data/fetch_student_marks/'.$department_id.'/'.$program_id.'/'.$curriculum_id.'/'.$crclm_term_id.'/'.$course_id.'/'.$qpd_id); ?>" class="btn btn-success"><i class="icon-eye-open icon-white"></i> View Marks </a>
									
									<button class="btn btn-primary" data-dismiss="modal" onclick="mte_add_page();"><i class="icon-ok icon-white"></i> Ok </button> 
								</div>
							
							<?php }else { ?>
								<!-- final exam -->
								<div class="modal-footer">
									<a href="<?php echo site_url('assessment_attainment/import_assessment_data/fetch_student_marks/'.$department_id.'/'.$program_id.'/'.$curriculum_id.'/'.$crclm_term_id.'/'.$course_id.'/'.$qpd_id); ?>" class="btn btn-success"><i class="icon-eye-open icon-white"></i> View Marks </a>
									
									<button class="btn btn-primary" data-dismiss="modal" onclick="main_page();"><i class="icon-ok icon-white"></i> Ok </button> 
								</div>
							<?php } ?>
						</div>
						
						<!-- Modal to display the file not uploaded yet  -->
						<div id="file_not_uploaded" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="file_not_uploaded" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Accept status
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> File needs to be uploaded before proceeding.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
												
						<!-- Modal to display remarks warning status  -->
						<div id="remarks_pending" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="remarks_pending" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Import status
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Remarks needs to be resolved before proceeding.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
						
						<!-- Modal to display incorrect file name status  -->
						<div id="incorrect_file_name" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="incorrect_file_name" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Invalid file name
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Unable to display the file details because the file name is invalid.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
						
						<!-- Modal to display incorrect file header status  -->
						<div id="incorrect_file_header" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="incorrect_file_header" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Invalid file header
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Unable to display the file details because the file headers are invalid or might be trying to upload incorrect file.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
						
						<!-- Modal to display file is empty status  -->
						<div id="csv_file_empty" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="csv_file_empty" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Warning
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Student template is empty.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" onClick="window.location.reload()" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
						
						<!-- Modal to display incorrect file extension status  -->
						<div id="invalid_file_extension" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
							<div class="container-fluid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Invalid file extension
									</div>
								</div>
							</div>

							<div class="modal-body">
								<p> Unable to display the file details because the file extension is invalid.  </p>
							</div>

							<div class="modal-footer">
								<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
		
		
	<!---place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 
	<?php $this->load->view('includes/js'); ?>
	<!---place js.php here -->
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/import_assessment_data.js'); ?>" type="text/javascript"></script>
