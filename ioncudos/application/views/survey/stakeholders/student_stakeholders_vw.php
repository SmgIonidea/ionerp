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
                            Student Stakeholders Import List 
                        </div>
                    </div>
					
					      <div class="span12">
                <div class="control-group ">                    
               
				<b>Department : <font color="red"> * </font> </b>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b>Program : <font color="red"> * </font> </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<b>Curriculum : <font color="red"> * </font> </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<b>Section : <font color="red"> * </font> </b> 
				<div class="controls">
                     
			
							<select id="dept_name" name="dept_name" class="form-control">
										<option value="">Select Department</option>
							</select>
													<select  style="width:150px;" id="program_name" name="program_name" class="form-control" >
									<option value="">Select Program</option>
								</select>
						<select   style="width:200px;" id="curriculum_name" name="curriculum_name" class="form-control">
										<option value="">Select curriculum</option>
									</select>	
										<select  style="width:150px;" id="section_name" name="section_name" class="form-control">
										<option value="">Select Section</option>
									</select>	
									 <a href="<?php echo base_url(); ?>survey/import_student_data/add_student_stakeholder" class="btn btn-primary pull-right"><i class='icon-plus-sign icon-white'></i> Add</a>&nbsp;&nbsp;
<!--									<a  href="<?php echo base_url(); ?>survey/import_student_data_excel/upload_student_data" class="pull-right btn btn-success pull-right"><i class='icon-download icon-white'></i> Bulk Import</a>  &nbsp;&nbsp;                                -->
									
					</div>
				</div>
			</div>
                  <!--  <div class="">
                        <table>
                            <tr>
								<td>
									<label for="dept_name"><b>Department:<span style="color:red;font-size:15px;">*</span></b></label>
									<select id="dept_name" name="dept_name" class="form-control">
										<option value="">Select Department</option>
									</select>
								</td>
								<td>
								<label for="program_name"><b>Program:<span style="color:red;font-size:15px;">*</span></b></b></label>
								<select  style="width:150px;" id="program_name" name="program_name" class="form-control" >
									<option value="">Select Program</option>
								</select>
								</td>
								<td>
								  <label for="curriculum_name"><b>Curriculum:<span style="color:red;font-size:15px;">*</span></b></label>
									<select   style="width:200px;" id="curriculum_name" name="curriculum_name" class="form-control">
										<option value="">Select curriculum</option>
									</select>	
								</td>
								<td>
								<label for="Section_name"><b>Section:<span style="color:red;font-size:15px;">*</span></b></label>
								<select  style="width:150px;" id="section_name" name="section_name" class="form-control">
										<option value="">Select Section</option>
									</select>	
								</td>
                                <td  style="width:130px;">
								<label for="curriculum_name"><b><span style="color:red;font-size:15px;"></span></b></label>
                                  <a  href="<?php echo base_url(); ?>survey/import_student_data_excel/upload_student_data" class="pull-right btn btn-success pull-right"><i class='icon-download icon-white'></i> Bulk Import</a>                                  
                                </td>
                                <td style="" >
								<label for="curriculum_name"><b><span style="color:red;font-size:15px;"></span></b></label>
									<a href="<?php echo base_url(); ?>survey/import_student_data/add_student_stakeholder" class="btn btn-primary pull-right"><i class='icon-plus-sign icon-white'></i> Add</a>
								</td>                              
                               
                            </tr>
                        </table>
                    </div>-->
                    <div class="clearfix"></div>
                    <input type="hidden" name="dept_id" id="dept_id" />
                    <input type="hidden" name="pgm_id" id="pgm_id" />
                    <input type="hidden" name="crclm_id" id="crclm_id" />
					<input type="hidden" name="section_id" id="section_id" />	
					<input type="hidden" name="student_usn_data" id="student_usn_data" />
                    <div id="student_data"></div>
                    <div id="update_status"></div>
                    <br/><br/><br/>
					<div class="span12">
						<div class="control-group ">  
							<div class="controls">
							<a href="<?php echo base_url(); ?>survey/import_student_data/add_student_stakeholder" class="btn btn-primary pull-right"><i class='icon-plus-sign icon-white'></i> Add</a>
