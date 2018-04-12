<?php
/**
 * Description	:	Add View for Course Module.
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 02-12-2015		Bhagyalaxmi S S			Added total weightage of cia add tee
 * 10-12-2015		Bhagyalaxmi S S			Added radio button for selection of theory, theory with lab and lab
 * 04-01-2016		Shayista Mulla 			Added loading image and cookies.
 */
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php if (isset($title)) echo $title . ' | '; ?> IonCUDOS </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/docs.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.jqplot.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/yearpicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/font_override.css'); ?>" media="screen" />

        <!-- Le fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/ico/favicon.png">

    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); addCourseToggleStatus();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
        <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add Course
                                    <p class="pull-right" style="font-size:12px; color:white;">Note: You can refer Term-wise Course details below before adding a New Course.</p>
                                </div>
                            </div>	
                            <form class="form-horizontal" method="POST" id="add_form_id"  name="add_form_id" action="<?php echo base_url('curriculum/course/insert_course_details'); ?>" >
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_id">Curriculum: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        if (!empty($curriculumlist)) {
                                                            $select_options1[''] = 'Select Curriculum';
                                                            foreach ($curriculumlist as $listitem1) {
                                                                $select_options1[$listitem1['crclm_id']] = $listitem1['crclm_name']; //group name column index
                                                            }
                                                        } else {
                                                            $select_options1[''] = 'No Curricula to display';
                                                        }
                                                        echo form_dropdown('crclm_id', $select_options1, 'crclm_id', 'class="input-medium required target1" id="crclm_id" autofocus = "autofocus"');
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_term_id">Term(Semester): <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <select id="crclm_term_id" name="crclm_term_id" class="input-medium required">
                                                            <option value>Select Term</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_type_id">Type of Course: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <select id="crs_type_id" name="crs_type_id" class="input-medium required">
                                                            <option value>Select Course Type</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- TO toggle between  Theory , Theory with Lab and Lab selection -->
                                                <div class="control-group">
                                                    <p class="radio_button inline">
                                                        <input id="toggleElement0" type="radio" name="toggle" checked />
                                                        Theory 
                                                        <input id="toggleElement2" type="radio" name="toggle"  />
                                                        Theory with Lab
                                                        <input id="toggleElement1" type="radio" name="toggle"  />
                                                        Lab / Project Work / Others.
                                                    </p>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_code">Course Code: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_code); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_title">Course Title:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_title); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_acronym">Course Acronym:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_acronym); ?>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label" for="lect_credits">Lecture <?php echo $this->lang->line('credits'); ?>:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($lect_credits, 'id="lect_credits"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="tutorial_credits">Tutor <?php echo $this->lang->line('credits'); ?>:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($tutorial_credits, 'id="tutorial_credits"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="practical_credits">Practical <?php echo $this->lang->line('credits'); ?>:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($practical_credits, 'id="practical_credits"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                                <!-- <div class="control-group">
                                                    <p class="control-label" for="self_study_credits">Self Study <?php echo $this->lang->line('credits'); ?>:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($self_study_credits, 'id="self_study_credits"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>-->
                                                <div class="control-group">
                                                    <p class="control-label" for="total_credits"> Total <?php echo $this->lang->line('credits'); ?>:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_credits); ?>
                                                    </div>
                                                </div>
                                                <!--added by bhagya-->
                                                <div class="control-group">													
                                                    <p class="control-label" ><?php ?> <input type="checkbox" class="mte_flag_check_box" id="cia_check"  name="cia_check" checked />&nbsp;&nbsp;&nbsp;Total <?php echo $this->lang->line('entity_cie'); ?> Weightage: </p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_cia_weightage); ?> %
                                                        <span class="date_error"></span>
                                                    </div>
                                                </div>  
												<input type="hidden" id="mte_flag" name="mte_flag" value="<?php echo $mte_flag[0]['mte_flag']?>" />
												<input type= "hidden" id="vw_page_type"  name="vw_page_type"  value="add_page"/>
												<?php if($mte_flag[0]['mte_flag'] == 1) {?>
													<div class="control-group">													
														<p class="control-label" > <input type="checkbox" class="mte_flag_check_box" id="mte_check" name="mte_check" checked /> Total MTE Weightage: </p>
														<div class="controls">
															<?php echo form_input($total_mte_weightage); ?> %
															<span class="date_error"></span>
														</div>
													</div>
												<?php } ?>
                                                <div class="control-group">													
                                                    <p class="control-label" > <input type="checkbox"  class="mte_flag_check_box" id="tee_check" name="tee_check" checked /> Total <?php echo $this->lang->line('entity_see'); ?>  Weightage: </p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_tee_weightage); ?> %
                                                        <span class="date_error"></span>
                                                    </div>
													<span class="edit_check_error " style="color:#b94a48"></span>
                                                </div> 
                                                <div class="control-group">
                                                    <p class="control-label" >Total Weightage: </p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_weightage); ?> %
                                                          <span class="date_error " style="color:#b94a48"></span>
                                                    </div>
                                                </div>
                               
                                            </div><!--span6 ends here-->
                                            <div class="span6">
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_domain_id" class="crs_domain_id">Course Domain: <font color='red'>*</font></p> 
                                                    <div class="controls">
                                                        <?php
                                                        if (!empty($course_domain_list)) {
                                                            $select_options[''] = 'Select Course Domain';
                                                            foreach ($course_domain_list as $domain_item) {
                                                                $select_options[$domain_item['crs_domain_id']] = $domain_item['crs_domain_name']; //group name column index
                                                            }
                                                        } else {
                                                            $select_options[''] = 'First Create Course Domain(s)';
                                                        }
                                                        echo form_dropdown('crs_domain_id', $select_options, 'crs_domain_id', 'class="input-medium required" id="crs_domain_id"');
                                                        ?><?php echo str_repeat("&nbsp;", 6); ?>	
                                                        <span id="add_crs_domain"><a data-target="#mymodal" data-toggle="modal" href="#mymodal" > Add Course Domain</a></span>
                                                    </div>

                                                </div>Note: After entering text use down arrow key & press enter and comma to select.
                                                <div class="control-group">
                                                    <p class="control-label" for="">Prerequisite Courses: </p>
                                                    <div class="controls">

                                                        <textarea type="text" id="fetch_prerequisite_courses" autocomplete="off" data-items="6" data-provide="typeahead" name="tags" placeholder="Enter Prerequisite Course"  class="tagManager" data-original-title=""> </textarea>

                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label" for="course_designer"><?php echo $this->lang->line('course_owner_full'); ?>: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <select id="course_designer" name="course_designer" class="input-medium required">
                                                            <option value>Select Course Owner</option>
                                                        </select>
                                                    </div>
                                                </div>
