<?php
/**
 * Description		:   TO display list of companies visited and allow add visited companies.
 * Created		:   14-06-2016
 * Author		:   Shayista Mulla		  
 * Modification History:
 *    Date                  Modified By                			Description
  ---------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid" style="width:100%; overflow:auto;">
    <table class="table table-bordered table-hover " id="companies_data" aria-describedby="example_info">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:5%;">Sl.No</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:15%;">Company / Industry</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:10%;">Company / Industry type</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:10%;">Sector Type</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"  style="width:20%;">Description</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:10%;">Collaboration Date</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" style="width:10%;">Total no. of students intake</th>
                <th class="header headerSortDown"  tabindex="0" aria-controls="example" aria-sort="ascending" style="width:10%;">No. of times visited</th>
                <th class="header" rowspan="1" colspan="1" style="width:7%;" tabindex="0" aria-controls="example" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="example" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="example" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td>
        </tbody>
    </table>
</div><br/><br/><br/>
<!--Add form -->
<form  class="form-horizontal" method="POST"  id="company_form" name="company_form" action="">
    <div class="navbar" id="company_visit_heading" style="display:none">
        <div class="navbar-inner-custom" >
            Edit Company Details
        </div>
    </div>
    <div class="row-fluid" style="overflow:auto;">
        <div class="span6" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label">Company / Industry: <font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($company_name); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Sector Type: <font color="red">*</font></p>
                <div class="controls">
                    <select size="1" id="sector_type_id" name="sector_type_id" class="required">
                        <option value="" selected> Select Sector Type</option>
                        <?php foreach ($sector_type_list as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>                                
            <div class="control-group">
                <p class="control-label">Company / Industry Type: <font color="red">*</font></p>
                <div class="controls">
                    <select size="1" id="company_type_id" name="company_type_id" class="required">
                        <option value="" selected> Select Company Type</option>
                        <?php foreach ($company_type_list as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="span6" style="overflow:auto;">
            <div class="control-group">
                <p class="span3" style="float:left;margin-left:1px;"> Collaboration Date: <font color="red">*</font></p>
                <div class="span9">
                    <div class="input-prepend">
                        <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                    </div>
                    <input style="width:51%" class="required input-medium datepicker" id="collaboration_date" name="collaboration_date" readonly="" type="text">
                </div>
            </div>
            <div class="control-group" >
                <p class="span3"  style="float:left;"> Description :</p>
                <div class="span9"> 
                    <?php echo form_textarea($company_description); ?>
                    <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                </div>
            </div>
        </div>
    </div><br/><br/><br/>
    <div class="pull-right">
        <button class="btn btn-primary" id="edit_form_submit" name="edit_form_submit" type="button" style="display:none"><i class="icon-file icon-white"></i> Update</button>
        <button id="add_form_submit" name="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
        <button class="btn btn-info" type="reset" id="reset_company"><i class="icon-refresh icon-white"></i> Reset</button>
    </div><br/><br/>
</form>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_companies_visited.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/companies_visited.js'); ?>" type="text/javascript"></script>
<!-- End of file companies_visited_list_vw.php 
                        Location: .nba_sar/modules/companies_visited/companies_visited_list_vw.php  -->