<!--							<a href="<?php echo base_url(); ?>survey/import_student_data_excel/upload_student_data" class="btn btn-success pull-right"><i class='icon-download icon-white'></i> Bulk Import</a>-->
							
							</div>
						</div>
					</div>
                    <br/><br/>

                    <!-- Modal to confirm before enabling a user -->
                    <div id="edit" class="modal hide fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Edit Student Stakeholder
                            </div>
                        </div>
                        <div class="modal-body">
                            <form name="student_data_update_form" id="student_data_update_form">
                                <div class="row-fluid">
                                    <div class="span1"><label align="">PNR: </label>
                                        <input type="hidden" name="et_ssd_id" id="et_ssd_id" /></div>
                                    <div class="span5">
                                        <input type='text' name='et_student_usn' id='et_student_usn' readonly='true' class="input-small" />
                                    </div> 
									<div class="span2"><label align="">Section:</label></div>
                                        <!--<input type="hidden" name="" id="" />=-->
                                    <div class="span4">
                                      <!--  <input type='text' name='et_section_name' id='et_student_usn' readonly='true' class="input-small" />-->
										<select  style="width:150px;" id="et_section_name" name="et_section_name" class="form-control">
											<option value="">Select Section</option>
										</select>	
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <label>Title: <span style="color:red;font-size:15px;">*</span></label>
                                        <select name='et_title' id='et_title' class="input-small">
                                            <option value="">--</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Miss.">Miss.</option>
                                            <option value="Prof.">Prof.</option>
                                            <option value="Dr.">Dr.</option>
                                        </select>
                                        <!--<input type="texr" name='et_title' id='et_title' class="input-small"/>-->
                                    </div>
                                    <div class="span6">
                                        <label>First Name:<span style="color:red;font-size:15px;">*</span> </label>
                                        <input type='text' name='et_first_name' id='et_first_name'class="input-large" />
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6"><label>Last Name: </label><input type='text' name='et_last_name' id='et_last_name' class="input-large"></div>
                                    <div class="span6">
                                        <label>Email:<span style="color:red;font-size:15px;">*</span> </label><input type='text' readonly name='et_email' id='et_email'>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <label>Contact Number: </label><input type='text' name='et_contact' id='et_contact'>
                                    </div>
                                    <div class="span6">
                                            <label align="left">DOB: </label><!--<input type='text' name='et_dob' id='et_dob'>-->
                                        <div class="input-append date">
                                            <input type="text" class="span12 yearpicker" id="dp3" name="et_dob" readonly="">
                                            <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                        </div>
                                    </div>

                                </div>
                                <div id="stud_edit_form_data">

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary update_ok" aria-hidden="true"><i class="icon-file icon-white"></i> Update</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <!-- Modal to confirm before enabling a user -->
                    <div id="enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Enable Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to enable? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary enable_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <!-- Modal to confirm before disabling a user -->
                    <div id="disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Disable Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to disable? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary disable_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <!-- Modal Confirm delete-->
                    <div id="delete_stud_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                 Delete Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p>Are you sure you want to delete?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="confirm_delete"><i class="icon-ok icon-white"></i> Ok</button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <!-- Modal Confirm delete-->
                    <div id="sucs_del_stud_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" id="modal_header">
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="modal_content">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" id="success_delete"><i class="icon-ok icon-white"></i> Ok</button> 
                        </div>
                    </div>                   
					<div id="cant_enable_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="empty_file" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" id="">
									Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" id="">
							Student with same PNR is Active in same or another Curriculum.<br/>
							You can't enable this Student.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" id="success_delete"><i class="icon-ok icon-white"></i> Ok</button> 
                        </div>
                    </div>
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
<!--<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.js');  ?>"></script>
<script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.pieRenderer.js');  ?>"></script>-->
<script src="<?php echo base_url(); ?>twitterbootstrap/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>twitterbootstrap/js/jquery.dataTables.rowGrouping.js" type="text/javascript"></script>


<script src="<?php echo base_url('twitterbootstrap/js/custom/student_stakeholders_list.js'); ?>" type="text/javascript"></script>	