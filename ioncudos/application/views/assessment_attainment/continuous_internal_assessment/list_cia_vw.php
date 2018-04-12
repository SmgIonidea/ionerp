<?php
	/* -----------------------------------------------------------------------------------------------------------------------------
		* Description	: Manage CIA Occasions list - view
		* Created By   : Arihant Prasad
		* Created Date : 10-09-2015
		* Date			Modified By						Description
		* 10-11-2015			Shayista Mulla			Hard code(entities) change by Language file labels.
		------------------------------------------------------------------------------------------------------------------------------
	*/
?>


<!DOCTYPE html>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
			</div>
			
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Manage <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>) Occasions List
						</div>
					</div>
					
                    <form name="add_form" id="add_form" method="post" action="<?php echo base_url('curriculum/topicadd'); ?>" class="form-horizontal">
                        <div class="row-fluid">								
                            <table>
                                <tr>
                                    <td>
                                        <label>
                                            Department:<font color='red'>*</font> 
                                            <select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();" >
                                                <option value="">Select Department</option>
                                                <?php foreach ($dept_data as $listitem) { ?>
                                                    <option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
												<?php } ?>
											</select> <?php echo str_repeat('&nbsp;', 8); ?>
										</label>
									</td>
                                    <td>
                                        <label>
                                            Program:<font color='red'>*</font>
                                            <select id="pgm_id" name="pgm_id" class="input-medium" onchange="select_crclm_list();">
                                                <option>Select Program</option>
											</select> <?php echo str_repeat('&nbsp;', 8); ?>
										</label>
									</td>
                                    <td>
                                        <label>
                                            Curriculum:<font color='red'>*</font> 
                                            <select id="crclm_id" name="crclm_id" autofocus = "autofocus" class="input-large" onchange="select_termlist();" >
                                                <option value="">Select Curriculum</option>
											</select> <?php echo str_repeat('&nbsp;', 8); ?>
										</label>
									</td>
                                    <td>
                                        <label>
                                            Term:<font color='red'>*</font> 
                                            <select id="term" name="term" class="input-medium" onchange="GetSelectedValue();">
                                                <option>Select Term</option>
											</select>
										</label>
									</td>
								</tr>
							</table>									
						</div>
						
                        <div class="row">
							
						</div>
						
                        <div id="cia_occasion_div"><br>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                                        <th class="header" width="10px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Section</th>
                                        <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Code</th>
                                        <th class="header span3" width="10px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Course Title</th>
                                        <th class="header span2" width="10px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Mode</th>
                                        <th class="header span2 " width="10px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Course Type</th> 
                                        <th class="header span2 " width="10px" role="columnheader" tabindex="0" aria-controls="example" align="center" style="width: 110px;">Instructor</th>
                                        <th class="header span2"  role="columnheader" tabindex="0" aria-controls="example" align="center" >Manage <?php echo $this->lang->line('entity_cie'); ?></th>
                                        <th class="header span2"  role="columnheader" tabindex="0" aria-controls="example" align="center" >Import Occasion</th>
                                        <th class="header span2 " width="10px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Status</th>
									</tr>
								</thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
								</tbody>
							</table>
						</div><br>
					</form>
                    <div id="import_occasions" class="modal hide fade new-modal-admin"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
						</div>
                        
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Import <?php echo $this->lang->line('entity_cie_full'); ?>(<?php echo $this->lang->line('entity_cie'); ?>) Occasions
								</div>
							</div>
							
                            <div class=" span12 div_border" style="margin: 0px;">
                                <div id="meta_data_title" class="span12">
                                    &nbsp;&nbsp;<u><b>Import to Course Details</b></u>
								</div>
								
                                <div class="span12">
                                    <div class="crlcm_name span5" id=""><b>Curriculum:</b> <font id="crlcm_name_font" color="blue"></font></div>
                                    <div class="term_name span5" id=""><b>Term Name:</b> <font id="term_name_font" color="blue"></font></div>
									
								</div>
                                <div class="span12">
                                    
                                    <div class="crs_name span5" id=""><b>Course Name:</b> <font id="crs_name_font" color="blue"></font></div>
                                    <div class="section_name span5" id=""><b>Section/Division:</b> <font id="section_name_font" color="blue"></font></div>
								</div>
							</div>
							
						</div>
                        <div class="modal-body">
                            <input type="hidden" name="course_id" id="course_id" value=""/>
                            <input type="hidden" name="curriculum_id" id="curriculum_id" value=""/>
                            <input type="hidden" name="dept_id" id="dept_id" value=""/>
                            <input type="hidden" name="program_id" id="program_id" value=""/>
                            <input type="hidden" name="term_id" id="term_id" value=""/>
                            <input type="hidden" name="to_section_id" id="to_section_id" value=""/>
							<div class="div_border" style="margin:4px;">
								<div> <u><b>Import From Course Details </b></u> </div>
								<form name="select_form" id="select_form" method="post" action="">
									<table class="table table-bordered dataTables_wrapper dataTable dataTables qp_table">
										<thead>
											<tr>
												<td>
													<label for="pop_crclm_list">Department<font color="red">*</font>:&nbsp;
														<select class="pop_dept_list input-medium required" name="pop_dept_list" id="pop_dept_list" >
															<option value="">Select Department</option>
														</select>
													</label>
												</td>
												<td>
													<label for="pop_prog_list">Program<font color="red">*</font>:&nbsp;
														<select class="pop_prog_list input-medium required" name="pop_prog_list" id="pop_prog_list" >
															<option value="">Select Program</option>
														</select>
													</label>
												</td>
												<td>
													<label for="pop_crclm_list">Curriculum<font color="red">*</font>:&nbsp;
														<select class="pop_crclm_list input-medium required" name="pop_crclm_list" id="pop_crclm_list">
															<option value="">Select Curriculum</option>
														</select>
													</label>
												</td>
											</tr>
											<tr>
												<td>
													<label for="pop_term_list">Term<font color="red">*</font>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<select class="pop_term_list input-medium required" id="pop_term_list" name="pop_term_list">
															<option value="">Select Term</option>
														</select>
													</label>
												</td>
												<td>
													<label for="pop_course_list">Course<font color="red">*</font>:&nbsp;&nbsp;&nbsp;
														<select class="pop_course_list input-medium required" id="pop_course_list" name="pop_course_list" >
															<option value="">Select Course</option>
														</select>
													</label>
												</td>
												<td></td>
											</tr>
										</thead>
									</table>
								</form>
								
                                <div style="margin-top: 20px;">
                                    
									<div class="div_border" id="">
										<div id="occasion_list_div">
											<div><u><b><?php echo $this->lang->line('entity_cie'); ?> Occasion List</b></u></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
                        <div class="modal-footer">
                            <button class="btn btn-primary"  id="import_occasion_button" disabled="disabled" draggable="true" ><i class="icon-download-alt icon-white"></i>Import</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
						</div>
					</div>
                    <div id="occasion_existance" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
						<div id="loading_popup" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
						</div>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                                    Warning
								</div>
							</div>
						</div>
						
                        <div class="modal-body" >
                            <p id="occasion_existance_body_msg"></p>
                            <p> If 'yes' click <b>YES</b> else click <b>CANCEL</b> to stop importing data</p>
						</div>
						
                        <div class="modal-footer">
                            <button class="btn btn-success delete_survey_button" id="import_delete_insert"  aria-hidden="true"> <i class="icon-ok icon-white"></i>&nbsp;&nbsp;Yes</button>
                            <button type="button" class="cancel btn btn-danger" data-dismiss="modal" id="import_abort"><i class="icon-remove icon-white"> </i>Cancel</button>
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
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/cia.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>



