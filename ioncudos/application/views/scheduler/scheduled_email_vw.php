<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php $this->load->view('survey/layout/sidebar'); ?> 
		<div class="span10">
			<!-- Contents -->
			<section id="contents">
				<div class="bs-docs-example">
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>"/>
					<!--content goes here-->	
					<div class="navbar">
						<div class="navbar-inner-custom">
							Scheduled Email List:
						</div>
					</div>
					
					<input type="hidden" name="dept_id" id="dept_id" />
					<input type="hidden" name="pgm_id" id="pgm_id" />
					<input type="hidden" name="crclm_id" id="crclm_id" />
					<table>
						<tbody>
							<tr>
								<td>
									<b>Department:<span style="color:red;font-size:15px;">*</span></b>
									<select id="dept_name" name="dept_name" class="form-control">
										<option value="">Select Department</option>
									</select>
								</td>
								<td>
									<b>&nbsp;&nbsp;&nbsp;&nbsp;Program:<span style="color:red;font-size:15px;">*</span></b>
									<select id="program_name" name="program_name" class="form-control"><option value="">Select Program</option></select>
								</td>
								<td>
									<b>&nbsp;&nbsp;&nbsp;&nbsp;Curriculum:<span style="color:red;font-size:15px;">*</span></b>&nbsp;&nbsp;&nbsp;&nbsp;
									<select id="curriculum_name" name="curriculum_name" class="form-control">
										<option value="">Select Curriculum</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<b>Survey Title:<span style="color:red;font-size:15px;">*</span></b>
									<select id="survey_title" name="survey_title" class="form-control">
										<option value="">Select Survey</option>
									</select>
								</td>
							</tbody>
						</table>
						<br><br>
						
						<!--Body starts here-->
						<div id='email_list_tab'>
							
						</div>
						<br /><br /><br />
						<!--Body ends here-->
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
		<script src="<?php echo base_url(); ?>twitterbootstrap/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url(); ?>twitterbootstrap/js/jquery.dataTables.rowGrouping.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>twitterbootstrap/js/custom/email_scheduled_list.js" type="text/javascript"></script>
		