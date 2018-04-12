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
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 470px;">Technical Magazine / Newsletter</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example"  style="width: 85px;">Date of Publication</th>
                <th class="header" rowspan="1" colspan="1" style="width: 40px;" tabindex="0" aria-controls="example" align="center" >Upload</th>
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
<form  class="form-horizontal" method="POST"  id="publication_form" name="publication_form" action="">
    <div class="navbar" id="company_visit_heading" style="display:none">
        <div class="navbar-inner-custom" >
            Edit Technical Magazines / Newsletter Details
        </div>
    </div>
    <div class="row-fluid edit_details" style="overflow:auto;" id="edit_details" name="edit_details"><br/>
        <div class="span5" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label">Publication Name :<font color="red">*</font></p>
                <div class="controls">
                    <?php echo form_input($publ_name); ?>
                </div>
            </div>

            <div class="control-group">
                <p class="control-label">Publication Type :<font color="red">*</font></p>
                <div class="controls">
                    <select size="1" id="publ_type_id" name="publ_type_id" class="required">
                        <option value="" selected> Select Publication Type </option>
                        <?php foreach ($publication_type as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="span7" style="overflow:auto;">
            <div class="control-group">
                <p class="control-label" style="float:left;margin-left:1px;"> Date of Publication :<font color="red">*</font></p>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                    </div>
                    <input style="width:45%" class="required input-medium datepicker" id="collaboration_date" name="collaboration_date" readonly="" type="text">
                </div>
            </div>

            <div class="control-group" >
                <p class="control-label"  style="float:left;"> Description :</p>
                <div class="controls"> 
                    <?php echo form_textarea($publ_desc); ?>
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
<!-- End of file publ_list_vw.php 
            Location: .nba_sar/modules/publ_tech_magazine/publ_list_vw.php  -->
