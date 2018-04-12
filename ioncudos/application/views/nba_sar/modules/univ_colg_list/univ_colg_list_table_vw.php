<?php
/**
 * Description          :   View table page for displaying list of University / College.
 * Created              :   22-11-2016
 * Author               :   Neha Kulkarni  
 * Modification History :
 * Date                     Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ------------------------------------------------------------------------------------------ */
?>


<div class="row-fluid" style="width:100%; overflow:auto;">
    <table class="table table-bordered table-hover " id="univ_colg_lists" aria-describedby="example_info">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width: 50px;">Sl No.</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  >University / College Name</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width: 200px;">Total no. of students placed</th>
                <th class="header" rowspan="1" colspan="1"  tabindex="0" aria-controls="example" align="center" style="width: 60px;">Upload</th>
                <th class="header" rowspan="1" colspan="1"  tabindex="0" aria-controls="example" align="center" style="width: 40px;">Edit</th>
                <th class="header" rowspan="1" colspan="1"  tabindex="0" aria-controls="example" align="center" style="width: 50px;">Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td> <td></td> <td></td> <td></td>
        </tbody>
    </table>
</div><br/><br/><br/> 
<form  class="form-horizontal" method="POST"  id="univ_colg_list_form" name="univ_colg_list_form" action="">
    <div class="navbar" id="univ_colg_heading" style="display:none">
        <div class="navbar-inner-custom" >
            Edit University / College Details
        </div>
    </div>
    <div class="row-fluid edit_data" style="overflow:auto;" id="edit_data" name="edit_data">
        <div class="span6" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label">University / College Name :<font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($univ_colg_name); ?>
                </div>
            </div>
            <div class="control-group" >
                <p class="control-label"  style="float:left;"> Description :</p>
                <div class="controls"> 
                    <?php echo form_textarea($univ_colg_desc); ?>
                    <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                </div>
            </div>
        </div>
    </div><br/>
    <div class="pull-right">
        <button class="btn btn-primary" id="edit_form" name="edit_form" type="button" style="display:none"><i class="icon-file icon-white"></i> Update</button>
        <button id="add_form" name="add_form" class="add_form btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
        <button class="btn btn-info" type="reset" id="reset_details"><i class="icon-refresh icon-white"></i> Reset</button>
    </div><br/><br/>
</form>  
<!-- End of file univ_colg_list_table_vw.php 
                        Location: .nba_sar/modules/univ_colg_list/univ_colg_list_table_vw.php  -->
