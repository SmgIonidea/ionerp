<?php
/* --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Controller Logic for List of CIA, Provides the facility to Add/Edit CIA QP.	  
 * Modification History:
 * Date							Modified By								Description
 * 21-08-2015					Abhinay Angadi							Newly added module	
 * 10-11-2015			Shayista Mulla			Hard code(entities) change by Language file labels. 
 * 22-02-2016			bhagyalaxmi S S						Added delete qp feature  
  ---------------------------------------------------------------------------------------------------------------------------------
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
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>) Question Paper List 
                        </div>
                    </div>
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <form name="add_form" id="add_form" method="post" class ="qp_data" action="<?php echo base_url('#'); ?>" class="form-horizontal">
                        <div class="row-fluid">								
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
                                            <select id="pgm_id" name="pgm_id" class="input-medium" onchange="select_crclm_list();">
                                                <option>Select Program</option>
                                            </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            Curriculum:<font color='red'>*</font> 
                                            <select id="crclm_id" name="crclm_id" autofocus = "autofocus" class="input-large" onchange="select_termlist();" >
                                                <option value="">Select Curriculum</option>
                                            </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            Term:<font color='red'>*</font> 
                                            <select id="term" name="term" class="input-medium" onchange="select_courselist();">
                                                <option>Select Term</option>
                                            </select>
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            Course:<font color='red'>*</font> 
                                            <select id="course" name="course" class="input-medium" onchange="GetSelectedValue();">
                                                <option>Select Course</option>
                                            </select>
                                        </label>
                                    </td>
                                </tr>
                            </table>									
                        </div>

                        <div class="row">

                        </div>

                        <div id="myModalQPdisplay_paper_modal_2" class="modal hide fade myModalQPdisplay_paper_modal_2 modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        CIA Question Paper View
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
                        <div>
                            </br>
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown" width="40px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                                        <th class="header headerSortDown " width="100px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Assessment Occasion Name </th>
										<th class="header headerSortDown " width="140px"  role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Section/Division</th>
                                      
                                        <th class="header  " width="80px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Assessment Type</th>
                                        <th class="header "  width="120px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Manage <?php echo $this->lang->line('entity_cie'); ?> QP</th>
                                        <!-- <th class="header span4" width="10px"  role="columnheader" tabindex="0" aria-controls="example"align="center">Course Title - (Code)</th>-->
                                        <th class="header"   width="100px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Course Mode</th>
                                        <th class="header  " width="180px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Course Owner / Instructor</th>
                                        <th class="header  " width="120px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Import CIA QP</th>
                                        <th class="header  " width="100px" role="columnheader" tabindex="0" aria-controls="example" align="center" >View QP</th>
										<th class="header  " width="180px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Upload QP</th>
                                        <th class="header " width="60px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table>
                        </div>
                        </br>
						    <input type="hidden" id="crs_id" name="crs_id" value = ""/>
							<input type="hidden" id="qpd_id" name="qpd_id" value = ""/>
							<input type="hidden" id="qpd_type" name="qpd_type" value = ""/>
							<input type="hidden" id="file_exist" name="file_exist" value = ""/>
							<input type="hidden" id="section_id" name="section_id" value = ""/>
							<input type="hidden" id="occasion_id" name="occasion_id" value = ""/>
							<input type="hidden" id="regerate_name" name="regerate_name" value = ""/>
							<input type="hidden" id="tee_qp_modal_no_fm_data" name="tee_qp_modal_no_fm_data" value = ""/>
							<input type="hidden" id="file_name" name="file_name" value="">								
							<input hidden name="upload_file" id="upload_file" class="test upload_file" type="file" >
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
<input type="hidden" name="view_crclm_id" id="view_crclm_id" value=""/>
<input type="hidden" name="view_term_id" id="view_term_id" value=""/>
<input type="hidden" name="view_crs_id" id="view_crs_id" value=""/>
<input type="hidden" name="view_qpd_type" id="view_qpd_type" value=""/>
<input type="hidden" name="view_ao_id" id="view_ao_id" value=""/>
<input type="hidden" name="view_qpd_id" id="view_qpd_id" value=""/>

<input type="hidden" name="pgmtype_id" id="pgmtype_id" value=""/>
<input type="hidden" name="crclm_id" id="crclm_id" value=""/>
<input type="hidden" name="term_id" id="term_id" value=""/>
<input type="hidden" name="crs_id" id="crs_id" value=""/>
<input type="hidden" name="ao_id" id="ao_id" value=""/>

<div id="qp_not_defined_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning
            </div>
        </div>
    </div>
    <div class="modal-body">
        Question paper is not defined for this occasion.
    </div>
    <div id ="modal_footer" class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
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
        Are you sure that you want to delete?
    </div>

    <div class="modal-footer">
        <a class="btn btn-primary"  id="delete_qp" ><i class="icon-ok icon-white"></i> Ok </a>
        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
    </div>
</div>
<div id="import_occasions_question_paper" class="modal hide fade modal-admin"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
    <div id="loading" class="ui-widget-overlay ui-front">
        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
    </div>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Import <?php echo $this->lang->line('entity_cie_full'); ?>(<?php echo $this->lang->line('entity_cie'); ?>) Question Paper
            </div>
        </div>
    </div>
    <div class="modal-body" style="padding: 20px;" style="hieght:auto;">

        <div class="div_border row" style="margin-bottom: 20px;">

            <div class="row-fluid">
                <div class="crlcm_name span4" id=""><b>Curriculum:</b> <font id="crlcm_name_font"></font></div>
                <div class="term_name span4" id=""><b>Term:</b> <font id="term_name_font"></font></div>
                <div class="crs_name span4" id=""><b>Section:</b> <font id="sec_name_font"></font></div>

            </div>
            <div class="row-fluid">
                <div class="crs_name span4" id=""><b>Course:</b> <font id="crs_name_font"></font></div>
                
                <div class="crs_name span4" id=""><b>Assessment Occasion:</b> <font id="ao_name_font"></font></div>
            </div>


        </div>
        <input type="hidden" name="course_id" id="course_id" value=""/>
        <input type="hidden" name="curriculum_id" id="curriculum_id" value=""/>
        <input type="hidden" name="occasion_ao_id" id="occasion_ao_id" value=""/>
        <input type="hidden" name="program_id" id="program_id" value=""/>
        <input type="hidden" name="qpterm_id" id="qpterm_id" value=""/>

        <div class="div_border">
            <form name="select_form" id="select_form" method="post" action="">
                <u><b>Import <?php echo $this->lang->line('entity_cie_full'); ?> QP From Course Details </b></u>
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
                <div class="div_border" id="qp_list_div">
                    <div><u><b><?php echo $this->lang->line('entity_cie'); ?> Question Paper List</b></u></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-primary"  id="import_qp_button" disabled="disabled" draggable="true" ><i class="icon-download-alt icon-white"></i>Import</button>
        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
</div>
<div id="cia_qp_delete_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning 
            </div>
        </div>
    </div>
    <div class="modal-body">You can't delete this CIA Question Paper.
    </div>
    <div id ="modal_footer" class="modal-footer">
        <button type="button" id="" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok </button>										
    </div>
</div>
<div id="qp_existance" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
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
        <p id="occasion_existance_body_msg"></p>
        <p> if <b>'YES'</b> click   <b>Ok</b> If <b>'NO'</b>  click  <b>Cancel</b> to Stop importing.</p>
    </div>

    <div class="modal-footer">
        <button class="btn btn-primary delete_survey_button" id="force_import_qp"  aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
        <button type="button" class="cancel btn btn-danger" data-dismiss="modal" id="import_cancel"><i class="icon-remove icon-white"> </i> Cancel</button>
    </div>
</div>
<div id="marks_uploaded_already_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning 
            </div>
        </div>
    </div>
    <div class="modal-body">Question paper cannot be imported as marks has been uploaded for this occasion.
    </div>
    <div id ="modal_footer" class="modal-footer">
        <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
    </div>
</div>

<div id="marks_uploaded_already_modal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning 
            </div>
        </div>
    </div>
    <div class="modal-body">Cannot Add/Edit the Question Paper as marks has been uploaded for this occasion.
    </div>
    <div id ="modal_footer" class="modal-footer">
        <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
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
            <form name="rubrics_report" id="rubrics_report" method="POST" action="<?php echo base_url('assessment_attainment/cia_rubrics/export_report/') ?>" >
                <input type="hidden" name="report_in_pdf" id="report_in_pdf" value="" />
            </form>
        </div>
    </div>
    <div id ="modal_footer" class="modal-footer">
        <a type="button" href="#" target="_blank" id="export_to_pdf" class="btn btn-success" ><i class="icon-book icon-white"></i> Export .pdf </a>										
        <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
    </div>
</div>

<div id="delete_rubrics_occasion_modal" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Warning 
            </div>
        </div>
    </div>
    <div class="modal-body">
       Are you sure you want to delete this occasion ?
       <input type="hidden" id="del_ao_method_id" name="del_ao_method_id" value="" />
       <input type="hidden" id="del_ao_id" name="del_ao_id" value="" />
    </div>
    <div id ="modal_footer" class="modal-footer">
        <button type="button" id="delete_rubrics_occasion" class="btn btn-success" ><i class="icon-ok icon-white"></i> Ok </button>										
        <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Cancel </button>										
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

<script>
    var cia_lang = "<?php echo $this->lang->line('entity_cie'); ?>";
    var topic_lang = "<?php echo $this->lang->line('entity_topic'); ?>";
    var co_lang = "<?php echo $this->lang->line('entity_clo'); ?>";
</script>
<!--</div>-->
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js_v3'); ?>
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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/manage_cia_qp.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/upload_qp.js'); ?>" type="text/javascript"> </script>
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
    var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
    var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
	var entity_clo_full_singular = "<?php echo$this->lang->line('entity_clo_full_singular'); ?>";
	var entity_clo_full = "<?php echo$this->lang->line('entity_clo_full'); ?>";	
</script>