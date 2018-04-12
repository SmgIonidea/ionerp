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
							<select  class="" name="dept" id="dept" autofocus = "autofocus" style="width:200px;"  onchange="fetch_pgm();">
							<option value="">Select Department</option>
								<?php foreach ($dept_details as $department): ?>
								<option value="<?php echo $department['dept_id']; ?>"><?php echo $department['dept_name']; ?></option>
								<?php endforeach; ?>
							</select>
				</div>		
				<div id="" class="span3" >
					 Program<font color="red"> * </font> : <select name="course_pgm_id" id="course_pgm_id" onchange="dept_curriculum1();">
						 <option>Select Program</option>
					</select>
				</div>	
				<div id="" class="span3" >
				 Curriculum<font color="red"> * </font> :
					 <select style="width:200px;" name="course_crclm_id" id="course_crclm_id" onchange="curclm();fetch_crclm1();">
						 <option>Select Curriculum</option>										
					</select>
				</div>	
				<div id="" class="span3" >
				Term<font color="red"> * </font> :
				
					<select name="term_course" id="term_course">
						<option>Select Term</option>
					</select>
				</div>
				
			</div>
			<div class="pull-right" id="export_pdf" style="display:none;">
				<a id="export_data" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
			</div><br/><br/>
			<div class="navbar" id="crclm_head" style="display:none;">
				<div class="navbar-inner-custom crclm_head">
						Curriculum Design Status
				</div>

			</div>
		
				<div id="course_state_table_new">
						
				</div>
			<div class="span12 " id="color_code" style="display:none;">
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> : Completed </b></div>
			</div>
			
			<br/><br/>

			<div>
			<div class="navbar" id="assessment_head" style="display:none;">
				<div class="navbar-inner-custom">
					Assessment Planning  and Data Import Status 
				</div>
				
				<div class="span12" style="font-size:14px;"><div class="span8"><b><?php echo $this->lang->line('entity_cie_full');?> and <?php echo  $this->lang->line('entity_see_full');?> - Assessment Planning  and Data Import Status </b></div></div>
			</div>
				
				<div id="assessment_attainment_dash_board_data"></div>
			
			<div style="width: 100%;" id="assess_attain" name="assess_attain">

				<!--border-right:none; border-bottom:none;border-top:none;-->
			  	<table style="display:none;" class="table table-bordered table-hover example_course_border" id="example_course"  aria-describedby="example_course" >
					<thead>
					</thead>
					<tbody id ="" role="alert" aria-live="polite" aria-relevant="all">
					</tbody>
				</table>
			  </div>
			<br/>
				<div class="span12 " id="color_code3" style="display:none;">
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> : Completed </b></div>
				</div><br/><br/>
			</div>
			<div >
			<div class="navbar" id="survey_co_head" style="display:none;">
				<div class="navbar-inner-custom">
					Curriculum -  Course Survey Status
				</div>
			</div>
			<div id="Survey">
			   <table style="display:none;"  class="table table-bordered table-hover" id="example" name="example" aria-describedby="example_info">
					<thead>
						<tr role="row">
							<th  style="color: #8E2727;width:80px;white-space: nowrap;" class=" sl_no header headerSortDown white-space:nowrap;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727; width:400px;" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727;" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> </th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
						</tr>
					</thead>
					<tbody id ="" role="alert" aria-live="polite" aria-relevant="all">
					</tbody>
				</table>			
			</div>
			<div class="span12 " id="color_code1" style="display:none;" >
			<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> :Completed </b></div>
			</div><br/><br/>
			<div class="navbar" id="survey_po_peo" style="display:none;">
				<div class="navbar-inner-custom">
					Curriculum PEO & PO Survey Status
				</div>
			</div>
			<div id="Survey_PEO">
			   <table style="display:none;" class="table table-bordered table-hover" id="example_peo_po" name="example" aria-describedby="example_info">
					<thead>
						<tr role="row">
							<th style="color: #8E2727;width:80px;white-space: nowrap;" class=" sl_no header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727; width:400px;" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727;" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> </th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
							<th  style="color: #8E2727" class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"></th>
						</tr>
					</thead>
					<tbody id ="" role="alert" aria-live="polite" aria-relevant="all">
					</tbody>
				</table>
			</div>
						<div class="span12 " id="color_code2" style="display:none;" >
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/red_dot.png'); ?>' width='15' height='15' alt=''/><b> : Pending </b></div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/orange_dot.png'); ?>' width='20' height='20' alt=''/><b> : In-Progress </b> </div>
				<div class="span3">
				<img src='<?php echo base_url('twitterbootstrap/img/green_dot.png'); ?>' width='13' height='13' alt=''/> <b> : Completed </b></div>
			</div>
				<br/><br/>
			</div>
			          
			</section>
		</div>
	</div>
</div>
<input type="hidden" name="program_level_pdf_status" id="program_level_pdf_status" />
  <div class="controls row-fluid">		
	<div id="state_status_grid" >
	</div>	
</div>

	<input type="hidden" name="pdf_cloned_crclm_level_status" id="pdf_cloned_crclm_level_status" />
	<input type="hidden" name="pdf_cloned_course_level_status" id="pdf_cloned_course_level_status" />
	<input type="hidden" name="cloned_course_data_status" id="cloned_course_data_status" />
	<input type="hidden" name="cloned_PEO_PO_data_status" id="cloned_PEO_PO_data_status" />
	<input type="hidden" name="cloned_color_code_status" id="cloned_color_code_status" />
	<input type="hidden" name="Survey_level_pdf_status" id="Survey_level_pdf_status" />
	<input type="hidden" name="curriculum_id1" id="curriculum_id1"/>
	<input type="hidden" name="dept_pdf" id="dept_pdf"/>
	<input type="hidden" name="prog_pdf" id="prog_pdf"/>
	<input type="hidden" name="term_pdf" id="term_pdf"/>
<div class="pull-right" id="export_pdf1" style="display:none;" >
	<a id="export_data_down" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
</div><br/><br/>

	
	<div id="co_po_mapping"  style="width:1170px;margin-left:-590px;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
			<div class="modal-header">
				<div class="navbar">
					<div class="navbar-inner-custom" id="crs_status_title">
						CO to PO Mapping Details
					</div>
				</div>
			</div>
			<div class="modal-body" style="height:300px;" id="">									
							
                                                            <div id="map_tbl_div" class="span9" style="overflow:auto;">
                                                                <div id="table_co_po_map" ></div>
                                                            </div>
                                                            <div id="graph_div" class="span3" style="overflow:auto;">
                                                                <div id="course_status_map_pie_chart" style="height:240px;width:250px; margin-top: 13px;" class="jqplot-target"></div>
                                                            </div>
								
								
								
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
			</div>
	</div>
	<style media="all" type="text/css">
    .alignRight { text-align: right; }
</style>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/dashboard/dashboard_course_status.js'); ?>"></script>
<script>
	var cia = "<?php echo $this->lang->line('entity_cie'); ?>";
	var tee = "<?php echo $this->lang->line('entity_tee'); ?>";
</script>
