<?php $this->load->view('includes/head'); ?>
<!--css for animated message display-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<style type="text/css">
	.large_modal{
	width:70%;
	margin-left: -35%; 
	}
</style>

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
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
				<?php if((!$this->ion_auth->in_group('Course Owner')) || $this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){?>
				<input type="hidden" name="logged_in_user" id="logged_in_user" value="2" />
				<?php }else if($this->ion_auth->in_group('Course Owner')){ ?>
				<input type="hidden" name="logged_in_user" id="logged_in_user" value="1" />
				<?php } ?>
				
				<div class="bs-docs-example">
					<!--content goes here-->
					<div class="navbar">
						<div class="navbar-inner-custom">
							Attainment / Target Levels
						</div>
					</div>
					<div id="loading" class="ui-widget-overlay ui-front">
						<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
					</div>
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
					<table style="width:100%;">
						<tr>
							<td>
								<label>Curriculum: <span style="color:red;">*</span>
								<select name="curriculum_data" id="curriculum_data" class="curriculum_name form-control">
									<option value="">Select Curriculum</option>
								</select></label>
							</td>
						</tr>
					</table>
					<div class="bs-example bs-example-tabs">
					<span id="status_msg"></span>
						<ul id="myTab" class="nav nav-tabs">
							<!--<li class="active"><a href="#bloom_level_tab" data-toggle="tab"> Program Outcome Attainment Level</a></li>-->
							<?php if((!$this->ion_auth->in_group('Course Owner')) || $this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){?>
							<!--<li id="crclm_div_data"><a href="#crclm_tab" data-toggle="tab"> Course Level Targets </a></li>-->
							<li id="perf_div_data"><a href="#performance_tab" data-toggle="tab"> Program Level Performance Levels </a></li>
							<?php } ?>
                            <li id="crs_div_data" class="active"><a href="#course_tab" data-toggle="tab"> Attainment Targets for Individual Course</a></li>
							<!-- <li id="bloom_level_div" class=""> <a href="#bloom_level_course_tab" data-toggle="tab">Bloom's Level Div </a> </li> -->
						</ul>
						<div id="myTabContent" class="tab-content">
							<!-- Tab two - Curriculum Level Assessment starts here 
                                                                <div class="tab-pane fade" id="crclm_tab">
								<!-- display Curriculum Level Assessment 
								<div class="navbar">
									<div class="navbar-inner-custom">
										Direct Attainment Targets 
									</div>
								</div>
								<b>Note : CO Level Target Attainment Levels ( Common targets for <?php echo $this->lang->line('entity_cie'); ?> & TEE data), following table is used for setting the targets for all Course Outcomes (COs) across all Courses for individual Curriculum</b>
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_crlm_table_view" style="overflow:auto;"></div>
								</div>
							</div>--><!--tab2 main div ends -->
							<!-- tab two ends here -->
							<!-- Tab three - performance assessment level POs-->
							<div class="tab-pane fade" id="performance_tab">
							<div class="navbar">
								<div class="navbar-inner-custom">
									<?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Level Performances Levels  
								</div>
							</div>
							<b>Note : This feature is useful for Chairman or <?php echo $this->lang->line('program_owner_full'); ?> to define, modify and set different performance levels for each <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>)</b>
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_po_list_table_view" style="overflow:auto;"></div>
								</div>
							</div>
							<!--Tab three ends here -- >
							<!-- Tab four - course level assessment threshold starts here -->
							<div class="tab-pane fade active in" id="course_tab">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Direct Attainment Level for Course
								</div>
							</div>
							<b>Note : This feature is useful to define specific target levels for selected Course</b>
								<table style="width:100%;">
									<tr>
										<td>
											<label>Term: <span style="color:red;">*</span>
											<select name="term_data" id="term_data" class="term_data form-control">
												<option value="">Select Term</option>
											</select></label>
										</td>
										<td>
											<label>Course: <span style="color:red;">*</span>
											<select name="course_data" id="course_data" class="course_data form-control" onchange="is_crclm_attainment_exists();">
												<option value="">Select Course</option>
											</select></label>
										</td>
									</tr>
								</table>
								<!-- display course level assessment threshold -->
                                                                
								<div data-target="#navbarExample" class="bs-docs-example">
									<div id="no_crclm_data"></div>
									<div class="generate_course_table_view" style="overflow:auto;"></div>
									<br><br>
								</div>
								
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="bloom_level_display"></div>
							
								</div>
							</div>
							<!--Tab four ends -->
						</div>
					</div>
				</div>
				<br/>
			</div>
		</div><!--End of span10-->
	</div><!--row-fluid-->
</div><!--container-fluid-->


<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/attainment_level/attainment_level.js'); ?>" type="text/javascript"> </script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>