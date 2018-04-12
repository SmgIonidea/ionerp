<?php
/**
 * Description          :	Generate Student intake Details table in Crriculum Student performance view page.

 * Created		:	20-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                       Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<?php if ($pgm_type_id == 44) { ?>
    <table class="table table-bordered" style="width:85%;">
        <tbody>
            <tr>
                <td>
                    1.
                </td>
                <td>
                    Sanctioned intake : <font color="red">*</font>
                </td>
                <td>
                    <?php if ($student_intake) { ?>
                        <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_intake']; ?>">
                        <input type="hidden" id="save_flag" name="save_flag" value="0">
                    <?php } else { ?>
                        <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right">
                        <input type="hidden" id="save_flag" name="save_flag" value="1">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>
                    2.
                </td>
                <td>
                    Total number of students, admitted through 
                    state level counseling : <font color="red">*</font>
                </td>
                <td>
                    <input type="text" id="stud_admitted_counselling" name="stud_admitted_counselling" class="required numbers text-right" value="<?php echo @$student_intake[0]['stud_admitted_counselling']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    3.
                </td>
                <td>
                    Number of students, admitted through 
                    institute level quota : <font color="red">*</font>
                </td>
                <td>
                    <input type="text" id="stud_admitted_quota" name="stud_admitted_quota" class="required numbers text-right" value="<?php echo @$student_intake[0]['stud_admitted_quota']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    4.
                </td>
                <td>
                    Number of students from 
                    lateral entry : <font color="red">*</font>
                </td>
                <td>
                    <?php if ($student_intake) { ?>
                        <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_lateral']; ?>">
                    <?php } else { ?>
                        <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>
                    5.
                </td>
                <td>
                    Rank range :
                </td>
                <td>
                    <table>
                        <tr>
                            <?php if ($student_intake) { ?>
                                <td style="text-align:center;border:0;"> From </td> 
                                <td style="border:0;">
                                    <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_from']; ?>">
                                </td>
                                <td style="text-align:center;border:0;"> To </td> 
                                <td style="border:0;">
                                    <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_to']; ?>">
                                </td>
                            <?php } else { ?>
                                <td style="text-align:center;border:0;"> From </td>
                                <td style="border:0;">
                                    <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right">
                                </td>
                                <td style="text-align:center;border:0;"> To </td> 
                                <td style="border:0;">
                                    <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right">
                                </td>
                            <?php } ?>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php } else if ($pgm_type_id == 45) { ?> 
        <table class="table table-bordered" style="width:72%;">
            <tbody>
                <tr>
                    <td>
                        1.
                    </td>
                    <td>
                        Sanctioned intake : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_intake']; ?>">
                            <input type="hidden" id="save_flag" name="save_flag" value="0">
                        <?php } else { ?>
                            <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right">
                            <input type="hidden" id="save_flag" name="save_flag" value="1">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        2.
                    </td>
                    <td >
                        Total number of students
                        admitted in first year : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_admitted" name="stud_admitted" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_admitted']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_admitted" name="stud_admitted" class="required numbers text-right">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        3.
                    </td>
                    <td>
                        Number of students from 
                        lateral entry : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_lateral']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        4.
                    </td>
                    <td>
                        Rank range :
                    </td>
                    <td>
                        <table>
                            <tr>
                                <?php if ($student_intake) { ?>
                                    <td style="text-align:center;border:0;"> From </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_from']; ?>">
                                    </td>
                                    <td style="text-align:center;border:0;"> To </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_to']; ?>">
                                    </td>
                                <?php } else { ?>
                                    <td style="text-align:center;border:0;"> From </td>
                                    <td style="border:0;">
                                        <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right">
                                    </td>
                                    <td style="text-align:center;border:0;"> To </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right">
                                    </td>
                                <?php } ?>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <table class="table table-bordered" style="width:72%;">
            <tbody>
                <tr>
                    <td>
                        1.
                    </td>
                    <td>
                        Sanctioned intake : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_intake']; ?>">
                            <input type="hidden" id="save_flag" name="save_flag" value="0">
                        <?php } else { ?>
                            <input type="text" id="stud_intake" name="stud_intake" class="required numbers text-right">
                            <input type="hidden" id="save_flag" name="save_flag" value="1">
                        <?php } ?>
                    </td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td>
                        2.
                    </td>
                    <td >
                        Total number of students
                        admitted in first year : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_admitted" name="stud_admitted" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_admitted']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_admitted" name="stud_admitted" class="required numbers text-right">
                        <?php } ?>
                    </td>
                    <!--<td style="text-align:center" class="span2">
                        <a href="#" class="view_details" id="view_details">Manage details</a>
                    </td>-->
                </tr>
                <tr>
                    <td>
                        3.
                    </td>
                    <td>
                        Number of students migrated 
                        to other programs : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_migrated_other_pgm" name="stud_migrated_other_pgm" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_migrated_other_pgm']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_migrated_other_pgm" name="stud_migrated_other_pgm" class="required numbers text-right">
                        <?php } ?>
                    </td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td>
                        4.
                    </td>
                    <td>
                        Number of students migrated 
                        to this program : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_migrated_this_pgm" name="stud_migrated_this_pgm" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_migrated_this_pgm']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_migrated_this_pgm" name="stud_migrated_this_pgm" class="required numbers text-right">
                        <?php } ?>
                    </td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td>
                        5.
                    </td>
                    <td>
                        Number of students from 
                        lateral entry : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_lateral']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_lateral" name="stud_lateral" class="required numbers text-right">
                        <?php } ?>
                    </td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td>
                        6.
                    </td>
                    <td>
                        Separate division students : <font color="red">*</font>
                    </td>
                    <td>
                        <?php if ($student_intake) { ?>
                            <input type="text" id="stud_division" name="stud_division" class="required numbers text-right" value="<?php echo $student_intake[0]['stud_division']; ?>">
                        <?php } else { ?>
                            <input type="text" id="stud_division" name="stud_division" class="required numbers text-right">
                        <?php } ?>
                    </td>
                    <!--<td></td>-->
                </tr>
                <tr>
                    <td>
                        7.
                    </td>
                    <td>
                        Rank range :
                    </td>
                    <td>
                        <table>
                            <tr>
                                <?php if ($student_intake) { ?>
                                    <td style="text-align:center;border:0;"> From </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_from']; ?>">
                                    </td>
                                    <td style="text-align:center;border:0;"> To </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right" value="<?php echo $student_intake[0]['rank_to']; ?>">
                                    </td>
                                <?php } else { ?>
                                    <td style="text-align:center;border:0;"> From </td>
                                    <td style="border:0;">
                                        <input type="text" id="rank_from" name="rank_from" class="numbers input-small text-right">
                                    </td>
                                    <td style="text-align:center;border:0;"> To </td> 
                                    <td style="border:0;">
                                        <input type="text" id="rank_to" name="rank_to" class="numbers input-small text-right">
                                    </td>
                                <?php } ?>
                            </tr>
                        </table>
                    </td>
                    <!--<td></td>-->
                </tr>
            </tbody>
        </table>
    <?php } ?>
    <div class="modal hide fade" id="admitted_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="width:60%;left:40%; display:block;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Students Admitted Details
                </div>
            </div>
        </div>
        <div class="modal-body">
            <input id="student_admitted_id" name="student_admitted_id" type="hidden">
            <input id="delete_id" name="delete_id" type="hidden"> 
            <div id="student_admited_tables">

            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
    <script>
        //function to view students admitted details list with Add,Edit,Delete functionality.
        $("#view_details").click(function () {
            var pgm_id = $.cookie('stud_perm_program');
            var crclm_id = $.cookie('stud_perm_curriculum');
            $('#loading').show();
            var post_data = {
                'pgm_id': pgm_id,
                'crclm_id': crclm_id
            }
            $.ajax({type: 'POST',
                url: base_url + 'nba_sar/curriculum_student_info/stud_admitted_dropdowns',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    $("#student_admited_tables").html(data['d1']);
                    $("#admitted_modal").modal('show');
                    display_admitted_modal();
                    $('#loading').hide();
                }
            });
        });
    </script>
    <!-- End of file student_intake_table_vw.php 
                            Location: .nba_sar/modules/curriculum_student_info/student_intake_table_vw.php  -->