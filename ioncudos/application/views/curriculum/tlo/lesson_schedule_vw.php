<?php
/*
 * Description		: TLO Lesson Schedule view page, provides the facility to Add the Lesson Schedule, Review and Assignment Contents.
 * Modification History :
 *   Date 			  Modified By					 Description
 * 05-05-2014          		Jevi V G         	Added file headers, function headers & comments. 
 * 11-12-2015			Shayista Mulla		Added column's to Add Review/Assignment Question.
 * 05-01-2016			Shayista Mulla		Added loading image.
 * 04-04-2016			Arihant Prasad		Code cleanup. Addition of new column - conduction date
 * 20-10-2016			Neha Kulkarni		Addition of new column - actual delivery date
  ----------------------------------------------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <!-- /TinyMCE -->
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
    <?php $this->load->view('includes/head'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />

    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents-->
                    <section id="contents">
                        <div class="bs-docs-example" >
                            <title><?php echo $title; ?></title>	 
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Chapter wise Plan
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="pgm_title" style="padding-top:0px;">Curriculum :
                                                    <b><?php echo $tlo_data_one[0]['crclm_name']; ?></b></label>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="term" style="padding-top:0px;">Term &nbsp;&nbsp;:
                                                    <b><?php echo $tlo_data_two[0]['term_name']; ?></b></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="course" style="padding-top:0px;">Course <?php echo str_repeat("&nbsp;", 6); ?>:
                                                    <b> <?php echo $tlo_data_three[0]['crs_title']; ?> (<?php echo $tlo_data_three[0]['crs_code']; ?>)</b></label>
                                            </div>
                                        </div>

                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label" for="topic" style="padding-top:0px;"><?php echo $this->lang->line('entity_topic'); ?> :
                                                    <b><?php echo $tlo_data_four[0]['topic_title']; ?></b></label>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="padding:5px;">Lesson Contents</th>
                                                <th style="padding:5px; white-space:nowrap;"><?php echo $this->lang->line('entity_topic'); ?> Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding:5px;">
                                                    <?php echo $tlo_data_four[0]['topic_content']; ?>
                                                </td>
                                                <td style="padding:5px;">
                                                    <?php echo $tlo_data_four[0]['topic_hrs']; ?>
                                                </td>
                                        <input type = "hidden" name="topic_hrs" id="topic_hrs" value ="<?php echo $tlo_data_four[0]['topic_hrs']; ?>"/>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Lesson Schedule
                                </div>
                            </div>
                            <div class="pull-right">
                                <button id="view_all" class="btn btn-primary view_schedule" type="button"><i class="icon-plus icon-white"></i> View Lesson Schedule</button>
                            </div>
                            <br><br>
                            <!-- Display Lesson schedule-->				 
                            <div id="display_portion_wrapper" class="dataTables_wrapper container-fluid" role="grid" style="display:none">
                                <table class="table table-bordered table-hover" id="display_portion" aria-describedby="example_info" >
                                    <thead>
                                        <tr role="row">
                                            <th class="header" style="width: 80px" role="columnheader" tabindex="0" aria-controls="display_portion"> Lecture No. </th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="display_portion"> Portion to be covered per hour </th>
                                            <th class="header" style="width: 135px" role="columnheader" tabindex="0" aria-controls="display_portion"> Planned Delivery Date </th>
                                            <th class="header" style="width: 130px" role="columnheader" tabindex="0" aria-controls="display_portion"> Actual Delivery Date </th>
                                            <th class="header" style="width: 40px" role="columnheader" tabindex="0" aria-controls="display_portion"> Edit </th>
                                            <th class="header" style="width: 50px" role="columnheader" tabindex="0" aria-controls="display_portion"> Delete </th>
                                        </tr>
                                    </thead>
                                </table>	
                            </div>
                            <!-- End of dispaly Lesson schedule-->
                            <form class="form-horizontal" id="lesson_schedule_add_form" action="" name="lesson_schedule_add_form" method="POST" >
                                <input type = "hidden" name="lesson_curriculum_id" id="lesson_curriculum_id" value ="<?php echo $curriculum_id; ?>"/>
                                <input type = "hidden" name="lesson_term_id" id="lesson_term_id" value ="<?php echo $term_id; ?>"/>
                                <input type = "hidden" name="lesson_course_id" id="lesson_course_id" value ="<?php echo $course_id; ?>"/>
                                <input type = "hidden" name="lesson_topic_id" id="lesson_topic_id" value ="<?php echo $topic_id; ?>"/>

                                <table class="table table-bordered">
                                    <tr>
                                        <th class="span_textbox"> Lecture No. <font color="red">*</font></th>
                                        <th> Portion to be covered per hour <font color="red">*</font></th>
                                        <th class="span_textbox"> Planned Delivery date </th>
                                        <th class="span_textbox"> Actual Delivery date </th>
                                    </tr>
                                    <?php $i = 1; ?>

                                    <tr>
                                        <td>
                                            <input type="text" class="text_align_right input-mini" name="lesson_schedule_id" id="lesson_schedule_id" value="" />
                                        </td>
                                        <td>
                                            <input type="text" class="input-xlarge required" name="portion_per_hour" id="portion_per_hour" value="" style="width:98%;" />
                                        </td>
                                        <td>
                                            <div class="input-prepend">
                                                <span class="add-on datepicker" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                                <input type="text" class="input-medium datepicker" id="ls_date" name="ls_date" readonly />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-prepend">
                                                <span class="add-on datepicker" id="btn_actual_date" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                                <input type="text" class="input-medium datepicker" id="ls_actual_date" name="ls_actual_date" readonly />
                                            </div>
                                        </td>
                                    </tr>		
                                </table>

                                <div><font color="red"><?php echo validation_errors(); ?></font></div>
                                <br> 
                                <div class="pull-right">
                                    <button id="submit" class="btn btn-primary lesson_schedule" type="button"><i class="icon-file icon-white"></i> Save</button>
                                </div>
                                <br><br>
                            </form>

                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add Review / Assignment Question
                                    <a href="http://math.typeit.org/" target="_blank" class="pull-right" style="text-decoration:none; font-size:12px; color:white;"> On-line Mathematical Editor </a>
                                </div>
                            </div>

                            <div class="span12">
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label class="control-label" for="pgm_title" style="padding-top:0px;">Curriculum :
                                                <b><?php echo $tlo_data_one[0]['crclm_name']; ?></b></label>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="term" style="padding-top:0px;">Term :
                                                <b><?php echo $tlo_data_two[0]['term_name']; ?></b></label>
                                        </div>
                                    </div>
                                    <div class="span5">
                                        <div class="control-group">
                                            <label class="control-label" for="course" style="padding-top:0px;">Course :
                                                <b> <?php echo $tlo_data_three[0]['crs_title']; ?> (<?php echo $tlo_data_three[0]['crs_code']; ?>)</b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button id="view_all_ques" class="btn btn-primary all_questions"  type="button"><i class="icon-plus icon-white"></i> View Questions</button>
                            </div>
                            <br><br><br><br>
                            <!-- dispaly questions-->
                            <div id="example_wrapper" class="dataTables_wrapper container-fluid" role="grid" style="display:none">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info" style="fonsize:12px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="header" style = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example" >Sl No. </th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Type</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Question</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('entity_tlo_singular'); ?></th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Bloom's level</th>
                                            <th class="header" style = "width: 70px;" role="columnheader" tabindex="0" aria-controls="example" >PI codes</th>
                                            <th class="header" style = "width: 40px;" role="columnheader" tabindex="0" aria-controls="example" >Edit</th>
                                            <th class="header" style = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>
                                        </tr>
                                    </thead>
                                </table>	
                            </div>
                            <!-- --><form  class="form-horizontal" id="review_assignment_form"  action="" name="review_assignment_form"  method="POST" >
                                <!-- <div class="questions" style=" margin-left:100px;">

                                    <div class="span12 control-group">
                                        <div class="controls span4">
                                            <p class="radio inline">
                                                <input type="radio" name="type_question" id="type_question_review" value="1" checked="checked" /> Review Question
                                            </p>
                                        </div>
                                        <div class="controls span4">
                                            <p class="radio inline">
                                                <input type="radio" name="type_question" id="type_question_assignment" value="2" />Assignment / Exercise
                                            </p>
                                        </div>
                                    </div>
                                    <div class="question_selection"></div>
                                </div>-->
                                <br>
                                <div class="row-fluid " id="question_details">
                                    <div class="row-fluid">
                                        <div class="span4" >
                                            <div class="control-group">
                                                <label class="control-label" for="question_type" style="width:100px;">Question Type: </label>
                                                <div class="controls" style=" margin-left:90px;">
                                                    &nbsp;&nbsp;<select id="question_type" name="question_type" class="input-large select_bl" >
                                                        <option value="1">Review</option>
                                                        <option value="2">Assignment / Exercise</option>
                                                    </select>							 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4" >
                                            <div class="control-group">
                                                <label class="control-label" for="tlo_id_1" style="width:100px;"><?php echo $this->lang->line('entity_tlo'); ?>: </label>
                                                <div class="controls" style=" margin-left:90px;">
                                                    &nbsp;&nbsp;<select id="tlo_ids" name="tlo_ids" class="input-large select_bl" >
                                                        <option value="">Select <?php echo $this->lang->line('entity_tlo_singular'); ?></option>
                                                        <?php
                                                        $i = 0;
                                                        foreach ($tlo_data_five as $tlo) {
                                                            $i++
                                                            ?>
                                                            <option value="<?php echo $tlo['tlo_id']; ?>" title="<?php echo (trim($tlo['tlo_statement'])); ?>"> <?php echo $this->lang->line('entity_tlo_singular') . $i; ?></option>
                                                        <?php } ?>
                                                    </select>							 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label class="control-label" for="bloom_id" style="width:100px;">Bloom's Level:</label>
                                                <div class="controls" style=" margin-left:90px;">
                                                    &nbsp;&nbsp;<select id="bloom_id" name="bloom_id" class="input-large" onChange = "select_pi_code();">
                                                        <option value="">Select Bloom's Level</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($org_details[0]['oe_pi_flag'] == '1') { ?>
                                            <div class="span4" >
                                                <div class="control-group">
                                                    <label class="control-label" for="pi_id" style="width:100px;" >PI Codes: </label>
                                                    <div class="controls" style=" margin-left:90px;">
                                                        &nbsp;&nbsp;<select id="pi_id" name="pi_id" class="input-large " >
                                                            <option value="">Select PI Code</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>                                   
                                            <?php
                                        } else {
                                            
                                        }
                                        ?>
                                    </div>
                                    <div class="span12 add_review_question">
                                        <!-- Tiny MCE Code
                                        <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                                        <!-- Field to display the description of the organization -->
                                        <div class="control-group">
                                            <label class="control-label" for="review_question_1" style="width:0px;">Question:<font color="red"><b>*</b></font></label>
                                            <div class="controls" style=" margin-left:90px;">
                                                <?php echo form_textarea($review_question); ?> 
                                                <div class="question_num"></div>
                                            </div>
                                            <input type = "hidden" class="input-mini" name="question_id" id="question_id" value =""/>
                                        </div>

                                    </div>
                                    <div class="row-fluid">
                                        <div class="span8">
                                            <span style="font-size:8px;padding-left:20px;vertical-align:middle;"><i><b>Note: </b>PNG, JPG, or GIF (500K max file size)</i></span>
                                            <br>
                                            <div id="image_show_1" class="form-horizontal span12" style=" margin-left:90px;">			
                                            </div>
                                            <!--<div id="image_append_1" class="controls span12" style=" margin-left:90px;"></div>-->
                                            <div id="errormsg" class="clearfix redtext">
                                            </div>	              
                                            <div id="pic-progress-wrap" class="progress-wrap" style="margin-top:0px;margin-bottom:0px;">
                                            </div>	

                                            <div id="picbox" class="clear" style="padding-top:0px;padding-bottom:0px;">
                                            </div>
                                        </div>
                                        <div id="image_insert_1"></div>
                                    </div>
                                </div>	
                                <div class="pull-right">
                                    <button class="btn btn-primary update_question" style="display:none;"><i class="icon-file icon-white"></i> Update</button>
                                    <button class="btn btn-danger cancel_question" style="display:none;"><i class="icon-remove icon-white"></i> Cancel</button>
                                    <button id="submit" class="btn btn-primary add_details"  type="button"><i class="icon-file icon-white"></i> Save</button>
                                    <?php if ($theroy_or_practicle != "") { ?>
                                        <a type="button" class="btn btn-danger" href="<?php echo base_url('curriculum/topic'); ?>"><i class="icon-remove icon-white"></i>Close</a><?php } else { ?>
                                        <a type="button" class="btn btn-danger" href="<?php echo base_url('curriculum/lab_experiment'); ?>"><i class="icon-remove icon-white"></i>Close</a>
                                    <?php } ?>
                                </div>
                            </form>	
                            <!-- End of dispaly questions-->
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="pull-right">

                                    </div>
                                </div>
                            </div>	
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div> 
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!--Modal -->
        <div id="my_modal_edit_scheule" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="my_modal_edit_scheule" data-backdrop="static" data-keyboard="false">
            <div class="modal-header">
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Edit Lesson Schedule
                    </div>
                </div>
            </div>

            <form role="form" id="lesson_schedule_edit_form" method="POST" style="margin:0px;">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th> Lecture No. </th>
                            <td>
                                <input type="text" class="input-mini" name="lesson_schedule_id1" id="lesson_schedule_id1" value ="" >
                            </td>
                        </tr>
                        <tr>
                            <th> Portion to be covered per hour </th> 
                            <td>
                                <input type="hidden" class="input-mini" name="portion_id" id="portion_id" value ="" />
                                <input type="text" class="input-xlarge required" name="portion" id="portion" value="" style="width:420px;"/>
                            </td>
                        </tr>
                        <tr>
                            <th> Planned Delivery Date </th>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on datepicker" id="date_btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                    <input type="text" class="input-medium datepicker" id="modal_ls_date" name="modal_ls_date" readonly />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th> Actual Delivery Date </th>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on datepicker" id="date_btn_date" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                    <input type="text" class="input-medium datepicker" id="modal_ls_actual_date" name="modal_ls_actual_date" readonly />
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div><font color="red"><?php echo validation_errors(); ?></font></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary lesson_schedule_edit" ><i class="icon-file icon-white"></i> Update </button>
                    <button class="btn btn-danger lesson_schedule_cancel" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button>
                </div>
            </form>
        </div>		

      <!--  <div id="myModal6" class="modal hide fade	"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; width:1200px; margin-left: -600px;" data-controls-modal="full-width" data-backdrop="static" data-keyboard="false" >
            <div class="modal-header">
                <div class="navbar-inner-custom">
                    Edit Question
                    <a href="http://math.typeit.org/" target="_blank" class="pull-right" style="font-size:12px; color:white;"> On-line Mathematical Editor </a>
                </div>
            </div>
            <div class="span12">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="control-group">
                            <label class="control-label" for="pgm_title" style="padding-top:0px;">Curriculum :
                                <b><?php echo $tlo_data_one[0]['crclm_name']; ?></b></label>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <label class="control-label" for="term" style="padding-top:0px;">Term :
                                <b><?php echo $tlo_data_two[0]['term_name']; ?></b></label>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label" for="course" style="padding-top:0px;">Course :
                                <b> <?php echo $tlo_data_three[0]['crs_title']; ?> (<?php echo $tlo_data_three[0]['crs_code']; ?>)</b></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <form  class="form-horizontal" id="question_edit_form" role="form" method="POST" >	
                    <div class="questions control-group" >
                        <div class="span6" >
                            <div class="span2">
                                <label class="radio-inline">
                                    <input type="radio" name="type_question_modal" id="question_review" value="1"> Review Question
                                </label>
                            </div>
                            <div class="span2">
                                <label class="radio-inline">
                                    <input type="radio" name="type_question_modal" id="question_assignment" value="2"> Assignment / Exercise
                                </label>
                            </div>
                        </div>
                        <div class="question_selection_edit"></div>
                    </div>
                    <br>
                    <div class="row-fluid " id="question_details">
                        <div class="row-fluid">
                            <div class="span4" >
                                <div class="control-group">
                                    <label class="control-label" for="tlo_id_1" style="width:100px;"><?php echo $this->lang->line('entity_tlo'); ?>: <font color="red"><b>*</b></font></label>
                                    <div class="controls" style=" margin-left:90px;">
                                        &nbsp;&nbsp;<select id="tlo_ids_edit" name="tlo_ids_edit" class="input-large required select_bl_edit" >
                                            <option value="">Select <?php echo $this->lang->line('entity_tlo_singular'); ?></option>
                                            <?php
                                            $i = 0;
                                            foreach ($tlo_data_five as $tlo) {
                                                $i++
                                                ?> 
                                                <option title="<?php echo (trim($tlo['tlo_statement'])); ?>" value="<?php echo $tlo['tlo_id']; ?>"> <?php echo $this->lang->line('entity_tlo_singular') . $i; ?></option>
                                            <?php } ?>
                                        </select>							 
                                    </div>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="control-group">
                                    <label class="control-label" for="bloom_id_2" style="width:100px;">Bloom's Level: <font color="red"><b>*</b></font></label>
                                    <div class="controls" style=" margin-left:90px;">
                                        &nbsp;&nbsp;<select id="bloom_id_edit" name="bloom_id_edit" class="input-large required select_bl" onChange = "select_pi_code_edit();" value="">
                                            <option value="">Select Bloom's level</option>
                                        </select>												 
                                    </div>
                                </div>
                            </div>
                            <?php if ($org_details[0]['oe_pi_flag'] == '1') { ?>
                                <div class="span4" >
                                    <div class="control-group">
                                        <label class="control-label" for="pi_id_edit" style="width:100px;" >PI Code: </label>
                                        <div class="controls" style=" margin-left:90px;">
                                            &nbsp;&nbsp;<select id="pi_id_edit" name="pi_id_edit" class="input-large" >
                                                <option value="">Select Pi code</option>
                                            </select>										 
                                        </div>
                                    </div>
                                </div>                                 
                            <?php
                            } else {
                                
                            }
                            ?>
                        </div>
                        <?php
//                        $question_detail['class'] = 'question_update_detail required noSpecialChars input-xlarge';
//                        $question_detail['id'] = 'questionId';
//                        $question_detail['name'] = 'questionId';
                        ?> 
                        <div class="span12 edit_review_assignment_question">
                            <!-- Tiny MCE Code
                            <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                            <!-- Field to display the description of the organization
                            <div class="control-group">
                                <label class="control-label" for="edit_review_assignment_question" style="width:0px;">Question:<font color="red"><b>*</b></font></label>
                                <div class="controls" style=" margin-left:90px;">
                                    

<?php //echo form_textarea($question_detail); ?>

                                    <div class="question_num_edit"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary update_question" aria-hidden="true"><i class="icon-file icon-white"></i> Update</button>
                <button class="btn btn-danger edit_question" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>	
            </div>
        </div> -->

        <!-- -->
        <div id="myModaldelete_1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-header">
                <div class="navbar-inner-custom">
                    Delete Confirmation 
                </div>
            </div>
            <div class="modal-body">
                <input type = "hidden" name="portion_delete" id="portion_delete_id" value =""/>
                <p>Are you sure you want to Delete?</p>
            </div>
            <div class="modal-footer">
                <button class="delete_portion btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
            </div>

        </div>

        <!-- -->
        <div id="alert_question" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style=	"display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
            <div class="modal-header">
                <div class="navbar-inner-custom">
                    Warning 
                </div>
            </div>
            <div class="modal-body">
                Question already exists.
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
            </div>
        </div>

        <!-- -->
        <div id="alert_schedule" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style=	"display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"></br>
            <div class="container-fluid">
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Warning
                    </div>
                    Lesson Schedule already exists.
                </div>
            </div>	
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
            </div>
        </div>

        <!-- -->
        <div id="myModaldelete_2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-header">
                <div class="navbar-inner-custom">
                    Delete Confirmation 
                </div>
            </div>

            <div class="modal-body">
                <input type = "hidden" name="question_delete" id="question_delete_id" value =""/>
                <input type = "hidden" name="question_del_id" id="question_del_id" value =""/>
                <p>Are you sure you want to Delete?</p>
            </div>
            <div class="modal-footer">
                <button class="delete_question btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
            </div>
        </div>

        <!-- -->
        <div id="schedule_update_alert" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style=	"display: none;" data-controls-modal="myModal2" data-backdrop="static" data-keyboard="false">
            <div class="modal-header">
                <div class="navbar-inner-custom">
                    Update Failure
                </div>
            </div>	
            <div class="modal-body">
                Lesson Schedule not updated.
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
            </div>
        </div>	

        <!-- -->
        <div id="question_update_alert" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style=	"display: none;" data-controls-modal="myModal2" data-backdrop="static" data-keyboard="false"></br>
            <div class="container-fluid">
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Update Failure
                    </div>
                    Question not updated.
                </div>
            </div>	
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
            </div>
        </div>

        <!--Modal End-->
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
        <style media="all" type="text/css">
            td.alignRight { text-align: right; }
        </style> 

        <script>
            var tlo_entity_lang = "<?php echo $this->lang->line('entity_tlo'); ?>";
        </script>
    </body>
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.js'); ?> "></script>
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "></script>
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/lesson_schedule.js'); ?> "></script>   
</html>
