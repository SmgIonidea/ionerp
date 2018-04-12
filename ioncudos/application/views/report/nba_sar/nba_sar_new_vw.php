
<?php
/**
* Description	:	Add View for Program Type Module.
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 20-08-2013		Mritunjay B S       NBA Self Assessment report
--------------------------------------------------------------------------------
*/
?>
<!--head here -->
	<?php 	$this->load->view('includes/head'); ?>
		<!--branding here-->
		<?php  $this->load->view('includes/branding'); ?>
		<!-- Navbar here -->
		<?php  $this->load->view('includes/navbar'); ?> 
		<div class="container-fluid">
			<div class="row-fluid">
				<!--sidenav.php-->
				<?php  $this->load->view('includes/sidenav_3'); ?>
				<div class="span10">
					<!-- Contents
			================================================== -->
					<section id="contents">
						<div class="bs-docs-example fixed-height">
							<!--content goes here-->	
							<div class="navbar">
								<div class="navbar-inner-custom">
									NBA Self Assessment Report
								</div>
								<div>
									<font color= "red" ><?php echo validation_errors(); ?></font>
								</div>
							</div>
	<div class="controls span12" id="nba_selection">
		<label for="nba_select_part_a" id="nba_select_part_a" class="span2">PART A &nbsp;&nbsp;<input class="controls nba_sar_part" type="radio" name="nba_select_part" id="nba_part_a" /></label>
		<label for="nba_select_part_b" id="nba_select_part_b" class="span2">PART B&nbsp;&nbsp;<input class="controls nba_sar_part" type="radio" name="nba_select_part" id="nba_part_b" /></label>
	</div>
							<!-- PART A Tab -->
	<div class="tabbable" id="part_a" style="display:none;"> <!-- Only required for left/right tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#insti_info" data-toggle="tab">Institutional Information</a></li>
			<li><a href="#dept_info" data-toggle="tab">Departmental Information</a></li>
			<li><a href="#prg_info" data-toggle="tab">Programme Specific Information</a></li>
		</ul>
		<div class="tab-content">
				<div class="tab-pane active" id="insti_info">
					<div class="control-group">
					<Strong>1.Institutional Information</strong>
					</div>
					<!--Institutional Information -->
						<div class="control-group">
							<label class="control-label" for="institute_info">1.1. Name and address of the institution and affiliating university</label>
							<div class="controls"> 
								<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="institute_info">1.2. Name, designation, telephone number, and e­mail address of the contact person for the NBA</label>
							<div class="controls"> 
								<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The name of the contact person, with other details, has to be listed here"></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<div class="">
								<label class="control-label" for="institute_info">1.3. History of the institution (including the date of introduction and number of seats of various programmes of study alongwith the NBA accreditation, if any) in a tabular form</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="History of the institution and its chronological development along with the past accreditation records need to be listed here listed here"></textarea>
									</div>
								</div><br>
							</div>
					</div>

					<div class="control-group">
						<div class="">
							<label class="control-label" for="institute_info">1.4. Ownership status: Govt. (central/state) / trust / society (Govt./NGO/private) / private/ other</label>
							<div>
								<div class="controls"> 
									<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Ownership status of the institute has to be listed here."></textarea>
								</div>
							</div><br>
						</div>
					</div>	
					
					<div class="control-group">
						<div class="">
							<label class="control-label" for="institute_info">1.5.1. Mission of the Institution</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Mission of the Institution."></textarea>
									</div>
								</div><br>
						</div>
					</div>	
					
					<div class="control-group">
						<div class="">
							<label class="control-label" for="institute_info">1.5.2. Vision of the Institution</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter vision of the Institution."></textarea>
									</div>
								</div><br>
						</div>
					</div>
						
					<div class="control-group">
						<div class="">
							<label class="control-label" for="institute_info">1.6. Organisational Structure</label>
								<div>
									<div class="controls"> 
										Organisational chart showing the hierarchy of academics and administration is to be included
									</div>
								</div><br>
						</div>
					</div>

					<div class="control-group">
						<div class="">
							<label class="control-label" for="institute_info">1.7. Financial status: Govt.(central/state)/ grants-in-aid/ not-for-profit/ private self-financing/ other</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
									</div>
								</div><br>
						</div>
					</div>	
					
					<div class="control-group">
						<div class="span12" id="nature_trust">
							<label class="control-label" for="institute_info">1.8. Nature of the trust/society</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Way of functioning and activities of the trust/society have to be listed here."></textarea>
									</div>
								</div>
								<label class="control-label" for="institute_info">list other institutions/colleges run by the trust/society</label>
									<div class="input-append" id="insti_list">
										<input type="text" name="" id="" placeholder="Name Of the Institution"/>
											<input type="text" name="yr" id="yr" placeholder="Year of Establishment"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<input type="text" name="" id="" placeholder="Location"/> 
													<button class="btn btn-primary" name="add_more" id="add_more"><i class="icon-plus-sign icon-white"></i> Add More</button>
									</div>
								<div id="insert_before"></div>
								<br>
						</div>
					</div>
					<div class="control-group">
						<div class="span12" id="sources_of_funds">
							<label class="control-label" for="institute_info">1.9. External sources of funds</label>
								<div>
									<div class="controls"> 
										<label class="control-label" for="institute_info">The different sources of the external funds over the last three financial years are to be listed here.</label>
									</div>
								</div>
									<div class="input-append span12" id="cfy">
										<input type="text" name="external_resource" id="external_resource" placeholder="Name of the external source"/>
										<input type="text" name="current_yr" id="current_yr" placeholder="Current Financial year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
										<button class="btn btn-primary" name="add_more" id="add_funds"><i class="icon-plus-sign icon-white"></i> Add More</button>
									</div>
										<div id="funds"></div>
										<br>
						</div>
					</div>
					
					<div class="control-group">
						<div class="span12" id="internal_sources_of_funds">
							<label class="control-label" for="institute_info">1.10. Internally acquired funds</label>
								<div>
									<div class="controls"> 
										<label class="control-label" for="institute_info">The different sources of the internal funds over the last three financial years are to be listed here.</label>
									</div>
								</div>
									<div class="input-append span12" id="internal_cfy">
										<input type="text" name="internal_resource" id="internal_resource" placeholder="Name of the internal source"/>
										<input type="text" name="int_current_yr" id="int_current_yr" placeholder="Current Financial year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
										<button class="btn btn-primary" name="add_more" id="int_add_funds"><i class="icon-plus-sign icon-white"></i> Add More</button>
									</div>
										<div id="int_funds"></div>
								<br>
						</div>
					</div>
					
					<div class="control-group">
						<div class="span12" id="scholarship_data">
							<label class="control-label" for="institute_info">1.11. Scholarships or any other financial assistance provided to students?</label>
								<div>
									<div class="controls"> 
										<label class="control-label" for="institute_info">If any scholarship or financial assistance is provided to the students then the details of such assistance over the last three financial years has to be listed here. Also mention needs to be made of the basis for the award of such scholarship.</label>
									</div>
								</div>
									<div class="input-append span12" id="internal_cfy">
										<input type="text" name="scholar_current_yr" id="scholar_current_yr" placeholder="Current Financial Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
										<input type="text" name="category" id="category" placeholder="Scholarship Category"/>
										<input type="text" name="scholarshp_assistance" id="scholarshp_assistance" placeholder="Scholarship Assistance"/>
										<input type="text" name="scholarshp_amount" id="scholarshp_amount" placeholder="Scholarship Amount"/>
										<button class="btn btn-primary" name="add_more" id="int_add_funds"><i class="icon-plus-sign icon-white"></i> Add More</button>
									</div>
										<div id="int_funds"></div>
										<br>
						</div>
					</div>
					
					<div class="control-group">
						<div class="span12" id="basic_criteria">
							<label class="control-label" for="institute_info">1.12. Basis/criterion for admission to the institution</label>
								<div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
									</div>
								</div>
								<div id="int_funds"></div>
								<br>
						</div>
					</div>
					
					<div class="control-group">
						<div class="span12" id="student_info">
							<label class="control-label" for="institute_info">1.13. Total number of engineering students</label>
								<div>
									<div class="controls"> 
										<label class="control-label" for="institute_info">Total number of engineering students, both boys and girls, has to be listed here. The data may be categorised in a tabular form under graduate or post graduate engineering, o r other programme, if applicable.</label>
									</div>
								</div>
									<div class="input-append span12" id="internal_cfy">
										<input type="text" name="student_current_yr" id="student_current_yr" placeholder="Current Academic Year"/>
										<input type="text" name="boys" id="boys" placeholder="Total No. of Boys"/>
										<input type="text" name="girls" id="girls" placeholder="Total No. of Girls"/>
										<input type="text" name="total_stud" id="total_stud" placeholder="Total No. of Students" readonly />
										<button class="btn btn-primary" name="add_more" id="student_num"><i class="icon-plus-sign icon-white"></i> Add More</button>
									</div>
									<div id="stud_num"></div>
								<br>
						</div>
					</div>
					
					<div class="control-group">
						<div class="span12" id="student_info">
							<label class="control-label" for="institute_info">1.14. Total number of employees</label>
								<div>
									<div class="controls"> 
										<label class="control-label" for="institute_info">Minimum and maximum number of staff on roll in the engineering institution, during the Current Academic Year (CAY) and the previous CAYs (1st July to 30th June)</label>
									</div>
								</div>
								<div class="controls">
								<strong>A:</strong> &nbsp; Regular Teaching Staff In Engineering
								</div>
										<div class="controls input-append span12">
												<input type="text" name="staff_current_yr" id="staff_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<input type="text" name="male_staff" id="male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>
												<input type="text" name="min_male_staff" id="min_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>
												<input type="text" name="female_staff" id="female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
												<input type="text" name="min_female_staff" class="input-medium" id="min_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
												<button class="btn btn-primary" name="add_more" id="regular_staff_add_btn"><i class="icon-plus-sign icon-white"></i> Add More</button>
										</div>
											<div class="controls">
											<strong>&nbsp;&nbsp;</strong> &nbsp; Regular Teaching staff in science & humanities
											</div>
								
											<div class="controls input-append span12">
													<input type="text" name="gen_staff_current_yr" id="gen_staff_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>&nbsp;&nbsp;
													<input type="text" name="gen_male_staff" id="gen_male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>&nbsp;&nbsp;
													<input type="text" name="gen_min_male_staff" id="gen_min_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>&nbsp;&nbsp;
													<input type="text" name="gen_female_staff" id="gen_female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
													<input type="text" name="gen_min_female_staff" class="input-medium" id="gen_min_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
													<button class="btn btn-primary" name="add_more" id="regular_staff_add_btn_one"><i class="icon-plus-sign icon-white"></i> Add More</button>
											</div>
											
											<div class="controls">
											<strong>&nbsp;&nbsp;</strong> &nbsp;Regular Non-Teaching Staff
											</div>
								
											<div class="controls input-append span12">
											<div class="input-append date">
													<input type="text" name="non_teach_current_yr" id="non_teach_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span></div>
													<input type="text" name="non_teach_male_staff" id="non_teach_male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>
													<input type="text" name="min_non_teach_male_staff" id="min_non_teach_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>
													<input type="text" name="non_teach_female_staff" id="non_teach_female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
													<input type="text" name="min_non_teach_female_staff" class="input-medium" id="min_non_teach_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
													<button class="btn btn-primary" name="add_more" id="non_teach_btn"><i class="icon-plus-sign icon-white"></i> Add More</button>
											</div>
											
											<div class="controls">
								<strong>B:</strong> &nbsp; Contract Teaching Staff In Engineering
								</div>
										<div class="controls input-append span12">
												<input type="text" name="b_staff_current_yr" id="b_staff_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<input type="text" name="b_male_staff" id="b_male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>
												<input type="text" name="b_min_male_staff" id="b_min_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>
												<input type="text" name="b_female_staff" id="b_female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
												<input type="text" name="b_min_female_staff" class="input-medium" id="b_min_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
												<button class="btn btn-primary" name="add_more" id="b_regular_staff_add_btn"><i class="icon-plus-sign icon-white"></i> Add More</button>
										</div>
											<div class="controls">
											<strong>&nbsp;&nbsp;</strong> &nbsp; Contract Teaching staff in science & humanities
											</div>
								
											<div class="controls input-append span12">
													<input type="text" name="b_gen_staff_current_yr" id="b_gen_staff_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
													<input type="text" name="b_gen_male_staff" id="b_gen_male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>
													<input type="text" name="b_gen_min_male_staff" id="b_gen_min_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>
													<input type="text" name="b_gen_female_staff" id="b_gen_female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
													<input type="text" name="b_gen_min_female_staff" class="input-medium" id="b_gen_min_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
													<button class="btn btn-primary" name="add_more" id="b_gen_regular_staff_add_btn_one"><i class="icon-plus-sign icon-white"></i> Add More</button>
											</div>
											
											<div class="controls">
											<strong>&nbsp;&nbsp;</strong> &nbsp; Contract Non-Teaching Staff
											</div>
								
											<div class="controls input-append span12">
													<input type="text" name="b_non_teach_current_yr" id="b_non_teach_current_yr" class="input-medium" placeholder="Current Academic Year"/><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
													<input type="text" name="b_non_teach_male_staff" id="b_non_teach_male_staff" class="input-medium" placeholder="Maximum Male Teaching staff in Engg"/>
													<input type="text" name="b_min_non_teach_male_staff" id="b_min_non_teach_male_staff" class="input-medium" placeholder="Minimum Male Teaching staff in Engg"/>
													<input type="text" name="b_non_teach_female_staff" id="b_non_teach_female_staff" class="input-medium" placeholder="Maximum Female Teaching staff in Engg"/>
													<input type="text" name="b_min_non_teach_female_staff" class="input-medium" id="b_min_non_teach_female_staff" placeholder="Minimum Female Teaching staff in Engg"/>
													<button class="btn btn-primary" name="add_more" id="b_non_teach_btn"><i class="icon-plus-sign icon-white"></i> Add More</button>
											</div>
											
											<div id="stud_num"></div>
											<br>
						</div>
					</div>
					
					<div class="control-group pull-right">
					<br><br><br>
						<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
						<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
						<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
					</div>
				</div>
								
				<div class="tab-pane" id="dept_info">
					<div class="control-group">
						<Strong>2.&nbsp;Departmental Information</strong>
					</div>
					
						<div class="control-group">
							<label class="control-label" for="dept_info">2.1. Name and address of the department</label>
							<div class="controls"> 
								<textarea id="dept_info" name="dept_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="contact_info">2.2. Name, designation, telephone number, and email address of the contact person for the NBA</label>
							<div class="controls"> 
								<textarea id="contact_info" name="contact_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
							</div>
						</div>
						<div class="control-group" id="dept_prog_data">
							<label class="control-label" for="dept_hist_info">2.3. History of the department including date of introduction and number of seats of various programmes of study along with the NBA accreditation ,if any</label>
							<div class="controls ">
								<div class="controls span3"> 
									<select name="dept_prog" id="dept_prog">
										<option>Select Program</option>
										<option>Bachelor of Engineering in Computer Science</option>
										<option>Master of Technology in Mechanical</option>
										<option>Master of Technology in Computer Science</option>
									</select>
								</div>
								<div class="controls span9"> 
									<textarea id="dept_prog_desc" name="dept_prog_desc" rows="3" cols="20" style="margin: 0px 0px 10px; width: 656px; height: 50px;" placeholder="Enter Details Here"></textarea>
									<button name="dept_prog_hist_add_btn" id="dept_prog_hist_add_btn" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> Add More</button>
								</div>
								<div id="dept_prog_add">
								</div>
							</div>
							<br>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="mision_vision">2.4. Mission and Vision of the Department</label>
							<div class="controls"> 
								<textarea id="mision_vision" name="mision_vision" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The department is required to specify its Mission and Vision."></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="mision_vision">2.5. List of the programmes/ departments which share human resources and/or the facilities of this programmes/ departments (in %)</label>
							<div class="controls"> 
								<textarea id="mision_vision" name="mision_vision" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution needs to mention the different programmes being run in the department which share the human resources and facilities with this department/programme being accredited."></textarea>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="total_num_stud">2.6. Total number of students</label>
							<div class="controls" id="dept_stud_num">
								<div class="input-append" id="dept_stud_data">
									<input type="text" name="dept_curnt_yr" id="dept_curnt_yr" placeholder="Current Academic Year" />
									<input type="text" name="ug_stud_num" id="ug_stud_num" placeholder="Total No. of UG Students" />
									<input type="text" name="pg_stud_num" id="pg_stud_num" placeholder="Total No. of PG Students" />
									<input type="text" name="total_stud" id="total_stud" placeholder="Total No. Students" />
									<a class="btn btn-primary" name="stud_num_add" id="stud_num_add"><i class="icon-plus-sign icon-white"></i>Add More</a>	
								</div>
								<div id="dept_stud_data_one">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="total_num_stud">2.7. Minimum and maximum number of staff on roll during the current and three previous academic years (1st July to 30th June) in the department</label>
							<div class="controls" id="dept_teach_detail">
								<div id="label_dept_teach_data">
									<label class="control-label" for="dept_teach_data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Teaching staff in the department</label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="input-append" id="dept_teach_data">
											<input type="text" name="dept_teach_curnt_yr" id="dept_teach_curnt_yr" placeholder="Current Academic Year" />
											<input type="text" name="max_teach_staff" id="max_teach_staff" placeholder=" Maximum Teaching staff in the department" />
											<input type="text" name="min_teach_staff" id="min_teach_staff" placeholder="Minimum Teaching staff in the department" />
											<input type="text" name="total_teach_staff" id="total_teach_staff" placeholder="Total No of Teaching staff in the department" />
											<a class="btn btn-primary" name="teach_num_add" id="teach_num_add"><i class="icon-plus-sign icon-white"></i>Add More</a>	Total No of Teaching staff in the department
										</div>
									<div id="teaching_staff">
									</div>
								</div>
								<div id="label_dept_non_teach_data">
									<label class="control-label" for="dept_non_teach_data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Non-Teaching staff in the department</label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="input-append" id="dept_non_teach_data">
											<input type="text" name="dept_non_teach_curnt_yr" id="dept_non_teach_curnt_yr" placeholder="Current Academic Year" />
											<input type="text" name="max_non_teach_staff" id="max_non_teach_staff" placeholder=" Maximum Non-Teaching staff in the department" />
											<input type="text" name="min_non_teach_staff" id="min_non_teach_staff" placeholder="Minimum Non-Teaching staff in the department" />
											<input type="text" name="total_non_teach_staff" id="total_non_teach_staff" placeholder="Total No of Non-Teaching staff in the department" />
											<a class="btn btn-primary" name="non_teach_num_add" id="non_teach_num_add"><i class="icon-plus-sign icon-white"></i>Add More</a>	
										</div>
									<div id="non_teaching_staff">
									</div>
								</div>
								<div id="dept_stud_data_one">
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="total_num_stud">2.7.1. Summary of budget for the CFY and the actual expenditure incurred in the CFYm1, CFYm2 and CFYm3 (for the Department)</label>
							<div class="controls" id="dept_budget">
								<div class="" id="budget">
									<table class="table table-bordered">
										<tr>
											<th>Items</th>
											<th>Budgeted in Current Financial Year</th>
											<th>Actual Expenses In Current Financial Year</th>
										</tr>
										
										<tr>
											<td>Laboratory Equipment</td>
											<td><input type="text" name="lab_equip_budget" id="lab_equip_budget" placeholder="" /></td>
											<td><input type="text" name="lab_equip_expenses" id="lab_equip_expenses" placeholder="" /></td>
										</tr>
										<tr>
											<td class="pull-center">Software</td>
											<td><input type="text" name="soft_budget" id="soft_budget" placeholder="" /></td>
											<td><input type="text" name="soft_expense" id="soft_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Laboratory consumable</td>
											<td><input type="text" name="lab_consumable_budget" id="lab_consumable_budget" placeholder="" /></td>
											<td><input type="text" name="lab_consumable_expense" id="lab_consumable_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Maintenance and spares</td>
											<td><input type="text" name="spares_budget" id="spares_budget" placeholder="" /></td>
											<td><input type="text" name="spares_expense" id="spares_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Training and Travel</td>
											<td><input type="text" name="training_budget" id="training_budget" placeholder="" /></td>
											<td><input type="text" name="training_expense" id="training_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Miscellaneous expenses for academic activities</td>
											<td><input type="text" name="miscel_budget" id="miscel_budget" placeholder="" /></td>
											<td><input type="text" name="miscel_expense" id="miscel_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Total</td>
											<td><input type="text" name="total_budget" id="total_budget" placeholder="" /></td>
											<td><input type="text" name="total_expense" id="total_expense" placeholder="" /></td>
										</tr>
								</table>
								<div class="pull-right" id="add_button">
									<a class="btn btn-primary" name="budget_add" id="budget_add"><i class="icon-plus-sign icon-white"></i> Add More</a>
								</div>
								</br>
									<div id="budget_div">
									</div>
								</div>
								
							</div>
						</div>
						
						<div class="control-group pull-right">
							<br><br><br>
						<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
						<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
						<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
					</div>
				</div>
				<div class="tab-pane" id="prg_info">
				<!-- Jevi's Code Starts from here-->
				
				<fieldset>
					<div class="control-group">
					<b>3. Programme Specific Information</b>
					</div>
					<!--Programme Information -->
													  
						    <div class="control-group">
							<label class="control-label" for="institute_info">3.1	Name of the Programme</label>
							<div class="row-fluid">
									  <div class="span1">
                                        <label class="control-label " for="name">UG in:<font color="red"><b>*</b></font></label>
									  </div>
									  <div class="span11">
                                            <input  class="span5" type="text" id="name_of_prgm" placeholder="Name of the Programme">
									  </div>	
							</div>
							</div>
							
							
							<div class="control-group ">
                                      <label class="control-label" for="institute_info">3.2		Title of the Degree</label>
							</div>
							
							<div class="row-fluid">
							  <div class="span3">
								<div class="controls">
										<select placeholder="Title of the Degree">
							 
										</select>
								</div>							  
							  </div>
							  <div class="span3">
								<div class="controls">
									<input id="pgm_title_last" name="pgm_title_last" type="text" style="background-color:#D1DEE4; font-weight:bold;" size="10" value="" readonly="">
										</div>
								</div>
								<div class="span3"></div>
								<div class="span3"></div>
							</div>
							
								
							<div class="control-group">
                                        <label class="control-label" for="institute_info">3.3	Details of Programme Coordinator</label>
                            </div>
							  		<div class="row-fluid">
									  <div class="span3">
										<div class="controls">
                                            <input type="text" name="name" value="" id="name" class="required submit11" placeholder="Name">
										</div>
									  </div>
									  
									   
									  <div class="span3">
										<div class="control-group">
									
											<div class="controls">
												<select name="designation_id" class="required">

												</select>    
											</div>
										</div>
									   </div>
									   									   
									   
									  <div class="span3">
										<div class="control-group">
                                       
											<div class="controls">
												<input type="text" name="phone_number" value="" id="phone_number" class="required submit11" placeholder="Phone Number" >   
											</div>
										</div>
									  </div>
									  
									  
									  <div class="span3">
										<div class="control-group">
										
											<div class="controls">
											<input type="text" name="email" value="" id="email" class="required submit11" placeholder="Email Id" >            
											</div>
											<?php// echo form_input($email); ?>
										</div>
									   </div>
									</div>						

						<div class="control-group">
							<label class="control-label" for="dept_hist_info">3.4 History of the programme along with the NBA accreditation, if any:</label>
							
						<div class="row-fluid">
						  <div class="span3">
						  <div class="controls">
								<div class="controls span3"> 
									<select name="dept_prog" id="dept_prog">
										<option>Select Program</option>
										<option>Bachelor of Engineering in Computer Science</option>
										<option>Master of Technology in Mechanical</option>
										<option>Master of Technology in Computer Science</option>
									</select>
								</div>
							</div>
						  </div>
						  
						  <div class="span9">
						  <div class="form-inline">
								<label class="control-label" for="dept_hist_info">Started with </label>
								<input class="input-small span2" name="term_min_duration" id="term_min_duration" type="text">
								<label class="control-label" for="dept_hist_info">seats in  </label>
								<div class="input-append date">
														<input type="text" class="span12 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
														<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
													
								</div>
							</div>
							&nbsp
								
								<div id="intake_increased">
									<div class="form-inline" id="intake_inc">
									<label class="control-label" for="intake_increase">Intake increased to</label>
									<input class="input-small span2" name="term_min_duration" id="term_min_duration" type="text">
									<label class="control-label" for="intake_increase">in </label>
										<div class="input-append date">
																<input type="text" class="span12 required yearpicker" id="intake_start_year" name="intake_start_year" readonly="" onchange="populate_year();">
																<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
																<button class="btn btn-primary add_intake" name="add_intake" id="add_intake"><i class="icon-plus-sign icon-white"></i></button>
															
										</div>
									</div>
								<br>
									<div id="intake"></div>
								</div>
								
								<div class="form-inline">
								<label class="control-label" for="dept_hist_info">Accredited in </label>
								<div class="input-append date">
									<input type="text" class="span12 required yearpicker" id="accredited_year" name="accredited_year" readonly="" onchange="populate_year();">
									<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>					
																		
								</div>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="institute_info">3.5	Deficiencies, weaknesses/concerns from previous accreditations:</label>
                                 <div>
									<div class="controls"> 
										<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder=""></textarea>
									</div>
								</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="institute_info">3.6	Total number of students in the programme:</label>
                                 
									<div class="controls"> 
										<input class="input-large" name="term_min_duration" id="term_min_duration" type="text" placeholder="Total number of students">
									</div>
								
						</div>
						
						<div class="control-group">
							<label class="control-label" for="institute_info">3.7	Minimum and maximum number of staff for the current and three
								previous academic years (1st July to 30th June) in the programme:</label>
						</div>
							<div class="staff_stat">
							<div class="staff_stat" id="staff">
							
							<div class="row-fluid">
									  <div class="span3">
										<div class="controls">
                                            <input type="text" name="min_staff_prgm" value="" id="min_staff_prgm" class="required submit11" placeholder="Minimum teaching staff">
										</div>
									  </div>
									  
									   
									  <div class="span3">
										<div class="control-group">
									
											<div class="controls">
												<input type="text" name="max_staff_prgm" value="" id="max_staff_prgm" class="required submit11" placeholder="Maximum teaching staff">   
											</div>
										</div>
									   </div>
									   									   
									   
									  <div class="span3">
										<div class="control-group">
                                       
											<div class="controls">
												<input type="text" name="min_non_staff_prgm" value="" id="min_non_staff_prgm" class="required submit11" placeholder="Minimum Non teaching staff" >   
											</div>
										</div>
									  </div>
									  
									  
									  <div class="span3">
										<div class="control-group">
										
											<div class="controls">
											<input type="text" name="max_non_staff_prgm" value="" id="max_non_staff_prgm" class="required submit11" placeholder="Maximum Non teaching staff" >            
											</div>
											<?php// echo form_input($email); ?>
										</div>
									   </div>
									   <button class="btn btn-primary add_staff_prgm" name="add_staff_prgm" id="add_staff_prgm"><i class="icon-plus-sign icon-white"></i></button>									
									</div>
									<br>
									<div id="intake_staff"></div>
									</div>
									</div>
									
						<div class="control-group">
							<label class="control-label" for="total_num_stud">3.8	Summary of budget for the CFY and the actual expenditure incurred in
							the CFYm1, CFYm2 and CFYm3 (exclusively for this programme in the department):</label>
							<div class="controls" id="dept_budget">
								<div class="" id="budget">
									<table class="table table-bordered">
										<tr>
											<th>Items</th>
											<th>Budgeted in Current Financial Year</th>
											<th>Actual Expenses In Current Financial Year</th>
										</tr>
										
										<tr>
											<td>Laboratory Equipment</td>
											<td><input type="text" name="lab_equip_pgm_budget" id="lab_equip_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="lab_equip_pgm_expenses" id="lab_equip_pgm_expenses" placeholder="" /></td>
										</tr>
										<tr>
											<td class="pull-center">Software</td>
											<td><input type="text" name="soft_pgm_budget" id="soft_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="soft_pgm_expense" id="soft_pgm_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Laboratory consumable</td>
											<td><input type="text" name="lab_consumable_pgm_budget" id="lab_consumable_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="lab_consumable_pgm_expense" id="lab_consumable_pgm_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Maintenance and spares</td>
											<td><input type="text" name="spares_pgm_budget" id="spares_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="spares_pgm_expense" id="spares_pgm_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Training and Travel</td>
											<td><input type="text" name="training_pgm_budget" id="training_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="training_pgm_expense" id="training_pgm_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Miscellaneous expenses for academic activities</td>
											<td><input type="text" name="miscel_pgm_budget" id="miscel_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="miscel_expense" id="miscel_expense" placeholder="" /></td>
										</tr>
										<tr>
											<td>Total</td>
											<td><input type="text" name="total_pgm_budget" id="total_pgm_budget" placeholder="" /></td>
											<td><input type="text" name="total_pgm_expense" id="total_pgm_expense" placeholder="" /></td>
										</tr>
								</table>
								<div class="pull-right" id="add_button">
									<a class="btn btn-primary" name="budget_pgm_add" id="budget_pgm_add"><i class="icon-plus-sign icon-white"></i> Add More</a>
								</div>
								</br>
									<div id="budget_pgm_div">
									</div>
								</div>
							</div>
						</div>
					</div>		
				</fieldset>
				
				<!-- Jevi Code Ends here-->
				<div class="control-group pull-right">
					<br><br><br>
						<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
						<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
						<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
				</div>
				
				</div>
		</div>
							</div>
							<!-- PART A Tab Ends Here -->
							<!-- PART B Tab --> 
							<div class="tabbable" id="part_b" style="display:none;"> <!-- Only required for left/right tabs -->
							  <ul id="myTab"class="nav nav-tabs">
								<li class="active"><a href="#vision" data-toggle="tab">Vision Mission & Objectives </a></li>
								<li class="dropdown" id="program"><a  class="dropdown-toggle" data-toggle="dropdown" href="" data-toggle="tab">Programme</a>
								<ul class="dropdown-menu">
								<li><a data-toggle="tab"  href="#program_outcome"><?php echo $this->lang->line('student_outcome_full'); ?></a></li>
								<li><a data-toggle="tab"  href="#program_curriculum">Program Curriculum</a></li>
								</ul>
								</li>
								<li class="dropdown" ><a href="#performance" data-toggle="tab">Performance</a>
								<ul class="dropdown-menu">
								<li><a data-toggle="tab"  href="#student_performance">Students Performance</a></li>
								<li><a data-toggle="tab"  href="#faculty_performance">Faculty Contribution</a></li>
								</ul>
								</li>
								<li><a href="#felicities" data-toggle="tab">Facilities and Technical</a></li>
								<li><a href="#academic" data-toggle="tab">Academic Support Units</a></li>
								<li><a href="#governance" data-toggle="tab">Governance</a></li>
								<li><a href="#improvement" data-toggle="tab">Continuous Improvement</a></li>
							  </ul>
							<div class="tab-content">
								<div class="tab-pane active" id="vision">
								<div class="control-group">
								<div class="control-group">
									<strong>1.Vision, Mission and Programme Educational Objectives</strong>
								</div>
								<div class="control-group">
									<strong>1.1.Vision and Mission</strong>
								</div>
										<div class="control-group">
												<label class="control-label" for="vision_mision">1.1.1. State the Vision and Mission of the institute and department</label>
												<div class="controls"> 
													<textarea id="vision_mision" name="vision_mision" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="List and articulate the vision and mission statements of the institute and department"></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="vision_mision_published">1.1.2. Indicate how and where the Vision and Mission are published and disseminated</label>
												<div class="controls"> 
													<textarea id="vision_mision_published" name="vision_mision_published" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe in which media (e.g. websites, curricula, books, etc.) the vision and mission are published and how these are disseminated among stakeholders."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="vision_mision_process">1.1.3. Mention the process for defining Vision and Mission of the department</label>
												<div class="controls"> 
													<textarea id="vision_mision_process" name="vision_mision_process" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Articulate the process involved in defining the vision and mission of the department from the vision and mission of the institute."></textarea>
												</div>
										</div>
										
										<div class="control-group">
											<strong>1.2. Programme Educational Objectives</strong>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="vision_mision_process">1.2.1. Describe the Programme Educational Objectives (PEOs)</label>
												<div class="controls">
													<div class="controls">
														<select size="1" id="crclm" name="crclm">
															<option value="5"> B. E in CSE  2010-2014 </option>
															<option value="6"> M.Tech in CSE  2012-2014 </option>
															<option value="7"> M.Tech in CSE  2015-2017 </option>
															<option value="8"> M.Tech in CSE  2018-2020 </option>
														</select>
													</div>
													<table class='table table-bordered'>
														<tr>
															<th>Program Educational Objectives (PEO's)</th>
														</tr>
														<tr>
															<td>1. Design, Construct and Deploy and Maintain Computing Solutions meeting the needs of the stakeholders.</td>
														</tr>
														<tr>
															<td>2. Work in multi-cultural environments and lead teams</td>
														</tr>
														<tr>
															<td>3. Provide solutions respecting the legal and ethical standards of governance and society.</td>
														</tr>
														<tr>
															<td>4. Contribute to profession through lifelong learning.</td>
														</tr>
													</table>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="peo_published">1.2.2. State how and where the PEOs are published and disseminated</label>
												<div class="controls"> 
													<textarea id="peo_published" name="peo_published" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe in which media (e.g. websites, curricula, books, etc.) the PEOs are published and how these are disseminated among stakeholders."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="peo_published">1.2.3. List the stakeholders of the programme</label>
													<div class="controls"> 
														<select size="1" id="crclm_for_stakeholder" name="crclm_for_stakeholder">
															<option value="5"> B. E in CSE  2010-2014 </option>
															<option value="6"> M.Tech in CSE  2012-2014 </option>
															<option value="7"> M.Tech in CSE  2015-2017 </option>
															<option value="8"> M.Tech in CSE  2018-2020 </option>
														</select>
													</div>
													<div class="controls"> 
													</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="peo_published">1.2.4. State the process for establishing the PEOs</label>
												<div class="controls"> 
													<textarea id="peo_published" name="peo_published" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe in which media (e.g. websites, curricula, books, etc.) the PEOs are published and how these are disseminated among stakeholders."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="consistency_peo">1.2.5. Establish consistency of the PEOs with the Mission of the institute</label>
												<div class="controls"> 
													<textarea id="consistency_peo " name="consistency_peo" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe how the Programme Educational Objectives are consistent with the Mission of the department."></textarea>
												</div>
										</div>
										
										<div class="control-group">
											<strong>1.3. Achievement of Programme Educational Objectives</strong>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="achievement_of_peo">1.3.1. Justify the academic factors involved in achievement of the PEOs</label>
												<div class="controls"> 
													<textarea id="achievement_of_peo " name="achievement_of_peo" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe the broad curricular components that contribute towards the attainment of the Programme Educational Objectives."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="admin_help_for_peo">1.3.2. Explain how administrative system helps in ensuring the achievement of the PEOs</label>
												<div class="controls"> 
													<textarea id="admin_help_for_peo " name="admin_help_for_peo" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe the committees and their functions, working process and related regulations."></textarea>
												</div>
										</div>
										
										<div class="control-group">
											<strong>1.4. Assessment of the achievement of Programme Educational Objectives</strong>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="admin_help_for_peo">1.4.1. Indicate tools and processes used in assessment of the achievement of the PEOs</label>
												<div class="controls"> 
													<textarea id="admin_help_for_peo " name="admin_help_for_peo" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe the committees and their functions, working process and related regulations."></textarea>
												</div>
										</div>
								</div>	
									
								<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
								</div>	
							</div>
								
								<div class="tab-pane" id="program_outcome">
									
									<!--Jevi Code Starts From here-->
									<div class="control-group">
									<strong>2. Programme Outcomes</strong>
									</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info"><i><b>2.1.Definition and Validation of Course Outcomes and Programme Outcomes</b></i></label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.1.1. List the Course Outcomes(COs) and Programme Outcomes (POs)</label>
										</div>
										<div class="controls"> 
											<select name="pgm_title" class="required target span4" id="pgm_id">
											<option value="">Select Program</option>
											<option value="2">Bachelor of Engineering in Computer Science</option>
											<option value="3">Master of Technoloy in Computer Networks</option>
											<option value="4">Master of Techology  in Computer Science</option>
											</select>
										</div>
										<table class="table table-bordered">
										  <thead>
											<tr>
											  <th>Sl. No</th>
											  <th>PO</th>
											</tr>
										  </thead>
										  <tbody>
											<tr>
											  <td>a. </td>
											  <td>Ability to apply knowledge of math, science and engineering to provide computing solutions.</td>
											</tr>
											<tr>
											  <td>b. </td>
											  <td>An ability to analyze a problem and identify and define the computing requirements appropriate to its solution.</td>
											</tr>
											<tr>
											  <td>c. </td>
											  <td>An ability to design and implement a computer based system process component or program to meet desired needs.</td>
											</tr>
											<tr>
											  <td>d. </td>
											  <td>Ability to function on multidisciplinary teams.</td>
											</tr>
											<tr>
											  <td>e. </td>
											  <td>Ability to identify, formulate problems and design, construct software systems of varying complexity.</td>
											</tr>
											<tr>
											  <td>f. </td>
											  <td>An understanding of professional and ethical responsibility</td>
											</tr>
										  </tbody>
										</table>
										<br>
										<div class="controls"> 
											<select name="pgm_title" class="required target span4" id="pgm_id" >
											<option value="">Select Course</option>
											<option value="2">Advanced C Programming</option>
											<option value="3">Information Security</option>
											</select>
										</div>
										<table class="table table-bordered">
										  <thead>
											<tr>
											  <th>Sl. No</th>
											  <th>CO</th>
											</tr>
										  </thead>
										  <tbody>
											<tr>
											  <td>1. </td>
											  <td>Illustrate the processes followed in securing the information.</td>
											</tr>
											<tr>
											  <td>2. </td>
											  <td>Analyze the cryptographic encoding & decoding techniques.</td>
											</tr>
											<tr>
											  <td>3. </td>
											  <td>Analyze the given framework of security for an application.</td>
											</tr>
											
										  </tbody>
										</table>
										<!--<div class="row-fluid">
										  <div class="span6">
											<div class="control-group">
												<div class="controls">
												   <div class="input-append">
													   <input class="input-xlarge" id="msisdn" size="" type="text" name="msisdn"><button
													   class="btn btn-primary" type="submit"><i class="icon-plus icon-white"></i></button>
												   </div>
											   </div>
											</div>
										  </div>
										  <div class="span6">
											<div class="control-group">
												<div class="controls">
												   <div class="input-append">
													   <input class="input-xlarge" id="msisdn" size="" type="text" name="msisdn"><button
													   class="btn btn-primary" type="submit"><i class="icon-plus icon-white"></i></button>
												   </div>
											   </div>
											</div>
										  </div>
										</div>-->
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.1.2. State how and where the POs are published and disseminated</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe in which media (e.g. websites, curricula, books, etc.) the POs are published and how these are disseminated among stakeholders"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.1.3. Indicate processes employed for defining of the POs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe the process that periodically documents and demonstrates that the POs are defined in alignment with the graduate attributes prescribed by the NBA."></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.1.4. Indicate how the defined POs are aligned to the Graduate Attributes prescribed by the NBA</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Indicate how the POs defined for the programme are aligned with the Graduate Attributes of NBA as articulated in accreditation manual"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.1.5. Establish the correlation between the POs and the PEOs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Explain how the defined POs of the program correlate with the PEOs"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info"><i><b>2.2. Attainment of Programme Outcomes</b></i></label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.2.1. Illustrate how course outcomes contribute to the POs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Provide the correlation between the course outcomes and the programme outcomes. The strength of the correlation may also be indicated"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.2.2. Explain how modes of delivery of courses help in attainment of the POs</label>
										</div>
										<div id="course_delivery_mode">
										<div class="control-group" id="course_mode">
											<div class="controls">
												<div class="input-append">
													
													<input id="Sl_no" class="span1 required digits" type="text" name="Sl_no" value="1" readonly="">&nbsp
													<input class="input-xxlarge" placeholder="Modes of delivery of courses" id="inputIcon" type="text">
																								
													<button class="btn btn-primary add_delivery_mode_of_course" id="add_delivery_mode_of_course"><i class="icon-plus icon-white"></i></button>
												</div>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe the different course delivery methods/modes (e.g. lecture interspersed with discussion, asynchronous mode of interaction, group discussion, project, etc.) used to deliver the courses and justify the effectiveness of these methods for the attainment of the POs."></textarea>
												
											</div>
										</div>
										<br>
										<div id="mode"></div>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.2.3. Indicate how assessment tools used to assess the impact of delivery of course/course content contribute towards the attainment of course outcomes/programme outcomes</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Describe different types of course assessment and evaluation methods (both direct and indirect) in practice and their relevance towards the attainment of POs."></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.2.4. Indicate the extent to which the laboratory and project course work are contributing towards attainment of the POs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Justify the balance between theory and practical for the attainment of the POs ."></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info"><i><b>2.3. Evaluation of the attainment of the Programme Outcomes</b></i></label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.3.1. Describe assessment tools and processes used for assessing the attainment of each PO</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">Include information on</label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">a) Listing and description of the assessment processes used to gather the data upon which the evaluation of each the programme outcome is based. Examples of data collection processes may include, but are not limited to, specific exam questions, student portfolios, internally developed
										assessment exams, project presentations, nationally-normed exams, oral exams, focus groups,industrial advisory committee;</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info">b) The frequency with which these assessment processes are carried out.</label>
										</div>
										<div class="controls"> 
											<input type="text" name="miscel_pgm_budget" id="miscel_pgm_budget" placeholder="" />
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.3.2. Indicate results of evaluation of each PO</label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">c) The expected level of attainment for each of the <?php echo $this->lang->line('student_outcome_full'); ?>;</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">d) Summaries of the results of the evaluation processes and an analysis illustrating the extent to which	each of the programme outcomes are attained; and</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">e) How the results are documented and maintained.</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										
										<div class="control-group">
                                        <label class="control-label" for="institute_info"><i><b>2.4. Use of evaluation results towards improvement of the programme</b></i></label>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.4.1. Indicate how the results of evaluation used for curricular improvements</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Articulate with rationale the curricular improvements brought in after the review of the attainment of the POs"></textarea>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.4.2. Indicate how results of evaluation used for improvement of course delivery and assessment</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Articulate with rationale the curricular delivery and assessment improvements brought in after the review of the attainment of the POs"></textarea>
										</div>
										<div class="control-group">
                                        <label class="control-label" for="institute_info">2.4.3. State the process used for revising/redefining the POs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Articulate with rationale how the results of the evaluation of the POs have been used to review/redefine the POs in line with the Graduate Attributes of the NBA"></textarea>
										</div>
									
									<!--Jevi Code Ends here-->
									<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
								</div>
									
								</div>
								
								<div class="tab-pane" id="program_curriculum">
									
									<!--Jevi's Code Starts Here-->
									
									
									<!--Jevi's Code EndsHere-->
									<!--Button Code Starts from here-->
									<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
								</div>
								<!--Button Code Ends Here-->
								</div>
								
								<div class="tab-pane" id="student_performance">
									<div class="control-group">
										<strong>4. Students Performance</strong>
									</div>
									
									<div class="control-group">
												<label class="control-label" for="admin_help_for_peo">Admission intake in the programme</label>
												<div class="controls"> 
													<table class="table table-bordered">
													<tr>
														<th>
															Item
														</th>
														<th>
															Current Academic Year
														</th>
													</tr>
													<tr>
														<td>
															Sanctioned intake strength in the programme (N)
														</td>
														<td>
															<input type="text" name="total_budget" id="total_budget" placeholder="" />
														</td>
													</tr>
													<tr>
														<td>
															Total number of admitted students in first year minus number of students migrated to other programmes at the end of 1st year (N1)
														</td>
														<td>
															<input type="text" name="total_budget" id="total_budget" placeholder="" />
														</td>
													</tr>
													<tr>
														<td>
															Number of admitted students in 2nd year in the same batch via lateral entry (N2)
														</td>
														<td>
															<input type="text" name="total_budget" id="total_budget" placeholder="" />
														</td>
													</tr>
													<tr>
														<td>
															Total number of admitted students in the programme (N1 + N2)
														</td>
														<td>
															<input type="text" name="total_budget" id="total_budget" placeholder="" />
														</td>
													</tr>
													</table>
												</div>
										</div>
										
										<div class="control-group">
											<strong>4.4 Professional Activities</strong>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="prof_societies">4.4.1 Professional societies / chapters and organising engineering events</label>
												<div class="controls"> 
													<textarea id="prof_societies " name="prof_societies" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may provide data for past three years."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="design_contest">4.4.2 Organisation of paper contests, design contests, etc. and achievements</label>
												<div class="controls"> 
													<textarea id="design_contest " name="design_contest" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may provide data for past three years."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="news_letter">4.4.3 Publication of technical magazines, newsletters, etc.</label>
												<div class="controls input-append"> 
													<input type="text" name="publication_name" id="publication_name" placeholder="Publication" /> 
													<input type="text" name="editor_name" id="editor_name" placeholder=" Editor "/>
													<input type="text" name="publishers" id="publishers" placeholder="Publisher"/>
													&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="news_letter_add_more"><i class="icon-plus-sign icon-white"></i>Add More</a>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="innovations">4.4.4 Entrepreneurship initiatives, product designs, and innovations</label>
												<div class="controls"> 
													<textarea id="innovations " name="innovations" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may specify the efforts and achievements."></textarea>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label" for="awards">4.4.5 Publications and awards in inter-institute events by students of the programme of study</label>
												<div class="controls"> 
													<textarea id="awards " name="awards" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may provide a table indicating those publications, which fetched awards to students in the events/conferences organised by other institutes."></textarea>
												</div>
										</div>
										
										<div class="control-group pull-right">
											<br><br><br>
												<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i>Save</a>
												<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i>Cancel</a>
												<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i>Export</a>
										</div>
								</div>
								
								<div class="tab-pane" id="faculty_performance">
									<div class="control-group">
										<strong>5. Faculty Contributions</strong> 
									</div>
									
									<div class="control-group">
											<strong>List of Faculty Members: Exclusively for the Programme / Shared with other Programmes</strong>
									</div>
									
								<div class="control-group">
									<div class="control-group">
										<div class="controls">
											<div class="controls row-fluid span4">
												<div class="">
													<label id="" for="">&nbsp;Name of the faculty member</label>
															<input type="text" id="" name="" class="span12" placeholder="Name of the faculty member" />
														</div>
												</div>
											<div class="controls row-fluid span4">
												<div class="">
													<label id="" for="">&nbsp;Qualification, university, and year of graduation</label>
														<input type="text" id="" name="" class="span12" placeholder="Qualification, university, and year of graduation" />
												</div>
											</div>
											<div class="controls row-fluid span4">
												<div class="">
													<label id="" for="">&nbsp;Designation and date of joining the institution</label>
														<input type="text" id="" name="" class="span12" placeholder="Designation and date of joining the institution" />
												</div>
											</div>
										</div>
										
										<div class="controls">
											<div class="controls row-fluid span4">
												<div class="">
													<br>
													<label id="" for="">Distribution of teaching load (%)</label>
														<input type="text" id="" name="" class="span4" placeholder="1St Year" />
														<input type="text" id="" name="" class="span4" placeholder="UG" />
														<input type="text" id="" name="" class="span4" placeholder="PG" />
												</div>
											</div>
											<div class="controls row-fluid span4">
												<div class="">
													<label id="" for="">Number of research publications in journals and conferences since joining</label>
														<input type="text" id="" name="" class="span12" placeholder="Number of research publications in journals" />
												</div>
											</div>
											
											<div class="controls row-fluid span4">
												<div class="">
													<br>
													<label id="" for="">&nbsp;IPRs</label>
														<input type="text" id="" name="" class="span12" placeholder="IPRs" />
												</div>
											</div>
										</div>
										
										<div class="controls">
											<div class="controls row-fluid span4">
												<div class="">
													<label>&nbsp;R&D and consultancy work with amount</label>
														<input type="text" id="" name="" class="span12" placeholder="R&D and consultancy work with amount" />
												</div>
											</div>
											
											<div class="controls row-fluid span4">
												<div class="">
													<label>&nbsp;Holding an incubation unit</label>
														<input type="text" id="" name="" class="span12" placeholder="Holding an incubation unit" />
												</div>
											</div>
											
											<div class="controls row-fluid span4">
												<div class="">
													<label>&nbsp;Interaction with outside world</label>
														<input type="text" id="" name="" class="span12" placeholder="Interaction with outside world" />
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<div class="ontrol-group">
									<div class="control-group">
									
										<strong><p>StudentTeacher Ratio (STR)</p></strong> 
									</div>
									<div class="controls">
										<strong>STR is desired to be 15 or superior</strong> 
									</div>
									<div class="controls">
									Assessment 	= 20 × 15/STR; subject to maximum assessment of 20 <br>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;STR = (x + y + z)/N1<br>
									where, 	x = Number of students in 2nd year of the program<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;y = Number of students in 3rd year of the program<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;z = Number of students in 4th year of the program<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N1 = Total number of faculty members in the program (by considering fractional load)
									</div>
									<div class="controls">
										<div class="controls row-fluid span12">
													<div class="input-append">
														<br>
														<label id="" for=""></label>
															<input type="text" id="" name="" class="span2" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
															<input type="text" id="" name="" class="span1" placeholder="X" />
															<input type="text" id="" name="" class="span1" placeholder="Y" />
															<input type="text" id="" name="" class="span1" placeholder="Z" />
															<input type="text" id="" name="" class="span2" placeholder="X+Y+Z" />
															<input type="text" id="" name="" class="span1" placeholder="N1" />
															<input type="text" id="" name="" class="span2" placeholder="STR" />
															<input type="text" id="" name="" class="span2" placeholder="Assessment(max. = 20)" />
															<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>
													</div>
													<div class="">
														<label>Average assessment</label>
															<input type="text" id="" name="" class="span2" placeholder="Average Assessment" />
													</div>
										</div>
										
									</div>
								</div>
								
								<div class="control-group" id="faculty_ratio_id">
								<strong>5.2 Faculty Cadre Ratio</strong>
								</div>
								
								<div class="control-group" id="faculty_ratio_data">
								<p>Assessment = 20 × CRI </p>
								<p>where, CRI = Cadre ratio index</p>
								<p>		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= 2.25 × (2x + y)/N; subject to max. CRI = 1.0</p>
								<p>where, x   = Number of professors in the program</p>
								<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;y = Number of associate professors in the program</p>
								</div>
								
							<div class="control-group">
								<div class="controls">
										<div class="controls row-fluid span12">
													<div class="input-append">
														<br>
														<label id="" for=""></label>
															<input type="text" id="" name="" class="span2" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
															<input type="text" id="" name="" class="span1" placeholder="X" />
															<input type="text" id="" name="" class="span1" placeholder="Y" />
															<input type="text" id="" name="" class="span2" placeholder="N" />
															<input type="text" id="" name="" class="span2" placeholder="CRI" />
															<input type="text" id="" name="" class="span2" placeholder="Assessment" />
															<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>
													</div>
													<div class="">
														<label>Average assessment</label>
															<input type="text" id="" name="" class="span2" placeholder="Average Assessment" />
													</div>
										</div>
										
									</div>
									
								</div>
																
								<div class="control-group">
								<strong>5.3 Faculty Qualifications</strong>
								</div>
								<div class="control-group">
									<table class="">
										<tr>
											<td>Assessment</td> <td> = </td> <td>3 × FQI</td>
										</tr>
										<tr>
											<td>where, FQI</td> <td> = </td> <td>Faculty qualification index</td>
										</tr>
										<tr>
											<td></td> <td> = </td> <td>(10x + 6y + 2z0)/N2 such that, x + y +z0 ≤ N2; and z0 ≤ z</td>
										</tr>
										<tr>
											<td>where, x</td> <td> = </td> <td>Number of faculty members with PhD</td>
										</tr>
										<tr>
											<td>y</td> <td> = </td> <td>Number of faculty members with ME/ M Tech</td>
										</tr>
										<tr>
											<td>z</td> <td> = </td> <td>Number of faculty members with B.E/B.Tech</td>
										</tr>
									
									</table>
								</div>
								<div class="control-group">
									<div class="controls">
										<div class="controls row-fluid span12">
													<div class="input-append">
														<br>
														<label id="" for=""></label>
															<input type="text" id="" name="" class="span2" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
															<input type="text" id="" name="" class="span1" placeholder="X" />
															<input type="text" id="" name="" class="span1" placeholder="Y" />
															<input type="text" id="" name="" class="span2" placeholder="N" />
															<input type="text" id="" name="" class="span2" placeholder="Z" />
															<input type="text" id="" name="" class="span2" placeholder="FQ" />
															<input type="text" id="" name="" class="span2" placeholder="Assessment" />
															<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>
													</div>
													<div class="">
														<label>Average assessment</label>
															<input type="text" id="" name="" class="span2" placeholder="Average Assessment" />
													</div>
										</div>
										
									</div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label" for="faculty_competency">5.4 Faculty Competencies correlation to Programme Specific Criteria</label>
										<div class="controls"> 
											<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Provide evidence that program curriculum satisfies the applicable programme criteria specified by the appropriate American professional associations such as ASME, IEEE and ACM. You may list the programme specific criteria and the competencies (specialisation, research publication, course developments, etc.,) of faculty to correlate the programme specific criteria and competencies."></textarea>
										</div>
								</div>
								
									<div class="control-group">
										<label class="control-label" for="faculty_competency">5.5 Faculty as participants/resource persons in faculty development/training activities</label>
											<div class="controls"> 
											
											</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="faculty_competency"><strong>5.6 Faculty Retention</strong></label>
											<div class="controls"> 
												<table class="">
													<tr>
														<td>Assessment</td> <td> = </td> <td>3 × RPI/N</td>
													</tr>
													<tr>
														<td>where, RPI</td> <td> = </td> <td>Retention Point index</td>
													</tr>
													<tr>
														<td></td> <td> = </td> <td>Points assigned to all faculty members</td>
													</tr>
													<tr>
														<td>where points assigned to a faculty member</td> <td> = </td> <td>1 point for each year of experience at the institute but not exceeding 5</td>
													</tr>
													
												</table>
											</div>
											
											<div class="controls"> 
												<table class="table table-bordered">
													<tr>
														<th>Items</th>
														<th class=" form-inline input-append"><label>Academic Year :</label> &nbsp;<input type="text" id="" name="" class="span6" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span></th>
													</tr>
													<tr>
														<td>Number of faculty members with experience of less than l year (x0)</td> 
														<td> <input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Number of faculty members with 1 to 2 years experience</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Number of faculty members with 2 to 3 years experience</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Number of faculty members with 3 to 4 years experience</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>Number of faculty members with 4 to 5 years experience</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>Number of faculty members with more than 5 years experience (x5)</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>N</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>RPI = x1 + 2x2 + 3x3 + 4x4 + 5x5</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>Assessment</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
													<tr>
														<td>Average Assessment</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
												</table>
												<div class="pull-right" id="add_button">
												<a class="btn btn-primary" id="add_more_button"><i class="icon-plus-sign icon-white"></i> Add More</a>
												</div>
											</div>
									</div>
									
									<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
								</div>
									
								</div>
								
								<div class="tab-pane" id="felicities">
								<!--Jevi's Code Starts from here-->
								
								<strong>6. Facilities and Technical Support</strong> 
								
									<div class="control-group">
												<label class="control-label" for="institute_info"><i><b>6.1 Classrooms in the Department</b></i></label>
									</div>
									<div class="control-group">
												<label class="control-label" for="institute_info">6.1.1 Adequate number of rooms for lectures (core/electives), seminars,tutorials, etc., for the program</label>
									</div>
										
										 <div class="row-fluid form-inline">
											 <div class="span1">
												<div class="controls">
                                                    <label class="control-label inline" for="inputProgramType">Lectures:  </font></label> 
                                                </div>
											</div>
											<div class="span4">
											
												<div class="controls">
														<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="No. of rooms" size="20">
                                                </div>
											</div>
											</div>
											<br>
										<div class="row-fluid form-inline">
											 <div class="span1">
												<div class="controls">
                                                    <label class="control-label inline" for="inputProgramType">Seminars:  </font></label> 
                                                </div>
											</div>
											<div class="span4">
											
												<div class="controls">
														<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="No. of rooms" size="20">
                                                </div>
											</div>
											</div>
											<br>
										<div class="row-fluid form-inline">
											 <div class="span1">
												<div class="controls">
                                                    <label class="control-label inline" for="inputProgramType">Tutorials:  </font></label> 
                                                </div>
											</div>
											<div class="span4">
											
												<div class="controls">
														<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="No. of rooms" size="20">
                                                </div>
											</div>
											</div>
											<br>
										 
									<div class="control-group">
												<label class="control-label" for="institute_info">6.1.2 Teaching aids---multimedia projectors, etc.</label>
									</div>
									<div id="course_delivery_mode">
										<div class="control-group" id="course_mode">
											<div class="controls">
												<div class="input-append">
													
													<input id="Sl_no" class="span1 required digits" type="text" name="Sl_no" value="1" readonly="">&nbsp;
													<input class="input-large" placeholder="Teaching Aids" id="inputIcon" type="text">
																								
													<button class="btn btn-primary add_delivery_mode_of_course" id="add_delivery_mode_of_course"><i class="icon-plus icon-white"></i></button>
												</div>
												
											</div>
										</div>
										<br>
										<div id="mode"></div>
									</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.1.3 Acoustics, classroom size, conditions of chairs/benches, air circulation,lighting, exits, ambience, and such other amenities/facilities</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder=""></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info"><i><b>6.2 Faculty Rooms in the Department</b></i></label>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.2.1 Availability of individual faculty rooms</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.2.2 Room equipped with white/black board, computer, Internet, and such other amenities/facilities</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.2.3 Usage of room for counselling/discussion with students</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here"></textarea>
										</div>
										<!--sample table-->
										<table class="table table-bordered">
											  <thead>
												<tr>
												  <th>Laboratory description in the curriculum</th>
												  <th>Exclusive use/shared</th>
												  <th>Space,number of students</th>
												  <th>Number of experiments</th>
												  <th>Quality of instruments</th>
												  <th>Laboratory manuals</th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  </tr>
												
												<tr>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												 </tr>
												
											  </tbody>
											</table>
										
										<div class="control-group">
												<label class="control-label" for="institute_info"><i><b>6.3 Laboratories in the Department to meet the Curriculum Requirements and the POs</b></i></label>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.3.1 Adequate, well-equipped laboratories to meet the curriculum requirements and the POs</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.3.2 Availability of computing facilities in the department</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.3.3 Availability of laboratories with technical support within and beyond working hours</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.3.4 Equipment to run experiments and their maintenance, number of students per experimental setup, size of the laboratories, overall ambience, etc.</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info"><i><b>6.4 Technical Manpower Support in the Department</b></i></label>
										</div>
										<!--Table-->
										<table class="table table-bordered">
											  <thead>
												<tr>
												  <th>Name of the Technical Staff</th>
												  <th>Designation</th>
												  <th>Payscale</th>
												  <th>Exclusive/Shared Work</th>
												  <th>Date of Joining</th>
												  <th colspan="2">Qualification</th>
												  <th>Other Technical Skills gained</th>
												  <th>Responsibility</th>
												</tr>
											  </thead>
											  <tbody>
												<tr>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												</tr>
												
												<tr>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  <td><input class="span9" type="text" placeholder=""></td>
												  
												</tr>
												
											  </tbody>
											</table>
						
										<div class="control-group">
												<label class="control-label" for="institute_info">6.4.1 Availability of adequate and qualified technical supporting staff for programme-specific laboratories</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
										<div class="control-group">
												<label class="control-label" for="institute_info">6.4.2 Incentives, skill-upgrade, and professional advancement</label>
										</div>
										<div class="controls"> 
											<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Assessment based on the information provided in the preceding table"></textarea>
										</div>
								
								<!--End of Code-->
								
								<!-- Button Code-->
								<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
								</div>
								<!-- Button Code Ends Here-->
								
								</div>
								
								<div class="tab-pane" id="academic">
									<div class="control-group">
										<strong>7 Academic Support Units and Teaching-Learning Process</strong>
									</div>
									
									<div class="control-group">
										<strong>Students Admission</strong>
									</div>
									
									<div class="control-group">
										<div class="controls"> 
												<table class="table table-bordered">
													<tr>
														<th>Items</th>
														<th class=" form-inline input-append"><label>Academic Year :</label> &nbsp;<input type="text" id="" name="" class="span6" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span></th>
													</tr>
													<tr>
														<td>Sanctioned intake strength in the institute (N)</td> 
														<td> <input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Number of students admitted on merit basis (N1)</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Number of students admitted on management quota/otherwise (N2)</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td>
													</tr>
													<tr>
														<td>Total number of admitted students in the institute (N1 + N2)</td> 
														<td><input type="text" id="" name="" class="span6" placeholder="" /></td> 
													</tr>
												</table>
												<div class="pull-right" id="add_button">
												<a class="btn btn-primary" id="add_more_button"><i class="icon-plus-sign icon-white"></i> Add More</a>
												</div>
											</div>
									</div>
									
									<div class="control-group">
										<p><strong>Admission quality</strong></p>
									</div>
									
									<div class="control-group">
										<div class="controls"> 
												<table class="table table-bordered">
													<tr>
														<th>Rank range</th>
														<th class=" form-inline input-append"><label>Academic Year :</label> &nbsp;<input type="text" id="" name="" class="span6" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span></th>
													</tr>
													<tr>
														<td>More than 98 percentile</td> 
														<td> <input type="text" id="" name="" class="span3" placeholder="" /></td>
													</tr>
													<tr>
														<td>95--98 percentile</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td>
													</tr>
													<tr>
														<td>90--95 percentile</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td>
													</tr>
													<tr>
														<td>80--90 percentile</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td> 
													</tr>
													<tr>
														<td>80--75 percentile</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td> 
													</tr>
													<tr>
														<td>75--80 percentile</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td> 
													</tr>
													<tr>
														<td>Admitted without rank</td> 
														<td><input type="text" id="" name="" class="span3" placeholder="" /></td> 
													</tr>
												</table>
												<div class="pull-right" id="ranking_add_button">
												<a class="btn btn-primary" id="rank_add_more_button"><i class="icon-plus-sign icon-white"></i> Add More</a>
												</div>
											</div>
									</div>
									<br><br>
									<div class="control-group">
										<p>List of faculty members teaching first year courses</p>
									</div>
									
									<div class="control-group">
										<div class="controls">
											<div class="span2">
											<br>
												<label>Name of the Faculty</label>
												<input type="text" id="" name="" class="span10" placeholder="" />
											</div>
											<div class="span2">
											<br>
												<label>Qualification</label>
												<input type="text" id="" name="" class="span8" placeholder="" />
											</div>
											<div class="span2">
											<br>
												<label>Designation</label>
												<input type="text" id="" name="" class="span8" placeholder="" />
											</div>
											<div class="span2">
											<br>
												<label>Date of joining</label>
												<input type="text" id="" name="" class="span8" placeholder="" />
											</div>
											<div class="span2">
											<br>
												<label>Department</label>
												<input type="text" id="" name="" class="span8" placeholder="" />
											</div>
											<div class="span2">
												<label>Distribution of teaching load (%)</label>
												<input type="text" id="" name="" class="span3" placeholder="1st Year" />
												<input type="text" id="" name="" class="span3" placeholder="UG" />
												<input type="text" id="" name="" class="span3" placeholder="PG" />
											</div>
										</div>
										<div class="pull-right" id="faculty_add_button">
										<a class="btn btn-primary" id="faculty_add_button"><i class="icon-plus-sign icon-white"></i> Add More</a>
										</div>								
									</div>
									<br>
									<br>
									<div class="control-group">
									
									</div>
									<br>
									<br>
									<br>
									<div class="control-group">
										<strong>7.1 Academic Support Units</strong>
									</div>
									<div class="control-group">
										<p>7.1.1 Assessment of First Year Student Teacher Ratio (FYSTR)</p>
									</div>
									<br>
									<br>
									<div class="control-group">
										<p>7.1.2 Assessment of Faculty Qualification Teaching First Year Common Courses</p>
									</div>
									
									<div class="controls"> 
												<table class="">
													<tr>
														<td>Assessment of qualification</td> <td> = </td> <td>3 × (5x + 3y + 2z0)/N, where x + y + z0 ≤ N and z0 ≤ Z</td>
													</tr>
													<tr>
														<td>x</td> <td> = </td> <td>Number of faculty members with PhD</td>
													</tr>
													<tr>
														<td>y</td> <td> = </td> <td>Number of faculty members with ME/MTech/NET-Qualified/MPhil</td>
													</tr>
													<tr>
														<td>z</td> <td> = </td> <td>Number of faculty members with BE/BTech/MSc/MCA/MA</td>
													</tr>
													
													<tr>
														<td>N</td> <td> = </td> <td>Number of faculty members needed for FYSTR of 25</td>
													</tr>
													
												</table>
											</div>
									
									<div class="control-group">
										<div class="controls input-append">
											<br>
												<label id="" for=""></label>
													<input type="text" id="" name="" class="span2" placeholder="CAY" /><span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
													<input type="text" id="" name="" class="span1" placeholder="X" />
													<input type="text" id="" name="" class="span1" placeholder="Y" />
													<input type="text" id="" name="" class="span1" placeholder="Z" />
													<input type="text" id="" name="" class="span2" placeholder="N" />
													<input type="text" id="" name="" class="span3" placeholder="Assessment of faculty qualification" />
													<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>
										</div>
										<div class="control-group">
											<label>Average assessment of faculty qualification</label>
											<input type="text" id="" name="" class="span3" placeholder="" />
										</div>
									</div>
									<div class="control-group">
										7.1.3 Basic science/engineering laboratories (adequacy of space, number of students per batch, quality and availability of measuring instruments, laboratory manuals, list of experiments)
									</div>
									
									<div class="control-group">
										<table class="table table-bordered">
										<tr>
											<td>Laboratory description</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Space,number of students</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Software used</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Type of experiments</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Quality of instruments</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Laboratory manuals</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										</table>
										<div class="pull-right" id="button">
										<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i> Add More</i></a>
										</div>
									</div>
									
									<div class="control-group">
									<br>
									<br>
										7.1.4 Language laboratory
									</div>
									
									<div class="control-group">
										<table class="table table-bordered">
										<tr>
											<td>Language laboratory</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Space, number of students</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Software used</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Type of experiments</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Quality of instruments</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										<tr>
											<td>Guidance</td>
											<td>
												<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 600px; height: 50px;" placeholder="Financial status of the institute has to be mentioned here."></textarea>
											</td>
										<tr>
										</table>
										<div class="pull-right" id="button">
										<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i> Add More</i></a>
										</div>
									</div>
									<div class="control-group">
										<strong>7.2 Teaching – Learning Process</strong>
									</div>
									<div class="control-group">
										7.2.4 Scope for self-learning
									</div>
									<div class="control-group">
										<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution needs to specify the scope for self-learning / learning beyond syllabus and creation of facilities for self-learning / learning beyond syllabus."></textarea>
									</div>
									
									<div class="control-group">
										7.2.5 Generation of self-learning facilities, and availability of materials for learning beyond syllabus
									</div>
									<div class="control-group">
										<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution needs to specify the facilities for self-learning / learning beyond syllabus."></textarea>
									</div>
									
									<div class="control-group">
										7.2.6 Career Guidance, Training, Placement, and Entrepreneurship Cell
									</div>
									<div class="control-group">
										<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may specify the facility and management to facilitate career guidance including counselling for higher studies, industry interaction for training/internship/placement, Entrepreneurship cell and incubation facility and impact of such systems"></textarea>
									</div>
									
									<div class="control-group">
										7.2.7 Co-curricular and Extra-curricular Activities
									</div>
									<div class="control-group">
										<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may specify the Co-curricular and extra-curricular activities, e.g., NCC/NSS, cultural activities, etc"></textarea>
									</div>
									
									<div class="control-group">
										7.2.8 Games and Sports, facilities, and qualified sports instructors
									</div>
									<div class="control-group">
										<textarea id="faculty_competency " name="faculty_competency" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="The institution may specify the facilities available and their usage in brief"></textarea>
									</div>
									
									<!--button Code-->
									<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
								</div>
									<!--button Code Ends-->
									
								</div>
								
								<div class="tab-pane" id="governance">
								
								<!--Jevi's Code Starts From here-->
								
									<strong>8. Governance, Institutional Support and Financial Resources</strong> 
		
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.1 Campus Infrastructure and Facility</b></i></label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.1.1 Maintenance of academic infrastructure and facilities</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.1.2 Hostel (boys and girls), transportation facility, and canteen</label>
		</div>
		<table class="table">
		  <thead>
		<tr>
		  <th>Hostels</th>
		  <th>No. of Rooms</th>
		  <th>No. of students accommodated</th>
		</tr>
		  </thead>
		  <tbody>
		<tr>
		  <td>Hostel for Boys:</td>
		  <td><input class="span6" type="text" placeholder=""></td>
		  <td><input class="span6" type="text" placeholder=""></td>
		  </tr>
		<tr>
		  <td>Hostel for Girls:</td>
		  <td><input class="span6" type="text" placeholder=""></td>
		  <td><input class="span6" type="text" placeholder=""></td>
		  </tr>
		
		  </tbody>
		</table>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.1.3 Electricity, power backup, telecom facility, drinking water, and security</label>
		</div>
		
		<div class="row-fluid">
		 <div class="span2">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Electricity:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span2">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Power backup:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span2">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Telecom facility:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span2">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Drinking water:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span2">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Security:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.2 Organisation, Governance, and Transparency</b></i></label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.2.1 Governing body, administrative setup, and functions of various bodies</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.2.2 Defined rules, procedures, recruitment, and promotional policies, etc.</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.2.3 Decentralisation in working including delegation of financial power and grievance redressal system</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.2.4 Transparency and availability of correct/unambiguous information</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.3 Budget Allocation, Utilisation, and Public Accounting</b></i></label>
		</div>
		<label class="control-label" for="institute_info">Summary of current financial year’s budget and the actual expenditure incurred (exclusively for the institution) for three previous financial years.</label>
		<!--Table-->
		<table class="table table-bordered">
		  <thead>
		<tr>
		  <th>Item</th>
		  <th>Budgeted in</th>
		  <th>Expenses in <div class="input-append date">
		<input type="text" class="span4 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		  <th>Expenses in <div class="input-append date">
		<input type="text" class="span4 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		  <th>Expenses in <div class="input-append date">
		<input type="text" class="span4 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		</tr>
		  </thead>
		  <tbody>
		<tr>
		  <td>Infrastructural built-up</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Library</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Laboratory equipment</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Laboratory consumables</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Teaching and non-teaching staff salary</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>R&D </td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Training and Travel</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Other, specify</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>Total</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		  </tbody>
		</table>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.3.1 Adequacy of budget allocation</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.3.2 Utilisation of allocated funds</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.3.3 Availability of the audited statements on the institute’s website</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.4 Programme Specific Budget Allocation, Utilisation</b></i></label>
		</div>
		<label class="control-label" for="institute_info">Summary of budget for the CFY and the actual expenditure incurred in the CFYm1 and CFYm2 (exclusively for this programme in the department):</label>
		<!--table here-->
		<div class="controls" id="dept_budget">
		<div class="" id="budget">
		<table class="table table-bordered">
		<tbody><tr>
		<th>Items</th>
		<th>Budgeted in Current Financial Year</th>
		<th>Actual Expenses In Current Financial Year</th>
		</tr>
		
		<tr>
		<td>Laboratory Equipment</td>
		<td><input type="text" name="lab_equip_pgm_budget" id="lab_equip_pgm_budget" placeholder=""></td>
		<td><input type="text" name="lab_equip_pgm_expenses" id="lab_equip_pgm_expenses" placeholder=""></td>
		</tr>
		<tr>
		<td class="pull-center">Software</td>
		<td><input type="text" name="soft_pgm_budget" id="soft_pgm_budget" placeholder=""></td>
		<td><input type="text" name="soft_pgm_expense" id="soft_pgm_expense" placeholder=""></td>
		</tr>
		<tr>
		<td class="pull-center">R&D </td>
		<td><input type="text" name="soft_pgm_budget" id="soft_pgm_budget" placeholder=""></td>
		<td><input type="text" name="soft_pgm_expense" id="soft_pgm_expense" placeholder=""></td>
		</tr>
		<tr>
		<td>Laboratory consumable</td>
		<td><input type="text" name="lab_consumable_pgm_budget" id="lab_consumable_pgm_budget" placeholder=""></td>
		<td><input type="text" name="lab_consumable_pgm_expense" id="lab_consumable_pgm_expense" placeholder=""></td>
		</tr>
		<tr>
		<td>Maintenance and spares</td>
		<td><input type="text" name="spares_pgm_budget" id="spares_pgm_budget" placeholder=""></td>
		<td><input type="text" name="spares_pgm_expense" id="spares_pgm_expense" placeholder=""></td>
		</tr>
		<tr>
		<td>Training and Travel</td>
		<td><input type="text" name="training_pgm_budget" id="training_pgm_budget" placeholder=""></td>
		<td><input type="text" name="training_pgm_expense" id="training_pgm_expense" placeholder=""></td>
		</tr>
		<tr>
		<td>Miscellaneous expenses for academic activities</td>
		<td><input type="text" name="miscel_pgm_budget" id="miscel_pgm_budget" placeholder=""></td>
		<td><input type="text" name="miscel_expense" id="miscel_expense" placeholder=""></td>
		</tr>
		<tr>
		<td>Total</td>
		<td><input type="text" name="total_pgm_budget" id="total_pgm_budget" placeholder=""></td>
		<td><input type="text" name="total_pgm_expense" id="total_pgm_expense" placeholder=""></td>
		</tr>
		</tbody></table>
		<div class="pull-right" id="add_button">
		<a class="btn btn-primary" name="budget_pgm_add" id="budget_pgm_add"><i class="icon-plus-sign icon-white"></i> Add More</a>
		</div>
		<br>
		<div id="budget_pgm_div">
		</div>
		</div>
		</div>
		
		<!--ends here-->
		<div class="control-group">
		<br><br>
		<label class="control-label" for="institute_info">8.4.1 Adequacy of budget allocation</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.4.2 Utilisation of allocated funds</label>
		</div>
		<div class="controls"> 
		<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 1027px; height: 66px;" placeholder="Enter Details Here">
		</textarea>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.5 Library</b></i></label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.5.1 Library space and ambience, timings and usage, availability of a qualified librarian and other staff, library automation, online access, networking, etc.</label>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Carpet area of library: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">	
		<div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Reading space: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">	
		<div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of seats in reading space: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">	
		<div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of users (issue book) per day <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of users(reading space) per day <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Timings: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="row-fluid form-inline">
		<div class="span2"><label class="control-label inline" for="inputProgramType">Working Day: <font color="red">* </font></label> </div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div></div><br>
		
		<div class="row-fluid form-inline">
		<div class="span2"><label class="control-label inline" for="inputProgramType">Weekend: <font color="red">* </font></label> </div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div>
		</div><br>
		
		<div class="row-fluid form-inline">
		<div class="span2"><label class="control-label inline" for="inputProgramType">Vacation: <font color="red">* </font></label> </div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div>
		<div class="span2"><div class="input-append bootstrap-timepicker">
		<input id="timepicker1" type="text" class="input-mini">
		<span class="add-on"><i class="icon-time"></i></span>
		</div></div>
		
		</div>
		<br>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of library staff <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of library staff with degree in Library<font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Management Computerisation for search,
		indexing, issue/return records Bar coding used <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.5.2 Titles and volumes per title</label>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of titles: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of volumes: <font color="red">* </font></label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<!--Table-->
		<table class="table table-bordered">
		  <thead>
		<tr>
		  <th></th>
		  <th>Number of new titles added</th>
		  <th>Number of new editions added</th>
		  <th>Number of new volumes added</th>
		</tr>
		  </thead>
		  <tbody>
		<tr>
		  <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  
		</tr>
		
		<tr>
		  <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  
		</tr>
		
		<tr>
		  <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  
		</tr>
		 </tbody>
		</table>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.5.3 Scholarly journal subscription</label>
		</div>
		<!--Table-->
		<table class="table table-bordered">
		  <thead>
		<tr>
		  <th colspan="2">Details</th>
		  <th>
		<div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		  <th>
		<div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		  <th>
		<div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		  <th>
		<div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div>
		  </th>
		</tr>
		  </thead>
		  <tbody>
		<tr>
		  <td rowspan="2">Science</td>
		  <td>As soft copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>As hard copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td rowspan="2">Engg. and Tech.</td>
		  <td>As soft copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>As hard copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td rowspan="2">Pharmacy</td>
		  <td>As soft copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>As hard copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td rowspan="2">Architecture</td>
		  <td>As soft copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>As hard copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td rowspan="2">Hotel Management</td>
		  <td>As soft copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		<tr>
		  <td>As hard copy</td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		  </tbody>
		</table>
		
		<div class="control-group">
		<label class="control-label" for="institute_info">8.5.4 Digital Library</label>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability of digital library contents: <font color="red">* </font></label> 
													</div>
		</div>
		<label class="radio inline">
		  <input type="radio" name="optionsRadios"id="optionsRadios1" value="option1" checked> Yes
		</label>
		<label class="radio inline">
		  <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> No
		</label>
		</div>
		If available, then mention
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of courses: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of e-books: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability of an exclusive server: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability over Intranet/Internet: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability of exclusive space/room: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Number of users per day: </label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.5.5 Library expenditure on books, magazines/journals, and miscellaneous contents</label>
		</div>
		<table class="table table-bordered">
		  <thead>
		<tr rowspan="2">
		  <th>Year</th>
		  <th colspan="4">Expenditure</th>
		  <th>Comments,if any 
		  </th>
		  
		</tr>
		  </thead>
		  <tbody>
		<tr>
		   <td></td>
		  <td>Book</td>
		  <td>Magazines/journals (for hard copy subscription)</td>
		 <td>Magazines/journals (for soft copy subscription)</td>
		   <td>Misc.Contents</td>
		 <td></td>
		  
		</tr>
		<tr>
		   <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		</div></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		<tr>
		   <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		<tr>
		   <td><div class="input-append date">
		<input type="text" class="span8 required yearpicker" id="start_year" name="start_year" readonly="" onchange="populate_year();">
		<span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
		
		</div></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		 <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		  <td><input class="span9" type="text" placeholder=""></td>
		</tr>
		
		  </tbody>
		</table>
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.6 Internet</b></i></label>
		</div>
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Name of the Internet provider:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Available bandwidth:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Access speed:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability of Internet in an exclusive lab:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability in most computing labs:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability in departments and other units:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Availability in faculty rooms:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Institute’s own e-mail facility to faculty/students:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		
		<div class="row-fluid">
		 <div class="span4">
		<div class="control-group">
														<label class="control-label inline" for="inputProgramType">Security/privacy to e-mail/Internet users:</label> 
													</div>
		</div>
		<div class="span8">
		<div class="controls">
		<input type="text" name="specialization_acronym" value="" id="specialization_acronym" class="required noSpecialChars span6" onblur="program_acronym_fun()" placeholder="" size="20">
													</div>
		</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.7 Safety Norms and Checks</b></i></label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.7.1 Checks for wiring and electrical installations for leakage and earthing</label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.7.2 Fire-fighting measurements: Effective safety arrangements with emergency/ multiple exits and ventilation/exhausts in auditoriums and large
		classrooms/laboratories, fire-fighting equipment and training, availability of water, and such other facilities</label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.7.3 Safety of civil structure</label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">8.7.4 Handling of hazardous chemicals and such other activities</label>
		</div>
		
		<div class="control-group">
		<label class="control-label" for="institute_info"><i><b>8.8 Counselling and Emergency Medical Care and Firstaid</b></i></label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">Availability of counselling facility</label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">Arrangement for emergency medical care</label>
		</div>
		<div class="control-group">
		<label class="control-label" for="institute_info">Availability of first-aid unit</label>
		</div>
								
								<!--Jevi's Code Ends here-->
								
								
								<!--Button Code Starts from here-->
								<div class="control-group pull-right">
									<br><br><br>
										<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
										<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
										<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
								</div>
								<!--Button code Ends here-->
								</div>
								
								<div class="tab-pane" id="improvement">
								
									<div class="control-group">
										<strong>9. Continuous Improvement</strong>
									</div>
									<div class="control-group">
										<table>
											<tr>
												<td>
												<p>This criterion essentially evaluates the improvement of the different indices that have already been discussed in earlier criteria.</p>
												</td>
											</tr>
											<tr>
												<td>
												<p>From 9.1 to 9.5 the assessment calculation can be done as follows.</p>
												</td>
											</tr>
											<tr>
												<td>
												<p>a, b and c are the values of variables, which correspond to either LYGm2, LYGm1 and LYG or CAYm2, CAYm1 and CAY respectively, after scaled down each of them to a maximum value of 1.<p>
												
												</td>
											</tr>
											<tr>
												<td>
													<p>Assessment can be made as,</p>
													<p>Assessment = (b-a) + (c-b) + (a+b+c) x (5/3)</p>
												</td>
											</tr>
										</table>
									</div>
									<div class="control-group">
										<strong>9.1 Improvement in Success Index of Students</strong>
									</div>
									<div class="control-group">
									<p>From 4. 1</p>
									<p>a, b and c are the success indices which correspond to LYGm2, LYGm1 and LYG respectively.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													LYG(c)
												</th>
												<th>
													LYGm1(b)
												</th>
												<th>
													LYGm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													Success index
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									
									<div class="control-group">
										<strong>9.2 Improvement in Academic Performance Index of Students</strong>
									</div>
									<div class="control-group">
									<p>From 4.2</p>
									<p>a, b and c are calculated respectively for LYGm2, LYGm1 and LYG by dividing the API values, obtained from the criterion 4.2, by 10. The maximum value of a, b, and c should not exceed one.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													LYG(c)
												</th>
												<th>
													LYGm1(b)
												</th>
												<th>
													LYGm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													API
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									
									<div class="control-group">
										<strong>9.3 Improvement in StudentTeacher Ratio</strong>
									</div>
									<div class="control-group">
									<p>From 5.1</p>
									<p>a, b and c are calculated respectively for CAYm2, CAYm1 and CAY by dividing the STR values, obtained from the criterion 5.1, by 15. The maximum value of a, b, and c should not exceed one.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													LYG(c)
												</th>
												<th>
													LYGm1(b)
												</th>
												<th>
													LYGm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													STR
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									
									<div class="control-group">
										<strong>9.4 Enhancement of Faculty Qualification Index</strong>
									</div>
									<div class="control-group">
									<p>From 5.3</p>
									<p>a, b and c are calculated respectively for CAYm2, CAYm1 and CAY by dividing the FQI values, obtained from the criterion 5.3, by 10. The maximum value of a, b, and c should not exceed one.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													CAY(c)
												</th>
												<th>
													CAYm1(b)
												</th>
												<th>
													CAYm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													FQI
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									
									<div class="control-group">
										<strong>9.5 Improvement in Faculty Research Publications, R&D Work and Consultancy Work</strong>
									</div>
									<div class="controls">
									<div class="control-group">
									<p>From 5.7</p>
									<p>a, b and c are calculated respectively for CAYm2, CAYm1 and CAY by dividing the FRP values, obtained from the criterion 5.7, by 20. The maximum value of a, b, and c should not exceed one.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													CAY(c)
												</th>
												<th>
													CAYm1(b)
												</th>
												<th>
													CAYm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													FRP
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									<div class="control-group">
									<p>From 5.9</p>
									<p>a, b and c are calculated respectively for CAYm2, CAYm1 and CAY by dividing the FRDC values, obtained from the criterion 5.9, by 20. The maximum value of a, b, and c should not exceed one.</p>
									</div>
									<div class="control-group">
										<table class="table table-bordered">
											<tr>
												<th>
													Items
												</th>
												<th>
													CAY(c)
												</th>
												<th>
													CAYm1(b)
												</th>
												<th>
													CAYm2(a)
												</th>
												<th>
													Assessment
												</th>
											</tr>
											<tr>
												<td>
													FRDC
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
												<td>
													<input type="text" id="" name="" class="" placeholder="" />
												</td>
											</tr>
										</table>
										<div class="controls" id="improv_add_button">
										<!--<a class="btn btn-primary" name="" id=""><i class="icon-plus-sign icon-white"></i>Add More</i></a>-->
										</div>
									</div>
									</div>
									
									<!--Button Code Starts From Here-->
									<div class="control-group pull-right">
										<br><br><br>
											<a class="btn btn-primary" id="insti_info_save"><i class="icon-file icon-white"></i> Save</a>
											<a class="btn btn-danger" id="insti_info_close"><i class="icon-remove icon-white"></i> Cancel</a>
											<a class="btn btn-success" id="insti_info_save"><i class="icon-book icon-white"></i> Export</a>
									</div>
									<!--Button Code Ends Here-->
									
								</div>
								
							  </div>
							</div>
							<!-- PART B Tab Ends Here-->
				</div>
				</div>				
				</section>
			</div>
		</div>

		<!---place footer.php here -->
		<?php  $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php  $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/report/nba_sar.js'); ?>" type="text/javascript"></script>