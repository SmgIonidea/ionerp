<?php
/**
 * Description		:   To display list of companies.
 * Created		:   22-11-2016
 * Author		:   Neha Kulkarni
 * Modification History:
 *    Date                  Modified By                			Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation and issues fixed
  ---------------------------------------------------------------------------------------------- */
?>
<div class="row-fluid" style="overflow:auto;">
    <table class="table table-bordered table-hover " id="companies_data" aria-describedby="example_info">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 40px;">Sl No.</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 120px;">Company Name</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 85px;">Category</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 200px;">Sector</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 60px;">First Visit</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 110px;">No. of times visited</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 90px;">Total no. of students placed</th>
                <th class="header" rowspan="1" colspan="1" style="width: 55px;" tabindex="0" aria-controls="example" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" style="width: 30px;" tabindex="0" aria-controls="example" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" style="width: 40px;" tabindex="0" aria-controls="example" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td>
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
    <div class="row-fluid edit_details" style="overflow:auto;" id="edit_details" name="edit_details"><br/>
        <div class="span5" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label"><?php echo $this->lang->line('industry_sing'); ?> Name :<font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($company_name); ?>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Category :<font color="red">*</font></p>
                <div class="controls">
                    <select size="1" id="company_type_id" name="company_type_id" class="required">
                        <option value="" selected> Select Category </option>
                        <?php foreach ($category as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="control-group other_type" style="display:none;">
                <p class="control-label" for="other_type"> Other Category Type :<font color="red">*</font></p>
                <div class="controls">
                    <input placeholder="Enter Category type" type="text" id="other_type" name="other_type" class=""/>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Contact Name :</p>
                <div class="controls">
                    <?php echo form_input($contact_name); ?>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Email :</p>
                <div class="controls">
                    <?php echo form_input($email); ?>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">MoU signed by College :</p>
                <div class="controls">
                    <?php echo form_input($mou_flag); ?>
                </div>
            </div>

        </div>
        <div class="span7" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label" style="float:left;margin-left:1px;"> First Visit :<font color="red">*</font></p>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                    </div>
                    <input style="width:45%" class="required input-medium datepicker" id="collaboration_date" name="collaboration_date" readonly="" type="text">
                </div>
            </div>

            <div class="control-group">
                <p class="control-label" style="float:left;margin-left:1px;">Sector Type :<font color="red">*</font></p>
                <div class="controls">
                    <select size="1" id="sector_type_id" name="sector_type_id[]" class="required sector_type_id selected multiselect" multiple="multiple">
                        <?php foreach ($sector_type_list as $type_list) { ?>
                            <option rel="tooltip" title="<?php echo $type_list['mt_details_name']; ?>" value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo character_limiter($type_list['mt_details_name'], 16); ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Contact Number : <font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($contact_number); ?>
                </div>
            </div>
            <div class="control-group" >
                <p class="control-label"  style="float:left;"> Description :</p>
                <div class="controls">
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
<!-- End of file companies_list_list_vw.php 
                        Location: .nba_sar/modules/companies_list/companies_list_list_vw.php  -->