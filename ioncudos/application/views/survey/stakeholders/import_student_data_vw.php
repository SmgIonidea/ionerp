<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<?php $className = $this->router->fetch_class(); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php $this->load->view('survey/layout/sidebar'); ?> 
		<div class="span10">
		<div id="loading" class="ui-widget-overlay ui-front">
			<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
		</div>
			<!-- Contents -->
			<section id="contents">
				<div class="bs-docs-example">
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>"/>
					<!--content goes here-->	
					<div class="navbar">
						<div class="navbar-inner-custom">
							Import Student Stakeholders
						</div>
					</div>
					<form name="student_data_upload_form" id="student_data_upload_form" method="POST" enctype="multipart/form-data">
						<div class="row-fluid">
							<div class="span3">
								<label for="dept_name"><b>Department:<span style="color:red;font-size:15px;">*</span></b></label>
								<select id="dept_name" name="dept_name" class="form-control">
									<option value="">Select Department</option>
								</select>
							</div>
							<div class="span3">
								<label for="program_name"><b>Program:<span style="color:red;font-size:15px;">*</span></b></label>
								<select id="program_name" name="program_name" class="form-control">
									<option value="">Select Program</option>
								</select>
							</div>
							<div class="span3">
								<label for="curriculum_name"><b>Curriculum:<span style="color:red;font-size:15px;">*</span></b></label>
								<select id="curriculum_name" name="curriculum_name" class="form-control">
									<option value="">Select Curriculum</option>
								</select>
							</div>
							<div class="span3">
								<label for="section_name"><b>Section:<span style="color:red;font-size:15px;">*</span></b></label>
									<select  style="" id="section_name" name="section_name" class="form-control">
										<option value="">Select Section</option>
								</select>	
							</div>
						</div>
						<input name="Filedata" id="Filedata" type="file" size="1" style="opacity:0" />	
                                                <input type="hidden" name="section_name_data" id="section_name_data" />
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
                                                        <input type="hidden" id="url_address" name="url_address" value="http://localhost/ion.cdn/trunk/Coding/ioncudos_v4_03/survey/import_student_data_excel/download_excel"/>
                                                        1) Click here to &nbsp;&nbsp;<?php if ($className == "import_student_data_excel") { ?>
                                                            <!--<a href="survey/import_student_data_excel/download_excel" target="_blank" >Download Template</a>-->
                                                            <?php
                                                            echo anchor('survey/import_student_data_excel/download_excel', 'Download Template', array('title' => 'The best news!', 'target' => '_blank', 'id' => 'download_file', 'rel="facebox"'));
                                                            echo '<input type="hidden" name="import_type" id="import_type" value="excel"/>';
                                                            echo "<font color='#8E2727'> &nbsp;&nbsp;(File name: stakeholders_template.xls)</font>";
                                                        } else {
                                                            echo anchor('survey/import_student_data/csv', 'Download Template');
                                                            echo '<input type="hidden" name="import_type" id="import_type" value="csv"/>';
                                                            echo "<font color='#8E2727'> &nbsp;&nbsp;(File name: stakeholders_template.csv)</font>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        2) Select <font color="#8E2727"><b>Department, Program, Curriculum</b></font> and Click on <font color="#8E2727"><b>"Upload"</b></font> button to upload the .xls file. Make sure that the <font color="#8E2727"><b>file name</b></font> and <font color="#8E2727"><b>file headers</b></font> are not altered.</br>
                                                        <?php echo str_repeat('&nbsp', 3); ?>
                                                        (Note: <font color="#8E2727"><b>Discard previous downloaded file</b></font> before downloading new file)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        3) <font color="#8E2727"><b>PNR, Title, First Name, Email</b></font> fields are Mandatory and cannot be left blank. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        4) Click on <font color="#8E2727"><b>"Accept"</b></font> button to save the student data and return back to list page. Make sure that all the <font color="#8E2727"><b>remarks are resolved</b></font> before proceeding.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        5) Click on <font color="#8E2727"><b>"Cancel"</b></font> button to discard (if any file has been uploaded) and return back to list page.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        6) To replace students' data follow step 1.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
					<hr>

					<div id="student_data"></div>
					<hr>
                                        <button id="update_data" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i>Upload .xls</button>
                                        <button id="download_data" class="btn btn-primary pull-right" style="margin-right: 2px;"><i class="icon-download-alt icon-white"></i>Download .xls</button>
                                        <br />
                                        <br /><br />
					<!--Cancel -->
					<button id="discard" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;" onclick="drop_temp_table();"><i class="icon-remove icon-white"></i> Cancel</button>
					<?php
						if($className=="import_student_data_excel"){
						?>
						<!--Accept-->
						<button id="accept" value="accept" class="btn btn-success pull-right" style="margin-right: 2px;" onclick="insert_into_main_table();"><i class="icon-ok icon-white"></i> Accept .xls </button>
						<!--import-->
						<button type="button" id="file_uploader" value="Upload" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Upload .xls </button><br><br>
						<?php }else{?>
						<!--Accept-->
						<button id="accept" value="accept" class="btn btn-success pull-right" style="margin-right: 2px;" onclick="insert_into_main_table();"><i class="icon-ok icon-white"></i> Accept .csv </button>
						<!--import-->
						<button type="button" id="file_uploader" value="Upload" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Upload .csv </button><br><br>
						
					<?php } ?>
					<br><br>
					<hr>
					<input type="hidden" name="dept_id" id="dept_id" />
					<input type="hidden" name="pgm_id" id="pgm_id" />
					<input type="hidden" name="crclm_id" id="crclm_id" />
					<input type="hidden" name="section_id" id="section_id" />
					<div id="student_duplicate_data"></div>
					<div id="student_duplicate_data"></div>
					<!--Cancel -->
					<button id="discard_dup" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;visibility:hidden;" onclick="discard_entry();"><i class="icon-remove icon-white"></i> Cancel</button>					
					<button type="button" id="update_dup_data" value="update" class="btn btn-success pull-right" style="margin-right: 2px;visibility:hidden;" onclick="update_duplicate_data();"><i class="icon-edit icon-white"></i> Update</button>
					<br><br>
					
					<!-- Modal to display the remarks status  -->
					<div id="remarks_exists" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="remarks_exists" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Upload Failure - Remarks Found
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> There are Remarks found (exists) in the uploaded file, Kindly verify / check the uploaded data, if there are any remarks correct those and re-upload the file. </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					
					<!-- Modal to display the File status  -->
					<div id="file_not_uploaded" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="file_not_uploaded" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> Kindly upload the file before proceeding to accept.  </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					<!-- Modal to display the Import status  -->
					<div id="import_status" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="import_status" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Data Imported Confirmation
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> The Student data has been uploaded successfully.  </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close </button> 
						</div>
					</div>					
					<!-- Modal to display the Import status  -->
					<div id="student_duplicate_email_data" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="import_status" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Duplicate Data
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p>  There are  Students  with the same Email in different Curriculum or within the Curriculum. Kindly verify the Email and proceed.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close </button> 
						</div>
					</div>
					
					<!-- Modal to display the Duplicate data Found  -->
					<div id="duplicate_data" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="duplicate_data" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Duplicate PNR - Error Message
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> There is a Student with the same PNR number in different Curriculum. Kindly verify the PNR and proceed. </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					
					<!-- Modal to display the Update Status -->
					<div id="update_status" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="update_status" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Data Imported Confirmation
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> The Student data has been uploaded successfully. </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					<!-- Modal to display the Error-->
					<div id="error_display" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_display" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Error Message
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p> Something went wrong please try again. </p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					<!-- Modal to display the Error-->
					<div id="invalid_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning 
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p>Invalid file uploaded, Kindly upload the valid file.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					<!-- Modal to display the Error-->
					<div id="empty_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning 
								</div>
							</div>
						</div>
						
						<div class="modal-body">
							<p>File is empty.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick="clearFields();"><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
					
					
					
					
					<!--Do not place contents below this line-->
				</section>	
			</div>
		</div>
	</div>
	<!---place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 
	<?php $this->load->view('includes/js_v3'); ?>
	
	<!---place js.php here -->
	<script>
		var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
	
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
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
	<!--<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.js'); ?>"></script>
	<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.pieRenderer.js'); ?>"></script>-->
	
	<script src="<?php echo base_url('twitterbootstrap/js/custom/import_student_data.js'); ?>" type="text/javascript"></script>