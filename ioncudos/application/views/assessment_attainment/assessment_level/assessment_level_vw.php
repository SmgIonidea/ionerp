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
				<div class="bs-docs-example">
					<!--content goes here-->
					<div class="navbar">
						<div class="navbar-inner-custom">
							Attainment Levels
						</div>
					</div>
					<br />
					<div id="loading" class="ui-widget-overlay ui-front">
						<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
					</div>
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
					<table>
						<tr>
							<td>
								<label>Program: <span class="err_msg">*</span></label>
								<select name="program_name" id="program_name" class="program_name form-control">
									<option value="">Select program</option>
									<?php
										if(!empty($program_list)){
											foreach($program_list as $data){
												echo "<option value='".$data["pgm_id"]."'>".$data['pgm_acronym']."</option>";
											}//End of For loop
										}//End of if
									?>
								</select>
							</td>
							<td>
								<label>Curriculum: <span style="color:red;">*</span></label>
								<select name="curriculum_data" id="curriculum_data" class="curriculum_name form-control">
									<option value="">Select Curriculum</option>
								</select>
							</td>
							<td>
								<label>Terms: <span style="color:red;">*</span></label>
								<select name="term_data" id="term_data" class="term_data form-control">
									<option value="">Select Term</option>
								</select>
							</td>
							<td>
								<label>Course: <span style="color:red;">*</span></label>
								<select name="course_data" id="course_data" class="course_data form-control">
									<option value="">Select Course</option>
								</select>
							</td>
						</tr>
					</table>
					<div class="bs-example bs-example-tabs">
						<ul id="myTab" class="nav nav-tabs">
							<li class="active"><a href="#bloom_level_tab" data-toggle="tab"> Program Level Attainment </a></li>
							<li><a href="#po_tab" data-toggle="tab"> Curriculum Level Attainment </a></li>
							<li><a href="#performance_tab" data-toggle="tab"> Performance Outcome Level (PO) </a></li>
							<li><a href="#course_tab" data-toggle="tab"> Course Level Attainment </a></li>
						</ul>
						<div id="myTabContent" class="tab-content">
							<!-- Tab one - Program Level Attainment starts here -->
							<div class="tab-pane fade in active" id="bloom_level_tab">
								<!-- display Program Level Attainment -->
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_pgm_table_view" style="overflow:auto;"></div>
									<form name="apl_form_add" id="apl_form_add" method="POST">
										<input type="hidden" name="pgm_level_counter" id="pgm_level_counter" value="0"/>
										<input type="hidden" name="counter" id="counter" value="0"/>
										<?php echo "<input type='hidden' name='apl_id_pgm' id='apl_id_pgm' class='apl_id_pgm'/>"; ?>
										<table class="table table-bordered table-stripped">
											<tr>
												<th>SI.No</th>
												<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
												<th>Attainment Level Value <span class="err_msg">*</span></th>
												<th>Student % <span class="err_msg">*</span></th>
												<th>Target % <span class="err_msg">*</span></th>
											</tr>
											<tr>
												<td><center>1.</center></td>
												<td><center><input type="text" name="apl_level_name" id="apl_level_name" placeholder="Level name" class="loginRegex required" required/></center></td>
												<td><center><input type="text" name="apl_level_value" id="apl_level_value" placeholder="Level Value" class="onlyDigit required" required/></center></td>
												<td><center><input type="text" name="apl_student_perc" id="apl_student_perc" placeholder="Student %" class=" onlyDigitrequired" required/></center></td>
												<td><center><input type="text" name="apl_target_perc" id="apl_target_perc" placeholder="Target %" class="onlyDigit required" required/></center></td>
											</tr>
										</table>
										<br/>
										<!--Save buttons-->
										<div class="pull-right">
											<button class="apl_add_btn btn btn-primary" id="apl_add_btn"><i class="icon-file icon-white"></i> save </button>
											<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
										</div>
										<br/>
									</form>									
								</div>
							</div><!--tab1 main div ends-->
							<!-- tab one ends here -->
							<!-- Tab two - Curriculum Level Assessment starts here -->
							<div class="tab-pane fade" id="po_tab">
								<!-- display Curriculum Level Assessment -->
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_crlm_table_view" style="overflow:auto;"></div>
									<form name="acl_form_add" id="acl_form_add" method="POST">
										<input type="hidden" name="crclm_id" id="crclm_id" />
										<table class="table table-bordered table-stripped">
											<tr>
												<th>SI.No</th>
												<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
												<th>Attainment Level Value <span class="err_msg">*</span></th>
												<th>Student % <span class="err_msg">*</span></th>
												<th>Target % <span class="err_msg">*</span></th>
											</tr>
											<tr>
												<td><center>1.</center></td>
												<td><center><input type="text" name="acl_level_name" id="acl_level_name" placeholder="Level name" class="loginRegex required" required/></center></td>
												<td><center><input type="text" name="acl_level_value" id="acl_level_value" placeholder="Level Value" class="onlyDigit required" required/></center></td>
												<td><center><input type="text" name="acl_student_perc" id="acl_student_perc" placeholder="Student %" class="onlyDigit required" required/></center></td>
												<td><center><input type="text" name="acl_target_perc" id="acl_target_perc" placeholder="Target %" class="onlyDigit required" required/></center></td>
											</tr>
										</table>
										<br/>
										<!--Save buttons-->
										<div class="pull-right">
											<button class="acl_add_btn btn btn-primary" id="acl_add_btn"><i class="icon-file icon-white"></i> save </button>
											<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
										</div>
										<br/>
									</form>	
								</div>
							</div><!--tab2 main div ends -->
							<!-- tab two ends here -->
							<!-- Tab three - performance assessment level POs-->
							<div class="tab-pane fade" id="performance_tab">
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_po_list_table_view" style="overflow:auto;"></div>
								</div>
							</div>
							<!--Tab three ends here -- >
							<!-- Tab four - course level assessment threshold starts here -->
							<div class="tab-pane fade" id="course_tab">
								<!-- display course level assessment threshold -->
								<div data-target="#navbarExample" class="bs-docs-example">
									<div class="generate_course_table_view" style="overflow:auto;"></div>
									<form name="acrsl_add_form" id="acrsl_add_form" method="POST">
										<input type="hidden" name="curriculum_id" id="curriculum_id" />
										<input type="hidden" name="term_id" id="term_id" />
										<input type="hidden" name="course_id" id="course_id" />
										<table class="table table-bordered table-stripped">
											<thead>
												<tr>
													<th>SI.No</th>
													<th>Attainment Level Name Alias <span class="err_msg">*</span></th>
													<th>Attainment Level Value <span class="err_msg">*</span></th>
													<th>Direct %  <span class="err_msg">*</span></th>
													<th>Indirect %  <span class="err_msg">*</span></th>
													<th>Scoring more than</th>
													<th>Target % <br/>(University average % marks ) <span class="err_msg">*</span></th>
													
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><center>1.</center></td>
													<td><center><input type="text" name="acrsl_level_name" class="input-large loginRegex required" id="acrsl_level_name" placeholder="Level Name"></center></td>
													<td><center><input type='text' name="acrsl_level_val" id="acrsl_level_val" class="input-mini onlyDigit required"/></center></td>
													<td><center><input type='text' name="acrsl_direct_perc" id="acrsl_direct_perc" class="input-mini onlyDigit required" style="text-align:right;"/></center></td>
													<td><center><input type='text' name="acrsl_indirect_perc" id="acrsl_indirect_perc" class="input-mini onlyDigit required" style="text-align:right;"/></center></td>
													<td><center><input type='text' name="acrsl_conditional_opr" id="acrsl_conditional_opr" class="input-mini required" value=">=" readonly='readonly' style="text-align:center;"/></center></td>
													<td><center><input type='text' name="acrsl_target_perc" id="acrsl_target_perc" class="input-mini onlyDigit required" style="text-align:right;"/></center></td>
												</tr>
											</tbody>
										</table>
										<div class="pull-right">
											<button class="acrsl_add_btn btn btn-primary" id="acrsl_add_btn"><i class="icon-file icon-white"></i> save </button>
											<button type="reset" class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
										</div>
									</form>
									
									<br><br>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/assessment_level.js'); ?>" type="text/javascript"> </script>
