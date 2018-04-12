<?php
/* --------------------------------------------------------------------------------------------------------
 * Description	: Student Attainment list view page, provides the facility to view the attainment of each student.
 * Modification History:
 * Date				Author				Description
 * 25-02-2015		Jevi V G     	   
 * ------------------------------------------------------------------------------------------------------
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
                        <div class="bs-docs-example" >
                            <!--content goes here-->
							<div class="navbar">
									<div class="navbar-inner-custom">
										Student Course Outcomes(COs) Attainment
									</div>
							</div>
							
							<form  method="POST" target="_blank" id="add_form" name="add_form" class="form-horizontal"  action="<?php echo base_url('assessment_attainment/student_attainment/export_to_pdf');  ?>">
							<table style="width:100%; overflow:auto;">
							<tr>
							<td>
								<p>Curriculum:<font color='red'>*</font>
								<?php foreach ($crclm_data as $listitem2) {
											$select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name']; //group name column index
									}
									if(!isset($select_options1))
										$select_options1['0'] = 'No Curriculum to display';
									echo form_dropdown('crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'onchange="select_term();" "class="required input-medium" id="crclm_id" autofocus = "autofocus"');
								?>
								</p>
							</td>
							<td>
								<p>Term:<font color='red'>*</font> 
									<select id="term" name="term" class="input-medium" onchange="select_course();">
										<option value="">Select Term</option>
									</select>
								</p>
							</td>
							<td>
								<p>Course:<font color='red'>*</font>
									<select id="course" name="course" class="input-medium" onchange="select_type();">
										<option value="0">Select Course</option>
									</select>
								</p>
							</td>
							<td>
								<p>Type:<font color='red'>*</font>
									<select id="type_data" name="type_data" class="input-medium">	
										<option>Select Type</option>
									</select>
								</p>
							</td>
							<td>
								<div id="section_div" style="display:none;">
									<p>Section:<font color='red'>*</font>
										<select style="width:100px;" id="section" name="section" class="input-medium">	
											<option>Select Section</option>
										</select>
									</p>
								</div>
							</td>
							<td>
								<div id="occasion_div" style="display:none;">
									<p>Occasion:<font color='red'>*</font>
										<select id="occasion" name="occasion" class="input-medium">	
											<option>Select Occasion</option>
										</select>
									</p>
								</div>
							</td>
							<td>
								<input type="hidden" id="tee_qpd_id" name="tee_qpd_id" value=""/>
								<div id="usn_div" style="display:none;">
									<p>Student:<font color='red'>*</font>
										<select id="student_usn" name="student_usn" class="input-medium">	
											<option></option>
										</select>
									</p>
								</div>
							</td>
							</tr>
				
							</table>	
							
							<div id="export_doc_div" class="pull-right">
                                        <button type="button" disabled='disabled' id="export_doc" class="export_doc btn-fix btn btn-success" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
                                        <input type="hidden" name="type_id" id="type_id" value="2" />
                                        <input type="hidden" name="form_name" id="form_name" value="" />
                            </div> <br/> <br/> <br/>
								<div id="actual_data_div">
							
								</div>
									<div class="menu" id ="stud_question_analysis">
											 <div class="accordion">
											 <!-- Áreas -->
											  <div class="accordion-group">
												<!-- Área -->
												<div class="brand-custom">
												   <a class="brand-custom" data-toggle="collapse" href="#area2" style="text-decoration:none;" >
													<h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;<span id="student_name_usn"></span> &nbsp;&nbsp; - Question wise details </b></h5>
												   </a>
												</div>
												 <!-- /Área -->
												<div id="area2" class="accordion-body collapse">
												 <div class="accordion-inner">
												 
												 
													 <!-- /Equipamentos -->
												  <div id="data_div" style="width:100%; overflow:auto;">
							
													</div>
												 </div>
												</div>
											  </div>
										   </div><!-- /accordion -->
										</div>	
								
								<div id="co_level_nav">                                           
                                </div>
								<div id="chart_plot_1"></div>								
								<!-- <br>
								<div id="co_level_student_threshold_nav">                                           
                                </div>
								<div id="chart_plot_5"></div>								
								<div id="student_docs"></div> -->
								<br>
								<!-- 
								<div id="course_attainment_nav">                                           
                                </div>
								<div id="chart_plot_2"></div>								
								<br>
								-->
								<div id="bloom_level_nav">                                           
                                </div>
								<div id="chart_plot_3"></div>
								<br>
								<div id="cumulative_performance_nav">                                           
                                </div>								
								<div id="chart_plot_4"></div>
								<br>
								<!--<div id="CLO_PO_attainment_nav">
                                </div>
								<div id="PO_ref_nav">
                                </div>
								<div id="chart_plot" ></div>
								<div id="CLO_PO_attainment_note" class="span12">
								</div>-->
                                <br>								
								<div id="course_outcome_attainment_graph_data" style="display:none;"></div>
								<div id="course_outcome_attainment_graph_data_data" style="display:none;"></div>
								<input type="hidden" id="course_outcome_attainment_graph_data_hidden" name="course_outcome_attainment_graph_data_hidden" />
								<input type="hidden" id="pdf_or_doc" name="pdf_or_doc" />
								<input type="hidden" id="file_name" name="file_name" />
								
								<input type="hidden" id="image1" name="image1" />
								<input type="hidden" id="image2" name="image2" />
								<input type="hidden" id="image3" name="image3" />
								<input type="hidden" id="main_head" name="main_head" />
								<!--<div id="main_head" style="display:none"></div>-->
								<!--<div id="course_outcome_contribution_graph_data" style="display:none;"></div>
								<input type="hidden" id="co_contribution_graph_data_hidden" name="co_contribution_graph_data_hidden" />-->
								<div id="bloom_level_distribution_graph_data" style="display:none;"></div>
								<input type="hidden" id="bloom_level_distribution_hidden" name="bloom_level_distribution_hidden" />
								<!--<div id="threshold_level_attainment_graph_data" style="display:none;"></div>
								<input type="hidden" id="threshold_level_attainment_hidden" name="threshold_level_attainment_hidden" />-->
								<div id="bloom_level_cumm_perf_graph_data" style="display:none;"></div>
								<input type="hidden" id="bloom_level_cumm_perf_graph_hidden" name="bloom_level_cumm_perf_graph_hidden" /><div id="student_attainment_analysis_data" style="display:none;"></div>
								<input type="hidden" id="student_attainment_analysis_data_hidden" name="student_attainment_analysis_data_hidden" />				
								<!--<div id="co_po_graph_data" style="display:none;"></div>
								<input type="hidden" id="co_po_graph_hidden" name="co_po_graph_hidden" />-->
								
							</form>
							
					
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
	var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
	var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
	var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
	var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
	var entity_clo_full_singular = "<?php echo $this->lang->line('entity_clo_full_singular'); ?>";
</script>
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/student_attainment.js'); ?>"></script>

<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
		<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
</html>



