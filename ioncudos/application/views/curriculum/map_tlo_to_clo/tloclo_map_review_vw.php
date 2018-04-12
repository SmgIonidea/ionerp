<?php
/** 
* Description	:	Reviewer's List View for TLO(Topic Learning Outcomes) to 
*					CO(Course Outcomes) Module. Selected Curriculum, Term, 
*					Course, Topic & its corresponding TLOs to CLOs mapping grid  
*					is displayed for review process.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
	<title> <?php if(isset($title)) echo $title.' | '; ?> IonCUDOS </title>
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

<body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); auto_load_table_grid();" onpageshow="if (event.persisted) noBack();">
<input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
									Mapping between <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>s) and Course Outcomes (COs) - <?php echo $this->lang->line('entity_topic'); ?>-wise
                                </div>
                            </div>
                            <form class="form-horizontal">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
											<div class="span6 pull-left">
											&nbsp;&nbsp;&nbsp;Curriculum :&nbsp; <b><?php echo $tlo_title[0]['crclm_name'];?></b></div>
											<div class="span6 pull-right">
											Term :&nbsp;<b><?php echo $tlo_title[0]['term_name'];?></b></div>
											<div class="span6 pull-left">
											Course :&nbsp;<b><?php echo $tlo_title[0]['crs_title'];?></b></div>
											<div class="span6 pull-right">
											<?php echo $this->lang->line('entity_topic'); ?> :&nbsp;<b><?php echo $tlo_title[0]['topic_title'];?></b></div>
                                            <div class="span6">
                                                <?php echo form_input($crclm_id); ?>
                                                <?php echo form_input($term); ?>
                                                <?php echo form_input($course); ?>
                                                <?php echo form_input($topic); ?>
                                            </div><!--span6 ends here-->
                                            <div id="tlo_clo_mapping_table_grid" class="bs-docs-example span8 scrollspy-example" style="width: 775px; height:auto; overflow: auto;">
                                            </div>
                                            <div class="span3">
                                                <div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:325px;">	
                                                    <div >
                                                        <p>  Course Outcome(CO) </p>
                                                        <textarea id="clo_statement_id" rows="5" cols="5" disabled>
                                                        </textarea>
                                                    </div>	
                                                    </br>
                                                    <div>
                                                        <p> Notes </p>
                                                        <textarea id="comment_notes_id" rows="5" cols="5" placeholder="Enter text here..." maxlength="200"></textarea>
                                                    </div>
                                                </div><!--span4 ends here-->
                                            </div>   </br> </br> </br>
                                            <div id="approver" type="hidden">
                                            </div>
                                        </div><!--row-fluid ends here-->
                                        </form>			
                                        <?php if ($state_id == 2) { ?>
                                            <div class="pull-right"><br>
                                                <button type="button" id="send_rework_button" class="btn btn-danger"  ><i class="icon-repeat icon-white"></i> Rework </button>
												<button type="button" id="accept_review_button"  class="btn btn-success" ><i class="icon-ok icon-white"></i> Accept </button>
                                            </div>
                                        <?php } ?>
										
                                        <!-- modal to display mapping status -->
                                        <div id="confirmation_review_accept_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="confirmation_review_accept_dialog_window" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                       Accept review of mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
												<p><b>Current step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs has completed.
												<p><b>Next step : </b> Ensure that all topic's mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs is complete.
												<p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"><?php echo $course_owner; ?></b>
												<h4><center>Current State for curriculum : <font color="brown"><b id="curriculum_name" style="color:#E67A17; text-decoration: underline;"><?php echo $tlo_title[0]['crclm_name'];?></b></center></font></h4>
												<img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_of_map_tll_clo.png'); ?>">
												</img> 
											</div>
											<div class="modal-body">
                                                <p> Are you sure you want to accept the entire mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs (<?php echo $this->lang->line('entity_topic'); ?>wise)? </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="send(); dashboard_update_accept();"><i class="icon-ok icon-white"></i> Ok </button> 
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                                            </div>
                                        </div>
										
										<!-- modal to display approval confirmation status -->
                                        <div id="review_accept_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="review_accept_dialog_window" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Review Accepted
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
												<p><b>Current step : </b>Mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs has completed.
												<p><b>Next step : </b> Ensure that all <?php echo $this->lang->line('entity_topic'); ?>'s mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs is complete.
												<p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"><?php echo $course_owner;?></b>
												<h4><center>Current State for curriculum : <font color="brown"><b id="curriculum_name" style="color:#E67A17; text-decoration: underline;"><?php echo $tlo_title[0]['crclm_name'];?></b></center></font></h4>
												<img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_of_map_tll_clo.png'); ?>">
												</img> 
											</div>
											<div class="modal-body">
                                                <p> The entire Mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs(<?php echo $this->lang->line('entity_topic'); ?>wise) has been Accepted.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="review_refresh_all_buttons" onclick=" review_accept();"><i class="icon-ok icon-white"></i> Ok </button> 
                                            </div>
                                        </div>
                                       
									    <!-- modal to display rework confirmation status -->
                                        <div id="confirmation_mapping_send_rework_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="confirmation_mapping_send_rework_dialog_window" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Send for rework confirmation
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
												<p><b>Current step : </b>Rework of mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs.
												<p><b>Next step : </b> Review the mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs.
												<p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"><?php echo $course_owner;?></b>
												<h4><center>Current state for curriculum : <font color="brown"><b id="curriculum_name" style="color:#E67A17; text-decoration: underline;"><?php echo $tlo_title[0]['crclm_name'];?></b></center></font></h4>
												<img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_of_map_tll_clo.png'); ?>">
												</img> 
											</div>
											<div class="modal-body">
                                                <p> Are you sure you want to send entire mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs for rework ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="refresh_hide" onclick="dashboard_update_rework();"><i class="icon-ok icon-white"></i> Ok </button> 
                                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                                            </div>
                                        </div>
										
										<!-- modal to display rework success status -->
										<div id="sent_rework_dialog_window" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="sent_rework_dialog_window" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Send for rework of mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <p><b>Current step : </b>Review mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs has completed.
												<p><b>Next step : </b> Ensure that each <?php echo $this->lang->line('entity_topic'); ?>'s mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs is complete.
												<p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_name" style="color:#E67A17;"><?php echo $course_owner; ?></b>
												<h4><center>Current state for curriculum : <font color="brown"><b id="curriculum_name" style="color:#E67A17; text-decoration: underline;"><?php echo $tlo_title[0]['crclm_name'];?></b></center></font></h4>course_owner
												<img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/review_of_map_tll_clo.png'); ?>">
												</img> 
											</div>
											<div class="modal-body">
                                                <p> Entire mapping between <?php echo $this->lang->line('entity_tlo'); ?>s and COs(<?php echo $this->lang->line('entity_topic'); ?>wise) has been sent for rework successfully.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="refresh_hide" onclick=""><i class="icon-ok icon-white"></i> Ok </button> 
                                            </div>
                                        </div>
										
										<!-- Modal to display saved outcome elements & performance indicators -->
										<div id="myModal_pm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true"></br>
											<div class="container-fluid">
												<div class="navbar">
													<div class="navbar-inner-custom">
														Selected <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators
													</div>
												</div>
											</div>

											<div class="modal-body" id="selected_pm_modal">
												
											</div>

											<div class="modal-footer">
												<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
											</div>
										</div>
                                    </div>
                                </div>
                        </div>
                        <!--Do not place contents below this line-->
                    </section>			
                </div>					
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/reviewer_tlo_clo_map.js'); ?>" type="text/javascript"> </script>