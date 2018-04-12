<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: .
 * Author : Bhagyalaxmi S Shivapuji
 * Date : 29th May 2017
 * Modification History:
 * Date				Modified By				Description
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!-- <body data-spy="scroll" data-target=".bs-docs-sidebar">-->
   <!--branding here-->
    <?php $this->load->view('includes/branding'); ?>
 <!-- Navbar here -->
    <?php $this->load->view('includes/navbar'); ?>

	<div class="container-fluid">
       <div class="row-fluid">
			<?php $this->load->view('includes/sidenav_5'); ?>
			 <div class="span10">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
				<section id="contents">
					 <div class="bs-docs-example" >
						<div class="navbar">
                            <div class="navbar-inner-custom">
                                First Year Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment 
                            </div>
                        </div>
						<form method="POST" target='_blank' class="form-inline"  action="<?php echo base_url('assessment_attainment/tier1_first_year_co_attainment/export_to_doc'); ?>" id="export_to_doc_form" name="export_to_doc_form" >
						<div class="row-fluid">
							<div class="bs-example bs-example-tabs">
								<div class="control-group span3 form-horizontal_new">
								   <label class="control-label" style="width:100px;" for="inputEmail">Department: <font color="red">*</font> </label>
									<div class="controls">
										<select id="dept_id" name="dept_id" class="input-large" style="width:250px;">
											<option value="">Select Department</option>
											<?php foreach ($deptlist as $dept) { ?>
												<option value="<?php echo $dept['dept_id'] ?>"><?php echo $dept['dept_name'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								
								<div class="control-group span2 form-horizontal_new">
								   <label class="control-label" style="width:100px;" for="inputEmail">Program: <font color="red">*</font> </label>
									<div class="controls" >
										<select id="pgm_id" name="pgm_id" class="input-large"  style="width:160px;" >
											<option value="">Select Program</option>
										</select>
									</div>
								</div>	
								
								<div class="control-group span2 form-horizontal_new">
								   <label class="control-label" style="width:100px;" for="inputEmail">Curriculum: <font color="red">*</font> </label>
									<div class="controls">
										<select id="crclm_id" name="crclm_id" class="input-largem multiselect"  multiple="multiple" >											
										</select>
									</div>
								</div>		
								
								<div class="control-group span2 form-horizontal_new">
								   <label class="control-label" style="width:100px;" for="inputEmail">Term: <font color="red">*</font> </label>
									<div class="controls">
										<select id="term_id" name="term_id" class="input-large  multiselect"  multiple="multiple" >											
										</select>
									</div>
								</div>
								
								<div class="control-group span2 form-horizontal_new">
								   <label class="control-label" style="width:100px;" for="inputEmail">Course: <font color="red">*</font> </label>
									<div class="controls">
										<select id="crs_id" name="crs_id" class="input-large  multiselect"  multiple="multiple" >											
										</select>
									</div>
								</div>
								<!--<div class="control-group span2 form-horizontal_new">-->
									<div class="control-group form-horizontal_new pull-right">
                                        
										<br/>
											<button type="button"  disabled id="export_doc_btn" class="export_doc_btn btn-fix btn btn-success" abbr="0"><i class="icon-book icon-white" ></i> Export .doc</button>
											<input type="hidden" name="type_id" id="type_id" value="2" />
											<input type="hidden" name="form_name" id="form_name" value="" />
										
                                    </div>
								<!-- </div>-->
							</div>
						</div>
						<!-- Content will go here -->	
						<input type="hidden" id="export_graph_data_to_doc_individual" name= "export_graph_data_to_doc_individual" />
						<input type="hidden" id="export_graph_data_to_doc_overall" name= "export_graph_data_to_doc_overall" />
						<div style="display:none;" name="head_data" id="head_data" ></div>
						<input type="hidden" name="main_head" id="main_head" value="" />
						<input type="hidden" id="individual_attmt" name= "individual_attmt" />
						<input type="hidden" id="overall_attmt" name = "overall_attmt" />
							<div id="dynamic_grid"></div><br/><hr/>
							
							<div id = "fdy_attainment_grid">
								<div class="navbar">
									<div class="navbar-inner-custom">
										Overall Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment 
									</div>
								</div>
								<div id="attainment_chart_overall"></div>
								<table id="FDYAttainment_overall" name="FDYAttainment_overall"  class="table table-bordered table-hover" >
									<thead><th> Sl.No </th><th> CO Code </th><th> CO Code </th>
									<!-- <th> CIA Threshold </th><th> MTE Threshold </th><th> TEE Threshold </th><th> Threshold </th>-->
									<th> Average Threshold </th></thead>
									<tbody><td></td><td>No Data To Display</td><td></td><td></td> <!-- <td></td><td></td><td></td><td></td> --></tbody>
								</table>
							</div> 
						</form>
					 </div>
					<div id="course_wise_drill_down_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width: 600px;" data-controls-modal="course_wise_drill_down_modal" data-backdrop="static" data-keyboard="false"></br>
						<div class="container-fluid">
							<div class="navbar">
								<div class="navbar-inner-custom">
									 
								</div>
							</div>
						</div>

						<div class="modal-body">
							<p id="attainment_table_grid"></p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-danger" data-dismiss="modal" onclick=""><i class="icon-remove icon-white"></i> Close</button> 
						</div>
					</div>
				</section>
			</div>
	   </div>
	</div>
	 <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->	    
    <?php $this->load->view('includes/js'); ?>
	    <script>
        var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
        var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
		var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
        var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
        var course_outcome = "<?php echo $this->lang->line('entity_clo_full'); ?> <?php echo '('.$this->lang->line('entity_clo').')'; ?>";
    </script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_i/tier1_first_year_co_attainment.js'); ?>"></script><script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
	    <script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>    
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
	
	
		<style>
	#row {		
        display:            block;
        table-layout:       fixed;
        width:              100%;
        margin:             0 auto;
        white-space:        nowrap;
      }
      
      #row div {
		display:           inline-flex;
        vertical-align:     top;
      }

      .column {       
        -moz-border-radius: 15px;
        border-radius:      15px;
        border-color:       grey;
        box-shadow:         grey 1em 1em 1em
        -webkit-gradient:   grey 1em 1em 1em
        -moz-linear-gradient: grey 1em 1em 1em
        margin:             0 auto;
        padding:            15px;
        text-align:         center;
		overflow: hidden;		
      }

	</style>