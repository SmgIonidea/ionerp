<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : Permission Edit view Page.	  
 * Modification History :
 * Date				Modified By					Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<?php
$this->load->view('includes/branding');
?>
<!-- Navbar here -->
<?php
$this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Content Specific Guidelines
                        </div>
                    </div>
                    <div class="row pull-right">   
                        <a href="<?php echo base_url('configuration/help_content/help_content_add'); ?>" align="right">
                            <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                        </a> </div>
                    <br><br>
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead align = "center">
                                <tr class="gradeU even" role="row">
                                    <th class="header headerSortDown" style="width: 30px;" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                                    <th class="header headerSortDown" style="width: 600px;"  tabindex="0" aria-controls="example" aria-sort="ascending" >Content Specific Guidelines</th>
                                    <th class="header" rowspan="1" colspan="1" style="width: 40px;" tabindex="0" aria-controls="example" align="center" ><center></center>Upload</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 30px;" tabindex="0" aria-controls="example" align="center" ><center></center>Edit</th>
                            <th class="header" rowspan="1" colspan="1" style="width: 30px;" tabindex="0" aria-controls="example" align="center" ><center></center>Delete</th>
                            </tr>
                            </thead>
                            <tbody aria-live="polite" aria-relevant="all">
                                <?php
                                $i = 0;
                                foreach ($help_content as $help):
                                    ?>
                                    <tr>
                                        <td class="sorting_1" style="text-align:right;"><?php echo ++$i; ?></td>  
                                        <td class="sorting_1 table-left-align" title="<?php echo $help['entity_data']; ?>"><?php echo "Guidelines for establishing " . $help['entity_data']; ?></td>  
                                        <td class="sorting_1 table-left-align"><a data-val = "<?php echo $help['serial_no']; ?>" class="upload_data" href="#"> Upload </a></td>  
                                        <td> <center><a class="" href="<?php echo base_url('configuration/help_content/update_content') . '/' . $help['serial_no']; ?>">
                                        <i class="icon-pencil icon-black"> </i></a></center></td>
                                <td>
                                    <?php if ($help['used'] != 0) { ?>
                                    <center><a class="" href="#cantDelete" rel="tooltip" title="Enable" role="button"  data-toggle="modal"  id="<?php echo $help['serial_no']; ?>"><i class="icon-remove get_topic_id"> </i></a></center>
                                <?php } else { ?>
                                    <center><a class="" href="#myModal" rel="tooltip" title="Enable" role="button"  data-toggle="modal"  id="<?php echo $help['serial_no']; ?>" onclick="javascript:currentIDSt(<?php echo $help['serial_no']; ?>);"><i class="icon-remove get_topic_id"> </i></a></center>
                                <?php } ?>
                                </td>
                                </tr>

                            <?php endforeach; ?>
                            <input hidden id="file_upload">

                            </tbody>
                        </table><br><br><br>
                        <div class="row pull-right">   
                            <a href="<?php echo base_url('configuration/help_content/help_content_add'); ?>">
                                <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> </i> Add</button>
                            </a> 
                        </div><br>
                    </div>
                    <br><br>
                </div>
                <div id="error_msg" style="display:none">
                    <?php
                    $error = $this->session->userdata('error');
                    if (isset($error)) {
                        echo $error;
                        $this->session->unset_userdata('error');
                    }
                    ?>
                </div>
                <!-- Modal -->
                <br>
                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Delete Confirmation 
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_data();"><i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>
                <!-- File upload delete Modal Starts -->
                <div id="file_delete_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Delete Confirmation 
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:file_delete();"><i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>
                <!-- File upload delete Ends -->
                <div id="help_upload" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Guidelines Entity and Guidelines Upload File fields should not be empty before uploading.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>
                <!--Modal to intimate user can not delete the Guidelines Entity-->
                <div id="cantDelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You can not delete this Guidelines Entity as it has Uploaded Guidelines Document. </p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div>

                <form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
                    <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="mymodal" aria-hidden="true" style="display: none; width:750px; left: 600px; " data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_upload_artifacts">
                                    Upload Data
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div id="art"></div>
                            <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                <img style="width:155px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger pull-right" data-dismiss="modal" style="margin-right: 2px; margin-left: 2px;"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>			
                            <!--<button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>-->
                            <button class="btn btn-success pull-right" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
                        </div>
                    </div>
                </form>

                <!--Delete Modal--->
                <div class="modal hide fade" id="delete_file" name="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_del_conf">
                                Delete Confirmation
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" data-key="lg_delete_conf">
                        Are you sure you want to Delete?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                        <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                    </div>
                </div>

                <!--Warning modal for exceeding the upload file size-->
                <div class="modal hide fade" id="larger" name="larger" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" data-key="lg_file_name_too_long">
                        <p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-ok icon-white"></i> <span data-key="lg_close">Ok</span></button>									
                    </div>
                </div>

                <!--Error Modal for file name size exceed-->
                <div class="modal hide fade" id="file_name_size_exc" name="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_err_msg">
                                Error Message
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" data-key="lg_file_name_too_long">
                        File name is too long.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>									
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
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close">Ok</span></button>			
                    </div>
                </div>
                <br><br><br><br><br>
                <!--Do not place contents below this line-->	
            </section>                
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<!---place js.php here -->
<script src="<?php echo base_url('twitterbootstrap/js/setup.js'); ?>" type="text/javascript"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/help_list.js'); ?>"></script>
</body>
</html>
