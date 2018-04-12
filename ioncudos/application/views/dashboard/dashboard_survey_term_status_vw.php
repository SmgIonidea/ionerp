<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Author: Bhagyalaxmi S S
 * Modification History:
 * Date				Modified By				Description 
 * 05-2-2016		Bhagyalaxmi S S
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
	<div id="loading" class="ui-widget-overlay ui-front">
		<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
    </div>
<div class="container-fluid" >
    <div class="row-fluid">
        <div class="span12">
			<section id="contents">
			<div class="controls row-fluid">	
				<div class="span3">		
							<span class="dot"><span class="dot-inner"></span></span>
							Department<font color="red"> * </font> : 
							<select  class="" name="survey_dept" id="survey_dept" autofocus = "autofocus" style="width:200px;"  onchange="fetch_pgm();">
							<option value="">Select Department</option>
								<?php foreach ($dept_details as $department): ?>
								<option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_name']; ?></option>
								<?php endforeach; ?>
							</select>
				</div>		
				<div id="" class="span3" >
					 Program<font color="red"> * </font> : <select name="survey_pgm_id_crclm" id="survey_pgm_id_crclm" onchange="dept_curriculum1();">
						 <option>Select Program</option>
					</select>
				</div>	
				<div id="" class="span3" >
				 Curriculum<font color="red"> * </font> :
					 <select style="width:200px;" name="survey_crclm_id_crclm" id="survey_crclm_id_crclm" onchange="fetch_crclm1();">
						 <option>Select Curriculum</option>										
					</select>
				</div>	
			</div>
			<div class="pull-right" id="survey_export_pdf" style="display:none;">
				<a id="survey_export_data" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
			</div><br/>
			<div class="span12 " id="color_code" style="display:none;">
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> : Completed </b></div>
			</div>	
<br/><br/> <div id="survey_status_view"></div>


			<div class="span12 " id="color_code" style="display:none;">
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> : Completed </b></div>
			</div>			          
			</section>
		</div>
	</div>
</div>
<input type="hidden" name="course_survey_status" id="course_survey_status" />
  <div class="controls row-fluid">		
	<div id="state_status_grid" >
	</div>	
</div>

	<input type="hidden" name="pdf_cloned_crclm_level_status" id="pdf_cloned_crclm_level_status" />
	<input type="hidden" name="pdf_cloned_course_level_status" id="pdf_cloned_course_level_status" />
	<input type="hidden" name="cloned_course_data_status" id="cloned_course_data_status" />
	<input type="hidden" name="cloned_PEO_PO_data_status" id="cloned_PEO_PO_data_status" />
	<input type="hidden" name="cloned_color_code_status" id="cloned_color_code_status" />
	<input type="hidden" name="peo_po_survey_status" id="peo_po_survey_status" />
	<input type="hidden" name="curriculum_id1" id="curriculum_id1"/>
	<input type="hidden" name="dept_pdf" id="dept_pdf"/>
	<input type="hidden" name="prog_pdf" id="prog_pdf"/>
	<input type="hidden" name="term_pdf" id="term_pdf"/>
<div class="pull-right" id="survey_export_pdf1" style="display:none;" >
	<a id="survey_export_data_down" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
</div><br/><br/>
	
	<div id="co_po_mapping"  style="width:900px;margin-left:-450px;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
			<div class="modal-header">
				<div class="navbar">
					<div class="navbar-inner-custom" id="crs_status_title">
						CO to PO Mapping Details
					</div>
				</div>
			</div>
			<div class="modal-body" style="height:300px;" id="">									
							<div style="width:100%;" >
								<div style="width:80%; float:left;"><div id="table_co_po_map" style="position:absolute;"></div></div>
								<div style="width:20%; float:right;"><div id="course_status_map_pie_chart" style="margin-left:-100px;position:absolute;height:240px;width:250px;" class="jqplot-target"></div></div>
							</div>	
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
			</div>
	</div>
	<style media="all" type="text/css">
    .alignRight { text-align: right; }
</style>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_survey_status.js'); ?>"></script>
<script>
	var cia = "<?php echo $this->lang->line('entity_cie'); ?>";
	var tee = "<?php echo $this->lang->line('entity_tee'); ?>";
</script>
