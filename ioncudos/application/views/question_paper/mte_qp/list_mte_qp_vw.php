<?php
/**
 * Description	:	MTE QP List View
 * Created		:	03-02-2017. 
 * Author 		:   Bhagyalaxmi S S
 * Modification History:
 * Date				Modified By				Description
  -------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->

        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                           <?php echo $this->lang->line('entity_mte_full'); ?>  <?php echo "(". $this->lang->line('entity_mte') . ")"; ?> Question Paper(QP) List - Termwise
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
				<form name="qp_data" id="qp_data" method="POST" class="qp_data" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <label>
                                    Department:<font color='red'>*</font> 
                                    <select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();" >
                                        <option value="">Select Department</option>
                                        <?php foreach ($dept_data as $listitem): ?>
                                            <option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Program:<font color='red'>*</font>
                                    <select id="program" name="program" class="input-medium" onchange="select_crclm_list();">
                                        <option>Select Program</option>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Curriculum:<font color='red'>*</font> 
                                    <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-large" onchange="select_termlist();" >
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
                                <input type="hidden" id="crs_id" name="crs_id" value = ""/>
                            </td>
                        </tr>
                    </table>							
							<input type="hidden" id="qpd_id" name="qpd_id" value = ""/>
							<input type="hidden" id="qpd_type" name="qpd_type" value = ""/>
							<input type="hidden" id="file_exist" name="file_exist" value = ""/>
							<input type="hidden" id="section_id" name="section_id" value = ""/>
							<input type="hidden" id="occasion_id" name="occasion_id" value = ""/>
							<input type="hidden" id="abbr_address" name="abbr_address" value = ""/>
							<input type="hidden" id="regerate_name" name="regerate_name" value = ""/>
							<input type="hidden" id="tee_qp_modal_no_fm_data" name="tee_qp_modal_no_fm_data" value = ""/>
							<input type="hidden" id="file_name" name="file_name" value="">								
							<input hidden name="upload_file" id="upload_file" class="test upload_file" type="file" >
				</form>	
                    <br>
                    <div>
                        <div>
                            <table class="table table-bordered table-hover" id="mte_qp_list" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Code</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Title</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Core / Elective</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Total Marks</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Owner</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Manage <?php echo $this->lang->line('entity_mte'); ?> QP</th> <!--<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Create <?php echo $this->lang->line('entity_mte'); ?> QP</th> --><th class="header" role="columnheader" tabindex="0" aria-controls="example" >Import <?php echo $this->lang->line('entity_mte'); ?> QP</th>                                      
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table><br>
                        </div>
						
						<input type="hidden" id="import_qpd_id" name="import_qpd_id"/>
						<input type="hidden" id="import_ao_id" name="import_ao_id"/>
                        </br></br>
                        <!--Modal to confirm before deleting peo statement-->
        
                        <div id="mte_qp_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom" id="modal_title">
                                        Create <?php echo $this->lang->line('entity_mte'); ?>  Type Question Paper 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="tee_qp_list_table">
                                <input type="hidden" name="tee_lang_val" id="tee_lang_val" value="<?php echo $this->lang->line('entity_mte'); ?>" />
                                <p id="mte_qp_modal_text"></p>
                                <br><br>
                                <div id="rubrics_table_display_div"></div>
                            </div>
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                            <div id = "fm_not_defined_msg"></div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="mte_qp_creation" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Create New <?php echo $this->lang->line('entity_mte'); ?> </button>
                                <button id="mte_qp_modal_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>

                        <div id="mte_qp_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_mte'); ?> QP Delete Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to Delete this <?php echo $this->lang->line('entity_mte'); ?> Question Paper (QP).
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="mte_qp_delete_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
                                <button id="mte_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
                            </div>
                        </div>
		

						 <div id="rubrics_table_view_modal" class="modal hide fade modal-admin in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Rubrics List 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="rubrics_table_div">


                            </div>
                            <div id="pdf_report_generation">
                                <form name="rubrics_report" id="rubrics_report" method="POST" action="<?php echo base_url('question_paper/tee_rubrics/export_report/') ?>" >
                                    <input type="hidden" name="report_in_pdf" id="report_in_pdf" value="" />
                                </form>
                            </div>
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <a type="button" href="#" target="_blank" id="export_to_pdf" class="btn btn-success" ><i class="icon-book icon-white"></i> Export .pdf </a>										
                            <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
                        </div>
                    </div>

                        <div id="myModalQPdisplay" class="modal hide fade myModalQPdisplay modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                              data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_mte'); ?> Question Paper
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="" name="values_data" id="values_data" />
                            <div class="modal-body" id="qp_content_display" width="100%" height="auto">

                            </div>									
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>

                            <div class="modal-footer">
                                    <!--<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>-->

                              <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>																	
                    </div>
                    
					
					
					<div id="import_occasions_question_paper" class="modal hide fade modal-admin"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
						<div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
						</div>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Import <?php echo $this->lang->line('entity_mte_full'); ?>(<?php echo $this->lang->line('entity_mte'); ?>) Question Paper
								</div>
							</div>
						</div>
						<div class="modal-body" style="padding: 20px;" style="hieght:auto;">

							<div class="div_border row" style="margin-bottom: 20px;">

								<div class="row-fluid">
									<div class="crlcm_name span4" id=""><b>Curriculum:</b> <font id="crlcm_name_font"></font></div>
									<div class="term_name span4" id=""><b>Term:</b> <font id="term_name_font"></font></div>
									<div class="crs_name span4" id=""><b>Course:</b> <font id="crs_name_font"></font></div>
									<!--	<div class="crs_name span4" id=""><b>Assessment Occasion:</b> <font id="ao_name_font"></font></div>-->
									<!--  <div class="crs_name span4" id=""><b>Section:</b> <font id="sec_name_font"></font></div>-->
								</div>
								<div class="row-fluid">
								   
									
								   
								</div>


							</div>
							<input type="hidden" name="course_id" id="course_id" value=""/>
							<input type="hidden" name="curriculum_id" id="curriculum_id" value=""/>
							<input type="hidden" name="occasion_ao_id" id="occasion_ao_id" value=""/>
							<input type="hidden" name="program_id" id="program_id" value=""/>
							<input type="hidden" name="qpterm_id" id="qpterm_id" value=""/>

							<div class="div_border">
								<form name="select_form" id="select_form" method="post" action="">
									<u><b>Import <?php echo $this->lang->line('entity_cie_full'); ?> QP From Course Details </b></u>
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
									<div class="div_border" id="qp_list_div">
										<div><u><b><?php echo $this->lang->line('entity_mte'); ?> Question Paper List</b></u></div>
									</div>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button class="btn btn-primary"  id="import_qp_button" disabled="disabled" draggable="true" ><i class="icon-download-alt icon-white"></i>Import</button>
							<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
						</div>
					</div>	
					<div id="topic_not_defined_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="topic_not_defined_modal" data-backdrop="static" data-keyboard="false">
						<div class="modal-header">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning 
								</div>
							</div>
						</div>
						<div class="modal-body topic_error_msg">
						</div>
						<div id ="modal_footer" class="modal-footer">
							<button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
						</div>
					</div>					
                <div id="mte_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
					<div class="modal-header">
						<div class="navbar">
							<div class="navbar-inner-custom">
								<?php echo $this->lang->line('entity_mte'); ?> Rubrics Delete Confirmation
							</div>
						</div>
					</div>
					<div class="modal-body">
						Are you sure you want to delete this <?php echo $this->lang->line('entity_mte'); ?> Rubrics.
					</div>
					<div id ="modal_footer" class="modal-footer">
						<button type="button" id="mte_rubrics_delete_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
						<button id="mte_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
					</div>
				</div>
				
				<div id="cant_edit_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
					<div class="modal-header">
						<div class="navbar">
							<div class="navbar-inner-custom">
								<?php echo $this->lang->line('entity_mte'); ?> Rubrics Message
							</div>
						</div>
					</div>
					<div class="modal-body">
						You cannot edit this <?php echo $this->lang->line('entity_mte'); ?> Rubrics, as Assessment data is already imported (uploaded) against this <?php echo $this->lang->line('entity_mte'); ?> Rubrics.
					</div>
					<div id ="modal_footer" class="modal-footer">
						
						<button id="mte_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
					</div>
				</div>
				

				<div id="cant_delete_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
						<div class="modal-header">
							<div class="navbar">
								<div class="navbar-inner-custom">
									<?php echo $this->lang->line('entity_mte'); ?> Rubrics Message
								</div>
							</div>
						</div>
						<div class="modal-body">
							You cannot Delete this <?php echo $this->lang->line('entity_mte'); ?> Rubrics as it is already Rolled-out and  Assessment data is already imported (uploaded) against this <?php echo $this->lang->line('entity_mte'); ?> Rubrics.
						</div>
						<div id ="modal_footer" class="modal-footer">
							
							<button id="mte_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
						</div>
				</div>
				
				<div id="marks_uploaded_already_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
					<div class="modal-header">
						<div class="navbar">
							<div class="navbar-inner-custom">
								Warning 
							</div>
						</div>
					</div>
					<div class="modal-body">Cannot Edit the Question Paper as marks has been uploaded for this occasion.
					</div>
					<div id ="modal_footer" class="modal-footer">
						<button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
					</div>
				</div>
				
						
						<div id="mte_qp_delete_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Warning 
									</div>
								</div>
							</div>
							<div class="modal-body">Cannot Delete the Question Paper as marks has been uploaded for this occasion.
							</div>
							<div id ="modal_footer" class="modal-footer">
								<button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
							</div>
						</div>						

                    

            </section>	
        </div>
    </div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js_v3'); ?>
<!---place js.php here -->

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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.sizes.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/excanvas.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
<script>
                                    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
                                    var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
                                    var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
                                    var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
									var entity_clo_full_singular = "<?php echo$this->lang->line('entity_clo_full_singular'); ?>";
										var entity_clo_full = "<?php echo$this->lang->line('entity_clo_full'); ?>";
</script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/manage_mte_qp.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/upload_qp.js'); ?>" type="text/javascript"> </script>

