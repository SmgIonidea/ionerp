

<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>

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
        <?php //$this->load->view('includes/sidenav_1'); ?>
        <div class="span12">
            <!-- Contents
            ================================================== -->
            <div class="navbar">
                <div class="navbar-inner-custom">
                    <?php echo $title; ?>
                <!--<a role = "button"  id='edit' class="cursor_pointer pull-right" title="Edit QP Header"><i class="icon-edit icon-white"> </i><font color="white" size="2px">Edit</font></a>-->
                </div>
            </div>
            <div class="bs-docs-example ">
                <div ><b>Curriculum : <font color="#004b99"><?php echo $crclm_title; ?> </font>
                        &nbsp;&nbsp;&nbsp;&nbsp;Term :<font color="#004b99"> <?php echo $term_title; ?></font>
                        &nbsp;&nbsp;&nbsp;&nbsp;Course :<font color="#004b99"> <?php echo $course_title[0]['crs_title'] . "[" . $course_title[0]['crs_code'] . "]"; ?></font></b><br/></div>
            </div>
            <section id="contents">
                <div class="bs-docs-example ">
                    <a class="brand-custom" href= "<?php echo base_url('question_paper/tee_qp_list'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove icon-white"></i><span></span> Close</span></a>
                    <!--content goes here-->
                    <div class="menu">
                        <div class="accordion">
                            <div class="accordion-group">
                                <div class="brand-custom">
                                    <a class="brand-custom cursor_pointer" data-toggle="collapse" href="#collapse1" style="text-decoration:none;">
                                        <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
                                                <?php echo "Edit " . $this->lang->line('entity_tee') . " Framework"; ?></b></h5>
                                    </a>
                                </div>
                                <div></div></div>
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <form class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "">
                                    <div class="control-group">
                                        <p class="control-label" for="inputEmail">Question Paper Title<font color="red"><b>*</b></font> :</p>
                                        <div class="controls">
                                            <textarea class="required qpaper_title " name="qp_title" id="qp_title" style="margin: 0px; width: 1000px; height: 42px;" rows="3" cols="20" placeholder="Enter Model Question Paper Title" ><?php echo $meta_data[0]['qpd_title']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <div class="span4">
                                                <div class="control-group">
                                                    <label class="control-label" for="inputPassword">Total Duration (H:M)<font color="red"><b>*</b></font>:</label>
                                                    <div class="controls">
                                                        <input type="text" id="total_duration" name="total_duration" class="text_align_right  input-mini required total_duration" placeholder="in hours" value="<?php echo $meta_data[0]['qpd_timing']; ?>"  />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="span4">
                                                <div class="control-group">
                                                    <div class="">Course:
                                                        <input readonly class="required course_name" type="text" id="course_name" name="course_name" value="<?php echo $course_title[0]['crs_title'] . '  -  (' . $course_title[0]['crs_code'] . ')' ?>" style="width:265px;"  />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="span2">
                                                <div class="control-group">
                                                    <div class="">Maximum Marks<font color="red"><b>*</b></font> :
                                                        <input type="text" id="max_marks" name="max_marks" class="text_align_right allownumericwithoutdecimal  input-mini required max_marks numeric" value ="<?php echo $meta_data[0]['qpd_max_marks']; ?>"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span2">
                                                <div class="control-group">
                                                    <div class="">Grand Total <font class="font_color">*</font> :
                                                        <input type="text" id="Grand_total" name="Grand_total" class="text_align_right allownumericwithoutdecimal  input-mini required max_marks" value="<?php echo $meta_data[0]['qpd_gt_marks']; ?>"  />
                                                        <input type="hidden" value="<?php echo $meta_data[0]['qpd_gt_marks']; ?>" id="Grand_total_h" name="Grand_total_h"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <p class="control-label" for="inputEmail">Note <font color="red"><b></b></font>:</p>
                                        <div class="controls">
                                            <textarea class=" qp_notes " name="qp_notes" id="qp_notes" style="margin: 0px; width: 1000px; height: 42px;" rows="3" cols="20" placeholder="Enter Question Paper note here"  ><?php echo $meta_data[0]['qpd_notes']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <button type="button" name="save_header" id="save_header" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span> Save and Create QP</button>
                                            <button type="button"  name="update_header" id="update_header" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span> Update</button>
                                        </div>
                                    </div>
                                </form>

                                <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                    <div class="navbar">
                                        <div class="">
                                            <h5 style="color:#0088cc"><u><?php echo "Manage Section / Parts (Units)  Distribution"; ?></u></h5>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover dataTable" id="Edit_t" aria-describedby="" style="display:none">

                                        <thead>
                                            <tr role="row">
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >Section / Parts (Units)</th>
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >No. of Questions</th>
                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >Section / Parts (Units) Max Marks</td></tr>
                                        </thead>
                                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                                            <?php
                                            $i = 1;
                                            foreach ($unit_data as $topic) {
                                                ?>
                                                <tr  data-toggle="collapse" class="table_row">
                                                    <td class="text_align_right allownumericwithoutdecimal "><?php echo $i; ?></td>
                                                    <td><?php echo $topic['qp_unit_code']; ?></td>
                                                    <td class="text_align_right allownumericwithoutdecimal "><?php echo $topic['qp_total_unitquestion']; ?></td>
                                                    <td class="text_align_right allownumericwithoutdecimal " ><?php echo $topic['qp_utotal_marks']; ?></td>

                                                </tr><?php
                                                $i++;
                                            }
                                            ?>
                                        <input type="hidden" id="unit_sum" name="unit_sum" value="<?php echo $unit_sum; ?>"/>
                                        </tbody>
                                    </table>

                                    <div class="row-fluid" id="Edit_FM">
                                        <div class="span12">
                                            <section id="contents">

                                                <div class="">
                                                    <!--content goes here-->

                                                    <table class="table  table-condensed table-bordered table-hover" id="Edit_FM_table" style="border-collapse:collapse" aria-describedby="example_info">

                                                        <thead>
                                                            <tr role="row">
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Section / Parts (Units)</th>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >No. of Questions</th>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Section / Parts (Units) Max Marks</th>
                                                                <!--<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Edit</th>-->
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                                                            <?php
                                                            $i = 1;
                                                            foreach ($unit_data as $topic) {
                                                                ?>
                                                                <tr  data-toggle="collapse"><td><?php echo $i; ?></td>
                                                                    <td><input type="text" id="unitd_id" name="unitd_id[]" class="input-mini required max_marks" value="<?php echo $topic['qp_unit_code']; ?>"/>
                                                                        <input type="hidden" id="unitd_id_one" name="unitd_id_one[]" class="input-mini required" value="<?php echo $topic['qpd_unitd_id']; ?>"/>
                                                                    </td>
                                                                    <td><input type="text" id="no_q" name="no_q[]" class="text_align_right allownumericwithoutdecimal  input-mini required max_marks" value="<?php echo $topic['qp_total_unitquestion']; ?>"/></td>
                                                                    <td><input type="text" id="sub_marks" name="sub_marks[]" class="text_align_right allownumericwithoutdecimal  input-mini required edit_unit_max_marks" value="<?php echo $topic['qp_utotal_marks']; ?>"/></td>
                                                                    <td><a role = "button" href="#" class="delete_Unit" id="<?php echo $topic['qpd_unitd_id']; ?>" ><i class="icon-remove icon-black"> </i></a>
                                                                    </td></tr><?php
                                                                $i++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <button type="button" name="update_FM" id="update_FM" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span> Update</button>

                                                    <form id="frame_work_container" method="post" action>
                                                        <div class="form_container FM">
                                                            <h5 style="color:#0088cc"><u><?php echo "Add Unit"; ?></u></h5>
                                                            <!--<button type="button" class="btn btn-primary btn-sm" id="add_ExpenseRow">
                                                            <i class="icon-plus-sign icon-white"></i> ADD
                                                            </button>-->
                                                            <table id="expense_table" cellspacing="0" cellpadding="0" class="table table-bordered table-hover"  aria-describedby="example_info">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Section / Parts (Units) Name <font color="red"> *</font></th>
                                                                        <th>No. of Questions <font color="red"> *</font></th>
                                                                        <th>Section / Parts (Units) Max Marks  <font color="red"> *</font></th>
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><input type="text" id="units_01" name="units_01" maxlength="255" required /></td>
                                                                        <td><input type="text" id="ques_no_01" name="ques_no_01" maxlength="255" class="text_align_right allownumericwithoutdecimal " required /></td>
                                                                        <td><input type="text" id="marks_01" name="marks_01" maxlength="11" class="text_align_right allownumericwithoutdecimal  unit_max_marks required" /></td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div class="row-fluid">
                                                                <div class="span12">
                                                                    <button type="button" name="save_FM" id="save_FM" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span> Save</button>
                                                                </div>
                                                            </div>
                                                            <!--<button type="button" name="save_FM" id="save_FM" class="btn btn-primary pull-right"><i class="icon-ok icon-white"></i><span></span> Save</button>-->
                                                        </div>
                                                    </form>
                                                </div>
                                        </div>

                                        </section>
                                    </div>
                                </div>
                            </div>
                            </section>

                        </div>

                    </div></br>

                    <!--==============================================================================================================================================================-->


                </div>
                <!--==============================================================================================================================================================-->
                <div class="container-fluid">   
                    <div class="row-fluid">
                        <div class="span12">
                            <section id="contents">
                                <form>
                                    <div class="bs-docs-example ">				
                                        <!--content goes here-->
                                        <div class="menu">
                                            <div class="accordion">
                                                <div class="accordion-group">
                                                    <div class="brand-custom">
                                                        <a class="brand-custom" data-toggle="collapse" href="#collapse2" style="text-decoration:none;">
                                                            <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Manage Questions</b></h5>
                                                        </a>
                                                    </div>
                                                    <div></div></div>
                                                <div id="collapse2" class="panel-collapse collapse">
                                                    <a role="button" type="button"  id="Add_Question" class="btn btn-primary pull-right" ><i class="icon-plus-sign icon-white"></i>&nbsp;Add Question</a><br/><br/>
                                                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                                        <thead>
                                                            <tr role="row">

                                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >Sl.No.</th>
                                                                <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" >Q.No</th>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Question</th>
                                                                <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 11) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Course Outcomes(<?php echo $this->lang->line('entity_clo'); ?>) Code</th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 10) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Topic Title</th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 23) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Bloom's Level</th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 6) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>  Reference</th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 22) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Program Indicators(PI) Codes</th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                 
                                                                 <?php
                                                                foreach ($entity_list as $entity) {
                                                                    if ($entity['entity_id'] == 29) {
                                                                        ?>
                                                                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" ><?php echo $entity['alias_entity_name']; ?></th>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Marks</th>
                                                                <!-- <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Edit Weightage</th>-->
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Edit</th>
                                                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody role="alert" aria-live="polite" aria-relevant="all" id="tee_qp_data">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div></form></section></div></div></div>
                                    <div class="container-fluid">   
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <section id="contents">

                                                    <div class="bs-docs-example ">
                                                        <a class="brand-custom" href= "<?php echo base_url('question_paper/tee_qp_list'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove icon-white"></i><span></span> Close</span></a>
                                                        <div class="menu">
                                                            <div class="accordion">
                                                                <div class="accordion-group">
                                                                    <div class="brand-custom">
                                                                        <a class="brand-custom" data-toggle="collapse" href="#area4" style="text-decoration:none;" onclick="graph_load()">
                                                                            <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Question Paper Analysis</b></h5>
                                                                        </a>
                                                                    </div>
                                                                    <div id="area4" class="accordion-body collapse">
                                                                        <div class="accordion-inner">
                                                                            <div class="accordion" id="equipamento4">
                                                                                <!-- Equipamentos -->
                                                                                <div class="accordion-group">
                                                                                    <a class="accordion-toggle" data-toggle="collapse" style="text-decoration:none;" > </a>
                                                                                    <!-- Data starts from here... -->

                                                                                    <form class="form-horizontal" id="qp_graph_display">
                                                                                        <input type="hidden" name="crs" id="crs" value="<?php echo $crs_id; ?>"  />
                                                                                        <input type="hidden" name="qid" id="qid" value="<?php echo $meta_data[0]['qpd_id']; ?>"  />

                                                                                        <!-- might be required in future -->
                                                                                        <!--<div class="navbar">
                                                                                        <div class="navbar-inner-custom">
                                                                                        Bloom's Level Planned vs. Coverage Distribution
                                                                                        </div>
                                                                                        
                                                                                        </div>
                                                                                        <div class="row-fluid">
                                                                                        <div class="span12">
                                                                                        <div class="span6">
                                                                                        <div id="chart1"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
                                                                                        </div>
                                                                                        
                                                                                        </div>
                                                                                        <div class="span6" style="overflow:auto;">
                                                                                        <br>
                                                                                        <table id="bloomslevelplannedcoveragedistribution" border=1 class="table table-bordered">
                                                                                        <thead></thead>
                                                                                        <tbody></tbody>
                                                                                        </table>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="span12">
                                                                                        <div id="bloomslevelplannedcoveragedistribution_note"></div>
                                                                                        </div>
                                                                                        </div>
                                                                                        
                                                                                        <br>-->

                                                                                        <div class="navbar">
                                                                                            <div class="navbar-inner-custom">
                                                                                                Bloom's Level Marks Distribution
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row-fluid">
                                                                                            <div class="span12">
                                                                                                <div class="span6">
                                                                                                    <div id="chart2"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="span6" style="overflow:auto;">
                                                                                                    <br>
                                                                                                    <table id="bloomslevelplannedmarksdistribution" border=1 class="table table-bordered">
                                                                                                        <thead></thead>
                                                                                                        <tbody></tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="span12">
                                                                                                <div id="bloomslevelplannedmarksdistribution_note"></div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <br>

                                                                                        <div class="navbar">
                                                                                            <div class="navbar-inner-custom">
                                                                                                Course Outcome Marks Distribution
                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row-fluid">
                                                                                            <div class="span12">
                                                                                                <div class="span6">
                                                                                                    <div id="chart3"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="span6" style="overflow:auto;">
                                                                                                    <br>
                                                                                                    <table id="coplannedcoveragesdistribution" border=1 class="table table-bordered">
                                                                                                        <thead></thead>
                                                                                                        <tbody></tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="span12">
                                                                                                <div id="coplannedcoveragesdistribution_note"></div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row">
                                                                                        </div>

                                                                                        </br>
                                                                                    </form>
                                                                                    <!-- Data ends here... -->
                                                                                </div>
                                                                            </div>
                                                                            <!-- /Equipamentos -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div><!-- /accordion -->
                                                        <!--
                                                        <div class="row-fluid">
                                                        <div class="span12">
                                                        <a href= "<?php echo base_url('question_paper/tee_qp_list'); ?>" id="cancel_button"><b class="btn btn-danger pull-right"><i class="icon-remove icon-white"></i><span></span> Close</b></a>
                                                        </div>
                                                        </div>-->
                                                    </div>

                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <!--==============================================================================================================================================================-->


                                    <?php
// Dynamic Loading of UNIT and Questions
                                    $unit_counter = 1;

                                    foreach ($qp_unit_data as $unit_details) {
                                        $counter = 'a';
                                    }
                                    ?>
                                    <div id="edit_qp" id="edit_qp" class="row-fluid modal hide fade local-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-left: -500px; width:1000px;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                        <div class="span12">
                                            <section id="contents">
                                                <div class="bs-docs-example ">
                                                    <div class="navbar">
                                                        <div class="navbar-inner-custom">
                                                            <div class="span12">
                                                                <div class="span6">
                                                                    <div id="nav_title"></div>
                                                                </div>
                                                                <div class="span6">
                                                                    <a href="http://math.typeit.org/" target="_blank" class="pull-right" style="text-decoration:none; font-size:12px; color:white;"> On-line Mathematical Editor </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <form name="add_question_details" id="add_question_details" method="POST" action="" >
                                                        <table id="main_question_table" class="qp_table" style="margin-bottom:auto;">
                                                            <tbody>
                                                                <tr id="main_que_row">
                                                                    <td>
                                                                    <th>Section / Parts (Units)<font class="font_color">*</font>&nbsp;:</th><th> &nbsp;&nbsp; <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="Select Section" data-original-title="" name="unit_list_1_1" id="unit_list_1_1" class="input-small unit_onchange required">
                                                                            <option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
                                                                            <?php foreach ($unit_data as $topic) {
                                                                                ?>
                                                                                <option value="<?php echo $topic['qpd_unitd_id']; ?>" abbr="<?php echo $topic['qp_unit_code']; ?>" ><?php echo $topic['qp_unit_code']; ?></option>
                                                                            <?php } ?>
                                                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                    </td>
                                                                    <td>
                                                                    <th>Main Q.No.<font class="font_color">*</font> &nbsp;:</th><th> &nbsp;&nbsp; <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  name="Q_No_1_1" id="Q_No_1_1" class="input-small required">
                                                                            <option value="" abbr="Select Main Q.No.">Select</option>
                                                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                    </td>
                                                                    <td>

                                                                    <th>Sub Q.No.<font class="font_color"></font>&nbsp;:</th><th> &nbsp;&nbsp; <select   onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  
                                                                                                                                                         name="sec_id2" id="sec_id2" class="input-small option_onchange">
                                                                            <option value="" abbr="Select Sub Q.No.">Select</option>
                                                                            <option>a</option>
                                                                            <option>b</option>
                                                                            <option>c</option>
                                                                            <option>d</option>
                                                                            <option>e</option>
                                                                            <option>f</option>
                                                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                    <td colspan="4" style="padding-bottom: 14px;"><a class="btn btn-success cursor_pointer" id="import_rev_assin_questions" data-crs_id = "<?php echo $crs_id; ?>"><i class="icon-download icon-white"></i>&nbsp;&nbsp;&nbsp;Import Question</a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div id="questions" class="span12" style="display:none; padding-right:10px; margin:0px;">
                                                        </div>
                                                        <table id="tabl"  style="margin:0px;" class="table table-bordered qp_table" style="border:1px" >
                                                            <?php
//Generating Main Questions with reference to the QP framework
                                                            $mq_counter = 1;
                                                            $sub_counter = 1;
                                                            ?>
                                                            <tbody>

                                                                <tr> 
                                                                    <td style="width:380px;">
                                                                        <table style="" align="">
                                                                            <tr>
                                                                                <td>
                                                                                    <label class="control-label" for=""><b><div class="span4"></div>Mandatory</b> :</label>
                                                                                <th><input type="checkbox" name="mandatory" id="mandatory" class="mandatory" title="Click check-box if it is a Mandatory Question" style="margin:0px;" /> </th>
                                                                                </td>
                                                                            </tr><?php echo""; ?>
                                                                            <tr>								
                                                                                <td>									
                                                                                    <label class="control-label" for=""><b><div class="span2"></div>Question No.</b><font color="red"><b>*</b></font> :</label>
                                                                                <th><input type="text" name="ques_nme" id="ques_nme" class="ques_nme input-small"  placeholder="Question No." readonly /></th>
                                                                                <!-- <input type="hidden" name="question_name_<?php echo $mq_counter; ?>_1" id="question_name" value="<?php echo 'Q_No_' . $mq_counter . '_' . $counter; ?>" class="input-mini question_name_<?php echo $mq_counter; ?>" readonly />-->
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                            foreach ($qp_entity as $qp_entity_config) {
                                                                                if ($qp_entity_config['entity_id'] == 11) {
                                                                                    ?>
                                                                                    <tr> 
                                                                                        <td id="co_attach_<?php echo $mq_counter; ?>_1">
                                                                                            <label class="control-label"><b><div class=""></div>Course Outcome</b><font class="font_color">*</font>:</label>
                                                                                        <th>
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_1_1[]" id="co_list_1_1"  class="input-small co_onchange  co_list_data required" multiple="multiple">
                                                                                                <?php foreach ($co_details as $co) { ?>
                                                                                                    <option value="<?php echo $co['clo_id']; ?>" title="<?php echo $co['clo_statement']; ?>" abbr="<?php echo $co['clo_statement']; ?>"><?php echo $co['clo_code']; ?>
                                                                                                    </option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr><?php }if ($qp_entity_config['entity_id'] == 6) { ?>
                                                                                    <tr> 
                                                                                        <td>
                                                                                            <label class="control-label" for=""><b><div class=""></div><?php echo $this->lang->line('student_outcome_full'); ?></b><font color="red"><b>*</b></font>:</label>
                                                                                        <th>
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_1_1[]" id="po_list_1_1" class="input-small required po_list_data" multiple="multiple">
                                                                                            </select>
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr><?php }if ($qp_entity_config['entity_id'] == 10) { ?>
                                                                                    <tr>
                                                                                        <td>								
                                                                                            <label class="control-label" for=""><b><div class="span6"></div>Topic</b><font color="red"><b>*</b></font> :</label>
                                                                                        <th> 
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_1_1" id="topic_list_1_1" class="input-small topic_list_data required" multiple="multiple">																		
                                                                                                <?php foreach ($topic_details as $topic) { ?>
                                                                                                    <option title="<?php echo $topic['topic_title']; ?>" value="<?php echo $topic['topic_id']; ?>" abbr="<?php echo $topic['topic_title']; ?>" ><?php echo character_limiter($topic['topic_title'], 10); ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr><?php }if ($qp_entity_config['entity_id'] == 23) { ?>

                                                                                    <tr> 
                                                                                        <td>							 
                                                                                            <label class="control-label" for=""><b><div class="span2"></div>Bloom's Level</b><font color="red"><b>*</b></font>:</label>
                                                                                        <th>							
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_1_1" id="bloom_list_1_1" class="input-small required bloom_list_data" multiple="multiple">									
                                                                                                <?php foreach ($bloom_data as $blevel) { ?>
                                                                                                    <option title="<?php echo $blevel['level'] . " || " . $blevel['learning'] . " || " . $blevel['description'] . " || " . $blevel['bloom_actionverbs']; ?>" value="<?php echo $blevel['bloom_id']; ?>" abbr="<?php echo $blevel['bloom_actionverbs']; ?>" ><?php echo $blevel['level']; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>							
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr><?php } if ($qp_entity_config['entity_id'] == 22) { ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <label class="control-label" for=""><b><div class=""></div>Performance Indicator</b>:</label>
                                                                                        <th> 
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_1_1[]" id="pi_code_1_1" class="input-small pi_code_data" multiple="multiple">
                                                                                                <?php foreach ($pi_list as $pi) { ?>
                                                                                                    <option value="<?php echo $pi['msr_id']; ?>" abbr="<?php echo $pi['msr_statement']; ?>" ><?php echo $pi['pi_codes']; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr><?php } if ($qp_entity_config['entity_id'] == 29) { ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <label class="control-label" for=""><b><div class=""></div>Question Type <font color="red"><b>*</b></font></b>:</label>
                                                                                        <th> 
                                                                                            <select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="qn_type_1_1[]" id="qn_type_1_1" class="input-small qn_type_data" multiple="multiple">
                                                                                                <?php foreach ($question_type as $question) { ?>
                                                                                                    <option value="<?php echo $question['mt_details_id']; ?>" abbr="<?php echo $question['mt_details_name']; ?>" ><?php echo $question['mt_details_name']; ?></option>
                                                                                                <?php } ?>
                                                                                            </select>
                                                                                        </th>
                                                                                        </td>
                                                                                    </tr>
                                                                                        <?php } ?>
                                                                           <?php }  ?>

                                                                            <tr>
                                                                                <td>
                                                                                    <label class="control-label" for="mark"><b><div class="span7"></div>Marks</b><font color="red"><b>*</b></font>:</label>
                                                                                <th><input type="text" name="mark" id="mark" class="text_align_right allownumericwithoutdecimal input-small required mq_marks greaterMax" style="margin:0px;"  />
                                                                                    <input type="hidden" name="max_qpmarks" id="max_qpmarks"/>
                                                                                    <input type="hidden" name="val_status" id="val_status"/></th>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                    <td><label class="control-label" for="" style="margin-right:350px;"><b>Enter Question in below textarea </b>:</label>								

                                                                        <textarea class="question_textarea" name="question_1_1" id="question_1_1" style="margin: 0px; width: 551px; height: 40px;" ></textarea>
                                                                    </td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                        <input type="hidden" name="total_counter" id="total_counter" value="<?php echo --$mq_counter; ?>" />
                                                        <input type="hidden" name="array_data" id="array_data" value="" />
                                                        <input type="hidden" name="unit_counter" id="unit_counter" value="<?php echo --$unit_counter; ?>" />
                                                        <input type="hidden" name="qpf_id" id="qpf_id" value="<?php
                                                        if (!empty($qp_unit_data[0]['qpf_id'])) {
                                                            echo $qp_unit_data[0]['qpf_id'];
                                                        }
                                                        ?>" />
                                                        <input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id; ?>" />
                                                        <input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id; ?>" />
                                                        <input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id; ?>" />
                                                        <input type="hidden" name="pgm_id" id="pgm_id" value="<?php echo $pgm_id; ?>" />
                                                        <input type="hidden" name="qpp_id" id="qpp_id" value="<?php echo $qpd_id ?>"/>
                                                        <input type="hidden" name="qp_mq_id" id="qp_mq_id" value=""/>
                                                        <input type="hidden" name="qpd_type" id="qpd_type" value="5"/>
                                                        <input type="hidden" name="FM" id="FM" value="<?php echo $unit_data[0]['FM']; ?>"/>
                                                        <input type="hidden" name="model_qp_existance" id="model_qp_existance" value="<?php echo $model_qp_existance; ?>"/>
                                                        <div class="pan12">
                                                            <div class="control-group">
                                                                <div class=" controls">
                                                                    <div class="form-inline pull-right">
                                                                        <label for="qp_max_marks"><b>&nbsp;&nbsp;&nbsp;Grand Total Marks</b></label>
                                                                        <input type="text" id="qp_max_marks" name="qp_max_marks" class="text_align_right allownumericwithoutdecimal input-mini" value="<?php echo $total_marks . "/" . $meta_data[0]['qpd_gt_marks']; ?>" readonly />
                                                                    </div>
                                                                </div>
                                                                <div class=" controls">
                                                                    <div class="form-inline pull-right">
                                                                        <label for="qp_max_marks"><b>Add Section / Parts (Units) Marks </b></label>
                                                                        <input type="text" id="unit_marks" name="unit_marks" class="text_align_right allownumericwithoutdecimal input-mini" value="" readonly />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><br/><br/>
                                                        <div class="row-fluid">
                                                            <div class="span12">

                                                                <div class="form-inline pull-right ">
                                                                <!--<button type="button" name="save_data" id="save_data" class="btn btn-primary"><i class="icon-ok icon-white"></i><span></span> Save</button>-->

                                                                    <button type="button" name="update" id="update" onclick="graph_load()" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
                                                                    <button type="button" name="save_main_que" id="save_main_que" onclick="graph_load()" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                                                                    <!--<a href= "<?php echo base_url('question_paper/manage_model_qp'); ?>" id="del"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>	-->
                                                                    <button id="modal_cancel" class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="form-inline ">  </div>
                                            </section>

                                        </div>
                                    </div>
                                    </div>

                                </form>

                                <!-- modal for adding the weightage. -->

                                <div id="myModal_mapping_weightage" class="modal hide fade mapping_weightage" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Edit Weightage
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div id="weight_add_data" class="weight_add_data">
                                            <form id="map_weight_add" class="map_weight_add" method="POST" action="" >
                                                <div id="co_weight_mapped_table" >

                                                </div>
                                                <br>
                                                <div id="po_weight_mapped_table" >
                                                </div>
                                                <input type="hidden" name="main_question_id" id="main_question_id" value="" />
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="save_weight" class="save_weight btn btn-primary"><i class="icon-file icon-white "></i>Update</button>
                                        <button type="button" id="cancel_weight" data-dismiss="modal" class="cancel_weight btn btn-danger"><i class="icon-white icon-remove"></i>Cancel</button>
                                    </div>
                                    </form>
                                </div>
                                <!--=======================================================================================================================================-->

                                <!--=======================================================================================================================================--->
                                <!--Do not place contents below this line-->

                                <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Delete Confirmation
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h4><center>	Are you sure you want to delete this question?<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                    </div>
                                    <div class="modal-footer">
                                    <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                                        <a class="btn btn-primary" data-dismiss="modal" id="btnYes" onclick="graph_load()"><i class="icon-ok icon-white"></i> Ok </a>
                                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button>
                                    </div>
                                </div>
                                <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Delete
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h4><center>	Are you sure you want to delete the record?<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                    </div>
                                    <div class="modal-footer">
                                    <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                                        <a class="btn btn-primary" data-dismiss="modal" id="delete_Unit_modal"><i class="icon-ok icon-white"></i> Ok </a>
                                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button>
                                    </div>
                                </div>	
                                <div id="myModal_suc" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Save
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h4><center>Your data Has been saved.<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                    </div>

                                    <div class="modal-footer">
                                        <a class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </a>

                                    </div>
                                </div>
                                <div id="myModal_fail" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Ooops..
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h4><center>Sorry. Data Already Exist.<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                    </div>

                                    <div class="modal-footer">
                                        <a class="btn btn-primary" data-dismiss="modal" id="" ><i class="icon-ok icon-white"></i> Ok </a>

                                    </div>
                                </div>
                                <div id="myModal_data_full" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Ooops..
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <h4><center>Unit Full<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                    </div>

                                    <div class="modal-footer">
                                        <a class="btn btn-primary" data-dismiss="modal" id="" ><i class="icon-ok icon-white"></i> Ok </a>

                                    </div>
                                </div>


                                <!---====================================================================================================================-->




                                <!--=======================================================================================================================================-->

                                <div id="rev_assin_question_list" class="modal hide fade modal-admin" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="flase"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                                                Review/Assignment Questions
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" >
                                        <input type="hidden" name="course_id_one" id="course_id_one" value=""/>
                                        <div id="radio_btn_div" class="div_border_one">
                                            <div class="span4">
                                                <input type="radio" name="question_type" id="question_type_1" class="question_type" value="1" checked="checked" style="margin-top:0px;"/>&nbsp; Review Questions

                                            </div>
                                            <div class="span4">
                                                <input type="radio" name="question_type" id="question_type_2" class="question_type" value="2" style="margin-top:0px;"/> &nbsp;Assignment Questions

                                            </div>
                                        </div></br>
                                        <div id="question_div" class="div_border">
                                            <div><b><u>Question List</u></b></div>
                                            <div id="question_list_div" class="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cancel btn btn-success" id="question_ok" ><i class="icon-download icon-white" > </i>&nbsp; Import</button>
                                        <button type="button" class="cancel btn btn-danger" data-dismiss="modal" id="import_cancel"><i class="icon-remove icon-white"> </i> Cancel</button>
                                    </div>
                                </div>


                                <!---place footer.php here -->
                                <?php $this->load->view('includes/footer'); ?>
                                <!---place js.php here -->
                                <?php $this->load->view('includes/js'); ?>

                                <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "></script>
                                <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "></script>
                                <script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/tee_qp_edit_re.js'); ?>" type="text/javascript"></script>
                                <script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/model_qp_edit.js'); ?>" type="text/javascript"></script>
                                <!---------------------------------------------------------Edit-page------------------------------------------------------------------------>
                                <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "></script>
                                <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "></script>
                                <script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/FM.js'); ?>" type="text/javascript"></script>

                                <script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
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
                                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
                                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
                                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
                                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
                                <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
                                <script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>


                                <script type="text/javascript">
                                            <!--
                                function toggle_visibility(id) {
                                                var e = document.getElementById(id);
                                                if (e.style.display == 'block')
                                                    e.style.display = 'none';
                                                else
                                                    e.style.display = 'block';
                                            }
                                            //-->
                                            var co_lang = "<?php echo$this->lang->line('entity_clo'); ?>";
                                            var entity_clo_full_singular = "<?php echo$this->lang->line('entity_clo_full_singular'); ?>";
                                            var entity_clo_full = "<?php echo$this->lang->line('entity_clo_full'); ?>";
                                </script>
