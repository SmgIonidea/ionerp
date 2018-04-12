<?php
/**
 * Description          :	Edit View for Course Module.
 * Created		:	09-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 02-12-2015		Bhagyalaxmi 			Added total weightage of cia add tee
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
        <!-- Le fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/ico/favicon.png">

    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); editCourseToggleStatus();" onpageshow="if (event.persisted) noBack();">
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
                                    Edit Course
                                    <p class="pull-right" style="font-size:12px; color:white;">Note: You can refer Term-wise Course details below before editing a Course</p>
                                </div>
                            </div>	
                            <form class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action="<?php echo base_url('curriculum/course/update_course'); ?>">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_id">Curriculum: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($curriculumlist as $listitem1) {
                                                            $select_options1[$listitem1['crclm_id']] = $listitem1['crclm_name'];
                                                        }
                                                        echo form_dropdown('crclm_id', $select_options1, $course_details['0']['crclm_id'], 'id="crclm_id" class="input-medium" autofocus = "autofocus" onchange="select_term();"');
                                                        ?>                                                       
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_term_id">Term(Semester): <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($term_details as $listitem2) {
                                                            $select_options2[$listitem2['crclm_term_id']] = $listitem2['term_name'];
                                                        }
                                                        echo form_dropdown('crclm_term_id', $select_options2, $course_details['0']['crclm_term_id'], 'class="input-medium" id="crclm_term_id"');
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_type_id">Type of Course: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($coursetypelist as $listitem3) {
                                                            $select_options3[$listitem3['crs_type_id']] = $listitem3['crs_type_name'];
                                                        }
                                                        echo form_dropdown('crs_type_id', $select_options3, $course_details['0']['crs_type_id'], 'class="input-medium required"', 'id="crs_type_id"');
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- TO toggle between  Theory , Theory with Lab and Lab selection -->
                                                <div class="control-group">
                                                    <p class="radio_button inline">
                                                        <input id="toggleElement0" type="radio" name="toggle"  />
                                                        Theory 
                                                        <input id="toggleElement2" type="radio" name="toggle"  />
                                                        Theory with Lab
                                                        <input id="toggleElement1" type="radio" name="toggle"  />
                                                        Lab / Project Work / Others.
                                                    </p>
                                                </div>

                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_name">Course Code:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_code); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_name">Course Title:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($crs_title); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_name">Course Acronym:<font color='red'>*</font></p>
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
                                                </div> -->
                                                <div class="control-group">
                                                    <p class="control-label" for="total_credits" >Total <?php echo $this->lang->line('credits'); ?>:</p>
                                                    <div class="controls" >
                                                        <?php echo form_input($total_credits, 'id="total_credits"'); ?>
                                                    </div>
                                                </div>
                                                <!-- Added by Bhgaya-->
												<?php //var_dump($cia_flag); var_dump($mte_flag_org); var_dump($mte_flag);?>
												<div class="control-group">													
                                                    <p class="control-label" ><?php ?> <input type="checkbox" class="mte_flag_check_box" id="cia_check"  name="cia_check"  <?php if($cia_flag == 1) {?> checked <?php } ?>/>&nbsp;&nbsp;&nbsp;Total <?php echo $this->lang->line('entity_cie'); ?> Weightage: </p>
                                                    <div class="controls">
                                                        <?php echo form_input($total_cia_weightage); ?> %
                                                        <span class="date_error"></span>
                                                    </div>
                                                </div>  
												<input type="hidden" id="mte_flag" name="mte_flag" value="<?php echo $mte_flag_org[0]['mte_flag']?>" />
												<input type="hidden" id="weightage_status" name="weightage_status" value="" />
												<input type= "hidden" id="vw_page_type"  name="vw_page_type"  value="edit_page"/>
												<?php if($mte_flag_org[0]['mte_flag'] == 1) {?>
													<div class="control-group">													
														<p class="control-label" > <input type="checkbox" class="mte_flag_check_box" id="mte_check" name="mte_check"   <?php if($mte_flag == 1) {?> checked <?php } ?>  /> Total MTE Weightage: </p>
														<div class="controls">
															<?php echo form_input($total_mte_weightage); ?> %
															<span class="date_error"></span>
														</div>
													</div>
												<?php } ?>
                                                <div class="control-group">													
                                                    <p class="control-label" > <input type="checkbox"  class="mte_flag_check_box" id="tee_check" name="tee_check" <?php if($tee_flag == 1) {?> checked <?php } ?> /> Total <?php echo $this->lang->line('entity_see'); ?>  Weightage: </p>
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
                                                        <span class="edit_date_error" style="color:red"></span>
                                                    </div>
                                                </div>  
                                   
                                            </div><!--span6 ends here-->
                                            <div class="span6">
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_domain_id">Course Domain: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($course_domain_list as $domain_item) {
                                                            $select_options[$domain_item['crs_domain_id']] = $domain_item['crs_domain_name']; //course domain name column index
                                                        }
                                                        echo form_dropdown('crs_domain_id', $select_options, $course_details['0']['crs_domain_id'], 'class="input-medium required" id="crs_domain_id"');
                                                        ?>
                                                    </div>
                                                </div>Note: After entering text use down arrow key & press enter and comma to select.						  
                                                <div class="control-group">
                                                    <p class="control-label" for="">Prerequisite Courses: </p>
                                                    <div class="controls">
                                                        <textarea type="text" id="fetch_prerequisite_courses" autocomplete="off" data-items="6" data-provide="typeahead" name="tags" placeholder="Enter Prerequisite Course" class="tagManager" data-original-title=""> </textarea>
                                                        <div>
                                                            <?php foreach ($predessor_data as $item): ?>
                                                                <span class="myTag" id="<?php echo $item['predecessor_id']; ?>"><span style="height: auto;"><?php echo $item['predecessor_course']; ?> </span><a href="#" class="myTagRemover delete_course" id="tags_Remover_<?php echo $item['predecessor_id']; ?>" tagidtoremove="<?php echo $item['predecessor_id']; ?>" title="Remove">x</a></span>
                                                            <?php endforeach; ?>		
                                                        </div>

                                                    </div>
                                                    <div id="delete_crs_id">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="clo_owner_id"><?php echo $this->lang->line('course_owner_full'); ?>: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($co_userlist as $listitem4) {
                                                            if ($listitem4['active'] == 1) {
                                                                $active_state = "Active User";
                                                            } else {
                                                                $active_state = "In-Active User";
                                                            }
                                                            $select_options4[$listitem4['id']] = $listitem4['title'] . " " . $listitem4['first_name'] . " " . $listitem4['last_name'];
                                                            $title_list[$listitem4['id']] = $listitem4['email'] . "\n" . $active_state;
                                                            $active_list[$listitem4['id']] = $listitem4['active'];
                                                        }
                                                        echo form_dropdown_custom_new('clo_owner_id', $select_options4, $course_owner_details['owner_details']['0']['clo_owner_id'], 'class="input-medium required" id="clo_owner_id"', $title_list);
                                                        ?>
                                                    </div>
                                                </div>
<!--                                                <div class="control-group">
                                                    <p class="control-label" for="co_crs_owner_edit">Course Instructor(s) Name:</p>
                                                    <div class="controls">
<?php echo form_textarea($co_crs_owner_edit); ?>
                                                        <br/><span id="char_span_support" class="margin-left5">0 of 2000</span> 
                                                    </div>
                                                </div><br />-->
                                                <div class="control-group">
                                                    <p class="control-label" for="review_dept">Reviewer Department: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($departmentlist as $listitem5) {
                                                            $select_options5[$listitem5['dept_id']] = $listitem5['dept_name'];
                                                        }
                                                        echo form_dropdown('review_dept', $select_options5, $course_owner_details['reviewer_details']['0']['dept_id'], 'class="input-medium" id="dept_id" onchange="select_validator_dept();"');
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="validator_id">Course Reviewer: <font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <?php
                                                        foreach ($userlist as $listitem6) {
                                                            if ($listitem6['active'] == 1) {
                                                                $active_state = "Active User";
                                                            } else {
                                                                $active_state = "In-Active User";
                                                            }
                                                            $select_options6[$listitem6['id']] = $listitem6['title'] . " " . $listitem6['first_name'] . " " . $listitem6['last_name'];
                                                            $title_list[$listitem6['id']] = $listitem6['email'] . "\n" . $active_state;
                                                        }
                                                        echo form_dropdown_custom_new('validator_id', $select_options6, $course_owner_details['reviewer_details']['0']['validator_id'], 'class="input-medium" id="validator_id"', array('' => '') + $title_list);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crclm_name">Last Date to Review:<font color='red'>*</font></p>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                                            <input type="text" placeholder="Enter Last Date to Review" class=" required input-medium datepicker" id="last_date" name="last_date" value="<?php echo $course_owner_details['reviewer_details']['0']['last_date']; ?>" readonly/>
                                                            <span class="edit_date_error"></span> 
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
                                                    <p class="control-label" for="nid_term_marks">Total <?php echo $this->lang->line('testIV'); ?> Marks:<font color='red'>*</font></p>
                                                    <div class="controls">
<?php echo form_input($mid_term_marks, 'id="mid_term_marks"', 'onChange="total();"'); ?>
                                                    </div>
                                                </div>
                                             <!--   <div class="control-group">
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
														echo '(' . $this->lang->line('testI') . ' + ' . $this->lang->line('testIV') . ' + ' . $this->lang->line('testIII') . ')';
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
                                                    <p class="control-label" >Bloom's Domain: </p>
                                                    <div class="controls">
                                                        <?php
                                                        $i = 1;
                                                        foreach ($bloom_domain as $domain) {
                                                            if ($bld_active[$i - 1] == 1 && $domain['status'] == 1) {
                                                                ?>
                                                                <input type="checkbox" value="<?php echo $domain['bld_id']; ?>" name="bloom_domain_<?php echo $i; ?>" id="bloom_domain_<?php echo $i; ?>" class="check bloom_leves" data-bld="<?php echo $domain['bld_id']; ?>"checked="" />
                                                                <?php echo $domain['bld_name']; ?>  <br/>
                                                                <input type="hidden" name="bld_<?php echo $i; ?>" id="bld_<?php echo $i; ?>">

                                                            <?php } elseif ($domain['status'] == 1) {
                                                                ?> 
                                                                <input class="bloom_leves" type="checkbox" value="<?php echo $domain['bld_id']; ?>" name="bloom_domain_<?php echo $i; ?>" id="bloom_domain_<?php echo $i; ?>"/>
                                                                <?php echo $domain['bld_name']; ?> <br/>
                                                                <input type="hidden" name="bld_<?php echo $i; ?>" id="bld_<?php echo $i; ?>">

                                                                <?php
                                                            }$i++;
                                                        }
                                                        ?> <span class="error_span1" style="color:#b94a48" ></span>
                                                    </div>
                                                </div> <input type='text' style="visibility:hidden" id='fetch_clo_bl_flag_val' name='fetch_clo_bl_flag_val' value='<?php echo $clo_bl_flag; ?>'/>
												 <div class="control-group">
                                                    <p class="control-label" for="crs_id"></p>
                                                    <div class="controls">
														<?php echo form_input($crs_id); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="crs_mode"></p>
                                                    <div class="controls">
														<?php echo form_input($crs_mode, 'id="crs_mode"'); ?>
                                                    </div>
                                                </div>
                                                <!--added by bhagya-->

                                                <input type="hidden" name="curriculum" id="curriculum" value="" />
                                                <input type="hidden" name="term" id="term" value="" />
                                            </div><!--span6 ends here-->
                                        </div><!--row-fluid ends here-->
                                    </div><!--span12 ends here-->
                                </div>
                                <div class="pull-right">       
                                    <button class="edit_form_submit_id btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Update</button>
                                    <a href= "<?php echo base_url('curriculum/course'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                                </div>
                            </form>                         
                            <!--Modal Popup starts here-->
                            <div id="edit_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="edit_warning_dialog" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body" id="comments">
                                    <p >Course with this Course Code / Course Title already exists in the selected Curriculum.<br>
                                        Please verify. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                            <div id="cantDisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>The Bloom's Level of this Bloom's Domain are associated with the <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) or <?php echo $this->lang->line('entity_tlo_full_singular'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) or Question Paper ,  so You cannot disable this Bloom's Domain.</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>
                            <!--Modal Popup ends here -->

                            <br>
                            <!-- Course Details-->
                            <!--collapsible-->
                            <div class="menu">
                                <div class="accordion">
                                    <!-- �reas -->
                                    <div class="accordion-group">
                                        <!-- �rea -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area1" style="text-decoration:none;">
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Curriculum Details</b></h5>
                                            </a>
                                        </div>
                                        <!-- /�rea -->
                                        <div id="area1" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento1">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#equipamento1-1" href="#ponto1-1" style="text-decoration:none;">
                                                                <table class="table table-bordered" style="font-size:12px; width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Curriculum</th>
                                                                            <th>Description</th>
                                                                            <th>Total <?php echo $this->lang->line('credits'); ?></th>
                                                                            <th>Total Terms</th>
                                                                            <th>Start Year</th>
                                                                            <th>End Year</th>
                                                                            <th>Curriculum Owner</th>
                                                                        </tr>
                                                                    </thead>
<?php
$j = 1;
foreach ($curriculum_details as $row3):
    ?>
                                                                        <tr>
                                                                            <td><?php echo $row3['crclm_name'] ?></td>
                                                                            <td><?php echo $row3['crclm_description'] ?></td>
                                                                            <td><?php echo $row3['total_credits'] ?></td>
                                                                            <td><?php echo $row3['total_terms'] ?></td>
                                                                            <td><?php echo $row3['start_year'] ?></td>
                                                                            <td><?php echo $row3['end_year'] ?></td>
                                                                            <td><?php echo $row3['username'] ?></td>
<?php endforeach; ?>
                                                                    </tr>
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
                                    <!-- �reas -->
                                    <div class="accordion-group">
                                        <!-- �rea -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area2" style="text-decoration:none;" >
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;List of PEOs</b></h5>
                                            </a>
                                        </div>
                                        <!-- /�rea -->
                                        <div id="area2" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento2">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <a class="accordion-toggle" data-toggle="collapse"  style="text-decoration:none;" >
                                                                <!--table-->
                                                                <table class="table table-bordered" style="font-size:12px; width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="40px;" style="white-space:nowrap;">Sl No.</th>
                                                                            <th>Program Educational Objectives (PEOs)</th>
                                                                        </tr>
                                                                    </thead>
                                                                            <?php
                                                                            $i = 1;
                                                                            foreach ($peo_list as $row1):
                                                                                ?>
                                                                        <tr>
                                                                            <td style="text-align:right;"><?php echo $i; ?></td>
                                                                            <td colspan="4"><?php
                                                                                echo $row1['peo_statement'];
                                                                                $i++;
                                                                                ?>
                                                                            </td>
<?php endforeach; ?>
                                                                    </tr>
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
                                    <!-- �reas -->
                                    <div class="accordion-group">
                                        <!-- �rea -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area3" style="text-decoration:none;" >
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;List of POs</b></h5>
                                            </a>
                                        </div>
                                        <!-- /�rea -->
                                        <div id="area3" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento2">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading equipamento">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#equipamento1-2" style="text-decoration:none;" >
                                                                <!--table-->
                                                                <table class="table table-bordered" style="font-size:12px; width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="40px;" style="white-space:nowrap;">Sl No.</th>
                                                                            <th><?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>)</th>
                                                                        </tr>
                                                                    </thead>
                                                                            <?php
                                                                            $j = 1;
                                                                            foreach ($po_list as $row2):
                                                                                ?>
                                                                        <tr>
                                                                            <td style="text-align:right;"><?php echo $j; ?></td>
                                                                            <td colspan="4"><?php
                                                                                echo $row2['po_statement'];
                                                                                $j++;
                                                                                ?>
                                                                            </td>
<?php endforeach; ?>
                                                                    </tr>
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
                                    <!-- �reas -->
                                    <div class="accordion-group">
                                        <!-- �rea -->
                                        <div class="brand-custom">
                                            <a class="brand-custom" data-toggle="collapse" href="#area4" style="text-decoration:none;">
                                                <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Term-wise Course Details</b></h5>
                                            </a>
                                        </div>
                                        <!-- /�rea -->
                                        <div id="area4" class="accordion-body collapse">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento4">
                                                    <!-- Equipamentos -->
                                                    <div class="accordion-group">
                                                        <a class="accordion-toggle" data-toggle="collapse" style="text-decoration:none;" >
                                                            <!--table-->
                                                            <table class="table table-bordered" style="font-size:12px; width:100%">
                                                                <thead>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $term_id = '';
                                                                    $term_id2 = '';
                                                                    $total = 0;
                                                                    $sum = 0;
                                                                    foreach ($course_detailslist as $row):
                                                                        ?>
                                                                        <?php
                                                                        if ($term_id != $row['crclm_term_id']):
                                                                            foreach ($course_detailslist as $row1):
                                                                                if ($term_id2 != $row1['crclm_term_id']) {
                                                                                    $sum = $sum + $row1['total_credits'];
                                                                                    $term_id2 = $row1['crclm_term_id'];
                                                                                }
                                                                            endforeach;
                                                                            ?>			  
                                                                            <tr>
                                                                                <td colspan="2" style="color : blue"><b><?php echo $row['term_name']; ?></b>
                                                                                </td>
                                                                                <?php $total = $row['total_theory_courses'] + $row['total_practical_courses']; ?>
                                                                                <td colspan="2" style="color : blue"><b>Term Total Courses:-  <?php echo $total; ?> ( <?php echo $row['total_theory_courses']; ?>-(Theory) + <?php echo $row['total_practical_courses']; ?>-(Practical) )</b>
                                                                                </td>
                                                                                <td colspan="5" style="color : blue"><b>Term <?php echo $this->lang->line('credits'); ?>:-   <?php echo $row['term_credits']; ?> </b>
                                                                                </td>
                                                                                <td colspan="2" style="color : blue"><b>Term Duration:-   <?php echo $row['term_duration']; ?>(weeks)</b>
                                                                                </td>
        <?php
        $term_id = $row['crclm_term_id'];
        $j = 1;
        $total = 0;
        $sum = 0;
        ?>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Sl No.</th>
                                                                                <th>Code</th>
                                                                                <th>Course</th>
                                                                                <th>Core / Elective </th>
                                                                                <th>Acronym</th>
                                                                                <th>L</th>
                                                                                <th>T</th>
                                                                                <th>P</th>
                                                                                <th>Total <?php echo $this->lang->line('credits'); ?></th>
                                                                                <th>Course Owner</th>
                                                                                <th>Mode</th>
                                                                            </tr>
    <?php endif ?>
                                                                        <tr>
                                                                            <td style="text-align:right;"><?php echo $j; ?>.</td> 
                                                                            <td><?php echo $row['crs_code']; ?></td>
                                                                            <td><?php echo $row['crs_title']; ?></td>
                                                                            <td><?php echo $row['crs_type_name']; ?></td>
                                                                            <td><?php echo $row['crs_acronym']; ?></td>
                                                                            <td style="text-align:right;"><?php echo $row['lect_credits']; ?></td>
                                                                            <td style="text-align:right;"><?php echo $row['tutorial_credits']; ?></td>
                                                                            <td style="text-align:right;"><?php echo $row['practical_credits']; ?></td>
                                                                            <td style="text-align:right;"><?php echo $row['total_credits']; ?></td>
                                                                            <td><?php echo $row['username']; ?></td>
                                                                            <td><?php
                                                                        if ($row['crs_mode'] == 1)
                                                                            echo 'Practical';
                                                                        else
                                                                            echo 'Theory';
                                                                        $j++;
                                                                        ?> </td>
                                                                        </tr>
<?php endforeach; ?>
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
                        <!--Do not place contents below this line-->	
                </div>
                </section>
            </div>
        </div>
        <!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
        <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course.js'); ?>" type="text/javascript">
        </script>
