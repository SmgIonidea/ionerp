<?php
/**
 * Description          :	Generate Student Placement Details table in Crriculum Student performance view page.

 * Created		:	20-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<table class="table table-bordered" style="width:90%;">
    <tbody>
        <tr>
            <td>
                1.
            </td>
            <td>
                Number of students placed in
                Companies/ Government sectors : <font color="red">*</font>
            </td>
            <td>
                <?php if ($stud_placement) { ?>
                    <input type="text" id="stud_companies" name="stud_companies" class="required numbers text-right" value="<?php echo $stud_placement[0]['stud_companies'] ?>">
                <?php } else { ?>
                    <input type="text" id="stud_companies" name="stud_companies" class="required numbers text-right">
                <?php } ?>
            </td>
            <td style="text-align:center" class="span2">
                <a href="#" id="view_companies_visited" class="view_companies_visited">View details</a>
            </td>
        </tr>
        <tr>
            <td>
                2.
            </td>
            <td>
                Number of students admitted 
                to higher studies : <font color="red">*</font>
            </td>
            <td>
                <?php if ($stud_placement) { ?>
                    <input type="text" id="stud_higher_studies" name="stud_higher_studies" class="required numbers text-right" value="<?php echo $stud_placement[0]['stud_higher_studies'] ?>">
                <?php } else { ?>
                    <input type="text" id="stud_higher_studies" name="stud_higher_studies" class="required numbers text-right">
                <?php } ?>
            </td>
            <td style="text-align:center" class="span2">       
                <a href="#" id="view_higher_studies" class="view_higher_studies">View details</a>
            </td>
        </tr>
        <tr>
            <td>
                3
            </td>
            <td>
                Number of students turned into
                entrepreneur : <font color="red">*</font>
            </td>
            <td>
                <?php if ($stud_placement) { ?>
                    <input type="text" id="stud_entrepreneur" name="stud_entrepreneur" class="required numbers text-right" value="<?php echo $stud_placement[0]['stud_entrepreneur'] ?>">
                <?php } else { ?>
                    <input type="text" id="stud_entrepreneur" name="stud_entrepreneur" class="required numbers text-right">
                <?php } ?>
            </td>
            <td>                
            </td>
        </tr>      
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
    //function to view companies visisted details
    $("#view_companies_visited").click(function () {
        var pgm_id = $.cookie('stud_perm_program');
        var crclm_id = $.cookie('stud_perm_curriculum');
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id
        }
        $.ajax({type: "POST",
            url: base_url + 'report/companies_visited',
            data: post_data,
            success: function (msg) {
                $("#companies_visited_tabel").html(msg);
                $("#companies_visited_view").modal('show');
            }
        });
    });

    //function to view students higher studies details.
    $("#view_higher_studies").click(function () {
        var pgm_id = $.cookie('stud_perm_program');
        var crclm_id = $.cookie('stud_perm_curriculum');
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id
        }
        $.ajax({type: 'POST',
            url: base_url + 'report/curriculum_student_info/stud_higher_studies_dropdowns',
            data: post_data,
            dataType: 'json',
            success: function (data) {
                $("#higher_studies_tabel").html(data['d1']);
                $("#higher_study_view").modal('show');
                display_higher_studies_modal();
            }
        });
    });   
</script>
<!-- End of file student_placement_table_vw.php 
                        Location: .report/curriculum_student_info/student_placement_table_vw.php  -->