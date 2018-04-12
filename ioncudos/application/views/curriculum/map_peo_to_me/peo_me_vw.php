<?php
/**
* Description	:	View Logic for PEOs to MEs Mapping Module.
* Created		:	24-12-2014 
* Modification History:
* Date				Modified By				Description
* 27-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

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
    <?php foreach ($curriculum_list as $curriculum):
    if ($crclm_id == $curriculum['crclm_id']) {  ?>
		<body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack(); select_curriculum();" onpageshow="if (event.persisted) noBack();">
	<?php }
    ?>
	<?php endforeach; ?>
    <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
		<input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
				<div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
						</div>
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Mapping between Program Educational Objectives (PEOs) and Mission Elements(MEs)
                                    <!--<a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Help&nbsp;<i class="icon-white icon-question-sign"></i></a>-->
                                </div>
                            </div>
							<div class="span12">
                                <p> Curriculum: <font color="red"> * </font>
                                    <select name="curriculum_list" id="curriculum_list" autofocus = "autofocus" onChange = "javaScript:select_curriculum(); ">
                                        <option value="Select Curriculum"> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>" <?php
                                            if ($crclm_id == $curriculum['crclm_id']) {
                                                echo "selected=selected";
                                            }
                                            ?> > <?php echo $curriculum['crclm_name']; ?></option>
											<?php endforeach; ?>
                                    </select>
									<font color="red" ><p id="error"></p></font>
								</p>
								
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                   
                                        
                                            <div class="bs-docs-example span8 scrollspy-example" style="width: 70%; height:auto; overflow:auto;" >
                                                <form method="post" id="frm" name="frm"  onsubmit="">
                                                    <input type="hidden" id="crclm_id" name="crclm_id" >
                                                    <div id="mapping_table">
                                                    </div>
                                                </form>	
                                            </div>
                                        
                                        <div class="span3">
                                            <div data-spy="scroll" class="bs-docs-example span3" style="width:100%; height:500px;">	
                                                <div >
                                                    <p> Mission Elements (MEs) </p>
                                                    <textarea id="me_display_textbox_id" rows="5" cols="5" disabled>
                                                    </textarea>
                                                </div>
												
                                            </div><!--span4 ends here-->
                                        </div>
                                        <!-- Modal to display help content -->
                                        <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Mapping between PEOs and MEs guideline files
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body" id="po_peo_help_content_id">	
                                            </div>				  
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>Close</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="state" id="state" />
                                    <div class="pull-right" id="send_approve_div_id" style="visibility:hidden"><br>
                                       <!-- <button id="send_approve_button_id" class="btn btn-success"><i class="icon-user icon-white"></i> Save </button>		-->								
                                    </div>
                                    </form>
                                </div>
                           
                        </div>
						<!-- Modal to display the confirmation before unmapping -->
                        <div id="uncheck_mapping_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="uncheck_mapping_dialog_id" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Uncheck mapping confirmation
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Are you sure that you want to uncheck the mapping? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> Ok </button>
                                <button class="cancel btn btn-danger" data-dismiss="modal" onClick="cancel_uncheck_mapping_dialog();"><i class="icon-remove icon-white"></i><span></span> Cancel </button> 
                            </div>
                        </div>
						<!-- Modal to display the mapping status before sending for confirmation -->
                        <div id="incomplete_mapping_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="incomplete_mapping_dialog_id" data-backdrop="static" data-keyboard="true"></br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Mapping between PEOs and MEs Status 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Entire mapping between PEOs and MEs has to be completed.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo_me_map.js'); ?>" type="text/javascript"> </script>
		