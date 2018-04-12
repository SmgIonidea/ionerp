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
							Add Student Stakeholder
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid"> 
								<?php 
									echo form_open('survey/import_student_data/store_student_stakeholder', array('name' => 'add_stud_stakeholder_form', 'id' => 'add_stud_stakeholder_form', 'method' => 'post','class'=>'form-horizontal')); 
								?>
								<div class="span6">
									<div class="control-group">
										<?php echo form_label('Department:<font color="red"> * </font>','dept_name',array('class'=>'control-label')); ?>
										<div class="controls">
											<select id="dept_name" name="dept_name" class="input">
												<option value="">Select Department</option>
											</select>
											<?php echo form_error('dept_name', '<div class="error" style="color:red;">', '</div>'); ?>
											<input type="hidden" class="input" id="stakeholder_group_type" name="stakeholder_group_type" value="5" />
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Program:<font color="red"> * </font>','program_name',array('class'=>'control-label')); ?>
										<div class="controls">
											<select id="program_name" name="program_name" class="input">
												<option value="">Select Program</option>
											</select>
											<?php echo form_error('program_name', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Curriculum:<font color="red"> * </font>','curriculum_name',array('class'=>'control-label')); ?>
										<div class="controls">
											<select id="curriculum_name" name="curriculum_name" class="form-control">
												<option value="">Select curriculum</option>
											</select>
											<?php echo form_error('curriculum_name', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Section:<font color="red"> * </font>','section_name',array('class'=>'control-label')); ?>
										<div class="controls">
											<select id="section_name" name="section_name" class="form-control">
												<option value="">Select Section</option>
											</select>
											<?php echo form_error('section_name', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('PNR:<font color="red"> * </font>','student_usn',array('class'=>'control-label')); ?>
										<div class="controls">   
											<?php echo form_input(array('name'=>'student_usn','id'=>'student_usn','value'=>set_value('student_usn'),'class'=>'input')); ?>
											<?php echo form_error('student_usn', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('DOB:','contact',array('class'=>'control-label')); ?>
										<div class="controls">   
											<div class="input-append date">
												<input type="text" class="span12 yearpicker" id="dp3" name="dob" readonly="">
												<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											</div>
											<?php echo form_error('dob', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
								</div><!--span6-->
								<div class="span6">
									<div class="control-group">
										<?php echo form_label('Title:<font color="red"> * </font>','title',array('class'=>'control-label required')); ?>
										<div class="controls">
											<select name="title" id="title" class="input-small">
												<!--<option value="">--</option>-->
												<option value="Mr.">Mr.</option>
												<option value="Mrs.">Mrs.</option>
												<option value="Ms.">Ms.</option>
												<option value="Miss.">Miss.</option>
											</select>
											<?php echo form_error('title', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('First Name:<font color="red"> * </font>','first_name',array('class'=>'control-label')); ?>
										<div class="controls">   
											<?php echo form_input(array('name'=>'first_name','id'=>'first_name','value'=>set_value('first_name'),'class'=>'input')); ?>
											<?php echo form_error('first_name', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Last Name:','last_name',array('class'=>'control-label')); ?>
										<div class="controls">   
											<?php echo form_input(array('name'=>'last_name','id'=>'last_name','value'=>set_value('last_name'),'class'=>'input')); ?>
											<?php echo form_error('last_name', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Email Id:<font color="red"> * </font>','email',array('class'=>'control-label')); ?>
										<div class="controls">   
											<?php echo form_input(array('name'=>'email','id'=>'email','value'=>set_value('email'),'class'=>'input')); ?>
											<?php echo form_error('email', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
									<div class="control-group">
										<?php echo form_label('Contact Number:','contact',array('class'=>'control-label')); ?>
										<div class="controls">   
											<?php echo form_input(array('name'=>'contact','id'=>'contact','value'=>set_value('contact'),'class'=>'input')); ?>
											<?php echo form_error('contact', '<div class="error" style="color:red;">', '</div>'); ?>
										</div>
									</div>
								</div><!--span6-->
								<div class="pull-right">       
									<?php 
										echo form_button(array('type'=>'submit','name' => 'stkholder_submit', 'id' => 'stkholder_submit', 'value' => 'submit','content'=>'<i class="icon-file icon-white"></i> Save','class' => 'btn btn-primary margin-right5'));
										echo '&nbsp;&nbsp;';
										echo form_button(array('type'=>'reset','name' => 'stkholder_reset', 'id' => 'stkholder_reset', 'value' => 'reset','content'=>'<i class="icon-refresh icon-white"></i> Reset','class' => 'btn btn-info margin-right5')); 
										echo '&nbsp;&nbsp;';
										echo anchor('survey/import_student_data/', "<i class='icon-remove icon-white'></i> Cancel", array('class' => 'btn btn-danger'));
									?>
								</div>
								<?php echo form_close(); ?>
							</div> 
							
						</div>
					</div>
					<br/><br/><br/><br/><br/>
					<!--Do not place contents below this line-->
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
	
	<script src="<?php echo base_url('twitterbootstrap/js/custom/add_student_stakeholder.js'); ?>" type="text/javascript"></script>