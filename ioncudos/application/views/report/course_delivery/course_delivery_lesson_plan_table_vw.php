<?php
/**
 * Description          :	Generates Course Delivery Report

 * Created		:	March 24th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 26-02-2016		Shayista Mulla			Hard code replaced by entity language.
  ------------------------------------------------------------------------------------------ */
?>

<div id="gene_table">
    <?php // Title of the Page  ?>
    <h4 style="text-align:right; font-size: 14px;"><?php echo $this->ion_auth->user()->row()->org_name->theory_iso_code; ?></h4>
    <h4 style="text-align:center; font-size: 16px;">Course Plan</h4>
    <table id="course_delivery_course_plan_table_view" name="course_delivery_course_plan_table_view" class="table table-bordered" style="width:100%">
        <tbody>
            <tr>
                <td width=400>
                    <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Semester: </b><b needAlt=1 class="font_h ul_class"><?php echo $term_detail[0]['term_name']; ?></b>
                </td>
                <td width=200>
                    <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Year: </b><b needAlt=1 class="font_h ul_class"><?php echo $term_detail[0]['academic_year']; ?></b>
                </td>
            </tr>

            <?php foreach ($course_details as $course_details) { ?>
                <tr>
                    <td width=400>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course Title: </b><b needAlt=1 class="font_h ul_class" style="font-weight:normal;"><?php echo $course_details['crs_title']; ?></b>
                    </td>
                    <td width=300>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course Code: </b><b needAlt=1 class="font_h ul_class" style="font-weight:normal;"><?php echo $course_details['crs_code']; ?></b>
                    </td>
                </tr>
                <tr>
                    <td width=400>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Total Contact Hours: </b><b needAlt=1 class="font_h ul_class" style="font-weight:normal;"><?php echo $course_details['contact_hours']; ?></b>
                    </td>
                    <td width=300>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Duration of <?php echo $this->lang->line('testIII'); ?>: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $course_details['see_duration']; ?> Hours</b>
                    </td>
                </tr>
                <tr>
                    <td width=400>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $this->lang->line('testIII'); ?> Marks: </b><b needAlt=1 class="font_h ul_class" style="font-weight:normal;"><?php echo $course_details['see_marks']; ?></b>
                    </td>
                    <td width=300>
                        <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $this->lang->line('testI'); ?> Marks: </b><b needAlt=1 class="font_h ul_class" style="font-weight:normal;"><?php echo $course_details['cie_marks']; ?></b>
                    </td>
                </tr>
                <?php foreach ($course_owner as $course_owner) { ?>
                    <tr>
                        <td width=400>
                            <b needAlt=1 class="h_class font_h ul_class" style="margin:0px; font-weight:normal;">Lesson Plan Author: </b>
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $course_owner['title'] . ' ' . ucfirst($course_owner['first_name']) . ' ' . ucfirst($course_owner['last_name']); ?>
                                <?php
                                if ($course_owner['co_crs_owner']) {
                                    echo ', ' . $course_owner['co_crs_owner'];
                                }
                                ?>
                            </b>
                        </td>
                        <td width=300>
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Last Modified Date: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo date('d-m-Y', strtotime($course_details['create_date'])); ?></b>
                        </td>
                    </tr>
                    <?php
                }
                foreach ($course_validator as $course_validator) {
                    ?>
                    <tr>
                        <td width=400 >
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Checked By: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $course_validator['title'] . ' ' . ucfirst($course_validator['first_name']) . ' ' . ucfirst($course_validator['last_name']); ?></b>
                        </td>
                        <td width=300>
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Last Reviewed Date: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo date('d-m-Y', strtotime($course_validator['last_date'])); ?></b>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <span style="display: block;"></span>
    <?php
    $count = 0;
    foreach ($course_prerequisites as $prerequisite) {
        ?>
        <?php if (!empty($prerequisite['predecessor_course']) && $count == 0) { ?>
            <h4>Prerequisites: </h4>
            <?php
            $count = 1;
        }
        ?>
        <?php if (!empty($prerequisite['predecessor_course'])) { ?>
            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $prerequisite['predecessor_course'] . ' '; ?></b>
        <?php } ?>	
    <?php } echo '<br/>' ?>
    <span style="display: block;"></span>
    <h4><?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>): </h4>
    <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">At the end of the course the student should be able to:</b>
    <span style="display: block;"></span>
    <ul breakIt=1 class="ul_class" style="list-style-type: none;">
        <?php $temp_clo = $this->lang->line('entity_clo_singular'); ?>
        <?php foreach ($course_learning_objectives as $co) { ?>
            <label><li class="remove_li" style="text-align: both;"><?php $sl_no = str_replace($temp_clo, "", $co['clo_code']); ?> 
                    <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $sl_no . '. ' . $co['clo_statement']; ?></b></li></label>
        <?php } ?>
    </ul>
    <?php //co to po mapping   ?>
    <h4 class="ul_class" style="text-align:center; font-size:14px;"> Course Articulation Matrix: Mapping of <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) with <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></h4>
    <?php
    $arr = array();
    $count = 0;
    foreach ($po_list as $po) {
        $arr[$count] = 0;
        $count++;
    }
    foreach ($course_list as $current_course) {
        ?>
        <?php foreach ($clo_list as $clo) { ?>
            <?php if ($current_course['crs_id'] == $clo['crs_id']) { ?>
                <?php
                $count = 0;
                foreach ($po_list as $po) {
                    ?>
                    <?php
                    $temp = '';
                    foreach ($clo_po_map_details as $clo_po_data) {
                        if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                            if ($temp != $clo_po_data['map_level']) {
                                $temp = $clo_po_data['map_level'];
                                $map_level = $clo_po_data['map_level'];
                                $arr[$count] = 1;
                            }
                        }
                    } $count++;
                    ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <table class="table table-bordered table-hover" style="width:100%">   
        <tbody>
            <tr>		
                <td width=500><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course Title: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $course_details['crs_title']; ?></b></td>
                <td width=300><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Semester: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $term_detail[0]['term_name']; ?></b></td>
            </tr>
            <tr>
                <td width=500><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course Code: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $course_details['crs_code']; ?></b></td>
                <td width=300><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Year: </b><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $term_detail[0]['academic_year']; ?></td>
            </tr>
        </tbody>
    </table>
    <table id="table_view_clo_po" name="table_view_clo_po" class="table table-bordered table-hover" style="width:100%">   
        <tbody>
            <tr>
                <th class="sorting1" rowspan="1" class="ul_class" colspan="2" width="950" style="width:40%;"><h5 class="ul_class"><?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) / <?php echo $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes'); ?></h5></th>

                <?php
                $count = 0;
                if ($status == 1) {
                    foreach ($po_list as $po) {
                        if ($arr[$count] == 1) {
                            ?>
                            <th class="sorting1" title="<?php echo $po['po_statement']; ?>" rowspan="1" colspan="1" width="200" style="text-align:center;" id="po_stmt"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                            <?php
                        }$count++;
                    }
                    ?>
                    <?php
                } else {
                    foreach ($po_list as $po) {
                        ?>
                        <th class="sorting1" title="<?php echo $po['po_statement']; ?>" rowspan="1"  width="200"colspan="1" style="text-align:center;" id="po_stmt"><h4 class="ul_class"><?php echo $po['po_reference']; ?></h4></th>
                        <?php
                    }
                }
                ?>
            </tr>
            <?php foreach ($course_list as $current_course) { ?>
                <?php foreach ($clo_list as $clo) { ?>
                    <?php if ($current_course['crs_id'] == $clo['crs_id']) { ?>
                        <tr>
                            <th width=950 colspan="2"  style="width:40%;">
                                <h5 class="h_class ul_class" style="font-weight:normal; font-size:12px;"><?php
                                    $sl_no = str_replace("CO", "", $clo['clo_code']);
                                    echo $sl_no . '. ' . $clo['clo_statement'];
                                    ?></h5>
                            </th>
                            <?php
                            if ($status == 1) {
                                $count = 0;
                                foreach ($po_list as $po) {
                                    if ($arr[$count] == 1) {
                                        ?>
                                        <th style="text-align:center;" colspan="1" width="200" class="text-center">
                                            <?php
                                            $temp = '';
                                            foreach ($clo_po_map_details as $clo_po_data) {
                                                if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                    if ($temp != $clo_po_data['map_level']) {
                                                        $temp = $clo_po_data['map_level'];
                                                        $map_level = $clo_po_data['map_level'];

                                                        switch ($map_level) {
                                                            case 2:
                                                                ?><?php echo "<h4 class='ul_class'>2</h4>"; ?>
                                                                <?php
                                                                break;
                                                            case 3:
                                                                ?><?php echo "<h4 class='ul_class'>3</h4>"; ?>
                                                                <?php
                                                                break;
                                                            default:
                                                                ?><?php echo "<h4 class='ul_class'>1</h4>"; ?>
                                                                <?php
                                                                break;
                                                        }
                                                    }
                                                }
                                            }
                                        } $count++;
                                        ?>
                                    </th>
                                    <?php
                                }
                            } else {
                                ?>
                                    <?php foreach ($po_list as $po) { ?>
                                    <th style="text-align:center;" colspan="1" width="200" class="text-center">
                                        <?php
                                        $temp = '';
                                        foreach ($clo_po_map_details as $clo_po_data) {
                                            if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
                                                if ($temp != $clo_po_data['map_level']) {
                                                    $temp = $clo_po_data['map_level'];
                                                    $map_level = $clo_po_data['map_level'];

                                                    switch ($map_level) {
                                                        case 2:
                                                            ?><?php echo "<h4 class='ul_class'>2</h4>"; ?>
                                                            <?php
                                                            break;

                                                        case 3:
                                                            ?><?php echo "<h4 class='ul_class'>3</h4>"; ?>
                                                            <?php
                                                            break;

                                                        default:
                                                            ?><?php echo "<h4 class='ul_class'>1</h4>"; ?>
                                                            <?php
                                                            break;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </th>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
<?php } ?>
        </tbody>
    </table>
    <!--<h4 breakIt=1 noTb=1 class="h_class" style="font-weight:normal;">Degree of compliance &nbsp;&nbsp;&nbsp; <span class="s_class" style="font-weight:bold">L</span>: Low &nbsp;&nbsp;&nbsp; <span class="s_class" style="font-weight:bold">M</span>: Medium &nbsp;&nbsp;&nbsp; <span class="s_class"style="font-weight:bold">H</span>: High</h4>-->
<?php if ($justification != null) { ?>
        <table class="table table-hover" style="width:100%; overflow:auto;">
            <tr style="border:0;"><td style="border:0;" width="800"><b>Justification:</b></td></tr>
            <td class="table-bordered"><?php echo($justification); ?></td>
        </table>
        <br><?php } ?>
    <?php // OE & PI Title of the Page  ?>
    <?php if ($org_result[0]['oe_pi_flag'] == 1) { ?>
        <?php
        $size_pi = sizeof($oe_pi);
        if ($size_pi != 0) {
            ?>
            <h4 style="text-align:center;"><?php echo $this->lang->line('outcome_element_sing_full'); ?> addressed in the Course and corresponding <?php echo $this->lang->line('measures_full'); ?> </h4>
            <table class="table table-bordered" style="width:100%">
                <tbody>
                    <tr>
                        <td width=400><h4 class="font_h ul_class" style=" margin:0px;text-align:center;"><?php echo $this->lang->line('outcome_element_sing_full'); ?></h4></td>
                        <td width=400><h4 class="font_h ul_class" style=" margin:0px;text-align:center;"><?php echo $this->lang->line('measures_full'); ?></h4></td>
                    </tr>
                    <?php
                    $temp = 0;
                    $counter_pi = 1;
                    $next_value = next($oe_pi);
                    foreach ($oe_pi as $data) {
                        ?>
                        <tr>
                            <?php
                            if ($temp != $data['pi_id']) {
                                $temp = $data['pi_id'];
                                $next_data = empty($next_value['pi_id']) ? '-1' : $next_value['pi_id'];
                                if ($temp == $next_data) {
                                    echo '<td class="bottom_border" width=400>';
                                } else {
                                    echo '<td width=400>';
                                }
                                echo '<h4 class="font_h ul_class h_class" style="font-weight:normal;">' . substr($data['pi_codes'], 0, -2) . ' - ' . $data['pi_statement'] . '</h4>';
                                echo '</td>';
                            } else {
                                if ($counter_pi == $size_pi) {
                                    echo '<td class="top_border" width=400></td>';
                                } else {
                                    echo '<td class="top_border bottom_border" width=400></td>';
                                }
                            }
                            ?>
                            <td width=400>
            <?php echo '<h4 class="font_h ul_class h_class" style="font-weight:normal;">' . $data['pi_codes'] . ' - ' . $data['msr_statement'] . '</h4>'; ?>
                            </td>
                        </tr>
                        <?php
                        $next_value = next($oe_pi);
                        $counter_pi++;
                    }
                    ?>
                </tbody>
            </table>
            <h4 breakIt=1 noTb= 1 class="h_class" style="font-weight:normal;"> Eg: 1.2.3: Represents <?php echo $this->lang->line('student_outcome_full'); ?> ‘1’, <?php echo $this->lang->line('outcome_element_sing_full'); ?> ‘2’ and <?php echo $this->lang->line('measures_full'); ?> ‘3’. </h4>
        <?php } ?>
    <?php } ?>
    <?php // Course Content     ?>
<?php // Title of the Page        ?>
    <br/><h4 style="text-align:center;">Course Content </h4>
    <table class="table table-bordered table-hover" style="width:100%">
        <tbody>
            <tr>
                <td width=400>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Course Code: <?php echo $course_details['crs_code']; ?></h4>
                </td>
                <td width=400 style="colspan: 2;"colspan="2">
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Course Title: <?php echo $course_details['crs_title']; ?></h4>
                </td>
            </tr>
            <tr>
                <td width=400>				
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">L-T-P-<?php echo $this->lang->line('testII'); ?>: <?php echo $course_details['lect_credits'] . '-' . $course_details['tutorial_credits'] . '-' . $course_details['practical_credits'] . '-' . $course_details['self_study_credits']; ?></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;"><?php echo $this->lang->line('credits'); ?>: <?php echo $course_details['total_credits']; ?></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Contact Hrs: <?php echo $course_details['contact_hours']; ?></h4>
                </td>
            </tr>
            <tr>
                <td width=400>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;"><?php echo $this->lang->line('testI'); ?> Marks: <?php echo $course_details['cie_marks']; ?></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;"><?php echo $this->lang->line('testIII'); ?> Marks: <?php echo $course_details['see_marks']; ?></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Total Marks: <?php echo $course_details['total_marks']; ?></h4>
                </td>
            </tr>
            <tr>
                <td width=400>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Teaching Hrs: <?php echo $course_details['contact_hours']; ?></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;"></h4>
                </td>
                <td width=200>
                    <h4 class="font_h h_class ul_class" style="font-weight: normal;">Exam Duration: <?php echo $course_details['see_duration']; ?> hrs</h4>
                </td>
            </tr>
        </tbody>
    </table>
    <table breakIt=1 noTb=1 class="table table-bordered table-hover" style="width:100%">
        <tbody>
            <tr>
                <td width="1000"><h4 style="text-align: center" class="ul_class" >Content</h4></td>
                <td width="100"><h4 class="ul_class" >Hrs</h4></td>
            </tr>
            <?php
            $unit = 1;
            $temp_unit = '';
            foreach ($topic_result_data as $topics) {
                ?>
    <?php if ($temp_unit != $topics['t_unit_id']) { ?>
                    <tr>
                        <td width="1000" class="right_border" style="text-align:center;">
                            <?php
                            echo '<h4 style="text-align: center"  class="ul_class"> Unit - ' . $unit . '</h4>';
                            $temp_unit = $topics['t_unit_id'];
                            $unit++;
                            ?>
                        </td>
                        <td width="100" class="left_border">
                            <h4 class="ul_class"></h4>
                        </td>
                    </tr>
                    <?php
                } else {
                    
                }
                ?>
                <tr>
                    <td width="1000">
                        <h4 class="ul_class">Chapter No. <?php echo " " . $topics['topic_title']; ?> </h4>
                        <h4 class="font_h h_class ul_class" style="font-weight:normal;"><?php echo $topics['topic_content']; ?></h4>
                    </td>
                    <td width=100 style="white-space:nowrap; text-align:center;">
                        <h4 class="h_class ul_class" style="font-weight:normal;"><?php echo $topics['topic_hrs']; ?> hrs </h4>
                    </td>
                </tr>
<?php } ?>
        </tbody>
    </table>
    <?php
    $flag = 0;
    $author = '';
    foreach ($text_books as $books) {
        $books_text = $books['book_type'];
        if ($books_text == 0) {
            $flag = 1;
            $author.= '<li style="list-style-type:none">' . $books['book_sl_no'] . '. ' . ((!empty($books['book_author'])) ? $books['book_author'] : '');
            $author.= (!empty($books['book_title'])) ? ', ' . $books['book_title'] : '';
            $author.= (!empty($books['book_edition'])) ? ', ' . $books['book_edition'] : '';
            $author.= (!empty($books['book_publication'])) ? ', ' . $books['book_publication'] : '';
            $author.= (!empty($books['book_publication_year'])) ? ', ' . $books['book_publication_year'] : '';
            $author.='</li>';
        }
    }
    ?>
    <?php
    $text_book_title = '<h4 style="color:green;" id="text_book_main">Text Books (List of books as mentioned in the approved syllabus) </h4>';
    if ($flag == 1) {
        $text_book_title.=$author;
        echo $text_book_title;
    }
    ?>

    <?php
    $flag = 0;
    $author = '';
    foreach ($text_books as $books) {
        $books_text = $books['book_type'];
        if ($books_text == 1) {
            $flag = 1;
            $author.= '<li style="list-style-type:none">' . $books['book_sl_no'] . '. ' . ((!empty($books['book_author'])) ? $books['book_author'] : '');
            $author.= (!empty($books['book_title'])) ? ', ' . $books['book_title'] : '';
            $author.= (!empty($books['book_edition'])) ? ', ' . $books['book_edition'] : '';
            $author.= (!empty($books['book_publication'])) ? ', ' . $books['book_publication'] : '';
            $author.= (!empty($books['book_publication_year'])) ? ', ' . $books['book_publication_year'] : '';
            $author.='</li>';
        }
    }
    ?>
    <?php
    $text_book_title = '<h4 style="color:green;" id="text_book_main">References</h4>';
    if ($flag == 1) {
        $text_book_title.=$author;
        echo $text_book_title;
    }
    ?>
    <?php // Evaluation Scheme Title of the Page     ?>
<?php if (!empty($assessment)) { ?>
        <h4 style="space-before: 600;text-align:center;">Evaluation Scheme </h4>
        <h4 class="pull-left"><?php echo $this->lang->line('testI'); ?> Scheme </h4>
        <table breakIt=1 noTb=1 id="table_view_cu" name="table_view_cu" class="table table-bordered table-hover" style="width:350px;">
            <tbody>
            <td width=200>
                <h4 class="font_h ul_class" style="text-align:center;">Assessment</h4>
            </td>
            <td width=250>
                <h4 class="font_h ul_class" style="text-align:center;">Weightage in Marks</h4>
            </td>
            <?php
            $total = 0;
            $assess_index = 0;
            foreach ($assessment as $assess) {
                ?>
                <tr>
                    <td width=200 style="text-align:center;">
                        <?php
                        echo '<h4 class="font_h h_class ul_class" style="text-align:center; font-weight:normal;">' . $assess['assessment_name'] . '</h4>';
                        $assessment_array[$assess_index++] = $assess['assessment_name'];
                        ?>
                    </td>

                    <td width=250 style="text-align:center;">
                        <?php
                        $total = $total + $assess['weightage_in_marks'];
                        echo '<h4 class="font_h h_class ul_class" style="text-align:center; font-weight:normal;">' . $assess['weightage_in_marks'] . '</h4>';
                        ?>
                    </td>
                </tr>
    <?php } ?>

            <tr>
                <td style="text-align:center;" width=200>
                    <h4 class="font_h ul_class"> Total </h4>
                </td>
                <td style="text-align:center;" width=250>
                    <h4 class="font_h ul_class" style="text-align:center;"><?php echo $total; ?></h4>
                </td>
            </tr>
            </tbody>
        </table>
        <h4 class="pull-left" style="text-align:center;"> Course Unitization for Minor Exams and Semester End Examination </h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th width=800 style="text-align:center;width:40%;"><h4 class="upper_class font_h ul_class" style="text-align:center; font-weight:normal;">Topics / Chapters</h4></th>
                    <th style="text-align:center;" width=80><h4 class="upper_class font_h ul_class" style="text-align:center; font-weight:normal;">Teaching <?php echo $this->lang->line('credits'); ?></h4></th>
                    <?php
                    $assess_index = 0;
                    //to find number of minors
                    $column_size = sizeof($crs_unitization);
                    $col_span = $column_size + 3;

                    for ($i = 1; $i <= $column_size; $i++) {
                        ?>
                        <th width=80 style="text-align:center;"><h4 class="font_h ul_class" style="text-align:center; font-weight:normal;">No. of Questions in <?php echo $assessment_array[$assess_index]; ?></h4></th>
                        <?php
                        $assess_index++;
                    }
                    ?>
                </tr>

                <?php
                $temp = 0;
                $counter = 0;

                foreach ($topic_result_data as $topic_data) {
                    if ($temp != $topic_data['t_unit_id']) {
                        $temp = $topic_data['t_unit_id'];
                        ?>

                        <tr>
                            <td colspan="<?php echo $col_span; ?>" gridSpan="<?php echo $col_span; ?>">
            <?php //to display unit numbers       ?>
                                <h4 class="ul_class" style="text-align:center; font-weight:normal;"><?php echo $topic_data['t_unit_name']; ?></h4>
                            </td>
                        </tr>
        <?php } ?>
                    <tr>
                        <td width=800 style="width:40%;"><h4 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $topic_data['topic_title']; ?></h4></td>
                        <td style="text-align:center;" width=80><h4 class="h_class font_h ul_class" style="text-align:center; font-weight:normal;"><?php echo $topic_data['topic_hrs']; ?></h4></td>
                        <?php
                        $count = 1;
                        foreach ($crs_unitization as $crs_unit) {
                            ?>
            <?php //to display number of questions in minors        ?>
                            <td style="text-align:center;" width=80>
                                <h4 class="h_class font_h ul_class" style="text-align:center; font-weight:normal;">
                                    <?php
                                    if (!empty($crs_unit[$counter]['no_of_questions'])) {
                                        if ($crs_unit[$counter]['no_of_questions'] != 0.00) {
                                            echo $crs_unit[$counter]['no_of_questions'];
                                        } else {
                                            echo '--';
                                        }
                                    } else {
                                        echo 0;
                                    }
                                    ?>
                                </h4>
                            </td>
                    <?php } ?>
                    </tr>
                    <?php
                    $counter++;
                }
                ?>
            </tbody>
        </table>
        <h4>Note </h4>
        <ol class="ul_class">
            <li class="ul_class">Each Question carries 20 marks and may consists of sub-questions.</li>
            <li class="ul_class">Mixing of sub-questions from different chapters within a unit (<span style="font-weight:bold;">only for Unit I and Unit II</span>) is allowed in Minor I, II and <?php echo $this->lang->line('testIII'); ?>. </li>
            <li class="ul_class">Answer 5 full questions of 20 marks each (<span style="font-weight:bold;">two full questions from Unit I, II and one full questions from Unit III</span>) out of 8 questions in <?php echo $this->lang->line('testIII'); ?>.</li>
        </ol>
        <br />
        <table breakIt=1 noTb=1 width="100%" style="borderColor: FFFFFF;">
            <tr style="borderColor: FFFFFF;">
                <td width=800 style="borderColor: FFFFFF;"><h4>Date:</h4></td> <td style="borderColor: FFFFFF;text-align:right;"  width=200><h4>Head of Department</h4></td>
            </tr>
        </table>
        <?php
    } else {
        echo '';
    }
    ?>
    <?php // Title of the Page    ?>
    <h4 style="text-align:center;"> Chapterwise Plan </h4>
    <?php
    $counter = 0;
    if ($topics_data_details) {
        ?>
    <?php foreach ($topics_data_details as $topics_considered) { ?>
            <table class="ul_class table table-bordered table-hover" id="course_delivery_course_content_table_view" name="table_view_course_content" style="width:100%">
                <tbody>
                    <tr>
                        <td width=900 colspan="3" gridSpan=4 >
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course Code and Title: </b><b needAlt=1 class="font_h ul_class"><?php
                                if (!empty($course_details['crs_code'])) {
                                    echo $course_details['crs_code'] . " / ";
                                    echo $course_details['crs_title'];
                                }
                                ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td width=700>
                            <b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Chapter Number and Title: </b><b class="font_h ul_class"><?php echo " " . $topics_considered['topic_title']; ?></b>
                        </td>
                        <td width=250 colspan=3 gridSpan=3 style="text-align:center;width:30%">
                            <b needAlt=1 class="h_class font_h ul_class"  style="font-weight:normal;">Planned Hours: </b><b class="font_h ul_class"><?php echo $topics_considered['topic_hrs']; ?> hrs </b>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
            $size = sizeof($tlo_bl_data[$counter]);
            if (!empty($size)) {
                ?>
                <h4>Learning Outcomes:- </h4>
                <h4>At the end of the topic the student should be able to:</h4>
                <table id="course_delivery_course_content_table_view" name="table_view_course_content" class="table table-bordered table-hover" style="width:100%">
                    <tbody>
                        <tr>
                            <td></td>
                            <td width=1000 style="text-align:center;">
                                <h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('entity_tlo_full'); ?></h4>
                            </td>
                            <td width=100 style="text-align:center;">
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;text-align:center;"><?php echo $this->lang->line('entity_clo'); ?> </h4>
                            </td>
                            <td width=100 style="text-align:center;">
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;text-align:center;">BL</h4>
                            </td>
            <?php if ($org_result[0]['oe_pi_flag'] == 1) { ?>
                                <td width=150 style="text-align:center;">
                                    <h4  class="h_class font_h ul_class" style="white-space:nowrap;text-align:center; font-weight:normal;">CA Code </h4>
                                </td>
            <?php } ?>
                        </tr>

                        <?php
                        $i = 1;
                        for ($k = 0; $k < $size; $k++) {
                            if ($topics_considered['topic_id'] == $tlo_bl_data[$counter][$k]['topic_id']) {
                                ?>
                                <tr>
                                    <?php
                                    //strip <p>, </p> tags generated in tinymce
                                    $tlo_statement = str_ireplace('<p>', '', $tlo_bl_data[$counter][$k]['tlo_statement']);
                                    $tlo_statement = str_ireplace('</p>', '', $tlo_statement);
                                    ?>
                                    <td><h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:right;"><?php echo $i; ?></h4></td>
                                    <td width=1000><h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:left;"><?php echo $tlo_statement; ?></h4></td>
                                    <td width=100 style="text-align:center;"><h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:center;"><?php echo $tlo_bl_data[$counter][$k]['clo_code']; ?></h4></td>
                                    <td  width=100 style="text-align:center;"><h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:center;"><?php echo $tlo_bl_data[$counter][$k]['bloom_lvel']; ?></h4></td>
                                            <?php if ($org_result[0]['oe_pi_flag'] == 1) { ?>
                                        <td width=150 style="text-align:center;">
                                            <h4 class="h_class font_h ul_class" style="font-weight:normal; text-align:center;">
                                                <?php
                                                $ca_code_array = explode('.', $tlo_bl_data[$counter][$k]['pi_codes']);
                                                if (!empty($ca_code_array[0]) && !empty($ca_code_array[1])) {
                                                    $ca_code = $ca_code_array[0] . '.' . $ca_code_array[1];
                                                    echo $ca_code;
                                                }
                                                ?>
                                            </h4>
                                        </td>
                                <?php } ?>
                                </tr>
                    <?php
                }
                $i++;
            }
            ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php
            $question_size = sizeof($question_data[$counter]);
            $review_break_it = $assign_break_it = $course_break_it = '';

            if (!empty($assignment_data[$counter][0]['assignment_content'])) {
                $assign_break_it = 'breakIt=1 noTb=1';
            } else {
                if (!empty($question_data[$counter])) {
                    $review_break_it = 'breakIt=1 noTb=1';
                } else {
                    $course_break_it = 'breakIt=1 noTb=1';
                }
            }
            ?>
            <h4 class="ul_class">Lesson Schedule </h4>
            <table <?php echo $course_break_it; ?> id="course_delivery_course_content_table_view" name="table_view_course_content" class="table table-bordered table-hover" style="width:100%">
                <tbody>
        <?php // lesson schedule     ?>
        <?php if (!empty($topics_considered['t_unit_id'])) { ?>
                        <tr>
                            <td width=1000 colspan="4" gridSpan=4>                    
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;">Lecture No. - Portion covered per hour </h4>
                            </td>
                            <td width=180>
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;">Planned Delivery Date</h4>
                            </td>
                            <td width=180>
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;">Actual Delivery Date</h4>
                            </td>
                        </tr>
                        <?php
                        foreach ($lesson_qn_data as $quest_data) {
                            foreach ($quest_data as $q_d) {
                                $temp_topic_id = $q_d['topic_id'];
                                if ($temp_topic_id == $topics_considered['topic_id']) {
                                    $schedule = explode("||", $q_d['schedule']);
                                    $portion_ref = explode("##", $q_d['portion_ref']);
                                    $conduction_date = explode("|$|", $q_d['conduction']);
                                    $actual_date = explode("|$|", $q_d['actual_date']);
                                    $portion_per_hour_size = sizeof($schedule);

                                    for ($i = 0; $i < $portion_per_hour_size; $i++) {
                                        ?>
                                        <tr>
                                            <td gridSpan=4 colspan=4>
                            <?php
                            if (!empty($portion_ref[$i])) {
                                echo '<h4 class="h_class font_h ul_class" style="font-weight:normal;">' . $portion_ref[$i] . '. ';
                            }
                            echo $schedule[$i];
                            ?>
                                                </h4>
                                            </td>
                                            <td>
                            <?php
                            if (!empty($conduction_date[$i])) {
                                echo '<h4 class="h_class font_h ul_class" style="font-weight:normal;">' . date('d-m-Y', strtotime($conduction_date[$i]));
                            }
                            ?>
                                            </td>

                                            <td>
                                        <?php
                                        if (!empty($actual_date[$i])) {
                                            echo '<h4 class="h_class font_h ul_class" style="font-weight:normal;">' . date('d-m-Y', strtotime($actual_date[$i]));
                                        }
                                        ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php break; ?>
                        <?php
                    }
                }
            }
        }
        ?>
                </tbody>
            </table>

                    <?php
                    $serial_num = 1;
                    if (!empty($question_data[$counter])) {
                        ?>
                <h4 class="ul_class">Review Questions</h4>
                <table <?php echo $review_break_it; ?> id="course_delivery_course_content_table_view" name="table_view_course_content" class="table table-bordered table-hover" style="width:100%">
                    <tbody>
            <?php // review questions       ?>
                        <tr>
                            <th width=1000>
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;">Sl.No. - Questions </h4>
                            </th>
                            <th width=110 style="text-align:center;">
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;"><?php echo $this->lang->line('entity_tlo'); ?></h4>
                            </th>
                            <th width=100 style="text-align:center;">
                                <h4 class="h_class font_h ul_class" style="font-weight:normal;">BL </h4>
                            </th>
                        <?php if ($org_result[0]['oe_pi_flag'] == 1) { ?>
                                <th width=150 style="text-align:center;">
                                    <h4 class="h_class font_h ul_class" style="white-space: nowrap; font-weight:normal;">PI Code </h4>
                                </th>
                        <?php } ?>
                        </tr>

                                <?php
                                for ($a = 0; $a < $question_size; $a++) {
                                    if ($topics_considered['topic_id'] == $question_data[$counter][$a]['topic_id']) {
                                        ?>
                                <tr>
                                    <td width=1000>
                    <?php
                    $review_quest = strip_tags($question_data[$counter][$a]['review_question'], '<img>');
                    $review_quest = $review_quest . ' ' . strip_tags($question_data[$counter][$a]['assignment_question'], '<img>');
                    ?>
                                        <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:both;"><?php echo $serial_num . '. ' . $review_quest; ?></h4>
                                    </td>
                                    <td width=110 style="text-align:center;"><h4 class="h_class font_h ul_class" style="text-align:center; font-weight:normal;"><?php echo $question_data[$counter][$a]['tlo_code']; ?></h4></td>
                                    <td width=100 style="text-align:center;"><h4 class="h_class font_h ul_class" style="text-align:center; font-weight:normal;"><?php echo $question_data[$counter][$a]['level']; ?></h4></td>
                                <?php if ($org_result[0]['oe_pi_flag'] == 1) { ?>
                                        <td  width=150 style="text-align:center;"><h4 class="h_class font_h ul_class" style="text-align:center; font-weight:normal;"><?php echo $question_data[$counter][$a]['pi_codes']; ?></h4></td>					
                                <?php } ?>
                                </tr>
                        <?php
                    }
                    $serial_num++;
                }
                ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php //assignment    ?>
        <?php
        $serial = 1;
        $assgn_size = sizeof($assignment_data[$counter]);
        if (!empty($assignment_data[$counter][0]['assignment_content'])) {
            ?>
                <h4 class="ul_class"><?php echo $assgn_title; ?> </h4>
                <table <?php echo $assign_break_it; ?> class="table table-bordered">
                    <tbody>
            <?php
            for ($a = 0; $a < $assgn_size; $a++) {
                if ($topics_considered['topic_id'] == $assignment_data[$counter][$a]['topic_id']) {
                    ?>
                                <tr>
                                    <td width=1000><h4 class="ul_class h_class font_h" style="font-weight:normal;"><?php echo $serial . '. ' . strip_tags($assignment_data[$counter][$a]['assignment_content'], '<img>'); ?></h4>
                                    </td>
                                </tr>
                        <?php
                    }
                    $serial++;
                }
                ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php
            $counter++;
        }
        ?>
    <?php } ?>
<?php //minor question paper    ?>
<?php if ($meta_data_cia) { ?>
    <?php
    $m = 0;
    foreach ($main_qp_data_cia as $mq_cia) {
        ?>
            <table class="table table-bordered" style="width:100%;">
                <tbody>
                    <tr>
                        <td style="text-align:center" colspan="3" gridSpan="4" width=800><h4 class="ul_class">Question Paper Title:
        <?php echo $meta_data_cia[$m]['qpd_title']; ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td width=180 style="text-align:center;">
                            <h4 class="ul_class">Total Duration (H:M): <?php echo $meta_data_cia[$m]['qpd_timing']; ?></h4>
                        </td>
                        <td width=280 style="text-align:center;">
                            <h4 class="ul_class">Course: <?php echo $meta_data_cia[$m]['crs_title'] ?> (<?php echo $meta_data_cia[$m]['crs_code'] ?>)</h4>
                        </td>	
                        <td width=320 style="text-align:center;width:33%;">
                            <h4 class="ul_class">Maximum Marks: <?php echo $meta_data_cia[$m]['qpd_max_marks']; ?></h4>
                        </td>
                    </tr>
                    <tr>	
                        <td gridSpan="4" style="text-align:center" colspan="3"><h4 class="ul_class">Note: <?php echo $meta_data_cia[$m]['qpd_notes']; ?></h4></td>
                    </tr>
                </tbody>
            </table>

        <?php
        $m++;
        $unit_counter = 1;
        foreach ($unit_cia_data as $unit_details) {
            if ($mq_cia[$unit_counter - 1]['qp_unitd_id'] == $unit_details[$unit_counter - 1]['qpd_unitd_id']) {
                ?>
                    <table breakIt=1 noTb=1 class="table table-bordered">
                        <tbody>
                            <tr>
                                <td width=150 style="white-space:nowrap;"><h4 style="font-weight:normal; text-align:center;">Q.No.</h4></td>
                                <td width=820><h4 style="font-weight:normal; text-align:center; width:80%;">Questions</h4></td>
                                <td width=100 style="width:5%;"><h4 style="font-weight:normal; text-align:center;">Marks</h4></td>
                                <?php
                                foreach ($entity_list as $qp_config) {
                                    if ($qp_config['entity_id'] == 11) {
                                        ?>
                                        <td width=150><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('entity_clo_singular'); ?></h4></td>
                                    <?php } ?>
                                    <?php if ($qp_config['entity_id'] == 6) { ?>
                                        <td width=160 style="width:5%;"><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('so'); ?></h4></td>
                                    <?php } ?>
                                    <?php if ($qp_config['entity_id'] == 10) { ?>
                                        <td width=100><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('entity_topic'); ?></h4></td>
                                    <?php } ?>
                                    <?php if ($qp_config['entity_id'] == 23) { ?>
                                        <td width=100 style="width:5%;"><h4 style="font-weight:normal; text-align:center;">BL </h4></td>
                                    <?php } ?>
                    <?php if ($qp_config['entity_id'] == 22 && $org_result[0]['oe_pi_flag'] == 1) { ?>
                                        <td width=100><h4 style="font-weight:normal; text-align:center;">PI Code</h4></td>
                                    <?php
                                }
                            }
                            ?>
                            </tr>

                            <?php
                            //Generating Main Questions with reference to the QP framework
                            $mq_counter = 0;
                            $sub_counter = 1;
                            $temp = '';
                            $po_count = 0;
                            $unit_code = "";
                            foreach ($mq_cia as $mq_data_cia) {
                                if ($unit_code != $mq_data_cia['qp_unit_code']) {
                                    ?>
                                    <tr>
                                        <td style="text-align:center" colspan="7" gridSpan="7" width=800>
                                            <h4 class="ul_class"><?php echo ($mq_data_cia['qp_unit_code']) ?></h4>
                                        </td>
                                    </tr>
                        <?php
                        $unit_code = $mq_data_cia['qp_unit_code'];
                    }
                    ?>
                                <tr>
                                    <td width=150 style="white-space:nowrap;"><h4 class="font_h h_class ul_class"><?php echo $mq_data_cia['qp_subq_code']; ?></h4></td>
                                    <td width=800 style="text-align: left; width:75%;">
                                        <h4 class="font_h h_class ul_class" style="text-align: both; font-weight:normal;"><?php echo $mq_data_cia['qp_content']; ?></h4>
                                        <?php
                                        $img_temp = '';

                                        if ($img_temp != $mq_data_cia['qp_mq_code']) {
                                            $img_temp = $mq_data_cia['qp_mq_code'];
                                            if (isset($mq_data_cia['image_name'])) {
                                                $image_names = explode(",", $mq_data_cia['image_name']);
                                                $img_size = sizeof($image_names);
                                                for ($img = 0; $img < $img_size; $img++) {
                                                    $img_counter = $img;
                                                    $img_counter++;

                                                    $thumb_div = '<span class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';

                                                    $thumb_div .= '<table class=""><tr><td>';
                                                    ?>

                                                    <?php
                                                    if (@getimagesize(base_url() . '/uploads/' . $image_names[$img])) {
                                                        list($width, $height, $type, $attr) = getimagesize(base_url() . '/uploads/' . $image_names[$img]);
                                                        $img_width = '350';
                                                        $img_height = '300';

                                                        if ($width > $img_width) {
                                                            $width = $img_width;
                                                        } else {
                                                            
                                                        }

                                                        if ($height > $img_height) {
                                                            $height = $img_height;
                                                        } else {
                                                            
                                                        }
                                                        ?>

                                                        <img width=<?php echo $width; ?> height=<?php echo $height; ?> src=<?php echo base_url() . '/uploads/' . $image_names[$img]; ?> class="img-rounded" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" />

                                                    <?php } else { ?>
                                                        <h4 class="ul_class h_class" style="text-align:center;color:#b94a48;">(Image not found)</h4>
                                                        <?php
                                                    }
                                                    $thumb_div .= '</td></tr></table></span><span class="img_margin"></span>';
                                                    echo $thumb_div;
                                                }
                                            }
                                        } else {
                                            if (isset($mq_data_cia['image_name'])) {
                                                $image_names = explode(",", $mq_data_cia['image_name']);
                                                $img_size = sizeof($image_names);
                                                for ($img = 0; $img < $img_size; $img++) {
                                                    $img_counter = $img;
                                                    $img_counter++;
                                                    $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                                                    $thumb_div .= '<table class=""><tr><td><img src="' . base_url() . '/uploads/' . $image_names[$img] . '" class="img-rounded img-thumbnail" />';

                                                    $thumb_div .= '</td></tr></table></div><div class="img_margin"></div><span style="display: block;"></span>';
                                                    echo $thumb_div;
                                                }
                                            }
                                        }
                                        ?>
                                    </td>												

                                    <td width=100 style="text-align:center;">
                                        <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                    <?php echo trim($mq_data_cia['qp_subq_marks']); ?>
                                        </h4>
                                    </td>

                                    <?php
                                    $mapping_data = explode(',', $mq_data_cia['mapping_data']);
                                    if (count($mapping_data) == 5) {
                                        if (isset($mapping_data[2])) {
                                            $co_id = explode(':', $mapping_data[2]);
                                            $co_map = explode('-', $co_id[1]);
                                        }
                                        if (isset($mapping_data[0])) {
                                            $po_id = explode(':', $mapping_data[0]);
                                            $po_map = explode('-', $po_id[1]);
                                        }
                                        if (isset($mapping_data[1])) {
                                            $topic_id = explode(':', $mapping_data[1]);
                                        }
                                        if (isset($mapping_data[4])) {
                                            $bloom_id = explode(':', $mapping_data[4]);
                                            $bloom_map = explode('-', $bloom_id[1]);
                                        }
                                        if (isset($mapping_data[3])) {
                                            $picode_data = explode(':', $mapping_data[3]);
                                        }
                                    } else {
                                        if (isset($mapping_data[2])) {
                                            $co_id = explode(':', $mapping_data[2]);
                                            $co_map = explode('-', $co_id[1]);
                                        }
                                        if (isset($mapping_data[0])) {
                                            $po_id = explode(':', $mapping_data[0]);
                                            $po_map = explode('-', $po_id[1]);
                                        }
                                        if (isset($mapping_data[1])) {
                                            $topic_id = explode(':', $mapping_data[1]);
                                        }
                                        if (isset($mapping_data[3])) {
                                            $bloom_id = explode(':', $mapping_data[3]);
                                            $bloom_map = explode('-', $bloom_id[1]);
                                        }
                                    }

                                    foreach ($entity_list as $qp_entity_config) {
                                        if ($qp_entity_config['entity_id'] == 11) {
                                            ?>

                                            <td width=150>
                                                <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                    <?php
                                                    foreach ($co_data_cia as $co) {
                                                        for ($i = 0; $i < count($co_map); $i++) {
                                                            if ($co['clo_id'] == $co_map[$i]) {
                                                                echo trim($co['clo_code'] . ", ");
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </h4>
                                            </td>
                        <?php } ?>

                                        <?php if ($qp_entity_config['entity_id'] == 6) { ?>
                                            <td width=160>
                                                <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                            <?php echo($po_data[$m - 1][$po_count]); ?>
                                                </h4>
                                            </td>
                                                <?php } ?>

                                                <?php if ($qp_entity_config['entity_id'] == 10) { ?>
                                            <td width=100>
                                                <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                    <?php
                                                    foreach ($topic_data_cia as $topic) {
                                                        if ($topic['topic_id'] == $topic_id[1]) {
                                                            echo trim($topic['topic_title']);
                                                        }
                                                    }
                                                    ?>																
                                                </h4>
                                            </td>
                                                <?php } ?>

                                                <?php if ($qp_entity_config['entity_id'] == 23) { ?>
                                            <td width=100>
                                                <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                    <?php
                                                    foreach ($bloom_data_cia as $blevel) {
                                                        for ($i = 0; $i < count($bloom_map); $i++) {
                                                            if ($blevel['bloom_id'] == $bloom_map[$i]) {
                                                                echo trim($blevel['level'] . ", ");
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </h4>
                                            </td>
                                                <?php } ?>

                                                <?php if ($qp_entity_config['entity_id'] == 22 && $org_result[0]['oe_pi_flag'] == 1) { ?>
                                            <td width=100>
                                                <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                    <?php
                                                    foreach ($pi_list_cia as $pi_data) {
                                                        if (!empty($picode_data[1]) && $org_result[0]['oe_pi_flag'] == 1) {
                                                            if ($pi_data['msr_id'] == $picode_data[1]) {
                                                                echo trim($pi_data['pi_codes']);
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </h4>
                                            </td>
                                        <?php
                                    }
                                }
                                ?>
                                </tr>
                        <?php
                        $po_count++;
                    }
                    ?>
                        </tbody>
                    </table>
                    <?php
                }
            }
            ?>
    <?php } ?>
                        <?php } ?>
                        <?php // CIA model paper ends       ?>

<?php //see question paper       ?>
<?php if ($meta_data) { ?>
        <table class="table table-bordered" style="width:100%;">
            <tbody>
                <tr>
                    <td style="text-align:center" colspan="3" gridSpan="4" width=800><h4 class="ul_class">Question Paper Title:
    <?php echo $meta_data[0]['qpd_title']; ?></h4>
                    </td>
                </tr>
                <tr>
                    <td width=180 style="text-align:center;">
                        <h4 class="ul_class">Total Duration (H:M): <?php echo $meta_data[0]['qpd_timing']; ?></h4>
                    </td>
                    <td width=280 style="text-align:center;">
                        <h4 class="ul_class">Course: <?php echo $meta_data[0]['crs_title'] ?> (<?php echo $meta_data[0]['crs_code'] ?>)</h4>
                    </td>	
                    <td width=320 style="text-align:center;width:33%;"><h4 class="ul_class">Maximum Marks: <?php echo $meta_data[0]['qpd_max_marks']; ?></h4></td>
                </tr>
                <tr>	
                    <td gridSpan="4" style="text-align:center" colspan="3"><h4 class="ul_class">Note: <?php echo $meta_data[0]['qpd_notes']; ?></h4></td>
                </tr>
            </tbody>
        </table>

                            <?php
                            $unit_counter = 1;
                            foreach ($unit_def_data as $unit_details) {
                                ?>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td gridSpan="7" style="text-align:center" colspan="3" width=800>
                            <h4 class="ul_class">
                        <?php echo $unit_details['qp_unit_code']; ?>
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td width=150 style="white-space:nowrap;width:5%;"><h4 style="font-weight:normal; text-align:center;">Q.No.</h4></td>
                        <td width=800><h4 style="font-weight:normal; text-align:center;width:80%;">Questions</h4></td>
                        <td width=100 style="width:5%;"><h4 style="font-weight:normal; text-align:center;">Marks</h4></td>
                        <?php
                        foreach ($entity_list as $qp_config) {

                            if ($qp_config['entity_id'] == 11) {
                                ?>
                                <td width=150 style="width:5%;"><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('entity_clo_singular'); ?></h4></td>
                            <?php } ?>
                            <?php if ($qp_config['entity_id'] == 6) { ?>
                                <td width=160 style="width:5%;"><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('so'); ?></h4></td>
                            <?php } ?>
                            <?php if ($qp_config['entity_id'] == 10) { ?>
                                <td width=100 ><h4 style="font-weight:normal; text-align:center;"><?php echo $this->lang->line('entity_topic'); ?></h4></td>
                            <?php } ?>
                            <?php if ($qp_config['entity_id'] == 23) { ?>
                                <td width=100 style="width:5%;"><h4 style="font-weight:normal; text-align:center;">BL</h4></td>
            <?php } ?>

                        <?php if ($qp_config['entity_id'] == 22 && $org_result[0]['oe_pi_flag'] == 1) { ?>
                                <td width=100 ><h4 style="font-weight:normal; text-align:center;">PI Code</h4></td>
                            <?php
                        }
                    }
                    ?>
                    </tr>

                    <?php
                    //Generating Main Questions with reference to the QP framework
                    $mq_counter = 0;
                    $sub_counter = 1;
                    $temp = '';

                    foreach ($main_qp_data as $mq_data) {
                        if ($temp != $mq_data['qp_mq_code']) {
                            $temp = $mq_data['qp_mq_code'];
                            $mq_counter++;
                            $sub_counter = 1;
                            $alpha_counter = 'a';

                            $ref_div = '';
                        } else {
                            $temp_main = '';
                            $sub_counter++;
                            $alpha_counter++;
                            $temp_alpha = $alpha_counter;
                        }

                        if ($mq_data['qp_unitd_id'] == $unit_details['qpd_unitd_id']) {
                            ?>
                            <tr>
                                <td width=150 style="white-space:nowrap;text-align:center;"><h4 class="font_h h_class ul_class"><?php echo $mq_data['qp_subq_code']; ?></h4></td>
                                <td width=800 style="text-align:left;width:75%;">
                                    <h4 class="font_h h_class ul_class" style="text-align: both; font-weight:normal;"><?php echo $mq_data['qp_content']; ?></h4>
                                    <?php
                                    $img_temp = '';

                                    if ($img_temp != $mq_data['qp_mq_code']) {
                                        $img_temp = $mq_data['qp_mq_code'];
                                        if (isset($mq_data['image_name'])) {
                                            $image_names = explode(",", $mq_data['image_name']);
                                            $img_size = sizeof($image_names);

                                            for ($img = 0; $img < $img_size; $img++) {
                                                $img_counter = $img;
                                                $img_counter++;
                                                $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                                                $thumb_div .= '<table class=""><tr><td>';
                                                ?>

                                                <?php
                                                if (@getimagesize(base_url() . '/uploads/' . $image_names[$img])) {
                                                    list($width, $height, $type, $attr) = getimagesize(base_url() . '/uploads/' . $image_names[$img]);

                                                    $img_width = '350';
                                                    $img_height = '300';

                                                    if ($width > $img_width) {
                                                        $width = $img_width;
                                                    } else {
                                                        
                                                    }

                                                    if ($height > $img_height) {
                                                        $height = $img_height;
                                                    } else {
                                                        
                                                    }
                                                    ?>

                                                    <img src=<?php echo base_url() . '/uploads/' . $image_names[$img]; ?> class="img-rounded" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" />
                                                <?php } else { ?>
                                                    <div style="text-align:center;color:#b94a48;">(Image not found)</div>
                                                    <?php
                                                }
                                                $thumb_div .= '</td></tr></table></div><div class="img_margin"></div><span style="display: block;"></span>';
                                                echo $thumb_div;
                                            }
                                        }
                                    } else {
                                        if (isset($mq_data['image_name'])) {
                                            $image_names = explode(",", $mq_data['image_name']);
                                            $img_size = sizeof($image_names);
                                            for ($img = 0; $img < $img_size; $img++) {
                                                $img_counter = $img;
                                                $img_counter++;
                                                $thumb_div = '<div class="controls span1" id="img_thmb_' . $mq_counter . '_' . $sub_counter . '_' . $img_counter . '">';
                                                $thumb_div .= '<table class=""><tr><td><img src="' . base_url() . '/uploads/' . $image_names[$img] . '" class="img-rounded img-thumbnail" />';
                                                $thumb_div .= '</td></tr></table></div><div class="img_margin"></div><span style="display: block;"></span>';
                                                echo $thumb_div;
                                            }
                                        }
                                    }
                                    ?>
                                </td>

                                <td width=100 style="text-align:center;">
                                    <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                <?php echo $mq_data['qp_subq_marks']; ?>
                                    </h4>
                                </td>

                                <?php
                                $mapping_data = explode(',', $mq_data['mapping_data']);
                                $bloom_map = '';
                                if (count($mapping_data) == 5) {
                                    if (isset($mapping_data[2])) {
                                        $co_id = explode(':', $mapping_data[2]);
                                        $co_map = explode('-', $co_id[1]);
                                    }
                                    if (isset($mapping_data[0])) {
                                        $po_id = explode(':', $mapping_data[0]);
                                        $po_map = explode('-', $po_id[1]);
                                    }
                                    if (isset($mapping_data[1])) {
                                        $topic_id = explode(':', $mapping_data[1]);
                                    }
                                    if (isset($mapping_data[4])) {
                                        $bloom_id = explode(':', $mapping_data[4]);
                                        $bloom_map = explode('-', $bloom_id[1]);
                                    }
                                    if (isset($mapping_data[3])) {
                                        $picode_data = explode(':', $mapping_data[3]);
                                    }
                                } else {

                                    if (isset($mapping_data[2])) {
                                        $co_id = explode(':', $mapping_data[2]);
                                        $co_map = explode('-', $co_id[1]);
                                    }
                                    if (isset($mapping_data[0])) {
                                        $po_id = explode(':', $mapping_data[0]);
                                        $po_map = explode('-', $po_id[1]);
                                    }
                                    if (isset($mapping_data[1])) {
                                        $topic_id = explode(':', $mapping_data[1]);
                                    }
                                    if (isset($mapping_data[3])) {
                                        $bloom_id = explode(':', $mapping_data[3]);
                                        $bloom_map = explode('-', $bloom_id[1]);
                                    }
                                }
                                foreach ($entity_list as $qp_entity_config) {
                                    if ($qp_entity_config['entity_id'] == 11) {
                                        ?>
                                        <td width=150>
                                            <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                <?php
                                                foreach ($co_data_cia as $co) {
                                                    for ($i = 0; $i < count($co_map); $i++) {
                                                        if ($co['clo_id'] == $co_map[$i]) {
                                                            echo trim($co['clo_code'] . ", ");
                                                            break;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </h4>
                                        </td>

                                    <?php } ?>

                                    <?php if ($qp_entity_config['entity_id'] == 6) { ?>
                                        <td width=160>
                                            <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                <?php echo $po_data_val[$unit_counter - 1]; ?>
                                            </h4>
                                        </td>
                                            <?php } ?>

                                            <?php if ($qp_entity_config['entity_id'] == 10) { ?>
                                        <td width=100>
                                            <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                        <?php
                                        foreach ($topic_data_cia as $topic) {
                                            if ($topic['topic_id'] == $topic_id[1]) {
                                                echo trim($topic['topic_title']);
                                            }
                                        }
                                        ?>																
                                            </h4>
                                        </td>
                                            <?php } ?>

                                            <?php if ($qp_entity_config['entity_id'] == 23) { ?>
                                        <td width=100>
                                            <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                <?php
                                                if (!empty($bloom_data_cia)) {
                                                    foreach ($bloom_data_cia as $blevel) {
                                                        for ($i = 0; $i < count($bloom_map); $i++) {
                                                            if ($blevel['bloom_id'] == @$bloom_map[$i]) {
                                                                echo trim($blevel['level'] . ", ");
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </h4>
                                        </td>
                                            <?php } ?>

                                            <?php if ($qp_entity_config['entity_id'] == 22 && $org_result[0]['oe_pi_flag'] == 1) { ?>
                                        <td width=100>
                                            <h4 class="font_h h_class ul_class" style="font-weight:normal; text-align:center;">
                                                <?php
                                                foreach ($pi_list_cia as $pi_data) {
                                                    if (!empty($picode_data[1]) && $org_result[0]['oe_pi_flag'] == 1) {
                                                        if ($pi_data['msr_id'] == $picode_data[1]) {
                                                            echo trim($pi_data['pi_codes']);
                                                        }
                                                    }
                                                }
                                                ?>
                                            </h4>
                                        </td>
                                    <?php
                                }
                            }
                            ?>
                            </tr>
                    <?php
                }
            }
            ?>
                </tbody>
            </table>
        <?php
        $unit_counter++;
    }
    ?>
<?php } ?>
</div>

