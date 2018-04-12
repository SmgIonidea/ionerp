<?php
/**
 * Description		:   Table view page for placement.
 * Created		:   15-05-2016
 * Author		:   Shayista Mulla		  
 * Modification History:
 *    Date                  Modified By                			Description
 * 03-06-2017               Shayista Mulla                  Code clean up,indendation and issues fixed and added comments
  ---------------------------------------------------------------------------------------------- */
?>
<style>
    input::-moz-placeholder {
        text-align: left;
    }
    input::-webkit-input-placeholder {
        text-align: left;
    }
    table.spacing td {
        padding: 12px;
    }
</style>
<input type="hidden" id="select_list_data" name="select_list_data" value=""/>


<div class="row-fluid compa_display" style="width:100%; overflow:auto;">
    <table class="table table-bordered table-hover " id="example" aria-describedby="example_info" style="width:100%;">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" style = "width:5%" tabindex="0" aria-controls="example" aria-sort="ascending">Sl.No</th>
                <th class="header headerSortDown" style = "width:18%" tabindex="0" aria-controls="example" aria-sort="ascending" >Company / Industry</th>
                <th class="header headerSortDown" style = "width:15%" tabindex="0" aria-controls="example" aria-sort="ascending" >Role offered</th>
                <th class="header headerSortDown" style = "width:17%"tabindex="0" aria-controls="example" aria-sort="ascending" >Eligible Departments</th>
                <th class="header headerSortDown" style = "width:10%" tabindex="0" aria-controls="example" aria-sort="ascending" >CGPA / Percentage</th>
                <th class="header headerSortDown"  style = "width:9%" tabindex="0" aria-controls="example" aria-sort="ascending">Interview date</th>
                <th class="header headerSortDown"  style = "width:10%"tabindex="0" aria-controls="example" aria-sort="ascending">No. of students intake</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td><td></td><td></td> <td></td><td><table><tr><td></td></tr></table></td><td></td><td></td><td></td>
        </tbody>
    </table><br/>
