
<style>
    .div_border{
        border:1px solid #ddd;
        position:relative;
        padding: 10px 20px 10px;
    }
    .div_margin{
        margin-left: 0px !important;
    }


</style>
<div class="row-fluid">
        <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
        </div>
    <div class="span12">
        <div class="row-fluid"> 
            <div class="span12 div_border div_margin survey_and_course_data_div">
                <font><b>Survey Title :</b> <?php echo $su_title; //str_replace("%20"," ",$su_title);  ?></font>
            </div>
            <div class="span12 div_border div_margin " id="crclm_nd_crs_dtls">
                <font class="span3"><b>Curriculum :</b> <?php echo @$crclm_name; ?></font>
                <?php if (@$term_name) { ?>
                    <font class="span2"><b>Term :</b> <?php echo @$term_name; ?></font>
                <?php } else {
                    
                }
                ?>
                <?php if (@$course_name) {
                    ?>
                    <font class=""><b>Course Title :</b> <?php echo @$course_name; ?></font>
                    <?php } else {
                    
                }
                ?>
				<?php if($section_id != "Not Defined"){if (@$section_id) {
                    ?>
                    <font class=""><b> &nbsp;&nbsp;Section  :</b> <?php echo @$section_name; ?></font>
                    <?php } }else {
                    
                }
                ?>
            </div>
            <div class="radio_button">
                <div class="span12 div_border div_margin" >


<?php if ($radio_checked == 'attainment') {
    ?>
                        <div class="span3">
                            <input type="radio" data-crclm_id="<?php echo $crclm_id; ?>" data-survey_id="<?php echo $survey_id; ?>" data-crs_id="<?php echo $crs_id; ?>" data-su_for="<?php echo $su_for; ?>" data-status="<?php echo $status; ?>" name="host_survey_radio" id="host_survey_radio_1" class="host_survey_radio" data-host_type="attainment" checked="checked"/>&nbsp;&nbsp;<font id="attain">Indirect Attainment Entry</font>
                        </div>
                        <div class="span2">
                            <input type="radio" data-crclm_term_id = "<?php echo $crclm_term_id; ?>" data-section_id = "<?php echo $section_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" data-survey_id="<?php echo $survey_id; ?>" data-crs_id="<?php echo $crs_id; ?>" data-su_for="<?php echo $su_for; ?>" data-status="<?php echo $status; ?>" name="host_survey_radio" id="host_survey_radio_2" class="host_survey_radio" data-host_type="stakeholder"  />&nbsp;&nbsp;<font id="stake">Select Stakeholder</font>
                        </div>
                    <?php }
                    ?>

<?php
if ($radio_checked == 'stakeholder') {
    ?>
                        <div class="span3">
                            <input type="radio" data-crclm_id="<?php echo $crclm_id; ?>" data-survey_id="<?php echo $survey_id; ?>" data-crs_id="<?php echo $crs_id; ?>" data-su_for="<?php echo $su_for; ?>" data-status="<?php echo $status; ?>" name="host_survey_radio" id="host_survey_radio_1" class="host_survey_radio" data-host_type="attainment" />&nbsp;&nbsp;<font id="attain">Indirect Attainment Entry</font>
                        </div>
                        <div class="span2">
                            <input type="radio" data-crclm_term_id = "<?php echo $crclm_term_id; ?>" data-section_id = "<?php echo $section_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" data-survey_id="<?php echo $survey_id; ?>" data-crs_id="<?php echo $crs_id; ?>" data-su_for="<?php echo $su_for; ?>" data-status="<?php echo $status; ?>" name="host_survey_radio" id="host_survey_radio_2" class="host_survey_radio" data-host_type="stakeholder" checked="checked" /><font id="stake">&nbsp;&nbsp;Select Stakeholder</font>
                        </div>	
<?php } ?>

                </div>
                <br><br><br>
                <div id="load_survey_data"class="span12 div_border div_margin">
                </div>
                <br><br>
                <form enctype="multipart/form-data" class="form-vertical" name="upload_form" id="upload_form" action="" method="post">
                    <div class="span12 upload_survey_docs div_border div_margin" id="upload_survey_docs">
                        <div class="navbar">
                            <div class="navbar-inner-custom">Upload Survey Files
                            </div>
			</div>	
                        <label class="btn btn-success" for="my_file_selector">
                            <input id="my_file_selector" name="my_file_selector[]" id="my_file_selector" type="file" multiple style="display:none">
                            Choose File
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="up_crclm_id" id="up_crclm_id" value />
                        <input type="hidden" name="up_survey_id" id="up_survey_id" value />
                        <input type="hidden" name="up_crs_id" id="up_crs_id" value />
                        <input type="hidden" name="up_su_for" id="up_su_for" value />

                        <br>
                        <br>

                        <p id="display_file_name"> No Files Selected.</p>
                        <p><b>Note:</b><font>&nbsp;&nbsp;  Maximum file size allowed is 20,000 KB.</font>
							<br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							If File(s) List contains invalid file then none of the File(s) get uploaded. 
						</p>

                            <!--<div class="pull-right" id="upload_button">
                            <button class="btn btn-primary upload_docs" data-dismiss="modal" aria-hidden="true" id="survey_upload_docs"> <i class="icon-circle-arrow-up icon-white"></i>&nbsp;&nbsp;Upload</button>
                            </div>-->

                        <div class="survey_doc_table_div" id="survey_doc_table_div">
                            <table id="survey_doc_table" class="table table-bordered table-hover survey_doc_table dataTable" aria-describedby="example_info" align='center'>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Delete</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            
                        </div></br>
                        <div class="">
                            <button class="btn btn-primary upload_docs" data-dismiss="modal" aria-hidden="true" id="survey_upload_docs"> <i class="icon-circle-arrow-up icon-white"></i> Upload</button>
                            </div>
                    </div>

                </form>

                <div class="pull-right">
                    <button type=" button" name="close_survey_attainment_entry" id="close_survey_attainment_entry" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</button>
                </div>
                <!-- Modal to display delete confirmation message -->
                <div id="survey_reset" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" >
                        <p id="myModal_initiate_body_msg"> 
                            Are you sure you want to discard previously entered/selected values and re-enter/select the data as a fresh data ?
                        </p>
                        <p>
                            If Yes, Click on <b>Ok</b> button and continue...
                            If No, Click on <b>Cancel</b> button and continue...
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary modal_survey_reset_action" data-dismiss="modal" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                    </div>
                </div>

                <!-- Modal to display delete confirmation message -->
                <div id="file_upload_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom" id="myModal_initiate_head_msg">
                                Delete Confirmation
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" >
                        <p id="myModal_initiate_body_msg"> 
                            Are you sure you want to delete	?
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button data-survey_id="<?php echo $survey_id; ?>" data-crclm_id="<?php echo $crclm_id; ?>" class="btn btn-primary" id="delete_file_upload_docs" name="delete_file_upload_docs" data-dismiss="modal" aria-hidden="true"> <i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i>  Cancel</button>
                    </div>
                </div>
				<input type="hidden" id="delete_file_upload_docs_id" name="delete_file_upload_docs_id"/>
            </div>
        </div>
    </div>
</div>
