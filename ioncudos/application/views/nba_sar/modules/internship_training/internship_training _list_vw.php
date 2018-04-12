<?php
/**
 * Description		:   View page for Internship / Summer Training.
 * Created		:   24-06-2016
 * Author		:   Shayista Mulla		  
 * Modification History:
 *    Date                  Modified By                			Description
  ---------------------------------------------------------------------------------------------- */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav here-->
        <?php $this->load->view('includes/sidenav_6'); ?>
        <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
        <div class="span10">
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Internship / Summer Training
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid" style="width:100%; overflow:auto;">
                                <table style="width:90%">
                                    <tr>
                                        <td>
                                            <p>
                                                Department: <font color="red"> * </font><br>
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
                                                Program: <font color="red"> * </font><br>
                                                <select size="1" id="program" name="program" aria-controls="example" onChange = "fetch_curriculum();">
                                                    <option>Select Program</option>
                                                </select>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                Curriculum: <font color="red"> * </font><br>
                                                <select size="1" id="curriculum" name="curriculum" aria-controls="example" onChange = "fetch_details();">
                                                    <option>Select Curriculum</option>
                                                </select>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <?php echo $this->lang->line('industry_sing'); ?>: <font color="red"> * </font><br>
                                                <select size="1" id="company" name="company" aria-controls="example" onChange = "list_internship_details();">
                                                    <option value="">Select Company</option>
                                                </select>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Display Internship / Summer Training details -->
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <div id="details">
                        </div>
                        <!-- Delete Modal-->
                        <div class="modal hide fade" id="delete_details" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
                                <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
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
                                    <button class="btn btn-danger pull-right" data-dismiss="modal" type="button"><i class="icon-remove icon-white"></i> Close</button>	
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
                                <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel</button>
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
                                <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Close</button>									
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
                                <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>									
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
                                <button type="button" class="btn btn-danger btn-sm " data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>			
                            </div>
                        </div>                       
                    </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script>
    if ($.cookie('stud_perm_department') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#dept option[value="' + $.cookie('stud_perm_department') + '"]').prop('selected', true);
        fetch_program();
    }

    //Function to fetch program dropdown list.
    function fetch_program() {
        $.cookie('stud_perm_department', $('#dept option:selected').val(), {expires: 90, path: '/'});
        var department_id = $('#dept').val();
        var post_data = {
            'department_id': department_id
        }
        $('#loading').show();
        if (department_id) {
            $.ajax({type: "POST",
                url: base_url + 'nba_sar/internship_training/fetch_program',
                data: post_data,
                success: function (msg) {
                    $("#program").html(msg);
                    $('#loading').hide();
                    if ($.cookie('stud_perm_program') != null) {
                        // set the option to selected that corresponds to what the cookie is set to
                        $('#program option[value="' + $.cookie('stud_perm_program') + '"]').prop('selected', true);
                        fetch_curriculum();
                    }
                }
            });
        } else {
            $("#details").html("");
            $('#program').html('<option>Select Program</option>');
            $('#curriculum').html('<option>Select Curriculum</option>');
            $.cookie('stud_perm_program', '', {expires: 90, path: '/'});
            $.cookie('stud_perm_curriculum', '', {expires: 90, path: '/'});
            $('#loading').hide();
        }
    }

    //Function to fetch curriculum dropdown list.
    function fetch_curriculum() {
        $.cookie('stud_perm_program', $('#program option:selected').val(), {expires: 90, path: '/'});
        var program_id = $('#program').val();
        var post_data = {
            'program_id': program_id
        }
        $('#loading').show();
        if (program_id) {
            $.ajax({type: "POST",
                url: base_url + 'nba_sar/internship_training/fetch_curriculum',
                data: post_data,
                success: function (msg) {
                    $("#curriculum").html(msg);
                    $('#loading').hide();
                    if ($.cookie('stud_perm_curriculum') != null) {
                        // set the option to selected that corresponds to what the cookie is set to
                        $('#curriculum option[value="' + $.cookie('stud_perm_curriculum') + '"]').prop('selected', true);
                        fetch_details();
                    }
                }

            });
        } else {
            $("#details").html("");
            $('#curriculum').html('<option>Select Curriculum</option>');
            $.cookie('stud_perm_curriculum', '', {expires: 90, path: '/'});
            $('#loading').hide();
        }
    }
    //Function to fetch student performance view pages.
    function fetch_details() {
        $.cookie('stud_perm_curriculum', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
        var crclm_id = $('#curriculum').val();
        var pgm_id = $('#program').val();
        var dept_id = $('#dept').val();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'dept_id': dept_id,
        }
        $('#loading').show();
        if (crclm_id) {
            $.ajax({type: "POST",
                url: base_url + 'nba_sar/internship_training/fetch_details',
                data: post_data,
                dataType: 'json',
                success: function (msg) {
                    $('#loading').hide();
                    $("#details").html(msg['d1']);
                    $("#company").html(msg['d2']);
                    $('#loading').hide();
                    list_internship_details();
                    $('#loading').hide();
                }

            });
        } else {
            $("#details").html("");
            $('#loading').hide();
        }
    }
</script>
<!-- End of file internship_training _list_vw.php 
                        Location: .nba_sar/modules/internship_training/internship_training _list_vw.php  -->