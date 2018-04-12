<?php
/**
 * Description          :	Generate Student Placement Details table in Crriculum Student performance view page.

 * Created		:	20-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<table class="table table-bordered" style="width:75%;">
    <tbody>
        <tr>
            <td>
                1.
            </td>
            <td>
                Number of students placed in
                Companies/ Government sectors : <font color="red"></font>
            </td>
            <td>
                <?php
                if ($stud_placement) {
                    if ($stud_placement[0]['stud_companies'] == 0) {
                        $stud_companies = "";
                    } else {
                        $stud_companies = $stud_placement[0]['stud_companies'];
                    }
                    ?>

                    <input type="text" id="stud_companies" name="stud_companies" class=" numbers text-right" value="<?php echo $stud_companies; ?>">
                <?php } else { ?>
                    <input type="text" id="stud_companies" name="stud_companies" class=" numbers text-right">
                <?php } ?>
            </td>
            <!--<td style="text-align:center" class="span2">               
            </td>-->
        </tr>
        <tr>
            <td>
                2.
            </td>
            <td>
                Number of students admitted 
                to higher studies : <font color="red"></font>
            </td>
            <td>
                <?php
                if ($stud_placement) {

                    if ($stud_placement[0]['stud_higher_studies'] == 0) {
                        $stud_higher_studies = "";
                    } else {
                        $stud_higher_studies = $stud_placement[0]['stud_higher_studies'];
                    }
                    ?>
                    <input type="text" id="stud_higher_studies" name="stud_higher_studies" class=" numbers text-right" value="<?php echo $stud_higher_studies; ?>">
                <?php } else { ?>
                    <input type="text" id="stud_higher_studies" name="stud_higher_studies" class=" numbers text-right">
                <?php } ?>
            </td>
            <!--<td style="text-align:center" class="span2">       
                <a href="#" id="view_higher_studies" class="view_higher_studies">Manage details</a>
            </td>-->
        </tr>
        <?php if ($pgm_type_id != 44) { ?>
            <tr>
                <td>
                    3
                </td>
                <td>
                    Number of students turned into
                    entrepreneur : <font color="red"></font>
                </td>
                <td>
                    <?php
                    if ($stud_placement) {

                        if ($stud_placement[0]['stud_entrepreneur'] == 0) {
                            $stud_entrepreneur = "";
                        } else {
                            $stud_entrepreneur = $stud_placement[0]['stud_entrepreneur'];
                        }
                        ?>
                        <input type="text" id="stud_entrepreneur" name="stud_entrepreneur" class=" numbers text-right" value="<?php echo $stud_entrepreneur ?>">
                    <?php } else { ?>
                        <input type="text" id="stud_entrepreneur" name="stud_entrepreneur" class=" numbers text-right">
                    <?php } ?>
                </td>
                <!--<td>                
                </td>-->
            </tr>   
        <?php } ?>
    </tbody>
</table>
<div class="modal hide fade" id="companies_visited_view" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="width:70%;left:40%; display:block;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Companies Visited
            </div>
        </div>
    </div>

    <div class="modal-body" id="companies_visited_tabel">
    </div>
    <div class="modal-footer">
    </div>
</div>
<div class="modal hide fade" id="higher_study_view" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="width:70%;left:40%; display:block;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Higher Study Details
            </div>
        </div>
    </div>

    <div class="modal-body" id="higher_studies_tabel">
    </div>
    <div class="modal-footer">
    </div>
</div>
<script>
    //function to view students higher studies details.
    $("#view_higher_studies").click(function () {
        var pgm_id = $.cookie('stud_perm_program');
        var crclm_id = $.cookie('stud_perm_curriculum');
        $('#loading').show();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id
        }
        $.ajax({type: 'POST',
            url: base_url + 'nba_sar/curriculum_student_info/stud_higher_studies_dropdowns',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $("#higher_studies_tabel").html(data['d1']);
                $("#higher_study_view").modal('show');
                display_higher_studies_modal();
                $('#loading').hide();
            }
        });
    });
</script>
<!-- End of file student_placement_table_vw.php 
                        Location: .nba_sar/modules/curriculum_student_info/student_placement_table_vw.php  -->