<?php
/**
* Description	:	Add View for Course Module.
* Created		:	09-04-2013. 
* Modification History:
* Date				Modified By				Description
* 10-09-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
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
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/font_override.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/tagmanager/tagmanager.css'); ?>" media="screen" />
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

<body data-spy="scroll" data-target=".bs-docs-sidebar">
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
				<?php  $this->load->view('includes/sidenav_1'); ?>
				<div class="span10">
			<!-- Contents -->
			<section id="contents">
			<div class="bs-docs-example">
			<!--content goes here-->
			<div class="navbar">
			 <div class="navbar-inner-custom">
				Curriculum Adequacy Report
			 
			 </div>
			</div>	
			<form class="form-horizontal" method="POST" id="add_form_id"  name="add_form_id" action="<?php echo base_url('configuration/adequacy_report/insert_to_cc');?>" >
					<div class="row-fluid">
					  <div class="span12">
							<div class="control-group">
                                    <label class="control-label cursor_default" style="padding-top:0px;" for="course">Report Name: </label>
                                         <div class="controls">
                                                <b><?php 
												    echo $report_name;
												   ?></b>
                                         </div>
                                </div>
							<input type="hidden" name="report_id" id="report_id" value="<?php echo $email_to_cc_data['report_name'][0]['report_id']; ?>" >
							<div class="control-group tm-group">
					          <p class="control-label" for="">To: </p>
								<div class="controls">
									<input type="text" id="fetch_email_ids_to" autocomplete="off" data-items="6" data-provide="typeahead" name="tags_to" placeholder="Enter Email Id "  class="tm-input tm-tag span8" data-original-title="">
								</div>
				             </div>
							 <div class="control-group">
					          <p class="control-label" for="">CC: </p>
								<div class="controls">
									<input type="text" id="fetch_email_ids_cc" autocomplete="off" data-items="6" data-provide="typeahead" name="tags_cc" placeholder="Enter Email Id"  class="tm-input_cc tm-tag span8" data-original-title="">
								</div>
				             </div>
							
					  </div><!--span12 ends here-->
					</div>
					<div id="csv_table">
					</div>
					
					<div class="pull-right">       
					<button class="add_form_submit_id btn btn-primary" id="submit_add" type="submit"><i class="icon-file icon-white"></i> Save</button>
					<!--<button type="button" class="btn btn-primary" id="generate" name="generate" ><i class="icon-eye-open icon-file icon-white"></i> Anaylse </button>-->
					<a href= "<?php echo base_url('configuration/adequacy_report'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
					</div>
				</form>
				
				
				<!-- Modal to display delete confirmation message -->
                            <div id="myModal_emptyto" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_emptyto" data-backdrop="static" data-keyboard="true"></br>
                                <div class="container-fluid">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                           Alert 
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <p> 'To' cannot be empty. </p>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_clo();"> <i class="icon-ok icon-white"></i> Ok </button>
                                   
                                </div>
                            </div>
							<br>
			<table class="table table-bordered">
              <thead>
                <tr>
                  <th style="font-size:12px;">Department</th>
                  <th style="font-size:12px;">Program</th>
                  <th style="font-size:12px;">Curriculum</th>
                  <th style="font-size:12px;">Curriculum Owner</th>
                  <th style="font-size:12px;">PEO(Count)</th>
                  <th style="font-size:12px;">PO(Count)</th>
                  <th style="font-size:12px;">Courses(Count)</th>
                  <th style="font-size:12px;">Curriculum Total Credits</th>
                  <th style="font-size:12px;">Courses(Defined Credits)</th>
                  <th style="font-size:12px;">Topic(Count)</th>
                  <th style="font-size:12px;"><?php echo $this->lang->line('entity_tlo_singular'); ?>(Count)</th>
                </tr>
              </thead>
              <tbody>
			  <?php $i=0; foreach ($generate_csv_data as $result): ?>
			  
                <tr>
                   <td style="font-size:12px;"><?php echo $result['Department'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Program'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Curriculum'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Curriculum Owner'];?></td>
                  <td style="font-size:12px;"><?php echo $result['PEO(count)'];?></td>
                  <td style="font-size:12px;"><?php echo $result['PO(count)'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Courses(count)'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Curriculumn Total Credits'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Courses(Defined Credits)'];?></td>
                  <td style="font-size:12px;"><?php echo $result['Topic(count)'];?></td>
                  <td style="font-size:12px;"><?php echo $result['TLO(count)'];?></td>
				 
                </tr>
                <?php $i++; endforeach;?>
              </tbody>
            </table>
				<br>

	</div>
	<head>
		<script src="<?php echo base_url('twitterbootstrap/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo base_url('twitterbootstrap/tagmanager/tagmanager.js'); ?>"></script>
	</head>
		<script>
			jQuery(".tm-input").tagsManager({
      prefilled: null,
      CapitalizeFirstLetter: false,
      preventSubmitOnEnter: true, // deprecated
      isClearInputOnEsc: true, // deprecated
      AjaxPush: null,
      AjaxPushAllTags: null,
      AjaxPushParameters: null,
      delimiters: [9, 13, 44], // tab, enter, comma
      backspace: [8],
      maxTags: 0,
      hiddenTagListName: null, // deprecated
      hiddenTagListId: null, // deprecated
      replace: true,
      output: null,
      deleteTagsOnBackspace: true, // deprecated
      tagsContainer: null,
      tagCloseIcon: 'x',
      tagClass: '',
      validator: null,
      onlyTagList: false,
	  
    });
	
		jQuery(".tm-input_cc").tagsManager({
      prefilled: null,
      CapitalizeFirstLetter: false,
      preventSubmitOnEnter: true, // deprecated
      isClearInputOnEsc: true, // deprecated
      AjaxPush: null,
      AjaxPushAllTags: null,
      AjaxPushParameters: null,
      delimiters: [9, 13, 44], // tab, enter, comma
      backspace: [8],
      maxTags: 0,
      hiddenTagListName: null, // deprecated
      hiddenTagListId: null, // deprecated
      replace: true,
      output: null,
      deleteTagsOnBackspace: true, // deprecated
      tagsContainer: null,
      tagCloseIcon: 'x',
      tagClass: '',
      validator: null,
      onlyTagList: false,
	  
    });
			
	
		</script>
<!--ends here-->
			<br>
			<!--Do not place contents below this line-->	
			</div>
			</section>
		</div>
	</div>
</div>
	<!---place footer.php here -->
	<?php  $this->load->view('includes/footer'); ?> 
	<!---place js.php here -->
	<?php  $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/adequacy_report.js'); ?>" type="text/javascript"> </script>

