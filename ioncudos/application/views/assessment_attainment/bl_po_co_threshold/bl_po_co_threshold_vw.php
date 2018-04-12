<?php
/**
* Description	:	Bloom's Level, Program Outcome & Course Threshold List View
* Created		:	28-04-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
----------------------------------------------------------------------------------
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
					<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
					<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                             <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Defining Thresholds 
                                </div>
                            </div>
							<table>
								<tr>
									<td>
										Curriculum: <font color="red"> * </font>
										<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange="select_term();">
											<option value="0" selected> Select Curriculum </option>
											<?php foreach ($crclm_list as $list_item) { ?>
												<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
											<?php } ?>
										</select>
										<?php echo str_repeat('&nbsp', 13); ?>
									</td>
									<td>
										Term: <font color="red"> * </font>
										<select size="1" id="term" name="term" aria-controls="example" onChange="bl_po_crs();"></select>
									</td>
								</tr>
							</table>
							
							<div class="bs-example bs-example-tabs">
								<ul id="myTab" class="nav nav-tabs">
									<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
										<!--<li class="active"><a href="#bloom_level_tab" data-toggle="tab"> Bloom's Level Threshold </a></li>-->
										<li><a href="#po_tab" data-toggle="tab"> <?php echo $this->lang->line('student_outcome_full'); ?> Threshold </a></li>
									<?php } ?>
									
									<li><a href="#course_tab" data-toggle="tab"> Course Threshold </a></li>
								</ul>
								<div id="myTabContent" class="tab-content">
									<?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
										<!-- Tab one - bloom's level threshold starts here -->
										<div class="tab-pane fade in active" id="bloom_level_tab">
											<!-- display bloom level threshold -->							
											<div data-target="#navbarExample" class="bs-docs-example">
												<div class="generate_bl_table_view" style="overflow:auto;">
												</div>
											</div>
										</div>
										<!-- tab one ends here -->
										
										<!-- Tab two - program outcome threshold starts here -->
										<div class="tab-pane fade" id="po_tab">
											<!-- display program outcome threshold -->							
											<div data-target="#navbarExample" class="bs-docs-example">
												<div class="generate_po_table_view" style="overflow:auto;">
												</div>
											</div>
										</div>
										<!-- tab two ends here -->
									<?php } ?>
									
									<!-- Tab three - course threshold starts here -->
									<div class="tab-pane fade" id="course_tab">
										<!-- display program outcome threshold -->							
										<div data-target="#navbarExample" class="bs-docs-example">
											<div class="generate_course_table_view" style="overflow:auto;">
											</div>
										</div>
									</div>
									<!-- tab three ends here -->
								</div>
							</div>
						</div>
						<!--Do not place contents below this line-->
                    </section>	
                </div>
            </div>
        </div>
		
		<div id="bloom_level_crs_wise" class="modal hide fade"  style="width:900px;margin-left:-400px;display: none; " role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="" data-backdrop="static" data-keyboard="true"><br/>
			<div class="modal-header">
				<div class="navbar">
					<div class="navbar-inner-custom" id="crs_title">
						 Manage Bloom's Level Threshold
					</div>
				</div>
			</div>
			
			<div class="modal-body">
				<div id="bloom_data"></div>
			</div>
			
			<div class="modal-footer">
				<button class="btn btn-primary" id="save_course_bloom_level" onClick=""><i class="icon-file icon-white"></i>Update</button>
				<button class="cancel btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel</button> 
			</div>
		</div>
						
        <div id="clo_threshold_modal" class="modal hide fade"  style="width:1090px;margin-left:-550px;display: none; " role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="" data-backdrop="static" data-keyboard="true"><br/>
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
			
            <div class="modal-header">
                <div class="navbar">
                    <div class="navbar-inner-custom" id="crs_title">
                        Manage CO Level Threshold
                    </div>
                </div>
            </div>
            
			<div class="modal-body">
                            <input type="hidden" name="update_crs_id" id="update_crs_id" value="" />
                <div id="clo_data"></div>
            </div>
            
			<div class="modal-footer">
                <button class="btn btn-primary" id="save_course_clo_level" onClick=""><i class="icon-file icon-white"></i>Update</button>
                <button class="cancel btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel</button> 
            </div>
        </div>
	<!---place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 
	<!---place js.php here -->
	<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/bl_po_co_threshold.js'); ?>" type="text/javascript"> </script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
	
<!-- End of file bl_po_co_threshold_vw.php 
        Location: .views/assessment_attainment/bl_po_co_threshold/bl_po_co_threshold_vw.php -->
