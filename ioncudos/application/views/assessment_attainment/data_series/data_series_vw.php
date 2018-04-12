<?php
/*
* Description	:	Data Series List View
* Created		:	22-12-2014. 
* Author 		:   Jyoti V. Shetti
* Modification History:
* Date				Modified By				Description
15-10-2015			Shivaraj B              Added Export to pdf option and Ajax loading 
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
                    	<div id="loading" class="ui-widget-overlay ui-front">
                    		<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" id="loading_ajax" class='loading_ajax' />
                    	</div>
                        <div class="bs-docs-example">
                            <!--content goes here-->	
							<form method="POST" id="data_series_list_form" action="<?php echo base_url(); ?>assessment_attainment/data_series/export_to_pdf" name="data_series_list_form" target="_blank" class="form-inline" >
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Data Analysis Report
                                </div>
                            </div>
							
										<label>
											Department:<font color='red'>*</font> 
											<select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm();">	
												<option>Select Department</option>
												<?php foreach ($dept_data as $listitem): ?>
													<option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
												<?php endforeach; ?>
											</select>
										</label>
										<label>
											Program:<font color='red'>*</font>
											<select id="program" name="program" class="input-medium" onchange="select_crclm();">
												<option>Select Program</option>
											</select> 
										</label>
										<label>
											Curriculum:<font color='red'>*</font> 
											<select id="curriculum" name="curriculum" class="input-large" onchange="select_term();">
												<option value="">Select Curriculum</option>
											</select> 
										</label>
										<label>
											Term:<font color='red'>*</font> 
											<select id="term" name="term" class="input-medium" onchange="select_course();">
												<option>Select Term</option>
											</select>
										</label>
										<label>
											Course:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 6); ?>
											<?php echo str_repeat('&nbsp;', 1); ?><select id="course" name="course" class="input-large" onchange="select_type();">	
												<option value="0">Select Course</option>
											</select>
										</label><?php echo str_repeat('&nbsp;', 9); ?>
										<label>
											Type:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 5); ?>
											<?php echo str_repeat('&nbsp;', 2); ?><select id="type_data" name="type_data" class="input-medium">	
												<option>Select Type</option>
											</select>
										</label><?php echo str_repeat('&nbsp;', 9); ?>
										<div id="occasion_div" style="display:none;">
										<label>
											 &nbsp;Occasion:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 2); ?>
											<select id="occasion" name="occasion" class="input-large">	
												<option>Select Occasion</option>
											</select>
										</label></div>
						
							<br>

							<div id="data_series_analysis_nav"></div>
							<div id="actual_data_div"></div>
                            <div id="data_series_graph"></div><!--Division for graph display -->
                            <div id="data_series_analysis_rep" style="display:none;"></div>
                            <input type="hidden" name="data_series_analysis_rep_hidden" id="data_series_analysis_rep_hidden" />
							
							
									<input type="hidden" name="export_data_to_doc" id="export_data_to_doc" value="" />				
									<input type="hidden" name="export_graph_data_to_doc" id="export_graph_data_to_doc" value="" />
									<input type="hidden" name="file_name" id="file_name" value="" />
									<input type="hidden" name="main_head" id="main_head" value="" />
									<input type="hidden" name="po_attainment_graph_data" id="po_attainment_graph_data" value="" />
									<input type="hidden" name="pdf_doc" id="pdf_doc" value=""/>
                            </form>
                            <!--Do not place contents below this line-->
                    </section>	
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
		<script>
			var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
			var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
			var entity_cie_full = "<?php echo $this->lang->line('entity_cie_full'); ?>";
			var sos = "<?php echo $this->lang->line('sos'); ?>";
		</script>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/data_series.js'); ?>" type="text/javascript"> </script>
		

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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
