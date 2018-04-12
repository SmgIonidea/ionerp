<?php
/**
 * Description		:   Table view page for Internship / Summer Training.
 * Created		:   24-06-2016
 * Author		:   Shayista Mulla		  
 * Modification History:
 *    Date                  Modified By                			Description
  ---------------------------------------------------------------------------------------------- */
?>
<style>
    input::-moz-placeholder {
        text-align: left;
    }
    input::-webkit-input-placeholder {
        text-align: left;
    }
</style>
<div class="row-fluid" style="width:100%; overflow:auto;">
    <table class="table table-bordered table-hover " id="example" aria-describedby="example_info" style="width:100%;">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Sl.No</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" >Title / Subject</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" >Batch Members</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" >Guide</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending" >Company / Industry</th>
                <th class="header headerSortDown"  tabindex="0" aria-controls="example" aria-sort="ascending">Type</th>
                <th class="header headerSortDown"  tabindex="0" aria-controls="example" aria-sort="ascending">Duration</th>
                <th class="header headerSortDown"  tabindex="0" aria-controls="example" aria-sort="ascending">Status</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td><td></td><td></td> <td></td><td></td><td></td><td></td><td></td><td></td>
        </tbody>
    </table><br/><br/><br/>
</div>
<div id="edit_internship" class="navbar" style="display:none">
    <div class="navbar-inner-custom">
        Edit Internship / Summer Training
    </div>
</div>
<div class="row-fluid" style="overflow:auto;">
    <form  class="form-horizontal" method="POST"  id="training_form" name="training_form" action="">
        <div class="span6">
            <div class="control-group">
                <p class="control-label">Title / Subject : <font color="red"> * </font></p>
                <div class="controls">
                    <?php echo form_input($title); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Batch Member(s) : <font color="red"> * </font></p>
                <div class="controls">
                    <?php echo form_textarea($batch_members); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Guide : <font color="red"> * </font></p>
                <div class="controls">
                    <select size="1" id="guide_id" name="guide_id" class="required">
                        <option value="" selected> Select Guide</option>
                        <?php foreach ($guide as $list) { ?>
                            <option value="<?php echo $list['id']; ?>"> <?php echo $list['title'] . " " . ucfirst($list['username']); ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Location :</p>
                <div class="controls">
                    <?php echo form_input($location); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">From & To date : <font color="red"> * </font></p>
                <div class="controls span6">
                    <div class="span6">
                        <div class="input-prepend">
                            <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                        </div>
                        <input style="width:60%" class="required input-medium datepicker" id="from_duration" name="from_duration" readonly="" type="text">
                    </div>
                    <div class="span6">
                        <div class="input-prepend">
                            <span class="add-on" id="btn1" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                        </div>
                        <input style="width:60%" class="required input-medium datepicker" id="to_duration" name="to_duration" readonly="" type="text">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label"><?php echo $this->lang->line('industry_sing'); ?>: <font color="red"> * </font></p>
                <div class="controls">
                    <select size="1" id="company_id" name="company_id" class="required">
                        <option value="" selected> Select <?php echo $this->lang->line('industry_sing'); ?></option>
                        <?php foreach ($comapany_list as $type_list) { ?>
                            <option value="<?php echo $type_list['company_id']; ?>"> <?php echo $type_list['company_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Type : <font color="red"> * </font></p>
                <div class="controls">
                    <select size="1" id="intrshp_type" name="intrshp_type" class="required">
                        <option value="" selected> Select Training</option>
                        <?php foreach ($training as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="control-group">           
                <label class="control-label" for="last_name"> Stipend (in <img style="width:10px;height:10px;position:relative;left:2%;" src="<?php echo base_url('twitterbootstrap/img/rupee.png'); ?>" alt=""> ) : <font color="red"></font></label>
                <div class="controls">
                    <?php echo form_input($stipend); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label"> Cut off (%) :</p>
                <div class="controls">
                    <?php echo form_input($cut_off_percent); ?>
                </div>
            </div>
            <div class="control-group">
                <p class="control-label">Status : <font color="red"> * </font></p>
                <div class="controls">
                    <select size="1" id="status" name="status" class="required">
                        <option value="" selected> Select Status</option>
                        <?php foreach ($status as $type_list) { ?>
                            <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group" >
                <p class="control-label"> Description :</p>
                <div class="controls"> 
                    <?php echo form_textarea($description); ?>                 
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-primary update" id="update" style="display:none;"><i class="icon-file icon-white"></i> Update </button>
            <button id="form_submit" name="form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
            <button class="btn btn-info" type="reset" id="reset"><i class="icon-refresh icon-white"></i> Reset</button>
        </div><br/><br/>
    </form>
</div>
<input type="hidden" name="intrshp_id" id="intrshp_id"/>
<input type="hidden" name="delete_id" id="delete_id"/>
<input type="hidden" name="internship_id" id="internship_id"/>
<input type="hidden" name="upload_id" id="upload_id"/>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_placement.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/internship_training.js'); ?>" type="text/javascript"></script>
<script>
    var industry_title="<?php echo $this->lang->line('industry_sing'); ?>";
</script>
<!-- End of file internship_training_table_vw.php 
                        Location: .nba_sar/modules/internship_training/internship_training_table_vw.php  -->
