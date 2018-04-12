<?php
/**
 * Description		:	PEO grid displays the PEO statements and PEO type.
  PEO statements can be deleted individually and can be edited in bulk.
  Notes can be added, edited or viewed.
  PEO statements will be published for final approval.
 * 					
 * Created		:	01-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *    Date                  Modified By                			Description
 *  05-09-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 *  03-12-2015		   Neha Kulkarni			Added artifacts function.
 *  02-01-2016		   Shayista Mulla 			Added loading image and cokie.
 *  21-04-2016         Bhagyalaxmi S S          Addedd map_level weightage to the peo to me mapping 
  ---------------------------------------------------------------------------------------------- */
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
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <input type="hidden" id="peo_id" name="peo_id"/>
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">

                            <span class="control-label" data-key="lg_peos_lists"> Program Educational Objectives (PEOs) List </span>
                            <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;" ><span data-key="lg_guidelines">Guidelines</span>&nbsp;<i class="icon-white icon-question-sign "></i></a>
                            <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
                                <input type="hidden" id="art_val" name="art_val" value="5"/><span data-key="lg_artifacts">Artifacts </span><i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                        </div>
                    </div>
                    <button id="add_peo_one" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> <span data-key="lg_add_more">Add PEO</span></button>
                    <div>
                        <label >
                            <span data-key="lg_crclm">Curriculum</span>:<font color="red"> * </font>
                            <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" method="post">
                                <option value="" selected data-key="lg_sel_crclm"> Select the Curriculum </option>
                                <?php foreach ($results as $list_item): ?>
                                    <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                <?php endforeach; ?>
                            </select>

                        </label>
                    </div>
                    <center><b id="peo_current_state"></b></center>
                    <div>
                        <div >
                            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                <thead>
                                    <tr role="row">
                                        <th class="header headerSortDown" style = "width: 60px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Sl No.</th>
                                        <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Program Educational Objectives(PEO) </th>
                                        <th class="header headerSortDown" style = "width: 40px;" role="columnheader" tabindex="0"  >Edit</th>
                                        <th class="header" style = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example"> Delete </th>
                                    </tr>
                                </thead>
                                <tbody id="peolist">
                                    <!--refer list_peo_table_vw page-->
                                </tbody>
                            </table>
                        </div>
                        </br></br>

                        <!-- Modal to display the confirmation message before deleting PEO statement -->
                        <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_delete" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <span data-key="lg_del_conf">Delete Confirmation </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <p data-key="lg_delete_conf" data-key="lg_delete_conf"> Are you sure you want to Delete? </p>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary delete_peo" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                            </div>
                        </div>

                        <!-- Modal to display PEO statement notification -->
                        <div id="myModal_notification" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_delete" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom" data-key="lg_warning">
                                        Warning
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <p data-key="lg_cant_del_peo_stmt"> You cannot delete this PEO as it is in approved state.</p>
                            </div>

                            <div class="modal-footer">

                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close"> Ok</span></button>
                            </div>
                        </div>

                        <!--Error Msg When drop down box is not selected Modal-->
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom" data-key="lg_warning">
                                    Warning
                                </div>
                            </div>
                            <div class="modal-body">
                                <p data-key="lg_sel_crclm"> You have missed to select the Curriculum drop-down. 
                                    Please select the drop-down before you proceed to add PEO. </p>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="cancel btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close"> Ok</span></button>
                            </div>
                        </div>




                        <!-- Modal to display help content -->
                        <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" style="display: none; width:610px;">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom" data-key="lg_peo_guideline_file">
                                        Program Educational Objectives(PEOs) guideline files
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body" id="help_content">

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>
                            </div>	
                        </div>
                    </div><br>
                    <br>
                    <div class="row">
                        <button  id="map_peo_to_me" class="btn btn-success pull-left" type="submit" style="margin-left: 20px;"><i class="icon-hand-right icon-white"></i> <span data-key="lg_map_mission">Map to mission</span></button>

                        <button  id="publish" disabled class="btn btn-success pull-right" type="submit"><i class="icon-file icon-white"></i> <span data-key="lg_proceed_po">Proceed to <?php echo $this->lang->line('so'); ?> </span></button>

                        <button  id="add_peo_button" class="btn btn-primary pull-right add_btn" style="margin-right: 2px;"><i class="icon-plus-sign icon-white"></i> <span data-key="lg_add_more">Add PEO</span></button>
                    </div>

                    <!-- Modal to display the confirmation message before Publishing PEO statement -->
                    <div id="my_modal_publish" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" style="font-size:17px;" data-key="lg_create_po_conf">
                                    Proceed to Creation of <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p><b><span data-key="lg_current_st"> Current State</span>: </b><span data-key="lg_add_peo_comp"> Addition of Program Educational Objectives (PEOs) has been completed. </span></p>
                            <p><b><span data-key="lg_next_st"> Next State</span>: </b><span data-key="lg_add_po">Addition of <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>).</span> </p>
                            <p><span data-key="lg_email_sent_chairman"> An email will be sent to Chairman</span>: <b id="chairman_user" style="color:rgb(230, 122, 23);"></b> </p>

                            <h4><center><span data-key="lg_current_crclm_status"> Current status of curriculum</span>: <b id="crclm_name_peo" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                            <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/peo_list_img.png'); ?>">
                        </div>

                        <div class="modal-body">
                            <p data-key="lg_sure_crt_po"> Are you sure you want to proceed to creation of <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>)? </p>
                            <p><span data-key="lg_you_will">You will</span> <span class="badge badge-important" data-key="lg_not"> not </span><span data-key="lg_operation_one_stmt"> be able to ADD, DELETE PEOs after this, to current curriculum.</span>
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button class="submit btn btn-primary publish_po" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button> 
                            <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
                        </div>
                    </div>
                    <!-- Modal to display the confirmation message before Mapping Mission -->
                    <div id="my_map_mission" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 1070px;
                         margin-left: -540px;display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom" style="font-size:17px;" data-key="lg_map_bet_peo_misele">
                                    Mapping between PEOs and Mission Elements (MEs)
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">							
                            <div id="loading_data" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                            </div>
                            <div class="span12">
                                <div class="bs-docs-example span12 scrollspy-example" style="width: 100%; height:auto; overflow:auto;" >
                                    <form method="post" id="frm" name="frm"  onsubmit="">
                                        <input type="hidden" id="crclm_id" name="crclm_id" >
                                        <div id="peomeList_vw">
                                        </div>

                                        <div id="peomeList_admin_vw">
                                        </div>

                                        <div data-spy="scroll" class=" span5" id="side_bar" style="width:100%;">	
                                            <div id="note" class="span10">
                                                <p> Justification </p>
                                                <textarea style="width:100%" id="peo_me_comment_box_id" rows="3" cols="5" placeholder="Enter text here..." ></textarea>
                                            </div>
                                        </div>
                                    </form>	
                                </div>
                            </div>							
                        </div>
                        <br>
                        <div class="modal-footer">
                            <br>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>       
                        </div>
                    </div>

                    <!-- Modal to display the confirmation before unmapping -->
                    <div id="uncheck_mapping_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="uncheck_mapping_dialog_id" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_uncheck_map_conf">
                                    Uncheck mapping confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p data-key="lg_sure_uncheck_map"> Are you sure that you want to uncheck the mapping? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                            <button class="cancel btn btn-danger" data-dismiss="modal" onClick="cancel_uncheck_mapping_dialog();"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
                        </div>
                    </div>

                    <!-- Modal to display warning if curriculum dropdown is not selected - for adding PEOs -->
                    <div id="select_crclm_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_warning">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p data-key="lg_sel_crclm_dropdown"> Select Curriculum drop-down to Add Program Educational Objectives (PEOs). </p>
                        </div>


                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button> 
                        </div>
                    </div>

                    <!--Modal to display artifact content-->
                    <form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
                        <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="mymodal" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom" data-key="lg_upload_artifacts">
                                        Upload Artifacts
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <div id="art"></div>
                                <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                    <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger pull-right" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>			
                                <button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>
                                <button class="btn btn-success pull-right" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
                            </div>
                        </div>
                    </form>

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
                            Are you sure you want to delete?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                            <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
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

                    <!--Warning modal for selecting the curriculum-->
                    <div id="select_crclm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_warning">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" data-key="lg_sel_crclm_dropdown">
                            <p> Select the Curriculum drop-down.</p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close">Ok</span></button> 
                        </div>
                    </div>

                    <div id="edit_peo_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="edit_peo_modal" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom" data-key="lg_edit_peo">
                                    Edit PEO
                                </div>
                                <b id="crclm_name_text"></b>
                            </div>
                        </div>
                        <form id="peo_edit_form">
                            <div class="modal-body">
                                <div class="" style="">
                                    <div class="control-group">
                                        <label class="control-label" for="peo_reference"><span data-key="lg_peo_ref"> Program Educational Objective(PEO) Reference</span>: <font color="red"> * </font>&nbsp;&nbsp;
                                            <input class="input-mini" type="text" id="peo_state" name="peo_state" required/>	
                                        </label>										
                                    </div>

                                    <p class="control-label" for="peo_statement"><span data-key="lg_peo_stmt">Program Educational Objective(PEO) Statement</span>: <font color="red"> * </font></p>
                                    <div class="controls" >			
                                        <textarea id="peo_statement" class="char-counter" maxlength="2000" name="peo_statement" style="margin: 0px; width: 495px; height: 50px;" required></textarea>
                                        <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                    </div><br>

                                    <div id="attendees" class="control-group">
                                        <p class="control-group" for="attendees"><span data-key="lg_attendees_name"> Attendees Name</span>: <font color="red"> * </font></p>
                                        <div class="controls">
                                            <?php //echo form_textarea($attendees); ?>
                                            <textarea id="attendees_name" name="attendees_name" style="margin: 0px; width: 495px; height: 50px;" required></textarea>
                                        </div>
                                    </div>

                                    <div id="notes" class="control-group">
                                        <p class="control-label" for="notes"><span data-key="lg_attendees_notes"> Attendees Notes</span>: </p>
                                        <div class="controls">
                                            <?php //echo form_textarea($notes); ?>
                                            <textarea id="attendees_notes" name="attendees_notes" style="margin: 0px; width: 495px; height: 50px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="modal-footer">
                            <button class="btn btn-primary" id="peo_update" name="peo_update"><i class="icon-file icon-white"></i> <span data-key="lg_update">Update</span></button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Cancel</span></button> 
                        </div>
                    </div>

                    <!--Unmap modal-->
                    <div id="uncheck_mapping" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"  data-controls-modal="uncheck_mapping" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Unmap mapping confirmation
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure you want to unmap the mapping between PEO and  Mission Elements? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" id="test" onClick=" unmapping_new();"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="cancel btn btn-danger" data-dismiss="modal"  onClick="select_curriculum();"><i class="icon-remove icon-white"></i> Cancel</button> 

                        </div>
                    </div>

                    <!-- Modal for duplicate PEO Statement-->
                    <div id="duplicate_peo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="duplicate_peo" aria-hidden="true" data-controls-modal="duplicate_peo" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p data-key="lg_delete_conf" data-key="lg_delete_conf"> This PEO Statement already exists. </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close">Ok</span></button> 
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo.js'); ?>" type="text/javascript"></script>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<!-- End of file list_peo_vw.php 
Location: .curriculum/peo/list_peo_vw.php -->
