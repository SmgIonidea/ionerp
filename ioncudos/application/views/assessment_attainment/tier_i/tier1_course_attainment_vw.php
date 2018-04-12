<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic list view page, provides the fecility to view the Topic Contents.
 * Modification History:
 * Date				Modified By				Description
 * 1-08-2016                    Mritunjay B S                	        Course  Attainemnt Data view.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->

<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<body data-spy="scroll" data-target=".bs-docs-sidebar">
    <!--branding here-->
    <?php $this->load->view('includes/branding'); ?>
    <!-- Navbar here -->
    <?php $this->load->view('includes/navbar'); ?> 
    <div class="container-fluid">
        <div class="row-fluid">
            <!--sidenav.php-->
            <?php $this->load->view('includes/sidenav_5'); ?>
            <div class="span10">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <!-- Contents -->
                <section id="contents">
                    <div class="bs-docs-example" >
                        <!--content goes here-->
                        <div class="navbar">
                            <div class="navbar-inner-custom">
								<?php if($mte_flag == 1){ $mte = $this->lang->line('entity_mte').' , '; }else{ $mte = '';}?>
                                Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainment (Course <?php echo $this->lang->line('entity_cie'); ?> ,  <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)
                            </div>
                        </div>
                        <div class="bs-example bs-example-tabs">
                            <form  method="POST" id="course_attainment_form" name="course_attainment_form" class="form-inline"  action="<?php echo base_url('assessment_attainment/tier1_course_clo_attainment/export_to_doc'); ?>">
                                
                                <table style="width:100%; overflow:auto;">
                                <tr>
                                    <td>
                                        <p>
                                            Curriculum:<font color='red'>*</font> 
                                            <?php
                                            foreach ($crclm_data as $listitem2) {
                                                $select_options1[$listitem2['crclm_id']] = $listitem2['crclm_name']; //group name column index
                                            }
                                            if (!isset($select_options1))
                                                $select_options1['0'] = 'No Curriculum to display';
                                            echo form_dropdown('crclm_name', array('' => 'Select Curriculum') + $select_options1, set_value('crclm_id', '0'), 'onchange="select_term();" class="required input-large" id="crclm_id" autofocus = "autofocus"');
                                            ?>

                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Term:<font color='red'>*</font> 
                                            <select id="term" name="term" class="input-medium" onchange="select_course();">
                                                <option value="">Select Term</option>
                                            </select>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Course:<font color='red'>*</font> <?php echo str_repeat('&nbsp;', 0); ?>
                                            <select id="course" name="course" class="input-large">
                                                <option value="0">Select Course</option>
                                            </select>
                                        </p>
                                    </td>
									<td>
										 <button type="button" disabled="disabled" id="export_tab2_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
									</td>
                                </tr>
                            </table>
                            
                                <ul id="myTab" class="nav nav-tabs">
                                <li class="active toggle_doc_button" id="tab1"><a href="#direct_attainment" data-toggle="tab">Course - <?php echo $this->lang->line('entity_cie'); ?> Attainment</a></li>
								<?php if($mte_flag == 1) {?><li class="toggle_doc_button" id="tab4"><a href="#mte_attainment" data-toggle="tab">Course - <?php echo $this->lang->line('entity_mte'); ?> Attainment</a></li><?php } ?>
                                <li id="tab2" class="toggle_doc_button" ><a href="#indirect_attainment" data-toggle="tab"> Finalize Course - CO Attainment </a></li>
                                <li id="tab3" class="toggle_doc_button" ><a href="#bloom_level_attainment_data" data-toggle="tab"> Course - Bloom's Level Attainment </a></li>
								
                            </ul>
                            
                        <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade in active" id="direct_attainment">
                                    <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                    Course - <?php echo $this->lang->line('entity_cie'); ?> Attainment List (Section - wise)
                                            </div>
                                    </div>
                                    <div class="control-group span11 form-horizontal_new">
                                      <!--  <button type="button" disabled="disabled" id="export_tab1_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button> -->
                                        <input type="hidden" name="tab_name" id="tab_name" value="" />
                                        <input type="hidden" name="form_name" id="form_name" value="course_attainment_form" />
                                        <input type="hidden" name="export_doc_data" id="export_doc_data" value="" />
                                        <input type="hidden" name="file_name" id="file_name" value="" />
                                    </div>
                                    <div id="section_finalize_status_tbl"></div>
									<div id="redirect_link"></div>
                                    <div id="note_data"></div>
                                </div>
							<?php if($mte_flag == 1) {?> 
								<div class="tab-pane fade in" id="mte_attainment">
                                    <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                    Course - <?php echo $this->lang->line('entity_mte'); ?> Attainment List 
                                            </div>
                                    </div>

                                    <div id="mte_finalize_status_tbl"></div>
									<div id= "redirect_link_mte"></div>
                                    <div id="note_data_mte"></div>
                                </div><?php } ?>								 
									<div class="tab-pane fade" id="indirect_attainment" style="overflow:auto;">
                                    
                                    <div class="control-group span4 form-horizontal_new ">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Type: <font color="red">*</font></label>
                                       <div class="controls">
                                            <select id="select_occa_type" name="occa_type" class="select_occa_type input-medium multiselect"  multiple="multiple"></select>
												
                                       </div>
                                   </div><br/>
								  <div class="type_data" ></div>
                                  <!--  <div class="span10">
                                        <button type="button" disabled="disabled" id="export_tab2_to_doc" class="export_doc btn-fix btn btn-success pull-right" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
                                    </div>-->
                                    <div  style="display: none;" class="pull-right" id="attainment_finalize_button_div">
										<button name="tee_cia_attainment_finalize_btn" id="tee_cia_attainment_finalize_btn" type="button" class="btn btn-success"><i class="icon-ok icon-white"></i> Finalize Attainment</button>
                                    </div>
                                    <br><br><br>									      
                                    <div id="occasion_wise_attainment_div">
                                            <div class="navbar" id="occasion_wise_attainment_navbar"  ><br/>
                                                    <div class="navbar-inner-custom" id="ocaasion_type_navbar">
                                                            Course Outcome(COs) Attainment
                                                    </div>
                                            </div>

                                            <div id="all_section_co_attainment_chart" ></div>											
                                                    <div id="all_section_attainment"></div>
                                                    <div id="note_data1"></div>										
                                            <div id="breaks"></div>
                                            <input type="hidden" name="clo_id_data" id="clo_id_data" />
                                            <input type="hidden" name="clo_code_data" id="clo_code_data" />
                                            <input type="hidden" name="tee_cia_attainment" id="tee_cia_attainment" />
                                            <input type="hidden" name="average_ao_attainment" id="average_ao_attainment" />
                                            <input type="hidden" name="threshold_co_attainment" id="threshold_co_attainment" />
                                            <input type="hidden" name="threshold_tee_attainment" id="threshold_tee_attainment" />
                                            <input type="hidden" name="export_graph_data_to_doc" id="export_graph_data_to_doc" value="" />
                                            <br/><br/><br/>

                                    </div>

                                    <div id="breaks_id"></div><br/><br/><br/>
                                    <div id="display_finalized_attainment_div" style="display:none;" ><br/>
										<div class="navbar" id="cia_tee_attainment_navbar"  >
											<div class="navbar-inner-custom">
												Overall Course Outcomes (<?php echo $this->lang->line('entity_clo'); ?>) Attainments are Finalized
											</div>
										</div>
                                        <div id="display_finalized_attainment_tbl"> </div>
                                    </div>
                                    <br>
                                    <div id="display_finalized_po_attainment_div" style="display:none;" >

                            <div class="span12">
                                    <div class="">
                                             <div class="navbar" id="cia_tee_attainment_navbar_map"  >
                                                    <div class="navbar-inner-custom">
                                                            Course -  <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) to <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Attainment Matrix  
                                                    </div>
                                            </div>
                                                    <div id="display_finalized_po_attainment_mapping_tbl" style="overflow:auto;"> </div>
                                    </div>	
                            </div>		
                            <div class="span12">										
                                    <div class="span6">
                                            <div class="navbar" id="cia_tee_attainment_navbar_attain"  >
                                                    <div class="navbar-inner-custom">
                                                            <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('sos'); ?> Attainment by the Course
                                                    </div>
                                            </div>											
                                            <div id="display_finalized_po_attainment_tbl"> </div>
                                    </div>

                                    <div class="span6">
                                            <div class="navbar" id="cia_tee_attainment_navbar_wt" >
                                                    <div class="navbar-inner-custom">
                                                            Map Level Weightage
                                                    </div>
                                            </div>
                                            <div id="display_map_level_weightage"> </div>									
                                    </div>
                            </div>

                      </div>
                      <div id="display_finalized_po_attainment_div_note"></div>
                    </div>

                     <div class="tab-pane fade" id="bloom_level_attainment_data">
                            <div class="">	
                                    <div class="control-group span3 form-horizontal_new">
                                       <label class="control-label" style="width:67px;" for="inputEmail">Type: <font color="red">*</font></label>
                                       <div class="controls">
                                                    <select id="select_occa_type_blm" name="select_occa_type_blm" class="select_occa_type_blm input-medium">	
                                                                    <option value="">Select Type</option>
                                                                    <option value="3"><?php echo $this->lang->line('entity_cie'); ?></option>
                                                                    <option value="5"><?php echo $this->lang->line('entity_see'); ?></option>                                                    
                                                            </select>
                                       </div>
                               </div>								   
                                <div class="control-group span3 form-horizontal_new section_div" >
                                       <label class="control-label" style="width:70px;" for="inputEmail">Section: <font color="red">*</font></label>
                                       <div class="controls">
                                             <select id="tier1_section" name="tier1_section" class="input-large tier1_section" style="width:100px;">	
                                                    </select>
                                       </div>
                               </div>
                                    <div class="control-group span3 form-horizontal_new occassion_div" >
                                       <label class="control-label" style="width:100px;" for="inputEmail"> <?php echo $this->lang->line('entity_cie'); ?> Occasion: <font color="red">*</font></label>
                                       <div class="controls">
                                                    <select id="occasion" name="occasion" class="input-large" style="width:130px;">	
                                                                    <option value= "-1">Select Occasion</option>
                                                            </select>
                                       </div>
                               </div>
                                    <div class="control-group span3 form-horizontal_new" id="student_drop_down_div">
											   <label class="control-label" style="width:80px;" for="inputEmail">Student: <font color="red">*</font></label>
											   <div class="controls">
												 <select id="student_list" name="student_list" class="input-large student_list" style="width:100px;">
													</select>
											   </div>
										</div>
                                                                           <br/><br/>
									</div>
									<div class="navbar" id="bloom_level_attainment_navbar"  >
											<div class="navbar-inner-custom bloom_navabar_header">																				  
											</div>
									</div>	
									
									<div class="row-fluid" id="display_bloom_data">	
										
										<div>
											<div id="diplay_bloom_data_chart" ></div>
												<div  style=""><table class="table table-bordered table-hover " id="example_bloom" style="font-size:12px;text-align:right;width:60%" >
													<thead>
														<tr>
															<th style="width:50px;">Sl.No</th><th  style="width:50px;">Level</th><th  style="width:50px;"> Attainment</th><th  style="width:50px;">Threshold</th>													
														</tr>
													</thead>
													<tbody>
														<tr>
															<td> </td><td>  No data to display.</td><td></td><td></td>
														</tr>
													</tbody>
												</table>
											</div><br/><hr>
										</div>
									</div>
									<div id="display_student_data"></div>
									
                                
								<div id="student_bloom_level_attainment_data">
							
								</div>
								</div>
                        </div>
								
						
                            </form>
                        </div>
			
                        <div id="clo_attainment_finalize" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Finalize Attainment Confirmation
                                </div>
                            </div>	
                            <div class="modal-body">
                                <p>Are you sure you want to Finalize the Overall Course Outcomes (COs) Attainment for Final Submit to calculate Program Outcomes (POs) Attainment for the respective Curriculum ?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="finalize_clo_attainment btn btn-primary " id="finalize_clo_attainment"><i class="icon-ok icon-white" data-dismiss="modal"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                            </div>
                        </div><!--Finalize Modal-->
                    
                        <div id="clo_attainment_final_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Attainment update Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>The Course Outcomes (COs) Attainment are finalized and updated successfully.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel cancel_button btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>
                    
                        <!--Modal to display the message "Curriculum not selected needs your attention"-->
                        <div id="error_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Make sure that you select all the drop-downs before proceeding. </p>
                            </div>
                            <input type="hidden" name="error_dialog" id="error_dialog" /> 
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                            </div>
                        </div>
                        <!--Modal to display the "Attainment occasion wise "-->
                        <div id="attainmnet_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        CO Attainment Occasion Wise
                                    </div>
                                </div>
                            </div>
                        <div class="modal-body">
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Curriculum: <font color="blue" id="crclm_name"></font></label>
                                <label class="span5">Term: <font color="blue" id="term_name"></font></label>
                            </div>
                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                <label class="span6">Course: <font color="blue" id="crs_name"></font></label>                                
                            </div>
                            <div id="clo_stmt_div">
                            <div id="clo_statement">
                                
                            </div>
                            </div>
                            <div id="cia_weight" class="span5">
                                <b> <?php echo $this->lang->line('entity_cie'); ?> Weightage: </b> <font id="cia"></font>
                            </div>
                           <!-- <div id="tee_weight" class="span5">
                                <b> TEE Weightage: </b> <font id="tee"></font>                                 
                            </div>
                            <hr>-->
                            <div id="display_attainment_div">
                                
                            </div>
                        </div>
                        <input type="hidden" name="error_dialog" id="error_dialog" /> 
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                        </div>
                    </div>
                        <!--Modal to display the "Attainment occasion wise "-->
                        <div id="clo_cia_tee_attainment" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_cie'); ?>  & <?php echo $this->lang->line('entity_see'); ?> CO Attainment.
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                    <label class="span5">Curriculum: <font color="blue" id="clo_crclm_name"></font></label>
                                    <label class="span3">Term: <font color="blue" id="clo_term_name"></font></label>
                                    <label class="span4">Course: <font color="blue" id="clo_crs_name"></font></label>
                                </div>   
                                                            <div id="meta_data" class="span12 controls" style="margin: 0px;">
                                    <label class="span6"> <?php echo $this->lang->line('entity_cie'); ?> Attainment :<font id="total_cia_weightage"></font></label>
                                    <label class="span6"> <?php echo $this->lang->line('entity_see'); ?> Attainment :<font id="total_tee_weightage"></font></label>
                                </div>

                                <div id="cia_tee_clo_statement">

                                </div>

                                <div id="cia_tee_clo_statement_table_display">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                            </div>
                        </div>
                        <!--Modal to Finalize Attainment confirmation-->
                        <div id="finalize_direct_indirect_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             style="display: none;" data-controls-modal="finalize_direct_indirect_success" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Finalize Attainment Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>The Overall Course Outcomes (COs) Attainment are finalized and updated successfully (includes Direct & Indirect Attainment).</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                            </div>
                        </div>
                        <!--Modal to Finalize Attainment confirmation-->
                        <div id="finalize_direct_indirect_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                             style="display: none;" data-controls-modal="finalize_direct_indirect_confirmation" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Finalize Attainment Confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to Finalize the Overall Course Outcomes (COs) Attainment (includes Direct & Indirect Attainment) for Final Submit to calculate <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Attainment ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="finalize_direct_indirect_confrim" class="finalize_direct_indirect_confrim btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                            </div>
                        </div>		
                        <!--Modal to Select all dropdowns before proceeding further-->
                        <div id="select_all_dropdowns" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
                         style="display: none;" data-controls-modal="finalize_direct_indirect_confirmation" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Please select all drop-downs.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                        </div>
                    </div>	
                </section>
            </div>
        </div>
    </div>
    <!--</div>-->
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script>
        var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
        var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
		var entity_tee = "<?php echo $this->lang->line('entity_tee'); ?>";
		var entity_mte  = "<?php echo $this->lang->line('entity_mte '); ?>";
        var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
        var course_outcome = "<?php echo $this->lang->line('entity_clo_full'); ?> <?php echo '('.$this->lang->line('entity_clo').')'; ?>";
    </script>
   <!-- <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/course_attainment.js'); ?>"></script>-->
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_i/tier1_course_clo_attainment.js'); ?>"></script>

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
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js');  ?>"></script>
    <script type="text/javascript" src="<?php //echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js');  ?>"></script>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
