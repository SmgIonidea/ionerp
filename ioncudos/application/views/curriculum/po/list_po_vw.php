<?php
/**
 * Description		:	To display, add and edit Program Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                   Modified By                         	Description
 * 21-10-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 * 03-12-2015		   Neha Kulkarni			Added artifacts function.
 * 04-01-2015		   Shayista Mulla 			Added loading image and cookie.
 *  11-03-2016		   Shayista Mulla			Changed warning message statement,full point,UI improvement.
  ---------------------------------------------------------------------------------------------- */
?>
<html lang="en">
    <head>	
        <meta charset="utf-8">
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php if (isset($title)) echo $title . ' | '; ?> IonCUDOS </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/docs.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery-ui-custom.min.css'); ?>" media="screen" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/datepicker.css'); ?>" media="screen" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.jqplot.min.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/yearpicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-datepicker.min.css'); ?>" media="screen" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap.min.css'); ?>" media="screen" />-->
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/ico/favicon.png">

    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); select_curriculum();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>

        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <span data-key="lg_pos_list"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> List</span>
                                    <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;" data-key="lg_guidelines"> Guidelines&nbsp; <?php echo $crclm_id; ?><i class="icon-white icon-question-sign"></i></a>
                                    <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
                                        <input type="hidden" id="art_val" name="art_val" value="6"/><span data-key="lg_artifacts">Artifacts </span> <i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                                </div>
                            </div>
                            <button id="add_po_top" class="btn btn-primary pull-right"><i class="icon-plus-sign icon-white"></i> <span data-key="lg_add">Add <?php echo $this->lang->line('so'); ?></span></button>
                            <div>
                                <label><span data-key="lg_crclm"> Curriculum: </span><font color="red"> * </font>
                                    <?php if ($crclm_id != NULL) { ?>
                                        <select name="curriculum_list" id="curriculum_list" abbr="curriculum_list" onChange = "select_curriculum();">
                                            <option value="" data-key="lg_sel_crclm"> Select Curriculum </option>
                                            <?php foreach ($curriculum_list as $curriculum): ?>
                                                <option value="<?php echo $curriculum['crclm_id']; ?>" <?php
                                                if ($crclm_id == $curriculum['crclm_id']) {
                                                    echo "selected=selected";
                                                }
                                                ?> >
                                                            <?php echo $curriculum['crclm_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php } else { ?>
                                        <select name="curriculum_list" id="curriculum_list" autofocus = "autofocus" abbr="curriculum_list" onChange = "select_curriculum();">
                                            <option value="" data-key="lg_sel_crclm"> Select Curriculum</option>
                                            <?php foreach ($curriculum_list as $curriculum): ?>
                                                <option value="<?php echo $curriculum['crclm_id']; ?>" > 
                                                    <?php echo $curriculum['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php } ?>
                                </label>
                                <center><b id="po_current_state"></b></center>
                            </div>

                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:95px">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info" style="font-size: 12px;">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown" style="width: 100px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <span data-key="lg_slno">Sl No. </span></th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Statements </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 80px;" role="columnheader" tabindex="0" aria-controls="example"> <?php echo $this->lang->line('so'); ?> Type </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 200px;" role="columnheader" tabindex="0" aria-controls="example"> BoS Approval Details </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 40px;" role="columnheader" tabindex="0" aria-controls="example"> Edit </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 50px;" role="columnheader" tabindex="0" aria-controls="example"> Delete </th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">

                                    </tbody>
                                </table>
                                <div><b><font color="blue">Note : Statement(s) in blue color are <?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>).</font></b></div>
                            </div>

                            <div class="clear">
                            </div>

                            <div class="span11 pull-right" id="enableaddbutton" >
                                <button  id="publish" disabled="disabled" class="btn btn-success pull-right" type="submit" style="margin-right: 2px;" ><i class="icon-user icon-white"></i> <span data-key="lg_send_approval">Send for Approval</span></button>

                                <button id="add_po_bottom" class="btn btn-primary pull-right" style="margin-right: 2px;"><i class="icon-plus-sign icon-white"></i> <span data-key="lg_add">Add <?php echo $this->lang->line('so'); ?></span></button>
                            </div></br></br>

                            <!-- Modal to display help contents related to Program outcomes -->
                            <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" data-key="lg_pos_help_files">
                                            <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> help files
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>
                                </div>
                            </div>

                            <!-- Modal to display delete confirmation message -->
                            <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom" data-key="lg_del_conf">
                                        Delete Confirmation
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p data-key="lg_delete_conf"> Are you sure you want to Delete? </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="delete_po btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                                </div>
                            </div>

                            <div id="modal_cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> You cannot delete this <?php echo $this->lang->line('student_outcome_full'); ?> as it is in approved state. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>

                                </div>
                            </div>
                            <div id="modal_cant_delete_pending" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> You cannot delete this <?php echo $this->lang->line('student_outcome_full'); ?>, as it is in approval pending state. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>

                                </div>
                            </div>
                            <div id="modal_cant_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> You cannot edit this <?php echo $this->lang->line('student_outcome_full'); ?>, as it is in approval pending state. </p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>

                                </div>
                            </div>

                            <!-- Modal to display warning if curriculum dropdown is not selected - for adding POs -->
                            <div id="select_crclm_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" data-key="lg_warning">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" data-key="lg_sel_crclm_dropdown">
                                    <p> Select Curriculum drop-down to Add <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>). </p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button> 
                                </div>
                            </div>

                            <!-- Modal to display approval confirmation -->
                            <div id="myModal_confirmation" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_confirmation" data-backdrop="static" data-keyboard="false">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" data-key="lg_send_approval">
                                            Send for Approval
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <p><b><span data-key="lg_current_st"> Current State</span>: </b><span data-key="lg_add_pos_comptd"> Addition of <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> has been completed. </span></p>
                                    <p><b><span data-key="lg_next_st"> Next State</span>: </b> <span data-key="lg_apprvl_pos"> Approval of  <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>. </p>
                                    <p><span data-key="lg_email_bos_apprvl_auth"> An email will be sent to BoS (Approval authority)</span>: <b id="bos_user" style="color:rgb(230, 122, 23);"></b> </p>

                                    <h4><center><span data-key="lg_current_crclm_status"> Current status of curriculum </span>: <b id="crclm_name_po" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/po_list_img.png'); ?>">
                                </div>

                                <div class="modal-align-left">
                                    <p data-key="lg_sure_send_pos_apprvl"> Are you sure you want to send  <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> for approval? </p>
                                    <p><span data-key="lg_you_will"> You will</span> <span class="badge badge-important" data-key="lg_not"> not </span> <span data-key="lg_operation_two_stmt">be able to DELETE <?php echo $this->lang->line('sos'); ?> after this, to current curriculum.</span>
                                    </p>
                                </div>
                                <?php if ($approval_flag[0]['skip_approval'] == 1) { ?>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" data-dismiss="modal" onclick="po_creator_publish();"><i class="icon-user icon-white"></i> <span data-key="lg_send_approval">Send for Approval</span></button>								
                                        <button id="skip_approve_button_id" class="btn btn-success" data-dismiss="modal" onclick="publish();"><i class="icon-ok icon-white"></i> <span data-key="lg_skip_apprvl">Skip Approval</span></button>
                                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
                                    </div>
                                <?php } else { ?>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" data-dismiss="modal" onclick="po_creator_publish();"><i class="icon-user icon-white"></i> <span data-key="lg_send_approval">Send for Approval</span></button>								

                                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button> 
                                    </div>
                                <?php } ?>

                            </div>
                        </div>

                        <!--Modal to display artifact content-->
                        <form id="myform" name="myform" action="" method="POST" enctype="multipart/form-data" >
                            <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            <span data-key="lg_upload_artifacts">Upload Artifacts</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" >
                                    <div id="art"></div>
                                    <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                        <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>								
                                    <button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>							
                                    <button class="btn btn-success pull-right" style="margin-right: 2px;" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload </button>        				    					
                                </div>
                            </div>
                        </form>

                        <!--Warning Modal for Invalid File Extension--->
                        <div class="modal hide fade" id="file_extension" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <span data-key="lg_warning">Warning</span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <span data-key="lg_invalid_file_ext">Invalid File Extension.</span>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="icon-ok icon-white"></i> <span data-key="lg_close"> Ok</span></button>			
                            </div>
                        </div>

                        <!--Delete Modal--->
                        <div class="modal hide fade" id="delete_file" name="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        <span data-key="lg_del_conf"> Delete Confirmation </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <span data-key="lg_delete_conf"> Are you sure you want to Delete? </span>
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
                                    <div class="navbar-inner-custom">
                                        <span data-key="lg_err_msg"> Error Message </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <span data-key="lg_file_name_too_long"> File name is too long. </span>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>
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

                            <div class="modal-body">
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
                                    <div class="navbar-inner-custom">
                                        <span data-key="lg_warning"> Warning </span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-body">
                                <p data-key="lg_sel_crclm_dropdown"> Select the Curriculum drop-down.</p>
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

        <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
        <script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
        <script>
                                            var student_outcome = "<?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>";
                                                var student_outcome_short = "<?php echo $this->lang->line('student_outcome'); ?>";
                                                var so = "<?php echo $this->lang->line('so'); ?>";
                                                var student_outcomes = "<?php echo $this->lang->line('student_outcomes_full'); ?><?php echo $this->lang->line('student_outcomes'); ?>";
                                                var student_outcomes_short = "<?php echo $this->lang->line('student_outcomes'); ?>";
                                                var sos = "<?php echo $this->lang->line('sos'); ?>";
        </script>
