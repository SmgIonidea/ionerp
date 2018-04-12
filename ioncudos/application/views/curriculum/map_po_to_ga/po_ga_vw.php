<?php
/**
* Description		:	List View for GA(Graduate Attributes) to PO(Program Outcomes) Mapping Module.
* Created		:	24-03-2015. 
* Modification History:
* Date				Author			Description
* 24-03-2015		      Jevi V. G.             Added file headers, function headers, indentations & comments.
* 03-12-2015		      Neha Kulkarni	     Added artifacts function.
* 04-01-2015		      Shayista Mulla	             Added loading image and cookie.
* 11-03-2016		      Shayista Mulla		Changed warning message statement,UI improvement.
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
                                    Mapping between Graduate Attributes (GAs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> 
                                    <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
				    <a href="#" id="artifacts_modal" role="button" class="pull-right art_facts" data-toggle="modal" style="text-decoration: none; color: white; font-size:12px">
<input type="hidden" id="art_val" name="art_val" value="26"/>Artifacts <i class="icon-white icon-tags"></i><?php echo str_repeat("&nbsp;", 5); ?></a>
                                </div>
                            </div>
							
							<div class="span12">
                                <p> Curriculum: <font color="red"> * </font>

                                    <select name="curriculum_list" id="curriculum_list" autofocus = "autofocus" onChange = "javaScript:select_curriculum();">
                                        <option value="Select Curriculum"> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option data-id= "<?php echo $curriculum['pgm_id'];?>" value="<?php echo $curriculum['crclm_id']; ?>"  <?php
                                            if ($crclm_id == $curriculum['crclm_id']) {
                                                echo "selected=selected";
                                            }
                                            ?>> <?php echo $curriculum['crclm_name']; ?></option>
											<?php endforeach; ?>
                                    </select>
									<font color="red" ><p id="error"></p></font>
								</p>
								
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
										<div class="bs-docs-example span8 scrollspy-example" style="width: 100%; height:auto; overflow:auto;" >
											<form method="post" id="frm" name="frm" action="<?php echo base_url('curriculum/map_po_peo/send_for_Approve'); ?>" onsubmit="">
												<input type="hidden" id="crclm_id" name="crclm_id" >
												<div id="mapping_table">
												</div>
											</form>	
										</div>
											
                                        <!-- Modal to display help content -->
                                        <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true" >
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Mapping between GAs and <?php echo $this->lang->line('sos'); ?> guidelines
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body" id="po_ga_help_content_id">	
                                            </div>				  
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <!--<div class="pull-right" id="send_approve_div_id"><br>
                                        <button id="send_approve_button_id" class="btn btn-success"><i class="icon-user icon-white"></i> Save </button>
                                    </div>-->
                                </div>
                        </div>
						<!-- Modal to display the confirmation before unmapping -->
                        <div id="uncheck_mapping_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="uncheck_mapping_dialog_id" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
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
                                <button class="btn btn-primary" data-dismiss="modal" onClick="unmapping();"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="cancel btn btn-danger" data-dismiss="modal" onClick="cancel_uncheck_mapping_dialog();"><i class="icon-remove icon-white"></i> Cancel</button> 
                            </div>
                        </div>
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
				    	<img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
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
				Are you sure you want to delete?
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
				<button type="button" id="delete_selected" class="btn btn-primary btn-sm pull-right" style="margin-right: 2px;"><i class="icon-ok icon-white"></i> Ok</button>
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
		   <div id="select_crclm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
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
											
                        <!--Do not place contents below this line-->
                    </section>			
                </div>					
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po_ga_map.js'); ?>" type="text/javascript"> </script>
		<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
		<script src="<?php echo base_url('twitterbootstrap/js/upclick_artifacts.js'); ?>"></script>
	
		
