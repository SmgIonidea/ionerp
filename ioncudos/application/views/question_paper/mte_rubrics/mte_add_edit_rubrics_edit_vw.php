<?php
/**
*Description		: To display Criteria Range.
*Date				:	March 07 2017
*Author Name		:Bhagyalaxmi S S
*Modification History	:

*Date			Modified By 		Description
 *
  ------------------------------------------------------------------------------------------------- */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example" style="overflow:auto;">
                    <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
                    </div>
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo 'Rubrics List'; ?>
                        </div>
                    </div>	
                    <div id="meta_data_div" class="meta_data_div span12">
                        <div id="meta_data_div1" class="meta_data_div1 control-group">
                            <div class ="span4 controls">
                                <p><b>Curriculum : <font color="blue"><?php echo $meta_data['crclm_name']; ?></font></b></p>
                            </div>
                            <div class ="span4 controls">
                                <p><b>Term : <font color="blue"><?php echo $meta_data['term_name']; ?></font></b></p>
                            </div>
                            <div class ="span4 controls">
                                <p><b>Course : <font color="blue"><?php echo $meta_data['crs_title']; ?></font></b></p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="rubrics_table">
                        <?php echo $table; ?>
                    </div>
                    <br>
                    <div class="span12">
                    <div class="span6">
                    <div id='rubrics_note' class="alert alert-danger" role="alert" style="padding:3px; font-size: 11px;">
                        <span class="sr-only" style="color:#000;"><b>Note:</b></span>
                        Click "Finalize Rubrics" button after entering all rubrics data!
                    </div>                                    
                    </div>
                   <?php if($criteria_count != 0){ ?> 
                   <div class="span6">
                    <div id="gernerate_qp_button_div" class="pull-right">
                        <a id="export_to_pdf" target="_blank" href="#" class="btn btn-success" type="button" name="export_to_pdf"><i class="icon-book icon-white"></i> Export .pdf </a>
                        <?php  if($qp_roll_out == 0 ){ ?>
                           
                        <button id="generate_qp" class="btn btn-success" type="button" name="generate_qp"> Finalize Rubrics </button>
                        <?php }else{  ?>
                        <button id="generate_qp" class="btn btn-success" type="button" name="generate_qp" disabled> Rubrics Finalized </button> 
                        <?php } ?>
                    </div>
                    </div>
                  <?php  } ?>
                    </div>
                    <form name="rubrics_report" id="rubrics_report" method="POST" action="<?php echo base_url('assessment_attainment/cia_rubrics/export_report/') ?>" >
                    <input type="hidden" name="report_in_pdf" id="report_in_pdf" value="" />
                    </form>
                    <br><br>
                     <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $title; ?>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
                    <input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id; ?>" />
                    <input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id; ?>" />
                    <input type="hidden" name="section_id" id="section_id" value="<?php // echo $section_id; ?>" />
                    <input type="hidden" name="ao_id" id="ao_id" value="<?php //echo $ao_id; ?>" />
                    <input type="hidden" name="ao_type_id" id="ao_type_id" value="<?php // echo $ao_type_id; ?>" />
                    <input type="hidden" name="ao_method_id" id="ao_method_id" value="<?php echo $ao_method_id; ?>" />
                    <?php
                        if($qpd_id != null){
                            $qp_id = $qpd_id;
                        }else{
                            $qp_id = '0';
                        }
                    ?>
                    <input type="hidden" name="qpd_id" id="qpd_id" value="<?php echo $qp_id; ?>" />
                    
                    <div id="generate_scale_assessmen " class="pull-right">
                    <?php if($range_size == 0){ ?> 
                        <p>Enter No. of Columns (Scale of Assessment) for Rubrics <font color="red">*</font> 
                            <input type="text" name="count_of_range" id="count_of_range" class="input-mini" maxlength="1" style="margin-bottom: 0px;"/>
                            <button type="button" name="generate_scale" id="generate_scale" class="btn btn-primary">Generate Rubrics table</button>
                        </p>
                      
                   <?php }else{ ?> 
                        <p>
                            <button type="button" name="regenerate_scale" id="regenerate_scale" class="btn btn-primary">Re-Generate Scale </button>
                        </p>
                       
                    <?php } ?>
                        

                    </div>
                    <br>
                    <div id="rubrics_type" class="span12">
                        <div id="custom_rubrics_div" class="span3">
                            <p><input type="radio" name="rubrics_type" id="custom_rubrics" class ="rubrics_type_val" rub_typ="custom" entity_id ="0" style="margin-bottom: 6px;" checked="checked"/> Custom Criteria</p>

                        </div>
                         <div id="custom_rubrics_div" class="span3">
                            <p><input type="radio" name="rubrics_type" id="co_rubrics" rub_typ="co" class ="rubrics_type_val" entity_id ="11" style="margin-bottom: 6px;" /> CO as Criteria</p>
                        </div>
                        <?php if($meta_data['oe_pi_flag'] == 1){ ?>
                        <div id="custom_rubrics_div" class="span3">
                            <p><input type="radio" name="rubrics_type" id="oe_rubrics" rub_typ="oe" class ="rubrics_type_val" entity_id ="21"  style="margin-bottom: 6px;" /> OE as Criteria</p>
                        </div>  
                        <?php } ?>
                         <?php if($meta_data['oe_pi_flag'] == 1){ ?>
                        <div id="custom_rubrics_div" class="span3">
                            <p><input type="radio" name="rubrics_type" id="pi_rubrics" rub_typ="pi" class ="rubrics_type_val" entity_id ="22" style="margin-bottom: 6px;" /> PI as Criteria</p>
                        </div>  
                        <?php } ?>
                    </div>
                    
                    <br>
                    <br>
                    <br>
                    <br>
                    <div id="assessment_scale">
                        <?php 
                        if($range_size != 0){
                            echo $rubrics_table;
                        }else{
                            
                        }
                           
                        ?>
                    </div>
                    <br>
                    
                    <input type="hidden" id="edit_criteria_id" name="edit_criteria_id" class="input-mini" value="" />
                    <div id="edit_button_div" class="pull-right">
                        
                        <?php if($qp_roll_out == 0 ){ ?>
                        <button type="button" id="edit_save_rubrics" name="edit_save_rubrics" class="btn btn-primary" ><i class="icon-file icon-white"></i> Save </button>
                        <?php }else { ?>
                        <button type="button" id="force_edit_save_rubrics" name="force_edit_save_rubrics" class="btn btn-primary" ><i class="icon-file icon-white"></i> Save </button>
                        <?php } ?>
                        <button class="btn btn-primary" id="updata_criteria" data-dismiss="modal" aria-hidden="true" style="display:none; "><i class="icon-file icon-white"></i> Update  </button> 
                        <a class="btn btn-danger" id="cancel_criteria" href="<?php echo base_url('question_paper/manage_mte_qp'); ?>"><i class="icon-remove icon-white"></i> Close  </a> 
                        
                    </div>
                        
                    </div>
                    <!--Do not place contents below this line-->	
                </div>
            </section>
        </div>
    </div>
    <div id="regenerate_scale_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to Redefine the Rubrics definition ? </p>
            <p>All the Criteria and Scale of Assessment defined earlier will be deleted and you need to define all a fresh.</p>
            <br>

            <p>Press Ok to continue.</p>
                
        </div>
        <div id ="modal_footer" class="modal-footer">
            <button class="btn btn-primary" id="regenerate_rubrics_scale" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    <!-- Delete Criteria warning Modal -->
    <div id="delete_criteria_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            <input type="hidden" name="criteria_id" id="criteria_id" value="" class="input-mini" />
            <p>Are you sure you want to delete this Criteria? </p>
            
            <br>

            <p>Press Ok to continue.</p>
                
        </div>
        <div id ="modal_footer" class="modal-footer">
            <button class="btn btn-primary" id="delete_criteria_data" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    <!-- Edit Criteria Modal -->
     <div id="edit_criteria_modal" class="modal hide fade modal-admin in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Edit Rubrics Criteria 
                </div>
            </div>
        </div>
        <div class="modal-body">
            
            <div id="edit_rubrics_criteria_data_div">
                
            </div>
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Edit Criteria Modal -->
     <div id="delete_question_paper_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Rubrics is already finalized for <?php echo $this->lang->line('entity_mte'); ?> assessment import and you might have uploaded the assessment data.</p>            
            <p>If you want to define one more criteria under this rubrics then all the assessment data will be erased and you need to refinalize the rubrics. </p>           
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-primary" id="delete_created_qp" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Edit Criteria Modal -->
     <div id="force_edit_criteria_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Rubrics is already finalized for <?php echo $this->lang->line('entity_mte'); ?> assessment import and you might have uploaded the assessment data.</p>            
            <p>If you want to define one more criteria under this rubrics then all the assessment data will be erased and you need to refinalize the rubrics. </p>            
                     
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-primary" id="force_editing_critiria" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Delete Criteria Modal -->
     <div id="force_delete_criteria_warning_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Rubrics is already finalized for <?php echo $this->lang->line('entity_mte'); ?> assessment import and you might have uploaded the assessment data.</p>            
            <p>If you want to delete criteria under this rubrics then all the assessment data will be erased and you need to refinalize the rubrics. </p>           
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-primary" id="force_delete_critiria" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Finalize Rubrics Confirmation Modal message -->
     <div id="finalize_modal_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to finalize the defined rubrics?</p>            
            <p>Finalized rubrics will be available under <?php echo $this->lang->line('entity_mte'); ?>  assessment import.</p>  
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-primary" id="finalize_rubrics_data"><i class="icon-ok icon-white"></i> Ok </button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Finalize Rubrics Confirmation Modal message -->
     <div id="overwrite_rubrics_finalize_modal_confirmation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p>Already a Rubrics is Finalized for this course. Are you sure you want Refinalize the rubrics? </p>  
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            <button class="btn btn-primary" id="overwrite_rubrics_data"><i class="icon-ok icon-white"></i> Ok </button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
        </div>
    </div>
    
    <!-- Cannot Finalize Rubrics  -->
     <div id="cant_finalize_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p> You cannot Finalize this TEE Rubrics, as another TEE QP/Rubrics is Finalized or Rolled-Out and its  Assessment data is also imported (uploaded) against its TEE QP/Rubrics. </p>  
        </div>
        <div id ="modal_footer" class="modal-footer">
            
            
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
        </div>
    </div>
  
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/mte_rubrics/mte_rubrics.js'); ?>"></script>
 
<!-- End of file ao_method_add_vw.php Location: .configuration/standards/course_type/ao_method_add_vw.php -->
