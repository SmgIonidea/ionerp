<?php
/**
* Description	:	Tier II - Import Course wise Data List View
* Created		:	29-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 
----------------------------------------------------------------------
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
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
									<?php echo $this->lang->line('entity_see_full'); ?> Tier-II Course-wise <?php echo $this->lang->line('entity_see'); ?> Data Import
                                </div>
                            </div>
                           <table>
								<tr>
									<td align="left">
										Department:<font color='red'>*</font> 
										<select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();" >
											<option value="">Select Department</option>
											<?php foreach ($dept_data as $listitem): ?>
												<option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
											<?php endforeach; ?>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Program:<font color='red'>*</font>
										<select id="program" name="program" class="input-medium" onchange="select_crclm_list();">
											<option>Select Program</option>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Curriculum:<font color='red'>*</font> 
										<select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-medium">
											<option value="">Select Curriculum</option>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Term:<font color='red'>*</font> 
										<select id="term" name="term" class="input-medium" >
											<option>Select Term</option>
										</select>
									</td>
								</tr>
							</table>
							<div>
								<div>
									<table class="table table-bordered table-hover" id="example_table" aria-describedby="example_info">
										<thead>
											<tr role="row">
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 45px;">Sl No.</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 55px;">Code</th>
												<th class="header span4" role="columnheader" tabindex="0" aria-controls="example" style="width: 150px;">Course Title</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 85px;">Core / Elective</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 45px;"><?php echo $this->lang->line('credits'); ?></th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 50px;">Total Marks</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 140px;">Course Owner</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 60px;">Mode</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 130px;">Upload QP / View QP</th>
												<th class="header span3" role="columnheader" tabindex="0" aria-controls="example" style="width: 150px;">Template Actions</th>
                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" style="width: 60px;">Consolidated CIA & TEE Marks</th>
												<!--<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Import Status</th>-->
											</tr>
										</thead>
										<tbody role="alert" aria-live="polite" aria-relevant="all">
										</tbody>
									</table>
								</div>
								</br></br>
							</div>	
			
							<form name="tee_qp" id="tee_qp" method="POST" enctype="multipart/form-data">
								<input type="hidden" id="u_crclm_id" name="u_crclm_id" value="">
								<input type="hidden" id="u_term_id" name="u_term_id" value="">
								<input type="hidden" id="u_crs_id" name="u_crs_id" value="">
								<input type="hidden" id="u_filedata" name="u_filedata" value="">								
								<input hidden name="Filedata" id="Filedata" class="test Filedata" type="file" >
							</form>	


							<form name="re_tee_qp" id="re_tee_qp" method="POST" enctype="multipart/form-data">
								<input type="hidden" id="re_u_crclm_id" name="re_u_crclm_id" value="">
								<input type="hidden" id="re_u_term_id" name="re_u_term_id" value="">
								<input type="hidden" id="re_u_crs_id" name="re_u_crs_id" value="">
								<input type="hidden" id="re_u_filedata" name="re_u_filedata" value="">
								<input hidden name="Filedata_1" id="Filedata_1" class="test Filedata_1" type="file">
							</form>	
							
							<div id="success" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Warning 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Are you sure you want to replace the existing file? </p>
									<input type="hidden" name="modalfile" id="modalfile" class="Filedata" size="1" style="opacity:5" value="erwer"/>
								</div>

								<div class="modal-footer">
									<button class="btn btn-primary" data-dismiss="modal" id="upload_again"><i class="icon-ok icon-white"></i> Ok </button>
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
								</div>
							</div>		

							<div id="msg" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Warning 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Only .doc, .docx, .odt, .rtf and .pdf files <span class="badge badge-important"> of size 10MB </span> are allowed.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>	

							<div id="upload_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Warning 
										</div>
									</div>
								</div>	

								<div class="modal-body">
									<p> File needs to be uploaded.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>		
	
							<div id="rollout" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Warning 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Template needs to be downloaded before uploading the Question Paper.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>
				
							<div id="save" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Message 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Your file has been uploaded successfully.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>	

							<div id="failure" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Message 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Your file has <span class="badge badge-important">not </span> been uploaded successfully.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>	

							<div id="larger" name="larger" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="drop_main_table_confirmation" data-backdrop="static" data-keyboard="false"><br>
								<div class="container-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Warning 
										</div>
									</div>
								</div>

								<div class="modal-body">
									<p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
								</div>

								<div class="modal-footer">	
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
								</div>
							</div>							
                            <!--Do not place contents below this line-->
                    </section>	
                </div>
            </div>
        </div>
		<div id="target_or_threshold_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModa_cia_error" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Thresholds/Targets OR Attainment Levels are not defined for this course. Kindly define before importing student assessment data.</p>
                                    <p id="link_stmt">Click here to define <a id="define_threshold_target" class="cursor_pointer" > Thresholds/Targets OR Attainment Levels</a> </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
							 <div id="students_not_uploaded_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModa_cia_error" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Student list is not uploaded/imported for this Curriculum. Kindly request the concerned Chairman(HOD)/Program Owner to upload the Student list.</p>
                                    <p>Click here to <a id="students_upload_link" class="cursor_pointer" > Upload Students.</a> </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?>
		<?php $this->load->view('includes/js'); ?>
    
	<!---place js.php here -->
	<script>
		var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
	</script>
	<!--scripts to display qp -->
	<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/import_coursewise_tee/import_coursewise_tee.js'); ?>" type="text/javascript"> </script>
	
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/upload_tee_qp/upload_tee_qp.js'); ?>" type="text/javascript"> </script>
