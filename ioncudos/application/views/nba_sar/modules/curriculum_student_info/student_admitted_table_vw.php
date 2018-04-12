<?php
/**
 * Description          :	Generate Students Admitted Details table in Student Intake Details.

 * Created		:	08-06-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<table class="table table-bordered table-hover " id="example1" aria-describedby="example_info">
    <thead align = "center">
        <tr class="gradeU even" role="row">
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:5%;">Sl.No</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:15%;">Entrance Exam</th>                         
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:10%;">Category</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:5%;">Gender</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:14%;">Nationality - State</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:5%;">Intake</th>           
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:20%;">Opening - Closing Score/Rank</th>
            <th class="header" rowspan="1" colspan="1" style="width:3%;" tabindex="0" aria-controls="example" align="center" >Edit</th>
            <th class="header" rowspan="1" colspan="1" style="width:3%;" tabindex="0" aria-controls="example" align="center" >Delete</th>
        </tr>
    </thead>
    <tbody aria-live="polite" aria-relevant="all">
    <td></td><td></td><td></td> <td></td><td></td> <td></td> <td></td><td></td><td></td>
</tbody>
</table>
<form  class="form-horizontal" method="POST"  id="add_form" name="add_form" action="">
    <div id="edit_heading" style="display:none"><br/>
        <b style="color:green"> Edit Students Admitted Details</b>
    </div>
    <table>
        <tr>
            <td valign="top">
                <p>Entrance Exam : <font color="red">*</font><br/>
                    <select id="ent_exam" name="ent_exam" class="required input-medium">
                        <option value="" selected> Select Entrance Exam</option>
                        <?php foreach ($entrance_exam_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>"title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
            <td valign="top">
                <p>Category : <font color="red">*</font><br/>
                    <select  id="caste" name="caste" class="required input-medium">
                        <option value="" selected> Select Category </option>
                        <?php foreach ($category_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
            <td valign="top" style="padding-right:10px;">
                <p>Gender : <font color="red">*</font><br/>
                    <select  id="gender" name="gender" class="required input-medium">
                        <option value="" selected> Select Gender </option>
                        <?php foreach ($gender_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>  
            <td valign="top">
                <p>
                    Intake : <font color="red">*</font><br/>
                    <input type="text" name="num_intake" id="num_intake" class="required numbers text-right input-medium">
                </p>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding-right:5px;">
                <p> Nationality:<br/>
                    <select id="nation" name="nation" class="input-medium" onchange="show_country();">
                        <option value="" selected> Select Nationality</option>
                        <?php foreach ($nationality_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
            <td valign="top" id="specific" style="padding-right:10px;display:none;">
                Specify Country Name: <font color="red">*</font><br/>
                <input type="text" name="specified" id="specified" class="input-medium required">
            </td>
            <td valign="top" style="padding-right:10px;">
                State :<br/>
                <input type="text" name="state" id="state" class="input-medium">
            </td>
            <td valign="top" style="padding-right:10px;">
                Opening Score/Rank :<br/>
                <input type="text" name="add_rank_from" id="add_rank_from" class="numbers text-right input-small">
            </td>
            <td valign="top">
                Closing Score/Rank:<br/>
                <input type="text" name="add_rank_to" id="add_rank_to" class="numbers text-right input-small">    
            </td>
        </tr>
    </table>
    <div class="pull-right" id="button_list">
        <button type="button" class="btn btn-primary update" id="update" style="display:none"><i class="icon-file icon-white"></i> Update </button>
        <button id="submit" class="submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
        <button class="btn btn-info" type="reset" id="reset"><i class="icon-refresh icon-white"></i> Reset</button>
        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
    <br/>
</form>
<!-- Delete Modal-->
<div class="modal hide fade" id="delete_admitted_details" role="dialog" tabindex="-1" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-keyboard="true" >
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Delete Confirmation
            </div>
        </div>
    </div>

    <div class="modal-body">
        Are you sure you want to delete?
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-right" id="close" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
        <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/curriculum_student_admitted.js'); ?>" type="text/javascript"></script>
<!-- End of file student_admitted_table_vw.php 
                        Location: nba_sar/modules/curriculum_student_info/student_admitted_table_vw.php  -->