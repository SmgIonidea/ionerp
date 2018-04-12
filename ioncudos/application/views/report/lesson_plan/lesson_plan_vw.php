<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	October 24th, 2013

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
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
                <?php $this->load->view('includes/sidenav_3'); ?>
                <div class="span10"> 
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
							<div class="navbar">
								<div class="navbar-inner-custom">
									Lesson Plan Report
								</div>
							</div>								
							<div class="bs-example bs-example-tabs">
								<ul id="myTab" class="nav nav-tabs">
									<li class="active"><a href="#course_plan" data-toggle="tab"> Course Plan </a></li>
									<li><a href="#course_content" data-toggle="tab"> Course Content </a></li>
									<li><a href="#chapter_wise_plan" data-toggle="tab"> Chapterwise Plan </a></li>
									<li><a href="#course_unitization" data-toggle="tab"> Course Unitization </a></li>
									<li><a href="#oe_and_pi" data-toggle="tab"> <?php echo $this->lang->line('outcome_element_sing_short'); ?> and PI </a></li>									
								</ul>								
								<div id="myTabContent" class="tab-content">
									<!-- Tab one - Course Plan starts here -->
									<div class="tab-pane fade in active" id="course_plan">
										<form target="_blank" name="form_course_plan" id="form_course_plan" method="POST" action="<?php echo base_url('report/lesson_plan/to_word_course_plan'); ?>">
											<table style="width:80%;">
												<tr>
													<td>
														<p>
															Curriculum <font color="red"> * </font><br>
															<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
																<option value = '' selected="selected"> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</p>
													</td>						
													<td>
														<p>
															Term <font color="red"> * </font><br>
															<select size="1" id="term" name="term" aria-controls="example" onChange = "select_term_course();">
																<option value = '' selected> Select Term </option>
															</select>
														</p>
													</td>
													<td>
														<p>
															Course <font color="red"> * </font><br>
															<select size="1" id="course" name="course" aria-controls="example" onChange = "curriculum_year(); semester(); select_course_course_plan(); prerequisites(); clo_statements();">
																<option value = '' selected> Select Course </option>
															</select>
														</p>
													</td>
												</tr>
											</table>
											
											<div class="pull-right">
												<button id="export_course_plan" class="btn btn-success export_course_plan"><i class="icon-book icon-white"></i> Export .doc</button>
											</div>
		
											<!-- Title of the Page -->
											<input type="hidden" name="word_course_plan" id="word_course_plan" />
											<h5><center> Course Plan: <b id="curriculum_year"> </b></center></h5>
											
											<div class="pull-left">
												<p><b style="color:green;"> Semester: </b>
													<b id="semester"> </b>
												</p>
											</div><br>
											
											<div data-target="#navbarExample" class="bs-docs-example">
												<div id="table_view_course_plan" style="overflow:auto;">
												</div>
											</div>
											<br>
											
											<p><b style="color:green;"> Prerequisites </b>
												<p id="prerequisites">
												</p>
											</p><br>
											
											<p>
												<b style="color:green;"> Course Outcomes (COs): </b>
												<p> At the end of the course the student should be able to: </p>
												<p id="clo_statements">
												</p>
											</p>									
											
											<div class="pull-right">
												<button id="export_course_plan" class="btn btn-success export_course_plan"><i class="icon-book icon-white"></i> Export .doc</button>
											</div><br>
										</form>
									</div>
									<!-- Tab one - Course Plan ends here -->
									<!-- Tab two - Course Content starts here -->
									<div class="tab-pane fade" id="course_content">
										<form target="_blank" name="form_course_content" id="form_course_content" method="POST" action="<?php echo base_url('report/lesson_plan/to_word_course_content'); ?>">
											<table style="width:80%;">
												<tr>
													<td>
														<p> 
															Curriculum <font color="red"> * </font><br>
															<select size="1" id="cccrclm" name="cccrclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term_cc();">
																<option value = '' selected> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</p>
													</td>
													<td>
														<p>
															Term <font color="red"> * </font><br>
															<select size="1" id="ccterm" name="ccterm" aria-controls="example" onChange = "select_term_course_cc();">
																<option value = '' selected> Select Term </option>
															</select>
														</p>
													<td>
														<p>
															Course <font color="red"> * </font><br>
															<select size="1" id="cccourse" name="cccourse" aria-controls="example" onChange = "course_content_details(); course_code();">
																<option value = '' selected> Select Course </option>
															</select>
														</p>
													</td>
												</tr>
											</table>
											
											<div class="pull-right">
												<button id="export_course_content" class="btn btn-success export_course_content"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
											
											<!-- Title of the Page -->
											<div id="begin_course_content">
												<h5><center> Course Content </center></h5>
												
												<div class="pull-left">
													<p><b style="color:green;"> Course Code: <b id="course_code"> </b> </b></p>
												</div>
												<div class="pull-right">
													<p><b style="color:green;"> L-T-P: <b id="l_t_p"> </b> </b></p>
												</div>
												
												<div style="clear:both"></div>
												<div class="pull-left">
														<p><b style="color:green;"> Course Title: <b id="course_title"> </b> </b></p>
												</div>
												<div class="pull-right">
														<p><b style="color:green;"> <?php echo $this->lang->line('entity_cie'); ?>: <b id="cie_marks"> </b> </b></p>
												</div>
												
												<div style="clear:both"></div>
												<div class="pull-left">
													<p><b style="color:green;"> Teaching Hours: <b id="teaching_hours"> </b> Hours </b></p>
												</div>
												<div class="pull-right">
													<p><b style="color:green;"> <?php echo $this->lang->line('entity_see'); ?>: <b id="see_marks"> </b> </b></p>
												</div><br>
												
												<div data-target="#navbarExample" class="bs-docs-example">
													<div id="table_view_course_content" style="overflow:auto;">
													</div>
												</div>
											</div>
											
											<div class="pull-right">
												<button id="export_course_content" class="btn btn-success export_course_content"><i class="icon-book icon-white"></i> Export .doc</button>
											</div><br>
										</form>
									</div>
									<!-- Tab two - Course Content ends here -->
									<!-- Tab three - Course wise Plan starts here -->
									<div class="tab-pane fade" id="chapter_wise_plan">
										<form target="_blank" name="form_chapter_wise_plan" id="form_chapter_wise_plan" method="POST" action="<?php echo base_url('report/lesson_plan/to_word_chapter_wise_plan'); ?>">
											<table style="width:100%;">
												<tr>
													<td align="left">
														<p>
															Curriculum <font color="red"> * </font><br>
															<select size="1" id="cpcrclm" name="cpcrclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term_cp();">
																<option value = '' selected> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</p>
													</td>
													<td align="left">
														<p>
															Term <font color="red"> * </font><br>
															<select size="1" id="cpterm" name="cpterm" aria-controls="example" onChange = "select_term_course_cp();">
																<option value = '' selected> Select Term </option>
															</select>
														</p>
													</td>
													<td align="left">
														<p>
															Course <font color="red"> * </font><br>
															<select size="1" id="cpcourse" name="cpcourse" aria-controls="example" onChange = "select_topic();">
																<option value = '' selected> Select Course </option>
															</select>
														</p>
													</td>
													<td  align="left">
														<p>
															<?php echo $this->lang->line('entity_topic'); ?> <font color="red"> * </font><br>
															<select size="1" id="cptopic" name="cptopic" aria-controls="example" onChange = "chapter_wise_plan_content(); tlo_details(); review_question();">
																<option value = '' selected> Select <?php echo $this->lang->line('entity_topic'); ?> </option>
															</select>
														</p>
													</td>
												</tr>
											</table>
											
											<div class="pull-right">
												<button id="export_chapter_wise_plan" class="btn btn-success export_chapter_wise_plan"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
											
											<!-- Title of the Page -->
											<div id="begin_chapter_wise_plan">
												<h5><center> Chapterwise Plan </center></h5>
												
												<div data-target="#navbarExample" class="bs-docs-example">
													<div id="table_view_chapter_wise_plan" style="overflow:auto;">
													</div>
													<div id="lesson_plan" style="overflow:auto;"></div>
												</div><br>
											</div><br>
											
											<div class="pull-right">
												<button id="export_chapter_wise_plan" class="btn btn-success export_chapter_wise_plan"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
										</form>
									</div>
									<!-- Tab three - Course wise Plan ends here -->
									<!-- Tab four - Evaluation Scheme starts here -->
									<div class="tab-pane fade" id="course_unitization">
										<form target="_blank" name="form_course_unitization" id="form_course_unitization" method="POST" action="<?php echo base_url('report/lesson_plan/to_word_course_unitization'); ?>">
											<table style="width:90%">
												<tr>
													<td align="left">
														<p>
															Curriculum <font color="red"> * </font><br>
															<select size="1" id="cucrclm" name="cucrclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term_cu();">
																<option value = '' selected> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</p>
													</td>
													<td align="left">
														<p>
															Term <font color="red"> * </font><br>
															<select size="1" id="cuterm" name="cuterm" aria-controls="example" onChange = "select_term_course_cu();">
																<option value = '' selected> Select Term </option>
															</select>
														</p>
													</td>
													<td align="left">
														<p>
															Course <font color="red"> * </font><br>
															<select size="1" id="cucourse" name="cucourse" aria-controls="example" onChange = "course_unitization_content();">
																<option value = '' selected> Select Course </option>
															</select>
														</p>
													</td>
												</tr>
											</table>
											
											<div class="pull-right">
												<button id="export_course_unitization" class="btn btn-success export_course_unitization"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
											
											<!-- Title of the Page -->
											<div id="begin_course_unitization">
												<h5><center> Evaluation Scheme </center></h5>
												
												<div data-target="#navbarExample" class="bs-docs-example">
													<div id="table_view_cu" style="overflow:auto;">
													</div>
												</div><br>
											</div><br>
											
											<div class="pull-right">
												<button id="export_course_unitization" class="btn btn-success export_course_unitization"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
										</form>
									</div>
									<!-- Tab four - Evaluation Scheme ends here -->
									<!-- Tab five - OE and PI starts here -->
									<div class="tab-pane fade" id="oe_and_pi">
										<form target="_blank" name="form_oe_and_pi" id="form_oe_and_pi" method="POST" action="<?php echo base_url('report/lesson_plan/to_word_oe_pi'); ?>">
											<table style="width:80%">
												<tr>
													<td align="left">
														<label>
															Curriculum <font color="red"> * </font><br>
															<select size="1" id="oe_pi_crclm" name="oe_pi_crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term_oe_pi_();">
																<option value = '' selected> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</label>
													</td>
													<td align="left">
														<label>
															Term <font color="red"> * </font><br>
															<select size="1" id="oe_pi_term" name="oe_pi_term" aria-controls="example" onChange = "select_term_course_oe_pi_();">
																<option value = '' selected> Select Term </option>
															</select>
														</label>
													</td>
													<td align="left">
														<label>
															Course <font color="red"> * </font><br>
															<select size="1" id="oe_pi_course" name="oe_pi_course" aria-controls="example" onChange = "oe_pi_content();">
																<option value = '' selected> Select Course </option>
															</select>
														</label>
													</td>
												</tr>
											</table>
											
											<div class="pull-right">
												<button id="export_oe_and_pi" class="btn btn-success export_oe_and_pi"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
											
											<!-- Title of the Page -->
											<div id="begin_course_unitization">
												<h5><center> Program <?php echo $this->lang->line('outcome_element_plu_full'); ?> addressed in the Course and corresponding Performance Indicators (PIs) </center></h5>
												
												<div data-target="#navbarExample" class="bs-docs-example">
													<div id="table_view_oe_pi" style="overflow:auto;">
													</div>
												</div><br>
											</div><br>
											
											<div class="pull-right">
												<button id="export_oe_pi" class="btn btn-success export_oe_pi"><i class="icon-book icon-white"></i> Export .doc </button>
											</div><br>
										</form>
									</div>
									<!-- Tab four - Evaluation Scheme ends here -->
								</div>
							</div>
						</div>
					</section>
					
					<!-- Modal to display Warning Message -->
					<div id="myModal_export_status_one" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_export_status_one" data-backdrop="static" data-keyboard="true"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning
								</div>
							</div>
						</div>

						<div class="modal-body">
							<p> Select Curriculum, Term and Course before Exporting </p>
						</div>

						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/report/lesson_plan.js'); ?>" type="text/javascript"> </script>