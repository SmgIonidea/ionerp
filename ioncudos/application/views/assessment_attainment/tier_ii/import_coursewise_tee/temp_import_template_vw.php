<?php
/**
* Description	:	Tier II - Import Course wise Data List View
* Created		:	30-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 
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
                                    <?php echo $this->lang->line('entity_see_full'); ?> Tier-II Assessment Data Import - Student(s) Marks
                                </div>
                            </div>
							
							<!-- to display loading image when mail is being sent -->
							<div id="loading" class="ui-widget-overlay ui-front">
								<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
							</div>
							<form id="form_marks" name="form_marks">
								<table id="check" name="check">
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
										</tr>
										<tr>
											<td>
												<label class="cursor_default">
													Term: <font color="blue"><?php echo $term; ?></font>
												</label>
											</td>
											<td>
												<label class="cursor_default">
													Course: <font color="blue"><?php echo $course; ?></font>
													<?php echo str_repeat('&nbsp;', 13); ?>
												</label>
											</td>
											<td>
												<label class="cursor_default">
													File Name: <font color="blue"><?php echo $file_name.'.csv'; ?></font> 
												</label>
											</td>
										</tr> 
									</tbody>
								</table>
								<input type="hidden"  id="qp_type" name="qp_type" value = "<?php echo $no_of_questions; ?>"/>
								<?php if($no_of_questions == 1) { ?>
								<table id="total_validate">
									<tbody>
										<tr>
											<td>
												Total <?php echo $this->lang->line('entity_see_full'); ?> Marks against which upload is validated: 
												<input type="text" id="test" name="test" class="required input-mini rightJustified" value="<?php echo $qp_subq_marks; ?>"> <font color= "red"> <span id="msg"> </span> </input>
												<input type="hidden" id="unitid" name="unitid" class="required input-mini" value="<?php echo $qpd_unitd_id; ?>" /> 
												<input type="hidden" id="qpd_id" name="qpd_id" class="required input-mini" value="<?php echo $qpd_id; ?>" />
											</td>
										</tr>
										</tbody>
								</table>
								<?php } ?>
							</form>
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
											4) To replace students' data follow step 1.
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
							
							<!--Discard-->
							<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel </button>
							
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

							<div class="modal-footer">
								<a href="<?php echo site_url('tier_ii/import_coursewise_tee/fetch_student_marks/'.$department_id.'/'.$program_id.'/'.$curriculum_id.'/'.$crclm_term_id.'/'.$course_id); ?>" class="btn btn-success"><i class="icon-eye-open icon-white"></i> View Marks </a>
								
								<button class="btn btn-primary" data-dismiss="modal" onclick="main_page();"><i class="icon-ok icon-white"></i> Ok </button>
							</div>
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
								<p> Unable to display the file details because the file headers are invalid or might be trying to upload wrong file.  </p>
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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url();?>twitterbootstrap/js/jsgrid.min.js"></script>
