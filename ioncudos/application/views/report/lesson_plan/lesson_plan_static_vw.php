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
                <?php $this->load->view('includes/static_sidenav_3'); ?>
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
								</ul>
								<div id="myTabContent" class="tab-content">
									<!-- Tab one - Course Plan starts here -->
									<div class="tab-pane fade in active" id="course_plan">
										<table>
											<tr>
												<td>
													<label> &nbsp;&nbsp;&nbsp;&nbsp;
														Curriculum <font color="red"> * </font>
														<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term();">
															<option value="Curriculum" selected> Select Curriculum </option>
															<?php foreach ($curriculum_result as $list_item): ?>
																<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
															<?php endforeach; ?>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												</td>						
												<td>
													<label>
														Term <font color="red"> * </font>
														<select size="1" id="term" name="term" aria-controls="example" onChange = "select_term_course();">
															<option value="Term" selected> Select Term </option>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												</td>
												<td>
													<label>
														Course <font color="red"> * </font>
														<select size="1" id="course" name="course" aria-controls="example" onChange = "curriculum_year(); semester(); select_course_course_plan(); prerequisites(); clo_statements();">
															<option value="Course" selected> Select Course </option>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												</td>
											</tr>
										</table>
										<!-- Title of the Page -->
										<div id="begin_course_plan">
											<h4><center> Course Plan: <b id="curriculum_year"> </b></center></h4><br>
											
											<div class="pull-left">
												<label><b style="color:green;"> Semester: </b>
													<b id="semester"> </b>
												</label>
											</div><br>
											
											<div data-target="#navbarExample" class="bs-docs-example">
												<div id="table_view_course_plan" style="overflow:auto;">
												</div>
											</div>
											<br>
											
											<label><b style="color:green;"> Prerequisites </b>
												<p id="prerequisites">
												</p>
											</label><br>
											
											<label>
												<b style="color:green;"> Course Outcomes (COs): </b>
												<label> At the end of the course the student should be able to: </label>
												<p id="clo_statements">
												</p>
											</label>
										</div><br>
									</div>
									<!-- Tab one - Course Plan ends here -->
									<!-- Tab two - Course Content starts here -->
									<div class="tab-pane fade" id="course_content">
										<table>
											<tr>
												<td>
													<label> &nbsp;&nbsp;&nbsp;&nbsp;
														Curriculum <font color="red"> * </font>
														<select size="1" id="cccrclm" name="cccrclm" aria-controls="example" onChange = "select_term_cc();">
															<option value="Curriculum" selected> Select Curriculum </option>
															<?php foreach ($curriculum_result as $list_item): ?>
																<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
															<?php endforeach; ?>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												</td>
												<td>
													<label>
														Term <font color="red"> * </font>
														<select size="1" id="ccterm" name="ccterm" aria-controls="example" onChange = "select_term_course_cc();">
															<option value="Term" selected> Select Term </option>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												<td>
													<label>
														Course <font color="red"> * </font>
														<select size="1" id="cccourse" name="cccourse" aria-controls="example" onChange = "course_code(); course_content_details();">
															<option value="Curriculum" selected> Select Course </option>
														</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													</label>
												</td>
											</tr>
										</table>
										
										<!-- Title of the Page -->
										<div id="begin_course_content">
											<h4><center> Course Content </center></h4>
											
											<div class="pull-left">
												<label><b style="color:green;"> Course Code: <b id="course_code"> </b> </b></label>
											</div>
											<div class="pull-right">
												<label><b style="color:green;"> L-T-P: <b id="l_t_p"> </b> </b></label>
											</div>
											
											<div style="clear:both"></div>
											<div class="pull-left">
													<label><b style="color:green;"> Course Title: <b id="course_title"> </b> </b></label>
											</div>
											<div class="pull-right">
													<label><b style="color:green;"> <?php echo $this->lang->line('entity_cie'); ?>: <b id="cie_marks"> </b> </b></label>
											</div>
											
											<div style="clear:both"></div>
											<div class="pull-left">
												<label><b style="color:green;"> Teaching Hours: <b id="teaching_hours"> </b> Hours </b></label>
											</div>
											<div class="pull-right">
												<label><b style="color:green;"> <?php echo $this->lang->line('entity_see'); ?>: <b id="see_marks"> </b> </b></label>
											</div><br>
											
											<div data-target="#navbarExample" class="bs-docs-example">
												<div id="table_view_course_content" style="overflow:auto;">
												</div>
											</div><br>
											
											<label>
												<b style="color:green;"> Text Book (List of books as mentioned in the approved syllabus) </b>
												<p id="text_books"> </p>
											</label><br>
											
											<label>
												<b style="color:green;"> References </b>
												<p id="references"> </p>
											</label>
										</div><br>
									</div>
									<!-- Tab two - Course Content ends here -->
									<!-- Tab three - Course wise Plan starts here -->
									<div class="tab-pane fade" id="chapter_wise_plan">
										<table>
											<tr>
													<td align="left">
														<label>
															Curriculum <font color="red"> * </font>
															<select size="1" id="cpcrclm" name="cpcrclm" aria-controls="example" onChange = "select_term_cp();">
																<option value="Curriculum" selected> Select Curriculum </option>
																<?php foreach ($curriculum_result as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</label>
													</td>
													<td align="left">
														<label>
															Term <font color="red"> * </font>
															<select size="1" id="cpterm" name="cpterm" aria-controls="example" onChange = "select_term_course_cp();">
																<option value="Curriculum" selected> Select Term </option>
															</select> &nbsp; &nbsp; &nbsp;
														</label>
													</td>
													<td align="left">
														<label>
															Course <font color="red"> * </font>
															<select size="1" id="cpcourse" name="cpcourse" aria-controls="example" onChange = "select_topic();">
																<option value="Curriculum" selected> Select Course </option>
															</select> &nbsp;
														</label>
													</td>
													<td  align="left">
														<label>
															<?php echo $this->lang->line('entity_topic'); ?> <font color="red"> * </font>
															<select size="1" id="cptopic" name="cptopic" aria-controls="example" onChange = "chapter_wise_plan_content(); tlo_details(); review_question();">
																<option value="Curriculum" selected> Select <?php echo $this->lang->line('entity_topic'); ?> </option>
															</select> &nbsp;
														</label>
													</td>
												</tr>
										</table>
										
										<!-- Title of the Page -->
										<div id="begin_chapter_wise_plan">
											<h4><center> Chapterwise Plan </center></h4>
											
											<div data-target="#navbarExample" class="bs-docs-example">
												<div id="table_view_chapter_wise_plan" style="overflow:auto;">
												</div>
											</div><br>
												
											<table>
												<tr>
													<td>
														<label>
															<b style="color:green;"> Lesson Schedule </b>
															<table class="table table-bordered">
																<thead>
																	<tr>
																		<td> Class No. </td>
																		<td> Portion covered per hour </td>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td> </td>
																		<td> </td>
																	</tr>
																</tbody>
															</table>
														</label>
													</td>
												</tr>
											</table><br>
											
											<label>
												<b style="color:green;"> Review Questions </b>
												<p id="review_questions">
												</p>
											</label>
										</div><br>
									</div>
									<!-- Tab three - Course wise Plan ends here -->
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/report/lesson_plan.js'); ?>" type="text/javascript"> </script>