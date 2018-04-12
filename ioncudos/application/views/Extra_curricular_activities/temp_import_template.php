<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Program Outcome activity data import
                        </div>
                    </div>

                    <!-- to display loading image when mail is being sent -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>

                    <table>
                        <tbody>
                            <tr>                                
                                <td>
                                    <label class="cursor_default">
                                        Program: <font color="blue"><?= $activity[0]['pgm_title'] ?></font>
                                        <?php echo str_repeat('&nbsp;', 13); ?>
                                    </label>
                                </td>
                                <td>
                                    <label class="cursor_default">
                                        Curriculum: <font color="blue"><?= $activity[0]['crclm_name'] ?></font>
                                        <?php echo str_repeat('&nbsp;', 13); ?>
                                    </label>
                                </td>
                                <td>
                                    <label class="cursor_default">
                                        Term: <font color="blue"><?= $activity[0]['term_name'] ?></font>
                                    </label>
                                </td>
                            </tr>
                            <tr>                                
                                <td colspan="3">
                                    <label class="cursor_default">
                                        File Name: <font color="blue"><?= $file_name ?></font>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <b>Steps:</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1) Click on <font color="#8E2727"><b>"Upload"</b></font> button to upload the .csv file. Make sure that the <font color="#8E2727"><b>file name</b></font> and <font color="#8E2727"><b>file headers</b></font> are not altered.</br>
                                    <?php echo str_repeat('&nbsp', 3); ?>
                                    (Note: <font color="#8E2727"><b>Discard previous downloaded file from your system</b></font> before downloading new file)
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    2) Upon upload, student USN, student name, rubrics data and remarks will be displayed.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    3) Click on <font color="#8E2727"><b>"Accept"</b></font> button to save the rubrics data and return back to list page. Make sure that all the <font color="#8E2727"><b>remarks are resolved</b></font> before proceeding.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    4) Click on <font color="#8E2727"><b>"Cancel"</b></font> button to discard (if any file has been uploaded) and return back to list page.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    5) To replace rubrics data follow step 1.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" id="activity_id" name="activity_id" value="<?= $activity[0]['po_extca_id'] ?>">
                    <input type="hidden" id="crclm_id" name="crclm_id" value="<?= $activity[0]['crclm_id'] ?>">
                    <input type="hidden" id="term_id" name="term_id" value="<?= $activity[0]['crclm_term_id'] ?>">
                    <!--Discard-->
                    
                    <a id="discard_po_activity_data" href="<?php echo base_url('Extra_curricular_activities/Extra_curricular_activities/index'); ?>" value="discard" class="btn btn-danger pull-right" style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Cancel </a>
                    <!--Accept-->
                    <button id="accept_po_activity_data" value="accept" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Accept .csv </button>
                    <!--import-->
                    <button type="submit" id="uploade_activity_data" value="Upload" class="btn btn-success pull-right" style="margin-right: 2px;"><i class="icon-upload icon-white"></i> Upload .csv </button><br><br>
                </div>
                <!--display table with student usn and secured marks-->
                <div id="imprt_file_data" class="bs-docs-example"></div>       
            </section>
        </div>
    </div>
</div>
<!-- Modal to display the file not uploaded yet  -->
<div id="message_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="message_modal" data-backdrop="static" data-keyboard="false"></br>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom" id="modal_title">

            </div>
        </div>
    </div>

    <div class="modal-body">
        <p id="modal_body"></p>
    </div>

    <div class="modal-footer">
        <a class="btn btn-danger"  id="close_message_modal" abbr_href="<?php echo base_url('Extra_curricular_activities/Extra_curricular_activities/index'); ?>"><i class="icon-remove icon-white"></i> Close </a> 
    </div>
</div>

<!-- Modal to display the file not uploaded yet  -->
<div id="inline_edit_message_modal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="message_modal" data-backdrop="static" data-keyboard="false"></br>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom" id="modal_title">

            </div>
        </div>
    </div>

    <div class="modal-body">
        <p id="inline_edit_modal_body"></p>
    </div>

    <div class="modal-footer">
        <a class="btn btn-danger"  data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </a> 
    </div>
</div>

<div id="message_modal_import" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="message_modal" data-backdrop="static" data-keyboard="false"></br>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom" id="modal_title_import"></div>
        </div>
    </div>

    <div class="modal-body">
        <p id="modal_body_import"></p>
    </div>

    <div class="modal-footer">        
        <button class="btn btn-primary" data-dismiss="modal" id="close_message_modal_import"><i class="icon-ok icon-white"></i> Ok </button> 
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<!---place js.php here -->
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/inline_table_edit/jquery.tabledit.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('twitterbootstrap/js/custom/extra_curricular_activities_import.js') ?>"></script>
