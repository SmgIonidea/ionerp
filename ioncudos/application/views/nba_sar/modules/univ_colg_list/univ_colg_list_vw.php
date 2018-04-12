<?php
/**
 * Description          :   View page for displaying list of University / College.
 * Created              :   22-11-2016
 * Author               :   Neha Kulkarni  
 * Modification History :
 * Date                     Modified By                     Description
 * 01-06-2017               Shayista Mulla              Code clean up,indendation issues fixed,added coments
  ------------------------------------------------------------------------------------------ */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav here-->
        <?php $this->load->view('includes/sidenav_6'); ?>
        <div class="span10">
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>

                <div class="bs-docs-example">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            University / College List
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid" style="width:100%; overflow:auto;">
                                <table style="width:90%">
                                    <tr>
                                        <td>
                                            <p>
                                                Department :<font color="red"> * </font>
                                                <select size="1" id="dept" name="dept" aria-controls="example" onChange = "fetch_program();">
                                                    <option value="" selected> Select Department</option>
                                                    <?php foreach ($department_list as $list_item): ?>
                                                        <option value="<?php echo $list_item['dept_id']; ?>"> <?php echo $list_item['dept_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                Program :<font color="red"> * </font>
                                                <select size="1" id="program" name="program" aria-controls="example" onChange = "fetch_details();">
                                                    <option>Select Program</option>
                                                </select>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="details">
                    </div>
                    <form id="upload_form_data" name="upload_form_data" method="POST" enctype="multipart/form-data" >
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

                                <button class="btn btn-danger pull-right modal_close" data-dismiss="modal" data-id="#upload_modal" type="button"><i class="icon-remove icon-white"></i> Close</button>	

                                <button class="btn btn-primary pull-right" style="margin-right: 3px; margin-left: 3px;" id="save_desc" name="save_desc" value=""><i class="icon-file icon-white"></i> Save</button>

                                <button class="btn btn-success pull-right" style="margin-right: 3px; margin-left: 3px;" id="uploaded_file" name="upload_data" value=""><i class="icon-upload icon-white"></i> Upload</button>
                            </div>
                        </div>
                    </form>

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
                            <p> You cannot delete this Company details as students are associated with it.</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm pull-right " data-dismiss="modal" style="margin-right: 2px;" data-id="#cant_delete" ><i class="icon-remove icon-white"></i> Close</button>									
                        </div>
                    </div>

                    <!-- Delete Modal for uploaded file-->
                    <div class="modal hide fade" id="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            Are you sure you want to Delete?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal" data-id="#delete_file" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
                            <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>

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
                            Are you sure you want to Delete?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm pull-right " data-dismiss="modal"  style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
                            <button type="button" id="upload_file_delete" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>

                    <!--Warning Modal for Invalid File Extension--->
                    <div class="modal hide fade" id="file_extension" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_warning">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body" data-key="lg_invalid_file_ext">
                            Invalid File Extension.
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>			
                        </div>
                    </div>

                </div>
                <input type="hidden" id="edit_univ_colg_id" name="edit_univ_colg_id">
                <input type="hidden" id="univ_colg_id" name="univ_colg_id">
                <input type="hidden" id="upload_id" name="upload_id">
            </section>
        </div>    

    </div>
</div>    
<!--place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_companies_list.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/univ_colg_list.js'); ?>" type="text/javascript"></script>
<!-- End of file univ_colg_list_vw.php 
                        Location: .nba_sar/modules/univ_colg_list/univ_colg_list_vw.php  -->