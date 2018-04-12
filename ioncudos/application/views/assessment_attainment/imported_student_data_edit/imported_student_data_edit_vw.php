<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<link href="<?php echo base_url();?>twitterbootstrap/css/jsgrid.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>twitterbootstrap/css/jsgrid-theme.min.css" rel="stylesheet" />
<div class="container-fluid">
	<div class="row-fluid">
		<!--sidenav.php-->
		<?php $this->load->view('includes/sidenav_5'); ?>
		<div class="span10">
			<!-- Contents -->
			<section id="contents">
				<div id="loading" class="ui-widget-overlay ui-front">
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
				<div class="bs-docs-example">
					<!--content goes here-->
					<div class="navbar">
						<div class="navbar-inner-custom">
							Imported Student Data Edit
						</div>
					</div>
					<br />
					<form name="imported_student_edit_form" id="imported_student_edit_form">
					<table width="100%">
						<tr>
							<td><label>
								Department:<font color='red'>*</font> 
								<select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm();">	
									<option>Select Department</option>
									<?php foreach ($dept_data as $listitem): ?>
									<option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</label></td>
							<td>
								<label>
									Program:<font color='red'>*</font>
									<select id="program" name="program" class="input-medium" onchange="select_crclm();">
										<option>Select Program</option>
									</select> 
								</label>
							</td>
							<td>
								<label>
									Curriculum:<font color='red'>*</font> 
									<select id="curriculum" name="curriculum" class="input-large" onchange="select_term();">
										<option value="">Select Curriculum</option>
									</select> 
								</label>
							</td>
							<td><label>
								Term:<font color='red'>*</font> 
								<select id="term" name="term" class="input-medium" onchange="select_course();">
									<option>Select Term</option>
								</select>
							</label></td>
						</tr>
						<tr>
							<td>
								<label>
									Course:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 6); ?>
									<?php echo str_repeat('&nbsp;', 1); ?><select id="course" name="course" class="input-large" onchange="select_type();">	
										<option value="0">Select Course</option>
									</select>
								</label><?php echo str_repeat('&nbsp;', 9); ?>
							</td>
							<td>
								<label>
									Type:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 5); ?>
									<?php echo str_repeat('&nbsp;', 2); ?><select id="type_data" name="type_data" class="input-medium">	
										<option>Select Type</option>
									</select>
								</label>
								<?php echo str_repeat('&nbsp;', 9); ?>
							</td>
							<td>
								<div id="occasion_div" style="display:none;">
									<label>
										Occasion:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 2); ?>
										<select id="occasion" name="occasion" class="input-large">	
											<option>Select Occasion</option>
										</select>
									</label>
									<?php echo str_repeat('&nbsp;', 9); ?>
								</div>
							</td>
						</tr>
					</table>
					</form>
					<div id="jsGrid"></div>
				</div>
				<br/>
			</section>
		</div>
	</div><!--End of span10-->
</div><!--row-fluid-->
</div><!--container-fluid-->
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<script>
			var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
			var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
			var entity_cie_full = "<?php echo $this->lang->line('entity_cie_full'); ?>";
</script>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url();?>twitterbootstrap/js/jsgrid.min.js"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/imported_student_data_edit.js'); ?>" type="text/javascript"> </script>