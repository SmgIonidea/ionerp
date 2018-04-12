<?php
/**
* Description	:	Import Assessment Data List View
* Created		:	30-09-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
* 01-10-2014	   Arihant Prasad		Import and Export features,
										Permission setting, Added file headers, function headers, 
										indentations, comments, variable naming, 
										function naming & Code cleaning
* 12-11-2014		Jyoti				Modified for pdf creation
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
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_5'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
									<?php echo $this->lang->line('entity_see_full'); ?> Assessment Data Import List
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
							<br>
							<div>
								<div>
									<table class="table table-bordered table-hover" id="example_table" aria-describedby="example_info">
										<thead>
											<tr role="row">
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Code</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Title</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Core / Elective</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Total Marks</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Owner</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >View QP</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Actions</th>
												<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Import Status</th>
											</tr>
										</thead>
										<tbody role="alert" aria-live="polite" aria-relevant="all">
										</tbody>
									</table><br>
									
									<b>Note:</b> The courses for which the <?php echo $this->lang->line('entity_see'); ?> question paper(s) are <span class="badge badge-important"> rolled out (finalized) </span> will be displayed.
								</div>
								</br></br>
							</div>	
							
							<!-- modal to display qp -->
							<div id="myModalQPdisplay_paper_modal_2" class="modal hide fade myModalQPdisplay_paper_modal_2 modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
								  data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Question Paper
										</div>
									</div>

									<input type="hidden" value="" name="values_data" id="values_data" />
									<div class="modal-body" id="qp_content_display" width="100%" height="auto">
										
									</div>
									<div id="loading" class="ui-widget-overlay ui-front">
											<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
										</div>
								</div>														
								<div class="modal-footer">
									<!--<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>-->
									
									<button class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
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
							
                            <!--Do not place contents below this line-->
                    </section>	
                </div>
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
                           <!-- <a type="button" href="#" target="_blank" id="export_to_pdf" class="btn btn-success" ><i class="icon-book icon-white"></i> Export .pdf </a>-->										
                            <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
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
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js_v3'); ?>
    
	<!---place js.php here -->
	<script>
		var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
	</script>
	<!--scripts to display qp -->
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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/import_assessment_data.js'); ?>" type="text/javascript"> </script>
	
