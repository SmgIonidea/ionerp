<?php
/**
 * Description	:	Import Assessment Marks List View
 * Created		:	09-10-2014. 
 * Author 		:   Abhinay B.Angadi
 * Modification History:
 * Date				Modified By				Description
  13-11-2014		  Arihant Prasad		Permission setting, indentations, comments & Code cleaning
  22-01-2015			Jyoti				Modified to View QP of CIA
  28-08-2015		  Arihant Prasad		Provision for entering studentwise marks for individual type
  -------------------------------------------------------------------------------------------------
 */
?>


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
                <div class="row-fluid">
                    <div class="bs-docs-example">
                        <!--content goes here-->	
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Manage <?php echo $this->lang->line('entity_cie_full'); ?> (<?php echo $this->lang->line('entity_cie'); ?>) Occasions Marks
                            </div>
                        </div>
                        <form class="form-horizontal" method="POST" id="ciaadd_form_id" action="<?php echo base_url('assessment_attainment/import_cia_data/update_cia_marks'); ?>" name="ciaadd_form_id">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="cursor_default">
                                                Department: <font color="blue"><?php echo $department; ?></font>
                                                <?php echo str_repeat('&nbsp;', 13); ?>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="cursor_default">
                                                Program: <font color="blue"><?php echo $program; ?></font>
                                                <?php echo str_repeat('&nbsp;', 13); ?>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="cursor_default">
                                                Curriculum: <font color="blue"><?php echo $curriculum; ?></font>
                                                <?php echo str_repeat('&nbsp;', 13); ?>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="cursor_default">
                                                Term: <font color="blue"><?php echo $term; ?></font>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <label class="cursor_default">
                                Course: <font color="blue"><?php echo $course . " (" . $crs_code . ")"; ?></font>
                                <?php echo str_repeat('&nbsp;', 13); ?>
                                Section: <font color="blue"><?php echo $section_name; ?></font>
                                <?php echo str_repeat('&nbsp;', 13); ?>
                                No. of Occasions : <b><font color="blue"><?php echo count($cia_data); ?></b></font>
                            </label>
                            <div><br/>
							 <div class='navbar' >
								<div class='navbar-inner-custom'>
									<?php echo $this->lang->line('entity_cie_full') ."Occasions List"; ?>
								</div>
							</div>
                                <?php $cloneCntr = 0; ?>
                                <table id="generate" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sl.No.</th>
                                            <th>Assessment Occasion Description</th>
                                            <th>Assessment Occasion Method</th>
                                            <th>Assessment Type</th>
                                            <!--<th><?php //echo $this->lang->line('entity_cie');  ?> Weightage in %</th>-->
                                            <th><?php echo $this->lang->line('entity_cie'); ?> Max Marks</th>
                                            <th>Average / Import <?php echo $this->lang->line('entity_cie'); ?> Marks </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <input type="hidden" value="<?php echo $dept_id; ?>">
                                    <input type="hidden" value="<?php echo $prog_id; ?>">
                                    <input type="hidden" value="<?php echo $crclm_id; ?>">
                                    <input type="hidden" value="<?php echo $term_id; ?>">
                                    <input type="hidden" value="<?php echo $crs_id; ?>">
                                    <!--ref_id - to identify CIA or TEE-->
                                    <input type="hidden" value="<?php echo $ref_id = 'ref_12'; ?>">

                                    <tr>
									<?php  if(!empty($cia_data)) { ?>
                                        <?php for ($i = 0; $i < count($cia_data); $i++) { ?>
                                            <td>
                                                <?php echo $i+1 ; //echo $cia_data[$i]['ao_name'];  ?>
                                            </td>
                                            <td>
                                                <?php echo $cia_data[$i]['ao_description']; ?>
                                            </td>
                                            <td>
                                                <?php echo $cia_data[$i]['ao_method_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $cia_data[$i]['mt_details_name']; ?>
                                            </td>
                                            <!--<td> <p class="pull-right">
                                            <?php //echo $cia_data[$i]['weightage']. " %";?>
                                            </td>-->
                                            <td> <p class="pull-right">
                                                    <?php echo $cia_data[$i]['max_marks']; ?>
                                            </td>											
                                            <?php if (!empty($cia_data[$i]['qpd_id'])) { ?>
                                                <!-- CIA -->
                                                <td> <p class="pull-right">
                                                        <!--download template-->
                                                        <input type="hidden" class="span2" id="ao_id_<?php echo $cia_data[$i]['ao_id']; ?>" name="ao_id_<?php echo $i + 1; ?>" value="<?php echo $cia_data[$i]['ao_id']; ?>"/>
                                                        <a type="button" data-toggle="modal" data-crclm_id="<?php echo $crclm_id; ?>" data-term_id="<?php echo $term_id; ?>" data-section_id ="<?php echo $section_id; ?>" id="qp_download_template" class="qp_download_template cursor_pointer" title="Download Template" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/to_excel') . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'].'/'.$section_id; ?>"> Download </a> |

                                                        <!--import-->
                                                        <a type="button" data-toggle="modal" class="cursor_pointer import_data_details" data-crs_id="<?php echo $crs_id; ?>" data-term_id="<?php echo $term_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" title="Import Student Marks" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/temp_import_template') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'] . '/' . $ref_id; ?>"> Import </a> |

                                                        <!--view-->
                                                        <a type="button" data-toggle="modal" title="View Student Marks" href="<?php echo base_url('assessment_attainment/import_assessment_data/fetch_student_marks') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'] . '/' . $ref_id; ?>"> View </a>
                                                </td>
                                            <?php } else if ($cia_data[$i]['ao_type_id'] == 2) { ?>
                                                <!-- individual -->
                                                <td> <p class="pull-right">
                                                        <!--download template-->
                                                        <input type="hidden" class="span2" id="ao_id_<?php echo $cia_data[$i]['ao_id']; ?>" name="ao_id_<?php echo $i + 1; ?>" value="<?php echo $cia_data[$i]['ao_id']; ?>"/>
                                                        <a type="button" data-toggle="modal" data-crclm_id="<?php echo $crclm_id; ?>" data-term_id="<?php echo $term_id; ?>" data-section_id ="<?php echo $section_id; ?>" id="qp_download_template" class="qp_download_template cursor_pointer" title="Download Template" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/to_excel') . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'].'/'.$section_id;  ?>"> Download </a> |

                                                        <!--import-->
                                                        <a type="button" data-toggle="modal"  class="cursor_pointer import_data_details" data-crs_id="<?php echo $crs_id; ?>" data-term_id="<?php echo $term_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" title="Import Student Marks" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/temp_import_template') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'] . '/' . $ref_id; ?>"> Import </a> |

                                                        <!--view-->
                                                        <a type="button" data-toggle="modal" title="View Student Marks" href="<?php echo base_url('assessment_attainment/import_assessment_data/fetch_student_marks') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $cia_data[$i]['qpd_id'] . '/' . $cia_data[$i]['ao_id'] . '/' . $ref_id; ?>"> View </a>
                                                </td>
                                            <?php } else { ?>
                                                <td> <p class="pull-right">
                                                        QP is not defined
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
								<?php } else { ?>
								  <td colspan="6"> <p class="pull-left">No Data to Display. </td>
								
								<?php } ?>
                                    </tbody>
                                </table>
								 <input type="hidden" id="counter" name="counter" value="<?php echo $cloneCntr; ?>"/>
								<br/><HR><br/>
								<div class='navbar' >
									<div class='navbar-inner-custom'>
										<?php echo $this->lang->line('entity_mte_full') ."Occasions List"; ?>
									</div>
								</div>
								<table id="generate_mte" class="table table-bordered" style="width:100%">
								  <thead>
                                        <tr>
                                            <th>Sl.No.</th>
                                            <th>Assessment Occasion Description</th>
                                            <th>Assessment Occasion Method</th>
                                            <th>Assessment Type</th>
                                            <!--<th><?php //echo $this->lang->line('entity_cie');  ?> Weightage in %</th>-->
                                            <th><?php echo $this->lang->line('entity_mte'); ?> Max Marks</th>
                                            <th>Average / Import <?php echo $this->lang->line('entity_mte'); ?> Marks </th>
                                        </tr>
                                    </thead>
									<tbody>
                                    <tr>
									<?php  if(!empty($mte_data)) { ?>									
                                        <?php for ($i = 0; $i < count($mte_data); $i++) { ?>
                                            <td>
                                                <?php echo $i+1 ; //echo $mte_data[$i]['ao_name'];  ?>
                                            </td>
                                            <td>
                                                <?php echo $mte_data[$i]['ao_description']; ?>
                                            </td>
                                            <td>
                                                <?php echo $mte_data[$i]['ao_method_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $mte_data[$i]['mt_details_name']; ?>
                                            </td>
                                            <!--<td> <p class="pull-right">
                                            <?php //echo $mte_data[$i]['weightage']. " %";?>
                                            </td>-->
                                            <td> <p class="pull-right">
                                                    <?php echo $mte_data[$i]['max_marks']; ?>
                                            </td>

                                            <?php if (!empty($mte_data[$i]['qpd_id'])) { ?>
                                                <!-- CIA -->
                                                <td> <p class="pull-right">
                                                        <!--download template-->
                                                        <input type="hidden" class="span2" id="ao_id_<?php echo $mte_data[$i]['ao_id']; ?>" name="ao_id_<?php echo $i + 1; ?>" value="<?php echo $mte_data[$i]['ao_id']; ?>"/>
                                                        <a type="button" data-toggle="modal" data-crclm_id="<?php echo $crclm_id; ?>" data-term_id="<?php echo $term_id; ?>" data-section_id ="<?php echo $section_id; ?>" id="qp_download_template" class="qp_download_template cursor_pointer" title="Download Template" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/to_excel') . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'].'/'.$section_id; ?>"> Download </a> |

                                                        <!--import-->
                                                        <a type="button" data-toggle="modal" class="cursor_pointer import_data_details" data-crs_id="<?php echo $crs_id; ?>" data-term_id="<?php echo $term_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" title="Import Student Marks" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/temp_import_template') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'] . '/' . $ref_id; ?>"> Import </a> |

                                                        <!--view-->
                                                        <a type="button" data-toggle="modal" title="View Student Marks" href="<?php echo base_url('assessment_attainment/import_assessment_data/fetch_student_marks') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'] . '/' . $ref_id; ?>"> View </a>
                                                </td>
                                            <?php } else if ($mte_data[$i]['ao_type_id'] == 2) { ?>
                                                <!-- individual -->
                                                <td> <p class="pull-right">
                                                        <!--download template-->
                                                        <input type="hidden" class="span2" id="ao_id_<?php echo $mte_data[$i]['ao_id']; ?>" name="ao_id_<?php echo $i + 1; ?>" value="<?php echo $mte_data[$i]['ao_id']; ?>"/>
                                                        <a type="button" data-toggle="modal" data-crclm_id="<?php echo $crclm_id; ?>" data-term_id="<?php echo $term_id; ?>" data-section_id ="<?php echo $section_id; ?>" id="qp_download_template" class="qp_download_template cursor_pointer" title="Download Template" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/to_excel') . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'].'/'.$section_id;  ?>"> Download </a> |

                                                        <!--import-->
                                                        <a type="button" data-toggle="modal"  class="cursor_pointer import_data_details" data-crs_id="<?php echo $crs_id; ?>" data-term_id="<?php echo $term_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" title="Import Student Marks" abbr_href="<?php echo base_url('assessment_attainment/import_assessment_data/temp_import_template') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'] . '/' . $ref_id; ?>"> Import </a> |

                                                        <!--view-->
                                                        <a type="button" data-toggle="modal" title="View Student Marks" href="<?php echo base_url('assessment_attainment/import_assessment_data/fetch_student_marks') . '/' . $dept_id . '/' . $prog_id . '/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $mte_data[$i]['qpd_id'] . '/' . $mte_data[$i]['ao_id'] . '/' . $ref_id; ?>"> View </a>
                                                </td>
                                            <?php } else { ?>
                                                <td> <p class="pull-right">
                                                        QP is not defined
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
									<?php } else { ?>
								  <td colspan="6"> <p class="pull-left">No Data to Display. </td>
								
								<?php } ?>
                                    </tbody>
								</table>
                                <br><br>
                                
                                <div class="pull-right">
                                    </form>
                                    <a href= "<?php echo base_url('assessment_attainment/import_cia_data'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Close</b></a>
                                </div><br><br>
                            </div><br>
                            </div><br>
                            </div>
                            <div id="myModa_cia_error" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModa_cia_error" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>There are a mismatch in Average <?php echo $this->lang->line('entity_cie'); ?> entry(s), Please enter the valid marks. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                
                            <div id="target_or_threshold_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModa_cia_error" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Thresholds/Targets OR Attainment Levels are not defined for this course. Kindly define before importing student assessment data.</p>
                                    <p id="link_stmt">Click here to define <a id="define_threshold_target" class="cursor_pointer" > Thresholds/Targets OR Attainment Levels</a> </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                            
                            <div id="students_not_uploaded_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModa_cia_error" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Student list is not uploaded/imported for this Curriculum. Kindly request the concerned Chairman(HOD)/Program Owner to upload the Student list.</p>
                                    <p>Click here to <a id="students_upload_link" class="cursor_pointer" > Upload Students.</a> </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                    </div>
                </div>
                <!--Do not place contents below this line-->
            </section>	
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>

<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/import_mte_data.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/import_assessment_data.js'); ?>" type="text/javascript"></script>