</div>
<hr/>
<div class = "tab">
    <div id = "company_display"  style="display:none">
        <form  class="form-horizontal" method="POST"  id="placement_form" name="placement_form" action="">
            <div id="higher_placement" class="navbar" style="display:none">
                <div class="navbar-inner-custom">
                    Edit Placement
                </div>
            </div>
            <input type ="hidden" id ="plmt_id" name ="plmt_id" value=""/>
            <input type ="hidden" id ="first_visit" name ="first_visit" value=""/>

            <div class="row-fluid" style="overflow:auto;">
                <div class="span6">            
                    <div class="control-group">
                        <p class="control-label" id="selected_type_name"><?php echo $this->lang->line('industry_sing'); ?> : <font color="red">*</font></p>
                        <div class="controls">
                            <select size="1" id="company_id" name="company_id" class="required">
                                <option value="" selected> Select Company / Industry</option>                   
                            </select>
                        </div>
                    </div> 
                    <div class="control-group">
                        <p class="control-label">Eligible Departments :  <font color="red">* </font></p>
                        <div class="controls">
                            <select class="required" size="1" id="eligible_dept" name="eligible_dept"   multiple="multiple">
                                <?php foreach ($dept_list as $type_list) { ?>
                                    <option value="<?php echo $type_list['dept_id']; ?>" title="<?php echo $type_list['dept_name']; ?>"> <?php echo $type_list['dept_acronym']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                

                    <div class="control-group">
                        <p class="control-label" >Visit date : <font color="red">*</font></p>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on" id="btn_visit" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                            </div>
                            <input style="width:53%" class="required input-medium datepicker" id="visit_date" name="visit_date" readonly="" type="text">
                        </div>
                    </div>                
                    <div class="control-group">
                        <p class="control-label"> Positions being offered : </p>
                        <div class="controls">
                            <?php echo form_input($role_offered); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <p class="control-label">Pay package (in <img style="width:10px;height:10px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png'); ?>" alt="" /> ) :</p>
                        <div class="controls">
                            <?php echo form_input($pay); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <p class="control-label"  style="float:left;">Location of posting : </p>
                        <div class="controls">
                            <?php echo form_input($place_posting); ?>
                        </div>
                    </div>

                </div>
                <div class="span6">
                    <div class="control-group university_tab" >
                        <p class="control-label"  style="float:left;"> Sector : <font color="red">* </font> </p>
                        <div class="controls">
                            <select size="1" class="input-small  required" id="sector_list" name="sector_list" multiple="multiple">

                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <p  class="control-label" style="float:left;margin-left:1px;"> Program : <font color="red">* </font></p>
                        <div class="controls">                            
                            <select size="1" id="program_list" name="program_list" multiple="multiple" class="required"> </select>
                        </div>
                    </div>             
                    <div class="control-group">
                        <p  class="control-label" style="float:left;margin-left:1px;"> Curriculum : <font color="red">* </font></p>
                        <div class="controls">                            
                            <select size="1" id="curriculum_list" name="curriculum_list" multiple="multiple" class="required"> </select>
                        </div>
                    </div>    
                    <div class="control-group">
                        <p class="control-label" style="float:left;margin-left:1px;">Interview date : <font color="red">*</font></p>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                            </div>
                            <input style="width:45%" class="required input-medium datepicker" id="interview_date" name="interview_date" readonly="" type="text">
                        </div>
                    </div>                
                    <div class="control-group" >
                        <p class="control-label"  style="float:left;"> CGPA / Percentage % : <font color="red">*</font></p>
                        <div class="controls">
                            <?php echo form_input($cut_off_percent); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <p class="control-label" style="float:left;margin-left:1px;"> No. of vacancies : </p>
                        <div class="controls">
                            <?php echo form_input($no_of_vacancies); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group" >
                <p class="control-label" > Job Description :</p>
                <div class="controls"> 
                    <?php echo form_textarea($description); ?>
                    <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                </div>
            </div>        
            <div class="pull-right">
                <button type="button" class="btn btn-primary update" id="update_placement" style="display:none;"><i class="icon-file icon-white"></i> Update </button>
                <button id="add_form_submit" name="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                <button class="btn btn-info" type="reset" id="reset_placement"><i class="icon-refresh icon-white"></i> Reset</button>
            </div><br/><br/>
        </form>
    </div>
    <div class="row-fluid" id = "entrepreneur_display" style="display:none">
        <div class="row-fluid" style="width:100%;overflow:auto;">
            <table class="table table-bordered table-hover " id="example_entrepre" aria-describedby="example_info" style="width:100%;">
                <thead align = "center">
                    <tr class="gradeU even" role="row">
                        <th class="" style = "width:5%" tabindex="0" aria-controls="example" aria-sort="ascending">Sl.No</th>
                        <th class="" style = "width:18%" tabindex="0" aria-controls="example" aria-sort="ascending" >Name </th>
                        <th class="" style = "width:15%" tabindex="0" aria-controls="example" aria-sort="ascending" >Started Date</th>
                        <th class="" style = "width:17%"tabindex="0" aria-controls="example" aria-sort="ascending" >Sector</th>
                        <th class="" style = "width:10%" tabindex="0" aria-controls="example" aria-sort="ascending" >Location</th>             
                        <th class="" style = "width:10%" tabindex="0" aria-controls="example" aria-sort="ascending" >No. of students placed</th>             
                        <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Upload</th>
                        <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Edit</th>
                        <th class="header" rowspan="1" colspan="1" tabindex="0" aria-controls="example" align="center" >Delete</th>
                    </tr>
                </thead>
                <tbody aria-live="polite" aria-relevant="all">
                <td></td><td></td><td></td><td></td><td></td> <td></td><td><table><tr><td></td></tr></table></td><td></td><td></td>
                </tbody>
            </table><br/>
        </div>
        <div>
            <form  class="form-horizontal" method="POST"  id="entrepreneur_form" name="entrepreneur_form" action="" >
                <input type="hidden" id ="e_id" name ="e_id" />
                <div class="row-fluid " >
                    <div class="span6">
                        <div class="control-group">
                            <p class="control-label">Name :<font color="red">*</font></p>
                            <div class="controls">
                                <input type="text" class="required" id="name_entrepreneur" name ="name_entrepreneur" />
                            </div>
                        </div> 
                        <div class="control-group">
                            <p class="control-label" >Started Date :<font color="red">*</font></p>
                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on" id="btn_ent" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                                </div>
                                <input style="width:45%" class="required input-medium datepicker" id="started_date" name="started_date" readonly="" type="text">
                            </div>
                        </div> 
                    </div>
                    <div class="span6">            
                        <div class="control-group">
                            <p class="control-label">Sector :<font color="red">*</font></p>
                            <div class="controls">
                                <input type="text" class="required" id="sector_entrepreneur" name ="sector_entrepreneur" />
                            </div>
                        </div> 
                        <div class="control-group">
                            <p class="control-label">Location :<font color="red">*</font></p>
                            <div class="controls">
                                <input type="text" class="required" id="location_entrepreneur" name ="location_entrepreneur" />
                            </div>
                        </div>               
                    </div>
                </div>
                <div class="row-fluid " >
                    <div class="control-group">
                        <p class="control-label">Description :<font color="red"></font></p>
                        <div class="controls">
                            <textarea maxlength = 2000 class ="char-counter_ent " rows=2 cols = 50 placeholder ="Enter Description"style = "width: 83%" id="description_entrepreneur" name ="description_entrepreneur"> </textarea>
                            <br/><span id='char_span_support_ent' class='margin-left5'>0 of 2000. </span>
                        </div>
                    </div>					
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary update" id="update_entrepreneur" style="display:none;"><i class="icon-file icon-white"></i> Update </button>
                    <button id="add_entrepreneur" name="add_entrepreneur" class="add_entrepreneur btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                    <button class="btn btn-info" type="reset" id="reset_entrepreneur"><i class="icon-refresh icon-white"></i> Reset</button>
                </div><br/><br/>
            </form>
        </div>
    </div>
</div>
<!--Placement Intake Details Modal -->
<div class="modal hide fade" id="plmt_intake_view" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="width:80%;left:30%; display:block;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Placement Intake Details
            </div>
        </div>
    </div>

    <div class="modal-body">
        <div id="intake_table">                                 
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
    </div>
</div>
<!--Placement Intake Details Delete Modal -->
<div class="modal hide fade" id="delete_intake_details" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
        <button type="button" class="btn btn-danger btn-sm pull-right " style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
        <button type="button" id="delete_intake_selected" class="btn btn-primary btn-sm pull-right" data-dismiss="modal" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
    </div>
</div>
<input type="hidden" id="plmt_intake" name="plmt_intake"/>
<input type="hidden" id="intake_edit" name="intake_edit"/>
<input type="hidden" id="intake_delete" name="intake_delete"/>
<input type="hidden" id="intake_company_id" name="intake_company_id"/>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_placement.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/placement.js'); ?>" type="text/javascript"></script>

<script>
    $('#eligible_dept').multiselect({
        buttonWidth: '220px',
        numberDisplayed: 5,
        nSelectedText: 'selected',
        nonSelectedText: "Select Department",
        includeSelectAllOption: true,
        templates: {
            button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
        }

    });
	
    $('#category_list').multiselect({        
        buttonWidth: 220,
        numberDisplayed: 3,
        nSelectedText: 'selected',
        nonSelectedText: "Select Category",
        includeSelectAllOption: true,
        templates: {
            button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });
    $('#sector_list').multiselect({     
        buttonWidth: 220,
        numberDisplayed: 1,
        nSelectedText: 'selected',
        nonSelectedText: "Select Sector",
        includeSelectAllOption: true,
        templates: {
            button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
        }	 
    });
    $('#program_list').multiselect({
        buttonWidth: 220,
        numberDisplayed: 2,
        nSelectedText: 'selected',
        nonSelectedText: "Select Program",
        includeSelectAllOption: true,
        templates: {
            button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });     
    $('#curriculum_list').multiselect({     
        buttonWidth: 220,
        numberDisplayed: 1,
        nSelectedText: 'selected',
        nonSelectedText: "Select Curriculum",
        includeSelectAllOption: true,
        templates: {
            button: '<button type="button" class="multiselect btn btn-link  dropdown-toggle" data-toggle="dropdown"></button>'
        }	 
    });
</script>
<!-- End of file placement_table_vw.php 
                        Location: .nba_sar/modules/placement/placement_table_vw.php  -->