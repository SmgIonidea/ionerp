<?php
/**
 * Description          :	Generate Students higher studies Details table in Student Placement Details.

 * Created		:	09-06-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

  ------------------------------------------------------------------------------------------ */
?>
<table class="table table-bordered table-hover " id="example_1" aria-describedby="example_info">
    <thead align = "center">
        <tr class="gradeU even" role="row">
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:5%;">Sl.No</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:15%;">Entrance Exam</th>                         
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:10%;">Category</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:20%;">Gender</th>
            <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:10%;">Number of Students</th>
			<th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:10%;">Opening - Closing Score </th>
            <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="example" align="center" >Edit</th>
            <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="example" align="center" >Delete</th>
        </tr>
    </thead>
    <tbody aria-live="polite" aria-relevant="all">
    <td></td><td></td><td></td> <td></td><td></td> <td></td> <td></td> <td></td>
</tbody>
</table>
<form  class="form-horizontal" method="POST"  id="higher_studies_form" name="higher_studies_form" action="">
    <div id="higher_studies_heading" style="display:none"><br/>
        <b style="color:green"> Edit Higher Study Details</b>
    </div>
    <table>
        <tr>
            <td valign="top">
                <p>Entrance Exam : <font color="red">*</font><br/>
                    <select id="ent_exam_higher_studies" name="ent_exam_higher_studies" class="required">
                        <option value="" selected> Select Entrance Exam</option>
                        <?php foreach ($entrance_exam_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
            <td valign="top">
                <p>Category : <font color="red">*</font><br/>
                    <select  id="caste_higher_studies" name="caste_higher_studies" class="required">
                        <option value="" selected> Select Category </option>
                        <?php foreach ($category_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
            <td valign="top">
                <p>Gender : <font color="red">*</font><br/>
                    <select  id="gender_higher_studies" name="gender_higher_studies" class="required">
                        <option value="" selected> Select Gender </option>
                        <?php foreach ($gender_list as $list_item): ?>
                            <option value="<?php echo $list_item['mt_details_id']; ?>" title="<?php echo $list_item['mt_details_name_desc']; ?>"> <?php echo $list_item['mt_details_name']; ?> </option>
                        <?php endforeach; ?>
                    </select></p>
            </td>
		</tr>
		<tr>
            <td valign="top" style="padding-right:10px;">
                <p>
                    Number of Students : <font color="red">*</font><br/>
                    <input type="text" name="num_stud" id="num_stud" class="required numbers text-right">
                </p>
            </td>
			<td valign="top" style="padding-right:10px;">
				<p> Opening Score / Rank : <br/>
				<input type="text" name="opening_score" id="opening_score" class="numbers text-right">
				</p>
			</td>			 	
			<td valign="top" style="padding-right:10px;">
				<p> Closing  Score / Rank : <br/>
				<input type="text" name="closing_score" id="closing_score" class="numbers text-right">
				</p>
			</td>
        </tr>
    </table>
    <div class="pull-right" id="button_list">
        <button type="button" class="btn btn-primary update" id="update_higher_studies" style="display:none"><i class="icon-file icon-white"></i> Update </button>
        <button id="submit_higher_studies" class="submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
        <button class="btn btn-info" type="reset" id="reset_higher_studies"><i class="icon-refresh icon-white"></i> Reset</button>
        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
    <br/>
</form>
<!-- Delete Modal-->
<div class="modal hide fade" id="delete_higher_studies_details" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
        <button type="button" class="btn btn-danger btn-sm pull-right" id="close_higher_studies" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
        <button type="button" id="higher_studies_delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
    </div>
</div>
<input type="hidden" id="higher_studies_edit" name="higher_studies_edit">
<input type="hidden" id="higher_studies_del" name="higher_studies_del">
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/stud_higher_studies.js'); ?>" type="text/javascript"></script>
<!-- End of file student_admitted_table_vw.php 
                        Location: nba_sar/modules/curriculum_student_info/stud_higher_studies_table_vw.php  -->