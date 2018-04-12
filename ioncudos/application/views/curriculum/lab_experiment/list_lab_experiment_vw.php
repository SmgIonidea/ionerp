<?php
/**
 * Description	:	Lab experiment controller model and view
 * Created		:	March 24th, 2015

 * Author		:	Abhinay B.Angadi

 * Modification History:
 *   Date                Modified By                            Description
 * 30-10-2015			Bhagyalaxmi S S		 Addedd Proceed to Mapping Column
 * 05-01-2015			Shayista Mulla 			Added loading image.	
  ---------------------------------------------------------------------------------------------- */
  
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
					<?php $this->load->view('includes/sidenav_2'); ?>
					<div class="span10">
						<!-- Contents -->
						<section id="contents">
							<!--content goes here-->	
							<div class="bs-docs-example" >
								<!--content goes here-->
								<div class="row-fluid">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Lab Experiment List 
											<a href="#help" class="pull-right show_help" data-toggle="modal" onclick="show_help();" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign "></i></a>
										</div>
									</div>
									
									<div id="loading" class="ui-widget-overlay ui-front">
										<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
									</div>
									<a class="btn btn-primary pull-right" onclick="form_submit();"><i class="icon-plus-sign icon-white"></i> Add Experiment</a>
                            
									<form name="list_form_id" id="list_form_id" method="post" action="<?php echo base_url('curriculum/lab_experiment/add_lab_experiment'); ?>" class="form-horizontal"> 
										<div class="row">
											<div class="span5">
												<div class="control-group">
													<label class="control-label">Curriculum:<font color="red">*</font></label>
													<div class="controls">
														<select id="curriculum" name="curriculum" autofocus = "autofocus" style="width:100%;" onchange="select_term();" onLoad="select_term();">
															<option value="">Select Curriculum</option>
															<?php foreach ($crclm_name_data as $listitem): 
															echo $listitem['crclm_id'];?>
															<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="span5">
												<div class="control-group">
													<label class="control-label">Term:<font color="red">*</font></label>
													<div class="controls">
														<select id="term" name="term" style="width:100%;" onchange="select_course();">
															<option value="">Select Term</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="span5">
												<div class="control-group">
													<label class="control-label">Course:<font color="red">*</font></label>
													<div class="controls">
														<select id="course" name="course" style="width:100%;" onchange="select_category();">
															<option value="">Select Course</option>
														</select>
													</div>
												</div>
											</div>
											<div class="span5">
												<div class="control-group">
													<label class="control-label">Category:<font color="red">*</font></label>
													<div class="controls">
														<select id="category" name="category" style="width:100%;" onchange="GetSelectedValue();">
															<option value="">Select Category</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
										</div>
										<div style="overflow:auto">
											<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
												<thead>
													<tr role="row">
														<th class="header headerSortDown" width="85px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Expt. / Job No.</th>
														<th class="header headerSortDown " width="350px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Experiment / Job Details</th>
														 <th class="header headerSortDown" width="120px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >No. of Session/s per batch (estimate)</th>
														 <th class="header headerSortDown"  width="90px"  role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Marks / Experiment</th>
														 <th class="header headerSortDown " width="250px"role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Correlation of Experiment with theory</th>
														<th class="header " width="40px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Edit</th>
														<th class="header " width="50px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>
														<th class="header " width="80px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Add /Edit <?php echo $this->lang->line('entity_tlo_singular'); ?> </th>
														<th class="header " width="80px" role="columnheader" tabindex="0" aria-controls="example" align="center" >View <?php echo $this->lang->line('entity_tlo'); ?></th>
														<th class="header " width="100px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Manage Lesson schedule</th>
														<th class="header " width="120px" height="10px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Proceed TO Mapping</th>
													</tr>
												</thead>
												<tbody role="alert" aria-live="polite" aria-relevant="all">
												</tbody>
											</table>
										</div></br>
									</form><br>
									<div class="pull-right">
										<a class="btn btn-primary pull-right" onclick="form_submit();"><i class="icon-plus-sign icon-white"></i> Add Experiment </a>
									</div>
									<div class="pull-right">
										<a class="lab_books btn btn-success space_button pull-right"> Course Unitization </a>
									</div>
									<input type="hidden" value="" id="curriculum_edit"/>
									<input type="hidden" value="" id="term_edit"/>
									<input type="hidden" value="" id="topic_edit"/>
									<input type="hidden" value="" id="course_edit" />
									<!--Error Msg When drop down box is not selected Modal-->
									<div id="myModal_submit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-header">
											<div class="navbar-inner-custom">
												Warning
											</div>
										</div>
										<div class="modal-body">
											<p> Select all the drop-downs. </p>
										</div>
										<div class="modal-footer">
											<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>
										</div>
									</div>
									<div id="Warning_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Lesson Schedule Confirmation
                                            </div>
                                        </div>
                                    </div>
									<div class="modal-body">
										<p><b>Kindly confirm that you have completed the process of defining Lesson Schedule & Review Questions Section <br/>
										Click on &nbsp;<span style="color:white;background-color:#3370cc">&nbsp; Ok .</span>&nbsp;( If you completed & proceed for <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO Mapping. )<br/>
										Click on &nbsp;<span style="color:white;background-color:#b94646">&nbsp; Cancel . &nbsp;&nbsp;</span> ( If not completed make sure that you defined the Lesson Schedule & then proceed  for <?php echo $this->lang->line('entity_tlo_singular'); ?> to CO Mapping
										</b></p>
									</div>

                                    <div class="modal-footer">   
                                    <a class="btn btn-primary" data-dismiss="modal" id="proceed_tlo_co"><i class="icon-ok icon-white"></i> Ok </a> 
                                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                                    </div>
                                </div>
									<div id="double_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Lesson Schedule Re-Confirmation Message
                                            </div>
                                        </div>
                                    </div>
									<div class="modal-body">
										Re-confirm that you are skipping the process of defining Lesson Schedule and Review Questions Section.<br/><br/>
										An <span style="color:white;background-color:#a65959"> Email </span>&nbsp;  will be sent to the respective <font color="">Dept. Chairman.</font> 
										
									</div>

                                    <div class="modal-footer">   
                                    <a class="btn btn-primary" data-dismiss="modal" id="proceed_tlo_co_confirm"><i class="icon-ok icon-white"></i> Ok </a> 
                                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                                    </div>
                                </div>
									<!--Modal Delete Experiment start-->
									<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-header">
											<div class="navbar-inner-custom">
												Delete Confirmation
											</div>
										</div>
										<div class="modal-body">
											<p>Are you sure you want to Delete ?</p> 
											<p>It's associated <?php echo $this->lang->line('entity_tlo'); ?> will be deleted. If yes press on Ok button.</p>
										</div>
										<div class="modal-footer">
											<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_experiment();"><i class="icon-ok icon-white"></i> Ok </button>
											<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
										</div>
									</div>
									<!--Modal Delete Experiment end-->
									
									<!-- Modal to display myModal_tlo_view message -->
									<div id="myModal_tlo_view" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_tlo_view" data-backdrop="static" data-keyboard="true">
										<div class="modal-header">
											<div class="navbar">
												<div class="navbar-inner-custom">
													<?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) List
												</div>
											</div>
										</div>
										<div class="modal-body" id="expt_tlo_list">
										</div>
										<div class="modal-footer">
											<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>	
										</div>
									</div>
									
									<!-- Modal to display Warning Message - experiments has not been added yet -->
									<div id="myModal_expt_not_defined" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_expt_not_defined" data-backdrop="static" data-keyboard="true"></br>
										<div class="container-fluid">
											<div class="navbar">
												<div class="navbar-inner-custom">
													Warning
												</div>
											</div>
										</div>

										<div class="modal-body">
											<p> Lab experiments are not defined for this Course. </p>
										</div>

										<div class="modal-footer">
											<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
										</div>
									</div>
									<!--Modal myModal_tlo_view Experiment end-->
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
			
	<!---place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 
	<!---place js.php here -->
	<?php $this->load->view('includes/js'); ?>
	<script>
		var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
		var entity_tlo = "<?php echo $this->lang->line('entity_tlo'); ?>";

  </script>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/lsit_lab_experiment.js'); ?>" type="text/javascript"> </script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
    
<!-- End of file add_lab_experiment_vw.php 
            Location: .curriculum/lab_experiment/add_lab_experiment_vw.php -->
