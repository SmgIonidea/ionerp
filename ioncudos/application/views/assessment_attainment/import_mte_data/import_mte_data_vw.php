<?php
/**
 * Description	:	Import CIA Data List View
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
        <?php $this->load->view('includes/sidenav_5'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_mte'); ?>) Data Entry / Import List
                        </div>
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
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Code</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Title</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Core / Elective</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Credits</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Total Marks</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Owner</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Mode</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >View <?php echo $this->lang->line('entity_mte'); ?> Details</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Manage <?php echo $this->lang->line('entity_mte'); ?> Marks</th>
                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Status</th>
                                    </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                </tbody>
                            </table><br>
                        </div>
                        </br></br>
                        <!-- Modal to display send for myModal_cia_details message -->
                        <div id="myModal_cia_details" class="modal hide fade" style="width: 60%;right: 40%;left: 40%" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_cia_details" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php //echo $this->lang->line('entity_cie_full'); ?> Occasions List
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="get_mte_details_list">
                            </div>				
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>	
                            </div>
                        </div>

                        <div id="myModalQPdisplay" class="modal hide fade myModalQPdisplay modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                              data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_mte'); ?> Question Paper
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
                                    <!-- <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>-->

                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>

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
                    </div>

                    <!--Do not place contents below this line-->
            </section>	
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
            <!-- <a type="button" href="#" target="_blank" id="export_to_pdf" class="btn btn-success" ><i class="icon-book icon-white"></i> Export .pdf </a>	 -->									
            <button type="button" id="" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" ><i class="icon-remove icon-white"></i> Close </button>										
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js_v3'); ?>
<!---place js.php here -->
<script>
    var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
    var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
    var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
	var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
</script>

<!-- <script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>-->

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
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/import_mte_data.js'); ?>" type="text/javascript"></script>
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
</script>
