<?php
/**
 * Description		:   View page for visited companies.
 * Created		:   25-05-2016
 * Author		:   Shayista Mulla		  
 * Modification History:
 *    Date                  Modified By                			Description
  ---------------------------------------------------------------------------------------------- */
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<div id="loading" class="ui-widget-overlay ui-front">
    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
</div>
<div id="example_wrapper" class="dataTables_wrapper" role="grid">
    <table class="table table-bordered table-hover " id="companies_data" aria-describedby="example_info">
        <thead align = "center">
            <tr class="gradeU even" role="row">
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending"  style="width:5%;">Sl.No</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending"  style="width:15%;">Company / Industry</th>                         
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending"  style="width:10%;">Sector Type</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending"  style="width:20%;">Description</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending" style="width:10%;">Collaboration Date</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending" style="width:10%;">Total no. of students intake</th>
                <th class="header headerSortDown" tabindex="0" aria-controls="companies_data" aria-sort="ascending" style="width:10%;">No. of times visited</th>
                <th class="header" rowspan="1" colspan="1" style="width:7%;" tabindex="0" aria-controls="companies_data" align="center" >Upload</th>
                <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="companies_data" align="center" >Edit</th>
                <th class="header" rowspan="1" colspan="1" style="width:5%;" tabindex="0" aria-controls="companies_data" align="center" >Delete</th>
            </tr>
        </thead>
        <tbody aria-live="polite" aria-relevant="all">
        <td></td><td></td><td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td>
        </tbody>
    </table><br/><br/><br/>
    <!--Add form -->
    <form  class="form-horizontal" method="POST"  id="company_form" name="company_form" action="">
        <div class="row-fluid">
            <div class="span6">
                <div class="control-group">
                    <p class="control-label">Company Name: <font color="red">*</font></p>
                    <div class="controls">
                        <?php echo form_input($company_name); ?>
                    </div>
                </div>
                <div class="control-group">
                    <p class="control-label">Sector Type: <font color="red">*</font></p>
                    <div class="controls">
                        <select size="1" id="company_type_id" name="company_type_id" class="required">
                            <option value="" selected> Select Sector Type</option>
                            <?php foreach ($companies_type_list as $type_list) { ?>
                                <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>                                
                <div class="control-group">
                    <p class="control-label">Collaboration Date: <font color="red">*</font></p>
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on" id="btn" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                        </div>
                        <input style="width:35%" class="required input-medium datepicker" id="collaboration_date" name="collaboration_date" readonly="" type="text">
                    </div>
                </div>
            </div>
            <div class="span6">  
                <div class="control-group" >
                    <p class=""  style="float:left;"> Description :</p>
                    <div class="span9"> 
                        <?php echo form_textarea($company_description); ?>
                        <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">       
            <button id="add_form_submit" name="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
            <button class="btn btn-info" type="reset" id="reset_company"><i class="icon-refresh icon-white"></i> Reset</button>
            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
        </div><br/><br/>
    </form>
    <!-- Edit Modal -->
    <div id="edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;" data-controls-modal="enable_dialog" data-backdrop="static" data-keyboard="true">
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Edit Company Details
                </div>
            </div>
        </div>
        <div class="modal-body">
            <form  class="form-horizontal" method="POST"  id="edit_form" name="edit_form" action="">
                <div class="row-fluid">
                    <div class="control-group">
                        <p class="control-label">Company Name: <font color="red">*</font></p>
                        <div class="controls">
                            <input type="hidden" id="edit_company_id" name="edit_company_id">
                            <input name="edit_company_name" value="" id="edit_company_name" class="required" placeholder="Enter Company Name" type="text">
                        </div>
                    </div>
                    <div class="control-group">
                        <p class="control-label">Sector Type: <font color="red">*</font></p>
                        <div class="controls">
                            <select size="1" id="edit_company_type_id" name="edit_company_type_id" class="required">
                                <option value="" selected> Select Sector Type</option>
                                <?php foreach ($companies_type_list as $type_list) { ?>
                                    <option value="<?php echo $type_list['mt_details_id']; ?>"> <?php echo $type_list['mt_details_name']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                                
                    <div class="control-group">
                        <p class="control-label">Collaboration Date: <font color="red">*</font></p>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on datepicker" id="btn1" style="cursor:pointer; margin-top:1px;"><i class="icon-calendar"></i></span>
                            </div>
                            <input style="width:50%" class="required input-medium datepicker" id="edit_collaboration_date" name="edit_collaboration_date" readonly="" type="text">
                        </div>
                    </div>
                </div>             
                <div class="control-group">
                    <p class="control-label">Description :</p>
                    <div class="controls"> 
                        <textarea name="edit_company_description" cols="50" rows="2" id="edit_company_description" class="edit_char-counter" maxlength="2000" placeholder="Enter Company Description" style="margin: 0px; width: 80%;"></textarea>
                        <br/> <span id='edit_char_span_support' class='margin-left5'>0 of 2000. </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" id="edit_form_submit" name="edit_form_submit" type="button"><i class="icon-file icon-white"></i> Update</button>
            <button class="btn btn-danger modal_close" data-id="#edit_modal" type="button"><i class="icon-remove icon-white"></i> Close</button>
        </div>

    </div>
    <!-- Delete Modal-->
    <div class="modal hide fade" id="delete_company_details" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" style="margin-right: 2px;" data-id="#delete_company_details"><i class="icon-remove icon-white"></i> Cancel</button>
            <button type="button" id="delete_company_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
        </div>
    </div>
    <!-- Upload Modal-->
    <form id="upload_form" name="upload_form" method="POST" enctype="multipart/form-data" >
        <div class="modal hide fade" id="upload_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
            <div class="modal-header">
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Upload Files
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="upload_files"></div>
                <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                    <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                </div>
            </div>
            <div class="modal-footer">

                <button class="btn btn-danger pull-right modal_close" data-id="#upload_modal" type="button"><i class="icon-remove icon-white"></i> Close</button>	

                <button class="btn btn-primary pull-right" style="margin-right: 3px; margin-left: 3px;" id="save_res_guid_desc" name="save_upload_desc" value=""><i class="icon-file icon-white"></i> Save</button>

                <button class="btn btn-success pull-right" style="margin-right: 3px; margin-left: 3px;" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
            </div>
        </div>
    </form>
    <!-- Delete Modal for uploaded file-->
    <div class="modal hide fade" id="delete_upload_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" data-id="#delete_upload_file" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
            <button type="button" id="delete_file" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
        </div>
    </div>
    <!--Warning modal for exceeding the upload file size-->
    <div class="modal hide fade" id="larger" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" data-id="#larger" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Close</button>									
        </div>
    </div>
    <!--Error Modal for file name size exceed-->
    <div class="modal hide fade" id="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            File name is too long.
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" style="margin-right: 2px;" data-id="#file_name_size_exc"><i class="icon-remove icon-white"></i> Close</button>									
        </div>
    </div>
    <!--Warning Modal for Invalid File Extension--->
    <div class="modal hide fade" id="file_extension" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            Invalid File Extension.                                 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm modal_close" data-id="#file_extension"><i class="icon-remove icon-white"></i> Close</button>			
        </div>
    </div>
    <div class="modal hide fade" id="view_companies_student_intake" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Student Intake Details
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div id="placement_details">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" data-id="#view_companies_student_intake" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Close</button>                             
        </div>
    </div>
    <!--Warning modal for can not delete this company details-->
    <div class="modal hide fade" id="cant_delete" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
        <div class="modal-header">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Warning
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p> You cannot delete this Company details as it is linked to Student(s).</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm pull-right modal_close" style="margin-right: 2px;" data-id="#cant_delete" ><i class="icon-remove icon-white"></i> Close</button>									
        </div>
    </div>
</div>
<input type="hidden" id="delete_company_id" name="delete_company_id">
<input type="hidden" id="company_id" name="company_id">
<input type="hidden" id="upload_id" name="upload_id">

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_companies_visited.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/companies_visited.js'); ?>" type="text/javascript"></script>
<!-- End of file companies_visited_list_vw.php 
                        Location: report/curriculum_student_info/companies_visited_list_vw.php  -->