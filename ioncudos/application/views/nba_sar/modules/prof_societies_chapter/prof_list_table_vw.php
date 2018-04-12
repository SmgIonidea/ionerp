<?php
/**
 * Description          :   
 * Created              :   
 * Author               :   
 * Modification History :
 *    Date                  Modified By                         Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ---------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid" style="overflow:auto;">
    <table class="table table-bordered table-hover " id="companies_data" name="companies_data" aria-describedby="example_info">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 30px;">Sl No.</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 490px;">Professional Society / Chapter</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 65px;">Date</th>
                <th class="header" rowspan="1" colspan="1" style="width: 50px;" tabindex="0" aria-controls="example" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" style="width: 30px;" tabindex="0" aria-controls="example" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" style="width: 40px;" tabindex="0" aria-controls="example" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td> <td></td> <td></td> <td></td>
        </tbody>
    </table>
</div><br/><br/><br/>
<!--Add form -->
<form  class="form-horizontal" method="POST"  id="professional_form" name="professional_form" action="">
    <div class="navbar" id="company_visit_heading" style="display:none">
        <div class="navbar-inner-custom" >
            Edit Professional Societies / Chapters Details
        </div>
    </div>
    <div class="row-fluid edit_details" style="overflow:auto;" id="edit_details" name="edit_details"><br/>
        <div class="span5" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label"> Professional Society / Chapter :<font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($prof_name); ?>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Date :<font color="red">*</font></p>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                    </div>
                    <input style="width:52%" class="required input-medium datepicker" id="prof_year" name="prof_year" readonly="" type="text">
                </div>
            </div>



        </div>
        <div class="span7" style="overflow:auto;">
            <div class="control-group" >
                <p class="control-label"  style="float:left;"> Description :</p>
                <div class="controls"> 
                    <?php echo form_textarea($prof_desc); ?>
                    <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                </div>
            </div>

        </div>
    </div><br/><br/><br/>

    <div class="pull-right">
        <button class="edit_form btn btn-primary" id="edit_form" name="edit_form" type="button" style="display:none"><i class="icon-file icon-white"></i> Update</button>
        <button id="add_form" name="add_form" class="add_form btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
        <button class="btn btn-info" type="reset" id="reset_company"><i class="icon-refresh icon-white"></i> Reset</button>
    </div><br/><br/>
</form>
<!-- End of file prof_list_table_vw.php 
                        Location: .nba_sar/modules/prof_societies_chapter/companies_visited_list_vw.php  -->
