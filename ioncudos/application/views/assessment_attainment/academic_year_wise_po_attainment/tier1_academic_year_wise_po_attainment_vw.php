<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Academic Year wise PO attainment.	  
 * Modification History:
 * Date				Added By				Description
 
 * 04-04-2017		Mritunjay B S     	     Model Logic for Academic Year wise po attainment   
 * 10-04-2017 					Bhagyalaxmi S S							CAY PO Attainment
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<style>
.itemconfiguration
{
        overflow-y:auto;
		float:left;

}
</style>
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
				 <form class="form-horizontal" method="POST" target = "_blank" id="po_attainment_form" name="po_attainment_form" action="<?php echo base_url('assessment_attainment/tier1_po_attainment_academic_year_wise/export_pdf'); ?>">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Current Academic Year(CAY) <?php echo $this->lang->line('so'); ?> Attainment
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
                                    <select id="program" name="program" class="input-medium" onchange="">
                                        <option>Select Program</option>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
                            <td>
                                <label>
                                    Academic Year:<font color='red'>*</font> 
                                    <select id="academic_year" name="academic_year" autofocus = "autofocus" class="input-medium"  >
                                        <option value="">Select Year</option>
                                    </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                </label>
                            </td>
							<!-- <td>
								<label>
								<button type="button" id="export_to_doc" class="export_doc btn-fix btn btn-success " abbr="2"><i class="icon-book icon-white"></i> Export .doc</button>
								</label>
							</td> -->
							
							<td>
								<label>
								<button type="button" disabled='disabled' id="export_to_doc" class="export_doc btn-fix btn btn-success " abbr="2"><i class="icon-book icon-white"></i> Export .pdf</button>
								</label>
							</td>
                        </tr>
                    </table>
					
                    <br/>

				<div class= "graph" style="overflow: auto;">
					<div style="width:1250px" id="po_attainment_method_wise_chart1" ></div>
					<div style="width:1250px" id="po_attainment_method_wise_chart2" ></div>
					<div style="width:1250px" id="po_attainment_method_wise_chart3" ></div>
					<div style="width:1250px" id="po_attainment_method_wise_chart4" ></div><br/><br/><br/><br/>
				</div>					
				<div id="academic_data_table" class ="" style="overflow: auto;"></div>
				
				<div id="academic_data_table_note" class ="" ></div>
				
				<input type="hidden" id="tier" name ="tier" />
				<input type="hidden" id="po_stmt" name ="po_stmt" />
				<input type="hidden" id="po_id" name ="po_id" />	
				<input type="hidden" id="dept_name" name ="dept_name" />
				<input type="hidden" id="pgm_name" name ="pgm_name" />
				<input type="hidden" id="crclm_name_val" name ="crclm_name_val" />
				<input type="hidden" id="export_data" name ="export_data"/>
				<input type="hidden" id="main_head" name ="main_head"/>
				<input type="hidden" id="export_graph_data_to_doc" name ="export_graph_data_to_doc"/>
				<div id="po_attainment_main_head_data" style="display:none;"></div>
				<div id="bloom_level_cumm_perf_graph_data" style="display:none;"></div>
				</form>
					    <!-- Cannot Finalize Rubrics  -->
				<div id="term_wise_attainment_details" class="modal hide fade modal-admin in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
						<div class="modal-header">
							<div class="navbar">
								<div class="navbar-inner-custom">
									Program Outcome Attainment by individual Course
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div id="crclm_name_term_modal"></div>
							 <p class="po_reference"></p>		
							 <div class="span12" id="chart1" style="height: 300px; width:95%;">
							 </div>
							 
							 <div class="term_data"></div>
						</div>
						<div id ="" class="modal-footer">													
							<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
						</div>
				</div>
				
				<div id="myModalViewPoAssess" class="modal hide fade large_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none;">
					<div class="modal-header">
						<div class="navbar-inner-custom">
							View Performance Levels 
						</div>
					</div>
					<div class="modal-body">
						<div id="crclm_name"></div>
						<h5><?php echo $this->lang->line('student_outcome_full'); ?>:</h5>						
						<div id="selected_po_vw"></div>
						<hr>
						<div id="performance_po_list"></div>
					</div>
					<div class="modal-footer save_perform_assess_btns">
						<button class="btn btn-danger close_model" id="close_model" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
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
	var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
    var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
    var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
</script>

<script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>
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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>

<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier1_academic_year_wise_po_attainment.js'); ?>" type="text/javascript"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.8/plugins/jqplot.enhancedLegendRenderer.min.js" integrity="sha256-e9Cc8IaSdQYtIsAWiGXjWJXEPQpM2LuaZRNDksHGseg=" crossorigin="anonymous"></script>-->
<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
</script>
