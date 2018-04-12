<?php
/**
 * Description	:	Model QP List View
 * Created		:	09-10-2014. 
 * Author 		:   Abhinay B.Angadi
 * Modification History:
 * Date				Modified By				Description
 * 10-11-2015		   Shayista Mulla			Hard code(entities) change by Language file labels.
 * 22-02-2016		Bhagyalaxmi S S			Added Delete QP Column
  -------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
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
                            Model Question Paper (QP) List - Termwise
                        </div>
                    </div>
                    <!-- to display loading image when mail is being sent -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
                    </div>	
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
                    <div>
                        <div>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
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
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >View QP Details</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Manage Model QP</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Import Model QP</th>
                                        <th class="header" style="width:45px;" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>
                                        <th class="header" style="width:120px;" role="columnheader" tabindex="0" aria-controls="example" >Model QP Status</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table><br>
                        </div>
                    </div>
                    <input type="hidden" name="pgmtype_id" id="pgmtype_id" value=""/>
                    <input type="hidden" name="crclm_id" id="crclm_id" value=""/>
                    <input type="hidden" name="term_id" id="term_id" value=""/>
                    <input type="hidden" name="crs_id" id="crs_id" value=""/>
                    <!--Modal to confirm before deleting peo statement-->
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning 
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Please make sure that, the entire mapping between <?php echo $this->lang->line('entity_clo'); ?> to <?php echo $this->lang->line('so'); ?> Coursewise should be complete before proceeding to create Model Question Paper (QP).</p>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Proceed to Course Outcomes (COs) confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure that you want to proceed this course for the creation of Course Outcomes(COs)?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:publish_course();"><i class="icon-ok icon-white"></i> Ok </button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>

                    <div id="myModalQPdisplay_paper" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Question Paper Model
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <a href="#" class="myModalQPdisplay_paper_2" >LINK</a>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:publish_course();"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>


                    <div id="myModalQPdisplay_paper_modal_2" class="modal hide fade myModalQPdisplay_paper_modal_2 modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                          data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Model Question Paper View
                                </div>
                            </div>

                            <input type="hidden" value="" name="values_data" id="values_data" />
                            <div class="modal-body" id="qp_content_display" width="100%" height="auto">

                            </div>
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                        </div>														
                        <div class="modal-footer">
                           <!-- <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>-->

<!--<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>-->
                            <button class="qp_modal_hide btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                        </div>
                    </div>								

                    <div id="qp_not_defined_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            Model question paper is not defined for this course
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>

                    <!--Modal to display warning status - Request to create QP framework -->
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

                    <div id="model_qp_delete" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Confirmation Message
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            Are you sure that you want to delete this model question paper?
                        </div>

                        <div class="modal-footer">
                            <a class="btn btn-primary"  id="delete_qp" ><i class="icon-ok icon-white"></i> Ok </a>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    
                    <div id="import_model_question_paper" class="modal hide fade modal-admin"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                        <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                        </div>
                        
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Import Model Question Paper.
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" style="padding: 20px;" style="hieght:auto;">
                            <div class="div_border row" style="margin-bottom: 20px;">
                                <div class="row-fluid">
                                    <div class="crlcm_name span4" id=""><b>Curriculum Name:</b> <font id="crlcm_name_font"></font></div>
                                    <div class="term_name span4" id=""><b>Term Name:</b> <font id="term_name_font"></font></div>
                                    <div class="crs_name span4" id=""><b>Course Name:</b> <font id="crs_name_font"></font></div>
                                    
                                </div>
                            </div>
                            <input type="hidden" name="program_id" id="program_id" value=""/>
                            <input type="hidden" name="curriculum_id" id="curriculum_id" value=""/>
                            <input type="hidden" name="qpterm_id" id="qpterm_id" value=""/>
                            <input type="hidden" name="course_id" id="course_id" value=""/>
                            
                            
                          
                                <!--<div class=" container-fluid row-fluid" style="padding-left: 5px; padding-right:0px;">-->
                                <div class="div_border">
                                        <div> <u><b>Import Model QP From Course Details </b></u> </div>
                                         <form name="select_form" id="select_form" method="post" action="">
                                        <table class="table table-bordered dataTables_wrapper dataTable dataTables qp_table">
                                            <thead>
                                                <tr>
                                                    <td>
                                                        <label for="pop_crclm_list">Department<font color="red">*</font>:&nbsp;
                                                            <select class="pop_dept_list input-medium required" name="pop_dept_list" id="pop_dept_list" >
                                                                <option value="">Select Department</option>
                                                            </select>
                                                        </label>
                                                    </td>
                                                     <td>
                                                         <label for="pop_prog_list">Program<font color="red">*</font>:&nbsp;
                                                            <select class="pop_prog_list input-medium required" name="pop_prog_list" id="pop_prog_list" >
                                                                <option value="">Select Program</option>
                                                            </select>
                                                        </label>
                                                    </td>
                                                     <td>
                                                          <label for="pop_crclm_list">Curriculum<font color="red">*</font>:&nbsp;
                                                            <select class="pop_crclm_list input-medium required" name="pop_crclm_list" id="pop_crclm_list">
                                                                <option value="">Select Curriculum</option>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label for="pop_term_list">Term<font color="red">*</font>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <select class="pop_term_list input-medium required" id="pop_term_list" name="pop_term_list">
                                                                <option value="">Select Term</option>
                                                            </select>
                                                        </label>
                                                   </td>
                                                   <td>
                                                        <label for="pop_course_list">Course<font color="red">*</font>:&nbsp;&nbsp;&nbsp;
                                                            <select class="pop_course_list input-medium required" id="pop_course_list" name="pop_course_list" >
                                                                <option value="">Select Course</option>
                                                            </select>
                                                        </label>
                                                   </td>
                                                   <td></td>
                                                </tr>
                                            </thead>
                                        </table>
                                     </form>
                                    
                                <div style="margin-top: 20px;">
                                    
                                <div class="div_border" id="">
                                    <div><u><b>Model Question Paper List</b></u></div>
                                    <div id="qp_list_div"></div>
                                </div>
                                </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary"  id="import_qp_button" disabled="disabled" draggable="true" ><i class="icon-download-alt icon-white"></i>Import</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                </div>
                
               

                <!--Do not place contents below this line-->
            </section>	
        </div>
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
<div id="import_model_qp_existance" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="flase"></br>
                        <div id="loading_popup" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                        </div>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" >
                            <input type="hidden" name="course_id_one" id="course_id_one" value=""/>
                            <input type="hidden" name="curriculum_id_one" id="curriculum_id_one" value=""/>
                            <input type="hidden" name="ao_id_one" id="ao_id_one" value=""/>
                            <input type="hidden" name="qpd_id_one" id="qpd_id_one" value=""/>
                            <input type="hidden" name="qpterm_id_one" id="qpterm_id_one" value=""/>
                            <p id="model_existance_body_msg"></p>
                            <p> if <b>'YES'</b> click   <b>Ok</b> If <b>'NO'</b>  click  <b>Cancel</b> to Stop importing</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary delete_survey_button" id="overwrite_qp" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                            <button type="button" class="cancel btn btn-danger" data-dismiss="modal" id="import_cancel"><i class="icon-remove icon-white"> </i> Cancel</button>
                        </div>
                    </div>
<!---place footer.php here -->

<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js_v3'); ?>		

<!---place js.php here -->
<script>
    var topic_lang = "<?php echo $this->lang->line('entity_topic'); ?>";
    var co_lang = "<?php echo $this->lang->line('entity_clo'); ?>";	
	var entity_clo_full_singular = "<?php echo$this->lang->line('entity_clo_full_singular'); ?>";
	var entity_clo_full = "<?php echo$this->lang->line('entity_clo_full'); ?>";
</script>

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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>

<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/manage_model_qp.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>


