<?php
/**
 * Description		:	Approved Program Outcome grid along with its corresponding Performance Indicators
  and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                    Modified By                             Description
 * 11-01-2014		    Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 11-04-2014		   Jevi V G     	                Added help function. 
 * 03-12-2015		   Neha Kulkarni			Added artifacts function.
 * 04-01-2016		   Shayista 				Added the loading image.
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
    <?php foreach ($curriculum_list as $curriculum):
        if ($crclm_id == $curriculum['crclm_id']) {
            ?>
            <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); select_curriculum();" 
                  onpageshow="if (event.persisted) noBack();">
                  <?php } ?>
<?php endforeach; ?>
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
                    <!-- Contents -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
<?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators (PIs)
                                    <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign "></i></a>
                                    <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
                                        <input type="hidden" id="art_val" name="art_val" value="20"/>Artifacts <i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                                </div>

                            </div>

                            <input type="hidden" id="cloneCntr" value=0>
                            <input type="hidden" id="item_msr" value=0>

                            <div>
                                <p> Curriculum: <font color="red"> * </font>
<?php if ($crclm_id != NULL) { ?>
                                        <select name="curriculum_list" id="curriculum_list" abbr="curriculum_list" disabled="disabled">
                                            <option value=""> Select Curriculum </option>
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
                                            <option value=""> Select Curriculum </option>
                                            <?php foreach ($curriculum_list as $curriculum): ?>
                                                <option value="<?php echo $curriculum['crclm_id']; ?>" > 
                                            <?php echo $curriculum['crclm_name']; ?></option>
    <?php endforeach; ?>
                                        </select>
<?php } ?>
                                </p>
                                <center><b id="oe_pi_mandatory_optional"></b></center>
                                
                                <center><b id="pi_measures_current_state"></b></center>
                            </div>

                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:95px">
                                <table class="table table-bordered table-hover table-font" id="example" aria-describedby="example_info" style="font-size: 12px;">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown" style="width: 90px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> PO Code </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Approved <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> View <?php echo $this->lang->line('outcome_element_short'); ?> & PIs </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> BoS Comments </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> Manage <?php echo $this->lang->line('outcome_element_short'); ?> & PIs </th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">

                                    </tbody>
                                </table>
                            </div>
                            <div class="clear">
                            </div>

                            <div class="span11 pull-right" id="enableAddButton" style="display:none;">
                                <button id="publish" disabled="disabled" class="btn btn-success pull-right" type="submit" style="margin-right:2px;"><i class="icon-user icon-white"></i> Send for Approval </button>
                            </div>
                            <div class="span11 pull-right" id="proceedOePi" style="display:none;">
                                <button id="skipOePi" disabled="disabled" class="btn btn-success pull-right" type="submit" style="margin-right:2px;"><i class="icon-ok icon-white"></i> Proceed to Course </button>
                            </div><br><br>

                            <!-- Modal to display help content -->
                            <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="help" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
<?php echo $this->lang->line('outcome_element_sing_short'); ?> & PIs guideline files
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>

                            <!-- Modal to display send for approval confirmation message -->
                            <div id="myModal_po_pi_msr_list" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_po_pi_msr_list" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
<?php echo $this->lang->line('student_outcomes_full'); ?><?php echo $this->lang->line('student_outcomes'); ?>, <?php echo $this->lang->line('outcome_element_short'); ?> & PIs
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div id="po_pi_msr_list"> 

                                    </div>
                                </div>				
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
                                </div>
                            </div>

                            <!-- Modal to display warning message -->
                            <div id="pi_msr_warning" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="pi_msr_warning" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div> 
                                        You cannot add, edit or delete these <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs) as they are in either "Approval Pending" or "Approved" state.
                                    </div>
                                </div>				
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>	
                                </div>
                            </div>

                            <!-- Modal to display send for approval confirmation message -->
                            <div id="myModal_approval" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_approval" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Send for Approval Confirmation
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <p> <b> Current State: </b> Addition of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators has been completed. </p>
                                    <p> <b> Next State: </b> Approval of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs). </p>
                                    <p> An email will be sent to BoS (Approval authority): <b id="bos_user" style="color:rgb(230, 122, 23);"></b> </p>

                                    <h4><center> Current status of curriculum: <b id="crclm_name_oe_pi" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/oe_pi_list.png'); ?>">
                                </div>

                                <div class="modal-align-left">
                                    <p> Are you sure you want to send it for approval? </p>
                                    <p> You will <span class="badge badge-important"> not </span> be able to ADD, EDIT, DELETE  <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs after this, to current curriculum.
                                    </p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-success" data-dismiss="modal" onClick="publish();"><i class="icon-user icon-white"></i> Send for Approval</button>
                                    <button class="btn btn-success" data-dismiss="modal" onClick="accept();"><i class="icon-ok icon-white"></i> Skip Approval</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button> 
                                </div>
                            </div>

                            <!-- Modal to display proceed to course confirmation message -->
                            <div id="skip_myModal_approval" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="skip_myModal_approval" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Proceed to creation of Course(s) confirmation
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-align-left">
                                    <p> <?php echo $this->lang->line('outcome_element_plu_full'); ?> and PIs are set as <span class="badge badge-important"> optional </span>. Are you sure you want to proceed? </p>
                                    <p> You will <span class="badge badge-important"> not </span> be able to ADD, EDIT, DELETE  <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs after this, to current curriculum.
                                    </p>
                                </div>

                                <div class="modal-body">
                                    <p> <b> Current State: </b> Addition of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators has been completed. </p>
                                    <p> <b> Next State: </b> Addition of all courses. </p>
                                    <p> An email will be sent to <?php echo $this->lang->line('program_owner_full'); ?>: <b id="pgm_owner_user_accept" style="color:rgb(230, 122, 23);"></b> </p>

                                    <h4><center> Current status of curriculum: <b id="crclm_name_oe_pi_accept" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/oe_pi_rework_accept.png'); ?>">
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" onClick="accept();"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button> 
                                </div>
                            </div>

                            <!-- Modal for Error-->
                            <!-- Author : Mritunjay B S Date of Creation : 23/jan/2014 -->
                            <div id="myModal_error" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_error" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
<?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs error message
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="error_table">

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                                </div>
                            </div><!-- Modal for Error Ends here-->

                            <!--Modal to display artifact content-->
                            <form id="myform" name="myform" action="" method="POST" enctype="multipart/form-data" >
                                <div class="modal hide fade" id="mymodal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Upload Artifacts
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body">
                                        <div id="art"></div>
                                        <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                            <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger pull-right" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>		
                                        <button class="btn btn-primary pull-right" style="margin-right: 2px; margin-left: 2px;" id="save_artifact" name="save_artifact" value=""><i class="icon-file icon-white"></i> Save </button>
                                        <button class="btn btn-success pull-right" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
                                    </div>
                                </div>
                            </form>

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
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button>			
                                </div>
                            </div>

                            <!--Delete Modal--->
                            <div class="modal hide fade" id="delete_file" name="delete_file" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
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
                                    <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i>Cancel</button>
                                    <button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i>Ok</button>			    					
                                </div>
                            </div>

                            <!--Error Modal for file name size exceed-->
                            <div class="modal hide fade" id="file_name_size_exc" name="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Error Message
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
                                    <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>									
                                </div>
                            </div>

                            <!--Warning modal for selecting the curriculum-->
                            <div id="select_crclm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false"></br>
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <p> Select the Curriculum dropdown.</p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                                </div>
                            </div>

                    </section>
                </div>
            </div>
        </div>
    </div>
    <!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_and_measures.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_msr_approver.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
    <script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
    <script>

    var outcome_element = "<?php echo $this->lang->line('outcome_element_plu_full'); ?>";
	var student_outcomes_full = "<?php echo $this->lang->line('student_outcomes_full'); ?>";
	var sos = "<?php echo $this->lang->line('sos'); ?>";



    </script>

    <!-- End of file list_po_pi_msr_vw.php 
            Location: .curriculum/pi_measures/list_po_pi_msr_vw.php -->
