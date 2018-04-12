<?php
/**
 * Description	:	Model QP List View
 * Created		:	09-10-2014. 
 * Author 		:   Abhinay B.Angadi
 * Modification History:
 * Date				Modified By				Description
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
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $this->lang->line('entity_see'); ?> Question Paper(QP) List - Termwise
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
					<form name="qp_data" id="qp_data" class="qp_data" method="POST" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>
                                <label>
                                    Department:<font color='red'>*</font> 
                                    <select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();" >
                                        <option value="">Select Department</option>
                                        <?php foreach ($dept_data as $listitem): ?>
                                            <option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Program:<font color='red'>*</font>
                                    <select id="program" name="program" class="input-medium" onchange="select_crclm_list();">
                                        <option>Select Program</option>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Curriculum:<font color='red'>*</font> 
                                    <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-large" onchange="select_termlist();" >
                                        <option value="">Select Curriculum</option>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Term:<font color='red'>*</font> 
                                    <select id="term" name="term" class="input-medium" onchange="GetSelectedValue();">
                                        <option>Select Term</option>
                                    </select>
                                </label>                                
                            </td>
                        </tr>
                    </table>
					<br>
							<input type="hidden" id="crs_id" name="crs_id" value = ""/>
							<input type="hidden" id="qpd_id" name="qpd_id" value = ""/>
							<input type="hidden" id="qpd_type" name="qpd_type" value = ""/>
							<input type="hidden" id="file_exist" name="file_exist" value = ""/>
							<input type="hidden" id="section_id" name="section_id" value = ""/>
							<input type="hidden" id="occasion_id" name="occasion_id" value = ""/>
							<input type="hidden" id="abbr_address" name="abbr_address" value = ""/>
							<input type="hidden" id="regerate_name" name="regerate_name" value = ""/>
							<input type="hidden" id="tee_qp_modal_no_fm_data" name="tee_qp_modal_no_fm_data" value = ""/>
							<input type="hidden" id="file_name" name="file_name" value="">								
							<input hidden name="upload_file" id="upload_file" class="test upload_file" type="file" >
				</form>	

                    <div>
                        <div>
                            <table class="table table-bordered table-hover" id="tee_qp_list" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Code</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Title</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Core / Elective</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Total Marks</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Owner</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >View/Edit <?php echo $this->lang->line('entity_see'); ?> QP</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Add <?php echo $this->lang->line('entity_see'); ?> QP</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Upload <?php echo $this->lang->line('entity_see'); ?> QP</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Status</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table><br>
                        </div>	
                        </br></br>
                        <!--Modal to confirm before deleting peo statement-->
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Delete confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete? As there are Course Outcomes (COs) being defined for this course, those also get deleted?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_course();"><i class="icon-ok icon-white"></i> Ok </button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>
                        <div id="tee_qp_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom" id="modal_title">
                                        Create <?php echo $this->lang->line('entity_see'); ?> Question Paper 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="tee_qp_list_table">
                                <input type="hidden" name="tee_lang_val" id="tee_lang_val" value="<?php echo $this->lang->line('entity_see'); ?>" />
                                <p id="tee_qp_modal_text"></p>
                                <br><br>
                                <div id="rubrics_table_display_div">
                                    
                                </div>
                            </div>
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                            <div id = "fm_not_defined_msg"></div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="tee_qp_modal_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok </button>
                                <button id="tee_qp_modal_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div>
						<div id="topic_not_defined_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="topic_not_defined_modal" data-backdrop="static" data-keyboard="false">
							<div class="modal-header">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Warning 
									</div>
								</div>
							</div>
							<div class="modal-body topic_error_msg">
							</div>
							<div id ="modal_footer" class="modal-footer">
								<button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
							</div>
						</div>	
                        <div id="tee_qp_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Delete Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to Delete this <?php echo $this->lang->line('entity_see'); ?> Question Paper (QP).
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="tee_qp_delete_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
                            </div>
                        </div>

                        <div id="cannot_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Message
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                You cannot Delete this <?php echo $this->lang->line('entity_see'); ?> Question Paper(QP) as it is already Rolled-out.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close </button>
                            </div>
                        </div>

                        <div id="qp_rollout_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Roll-out Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body rollout_msg">Are you sure you want to Roll-out this <?php echo $this->lang->line('entity_see'); ?> Question Paper ?
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="roll_out_yes" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
                                <button id="roll_out_no" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
                            </div>
                        </div>
                        <div id="qp_rollout_modal_cant" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Roll-out Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">You can't Roll-Out this <?php echo $this->lang->line('entity_see'); ?> Question Paper as the data has been uploaded for the other <?php echo $this->lang->line('entity_see'); ?> Question Paper for the same course.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok </button>										
                            </div>
                        </div>


                        <div id="qp_rollout_forcible_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Edit Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">Are you sure you want to Edit this <?php echo $this->lang->line('entity_see'); ?> Question Paper(QP)?
                            </div>
                            <div id ="tee_edit_modal_footer" class="modal-footer">
                                <button type="button" id="tee_qp_edit_yes" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
                                <button id="tee_qp_edit_no" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
                            </div>
                        </div>

                        <div id="qp_cannot_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> QP Message
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">You cannot edit this <?php echo $this->lang->line('entity_see'); ?> Question Paper(QP), as Assessment data is already imported (uploaded) against this <?php echo $this->lang->line('entity_see'); ?> QP.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button id="roll_out_no" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>

                        <div id="myModalQPdisplay" class="modal hide fade myModalQPdisplay modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                              data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> Question Paper
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="" name="values_data" id="values_data" />
                            <div class="modal-body" id="qp_content_display" width="100%" height="auto">

                            </div>									
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>

                            <div class="modal-footer">
                                    <!--<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>-->

                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>

                        <div id="myModalQPCompare" class="modal hide fade myModalQPCompare" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;height:95%;width: 80%;left: 30%;right: 30%;bottom:10%;overflow-y:hidden;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Analyze Question Paper
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="" name="analysis_data" id="analysis_data" />
                            <div class="modal-body" id="qp_analysis_content_display" width="100%" style="max-height:75%;">

                                <div id="content">
                                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                        <li  class="active"><a href="#co_planned_coverage_dist" class="co_planned_coverage_dist_cls" data-toggle="tab">Course Outcome Planned Coverage Distribution</a></li>
                                        <!-- <li><a href="#topic_planned_coverage_dist" class="topic_planned_coverage_dist_cls" data-toggle="tab"><?php echo $this->lang->line('entity_topic'); ?> Planned Coverage Distribution</a></li>-->
                                        <li><a href="#blm_lvl_planned_coverage_dist" class="blm_lvl_planned_coverage_dist_cls" data-toggle="tab">Bloom's Level Planned vs. Coverage Distribution</a></li>
                                    </ul>
                                    <div id="my-tab-content" class="tab-content">
                                        <div class="tab-pane" id="blm_lvl_planned_coverage_dist">
                                            <h6>Bloom's Level Planned vs. Coverage Distribution</h6>
                                        </div>
                                        <div class="tab-pane active" id="co_planned_coverage_dist">
                                            <h6>Course Outcome Marks Distribution</h6>

                                        </div>
                                        <!--<div class="tab-pane" id="topic_planned_coverage_dist">
                                            <h6><?php //echo $this->lang->line('entity_topic'); ?> Planned Coverage Distribution</h6>
                                        </div>-->
                                        
                                    </div>
                                </div>

                            </div>									


                            <div class="modal-footer">		

                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>

                        <div id="check_framework" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                As there is no Question Paper Framework defined for this Program you won't be able to create Model Question Paper or Term End Evaluation Question Paper.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                            </div>
                        </div>
                        <div id="qp_without_framework_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                As there is no Question Paper Framework defined for this Program.<br/>
                                Do you want to create Question Paper with your own framework?
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-primary" id="qp_without_framework_ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                            </div>
                        </div>					
                        <div id="qp_without_framework_information" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                As there is no Question Paper Framework defined for this Program.<br/>							
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-primary" id="" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                                                            <!--<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>-->
                            </div>
                        </div>
                        <div id="roll_out_update_not" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                Question paper cannot be <span class="badge badge-important" >Rolled-Out</span>.						
                                Atleast one <?php echo $this->lang->line('entity_clo_singular'); ?> mapping is required.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>  Close </button>
                            </div>
                        </div>

                    </div>
                    
                    <div id="tee_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> Rubrics Delete Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this <?php echo $this->lang->line('entity_see'); ?> Rubrics.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button type="button" id="tee_rubrics_delete_ok" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Yes </button>
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> No </button>
                            </div>
                        </div>
                    
                    
                    <div id="cant_edit_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> Rubrics Message
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                You cannot edit this <?php echo $this->lang->line('entity_see'); ?> Rubrics, as Assessment data is already imported (uploaded) against this <?php echo $this->lang->line('entity_see'); ?> Rubrics.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>
                   <div id="topic_not_defined_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="topic_not_defined_modal" data-backdrop="static" data-keyboard="false">
						<div class="modal-header">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Warning 
								</div>
							</div>
						</div>
						<div class="modal-body topic_error_msg">
						</div>
						<div id ="modal_footer" class="modal-footer">
							<button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
						</div>
					</div>	
                    <div id="cant_delete_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> Rubrics Message
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                You cannot Delete this <?php echo $this->lang->line('entity_see'); ?> Rubrics as it is already Rolled-out and  Assessment data is already imported (uploaded) against this <?php echo $this->lang->line('entity_see'); ?> Rubrics.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>
                    
                    <div id="pending_message_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_see'); ?> Rubrics Message
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                msg
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                
                                <button id="tee_qp_delete_cancel" type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
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
                            <a type="button" href="#" target="_blank" id="export_to_pdf" class="btn btn-success" ><i class="icon-book icon-white"></i> Export .pdf </a>										
                            <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
                        </div>
                    </div>
                    
                     <div id="forcefull_qp_roll_back" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                A Question Paper is already <span class="badge badge-important" >Rolled-Out</span>.						
                                Still do you want to Import Question Paper for the course, 
                                Then Rolled-Out Question paper will be Rolled back to the Pending State.
                                <input type="hidden" name="qp_crs_id" id="qp_crs_id" value="" />
                                <input type="hidden" name="qp_crclm_id" id="qp_crclm_id" value="" />
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-primary"  id="qp_upload_continue"><i class="icon-ok icon-white"></i>  Continue </button>
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>  Cancel </button>
                            </div>
                        </div>
                    
                        <div id="qp_marks_uploaded_msg" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                A Question Paper is already <span class="badge badge-important" >Rolled-Out</span> and Marks also Uploaded.						
                                Still do you want to Import Question Paper for the course, 
                                Then Discard the Uploaded Marks for the Question paper.
                            </div>
                            <div id ="modal_footer" class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>  Close </button>
                            </div>
                        </div>

                    <!--Do not place contents below this line-->
            </section>	
        </div>
    </div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js_v3'); ?>
<!---place js.php here -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.sizes.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/excanvas.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script>
                                    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
                                    var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
                                    var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
                                    var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
									var entity_clo_full_singular = "<?php echo$this->lang->line('entity_clo_full_singular'); ?>";
									var entity_clo_full = "<?php echo$this->lang->line('entity_clo_full'); ?>";
</script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/tee_qp_list.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/upload_qp.js'); ?>" type="text/javascript"> </script>



