<?php
/**
 * Description	:	Add Edit Rubrics Criteria for the course.
 * 					
 * Created		:	20-10-2016
 *
 * Author		:	Mritunjay B S
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
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
                 <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
                    </div>
                <div class="bs-docs-example" style="overflow:auto;">
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

                        <div id="meta_data_div2" class="meta_data_div2 control-group">
                            <div class ="span4 controls">
                                <p><b>Section : <font color="blue"><?php echo $meta_data['mt_details_name']; ?></font></b></p>
                            </div>
                            <div class ="span4 controls">
                                <p><b>Assessment Occasion : <font color="blue"><?php echo $ao_description; ?></font></b></p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="rubrics_table">
                        <?php echo $table; ?>
                    </div>
                    <br>
                     <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $title; ?>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
                    <input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id; ?>" />
                    <input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id; ?>" />
                    <input type="hidden" name="section_id" id="section_id" value="<?php echo $section_id; ?>" />
                    <input type="hidden" name="ao_id" id="ao_id" value="<?php echo $ao_id; ?>" />
                    <input type="hidden" name="ao_type_id" id="ao_type_id" value="<?php echo $ao_type_id; ?>" />
                    <input type="hidden" name="ao_method_id" id="ao_method_id" value="<?php echo $ao_method_id; ?>" />
                    <div class="div_border">
                    <div id="generate_scale_assessment" class="pull-right">
                        <p>Enter No. of Columns (Scale of Assessment) for Rubrics <font color="red">*</font>
                            <input type="text" name="count_of_range" id="count_of_range" class="input-mini isNumber" maxlength="1" style="margin-bottom: 0px;"/>
                            <span id="err_msg" class="font_color"></span>
                            <button type="button" name="generate_scale" id="generate_scale" class="btn btn-primary">Generate Rubrics</button>
                        </p>

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
                    <form id="save_rubrics_data" name="save_rubrics_data" action="" method="post">
                    <div id="assessment_scale">
                    </div>
                    </form>
                    
                    <div id="button_div" class="pull-right">
                        <button type="button" id="save_rubrics" name="save_rubrics" class="btn btn-primary" disabled="disabled"><i class="icon-file icon-white"></i> Save </button>
                        <a class="btn btn-danger" id="cancel_criteria" href="<?php echo base_url('question_paper/manage_cia_qp'); ?>"><i class="icon-remove icon-white"></i> Close  </a> 
                    </div>
                    <br><br>
                    </div>
                        
                    </div>
                    <!--Do not place contents below this line-->	
                </div>
            </section>
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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/cia_rubrics.js'); ?>"></script>
 
<!-- End of file ao_method_add_vw.php 
                        Location: .configuration/standards/course_type/ao_method_add_vw.php -->