<!--                                                <div class="control-group">
                                                    <p class="control-label" for="co_crs_owner">Course Instructor(s) Name:</p>
                                                    <div class="controls">
                                                        <textarea type="textarea" id="co_crs_owner" name="co_crs_owner" maxlength="2000" placeholder="Enter Course Instructor Name(s)" class="char-counter"> </textarea>
                                                        <br/><span id="char_span_support" class="margin-left5">0 of 2000</span>
                                                    </div>
                                                </div>-->
                                                <div class="control-group">
                                                    <p class="control-label" for="review_dept">Reviewer Department: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($departmentlist as $itemlist2) {
                                                            $selectoptions2[$itemlist2['dept_id']] = $itemlist2['dept_name'];
                                                        }
                                                        echo form_dropdown('review_dept', array('' => 'Select Department') + $selectoptions2, set_value('dept_id', '0'), 'class="input-medium" id="dept_id" onchange="select_course_reviewer_dept();"');
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="course_reviewer">Course Reviewer: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <select id="course_reviewer" name="course_reviewer" class="input-medium required">
                                                            <option value>Select Reviewer</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label" for="last_date">Last Date to Review:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                            <input type="text" placeholder="Enter Last Date to Review" class="input-medium required datepicker" id="last_date" name="last_date" readonly>
                                                            <span class="date_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="contact_hours">Total Course Contact Hours:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($contact_hours, 'id="contact_hours"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <p class="control-label" for="cie_marks">Total <?php echo $this->lang->line('testI'); ?> Marks:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($cie_marks, 'id="cie_marks"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="mid_term_marks">Total <?php echo $this->lang->line('testIV'); ?> Marks:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($mid_term_marks, 'id="mid_term_marks"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                               <!-- <div class="control-group">
                                                    <p class="control-label" for="ss_marks">Total <?php echo $this->lang->line('testII'); ?> Marks:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($ss_marks, 'id="ss_marks"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>-->
                                                <div class="control-group">
                                                    <p class="control-label" for="see_marks">Total <?php echo $this->lang->line('testIII'); ?> Marks:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($see_marks, 'id="see_marks"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="total_marks">Total Marks:</p>
                                                    <div class="controls">
                                                        <label>
                                                            <?php echo form_input($total_marks, 'id="total_marks"', 'onChange="total();"'); ?>
                                                            <?php
                                                            echo str_repeat('&nbsp;', '2');
                                                            echo ' (' . $this->lang->line('testI') . ' + ' . $this->lang->line('testIV') . ' + ' . $this->lang->line('testIII') . ')';
                                                            ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="see_duration"><?php echo $this->lang->line('entity_see'); ?> Duration(Hours):<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($see_duration, 'id="see_duration"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>                                              
												
												<div class="control-group">
                                                    <p class="control-label" >Bloom's Domain: <font  class="mandatory" color ="red">  </font> </p>
                                                    <div class="controls bloom_leves1">
                                                        <?php
                                                        $i = 1;
                                                        foreach ($bloom_domain as $domain) {
                                                            if ($domain['status'] == 1) {
                                                                if ($i == 1) {
                                                                    ?>                                                                
                                                                    <input class = "bloom_leves" type="checkbox" value="<?php echo $domain['bld_id']; ?>" name="bloom_domain_<?php echo $i; ?>" id="bloom_domain_<?php echo $i; ?>" checked/>
                                                                <?php } else { ?>
                                                                    <input class = "bloom_leves" type="checkbox" value="<?php echo $domain['bld_id']; ?>" name="bloom_domain_<?php echo $i; ?>" id="bloom_domain_<?php echo $i; ?>"/>
                                                                    <?php
                                                                }
                                                                echo $domain['bld_name'];
                                                                ?>  <br/>
                                                                <input type="hidden" name="bld_<?php echo $i; ?>" id="bld_<?php echo $i; ?>">
                                                                <?php
                                                            }
                                                            $i++;
                                                        }
                                                        ?>
                                                        <span class="error_span1" style="color:#b94a48" ></span>
                                                    </div>
                                                </div>
												<div class="control-group">
                                                    <p class="control-label" for="crs_mode"></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_mode, 'id="crs_mode"'); ?>
                                                    </div>
                                                </div>
                                                <input type='text' style="visibility:hidden" id='fetch_clo_bl_flag_val' name='fetch_clo_bl_flag_val' value=''/>
                                                <input type="hidden" name="curriculum" id="curriculum" value="" />
                                                <input type="hidden" name="term" id="term" value="" />
                                            </div><!--span6 ends here-->
                                        </div><!--row-fluid ends here-->
                                    </div><!--span12 ends here-->
                                </div>

                                <!--Modal Pop-up starts here-->
                                <div id="add_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="false">
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Warning
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >The Course with this Course Code / Course Title already exists in this Curriculum. Please verify.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    </div>
                                </div>
                                <!--Modal Popup ends here -->
                                <div class="pull-right">       
                                    <button class="add_form_submit_id btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                                    <a href= "<?php echo base_url('curriculum/course'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                                </div>

                            </form>
                            <br>
                            <!---Add Course Domain modal--->
                            <div id="mymodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: block; width: 750; left: 700;" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Add Course Domain
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body" id="comments">

                                    <div class="controls">
                                        Course Domain Name :<font color="red">*</font>	<input type = "text" id = "crs_domain_name" name = "crs_domain_name" class = "input-medium required">  <span id="message"></span>

                                        Course Domain Description : <textarea id = "crs_domain_description" name = "crs_domain_description"></textarea>						
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit" id="save_crs_domain" name="save_crs_domain"><i class="icon-file icon-white"></i><span></span> Save</button>
                                    <button type="reset" class="btn btn-info" onclick="ClearFields();"><i class="icon-refresh icon-white"></i> Reset</button>
                                    <button class="btn btn-danger close1" data-dismiss="modal" onclick="ClearFields();"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                            <!--Add Course Domain modal ends-->
                            <div id="warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="warning_dialog" data-backdrop="static" data-keyboard="true">
                                </br>
                                <div class="container-fluid">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body" id="comments">
                                    <p >This Course Domain name already exists.</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                </div>
                            </div>

                            <!--collapsible-->
                            <div class="menu">
                                <div class="accordion">
                                    <!-- Áreas -->
                                    <div class="accordion-group">
                                        <!-- Área -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area1" style="text-decoration:none;">
                                                <h5><b>&nbsp; <i class="icon-chevron-down"></i>&nbsp;Curriculum Details</b></h5>
                                            </a>
                                        </div>
                                        <!-- /Área -->
                                        <div id="area1" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento1">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <!--table-->
                                                            <table class="table table-bordered" style="width:100%">
                                                                <thead>
                                                                    <tr style="font-size:12px;">
                                                                        <th>Curriculum</th>
                                                                        <th>Curriculum Description</th>
                                                                        <th>Total <?php echo $this->lang->line('credits'); ?></th>
                                                                        <th>Total Terms</th>
                                                                        <th>Start Year</th>
                                                                        <th>End Year</th>
                                                                        <th>Curriculum Owner</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tbody role="alert" aria-live="polite" aria-relevant="all" id="fetch_curriculum_details">
                                                                </tbody>
                                                            </table>
                                                            <!--table ends here-->
                                                        </div>
                                                    </div>
                                                    <!-- /Equipamentos -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /accordion -->
                            </div>
                            <div class="menu">
                                <div class="accordion">
                                    <!-- Áreas -->
                                    <div class="accordion-group">
                                        <!-- Área -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area2" style="text-decoration:none;" >
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;List of PEOs </b></h5>
                                            </a>
                                        </div>
                                        <!-- /Área -->
                                        <div id="area2" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento2">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <a class="accordion-toggle" data-toggle="collapse"  style="text-decoration:none;" >
                                                                <table class="table table-bordered" style="width:100%">
                                                                    <thead>
                                                                        <tr style="font-size:12px;">
                                                                            <th>Sl No.</th>
                                                                            <th>Program Educational Objectives (PEOs)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="fetch_peo_details">
                                                                    </tbody>
                                                                </table>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- /Equipamentos -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /accordion -->
                            </div>
                            <div class="menu">
                                <div class="accordion">
                                    <!-- Áreas -->
                                    <div class="accordion-group">
                                        <!-- Área -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area3" style="text-decoration:none;" >
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;List of POs </b></h5>
                                            </a>
                                        </div>
                                        <!-- /Área -->
                                        <div id="area3" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento2">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <a class="accordion-toggle" data-toggle="collapse"  style="text-decoration:none;" >
                                                                <!--table-->
                                                                <table class="table table-bordered" style="width:100%">
                                                                    <thead>
                                                                        <tr style="font-size:12px;">
                                                                            <th>Sl No.</th>
                                                                            <th><?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="fetch_po_details">
                                                                    </tbody>
                                                                </table>
                                                                <!--table ends here-->
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <!-- /Equipamentos -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /accordion -->
                            </div>
                            <div class="menu">
                                <div class="accordion">
                                    <!-- Áreas -->
                                    <div class="accordion-group">
                                        <!-- Área -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area4" style="text-decoration:none;">
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Term-wise Course Details</b></h5>
                                            </a>
                                        </div>
                                        <!-- /Área -->
                                        <div id="area4" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento4">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <a class="accordion-toggle" data-toggle="collapse" style="text-decoration:none;" >
                                                            <!--table-->
                                                            <table class="table table-bordered" style="width:100%">
                                                                <thead>
                                                                </thead>
                                                                <tbody>
                                                                <tbody role="alert" aria-live="polite" aria-relevant="all" id="fetch_termwise_course_details">
                                                                </tbody>
                                                            </table>
                                                            <!--table ends here-->
                                                    </div>
                                                </div>
                                                <!-- /Equipamentos -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /accordion -->
                        </div>
                        <head>
                            <script src="<?php echo base_url('twitterbootstrap/js/jquery.min.js'); ?>"></script>
                            <script src="<?php echo base_url('twitterbootstrap/tagmanager/bootstrap-tagmanager.js'); ?>"></script>
                        </head>
                        <script>
                                        jQuery(".tagManager").tagsManager({
                                            preventSubmitOnEnter: true,
                                            typeahead: true,
                                            typeaheadSource: autocomplete,
                                            blinkBGColor_1: '#FFFF9C',
                                            blinkBGColor_2: '#CDE69C',
                                        });

                                        function autocomplete(typeahead, query) {
                                            $.ajax({
                                                url: base_url + 'curriculum/course/course_name',
                                                type: "POST",
                                                data: "query=test",
                                                dataType: "JSON",
                                                async: false,
                                                success: function (data) {
                                                    typeahead.process(data);
                                                }
                                            });
                                        }
                        </script>
                        <!--ends here-->
                        <br>
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
        <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

