<?php
/**
* Description	:	Model(Database) Logic for Course Module(Add).
* Created		:	09-04-2013. 
* Modification History:
* Date				Modified By				Description
* 27-11-2014		Jevi V. G.        Added file headers, function headers, indentations & comments.

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
			<form class="form-horizontal" method="POST" id="edit_form_id"  name="edit_form_id" action="<?php echo base_url('configuration/adequacy_report/update_to_cc');?>" >
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
                                                        <input type="text" id="fetch_email_ids_to" autocomplete="off" data-items="6" data-provide="typeahead" name="tags_to" placeholder="Enter Email Id "  class="tm-input tm-tag span8" data-original-title="" >
														
                                                        <div>
															<?php 
																$to_email_list = explode(",", $email_to_data);
																$i=0;
															foreach ($to_email_list as $item): ?>
                                                                <span class="tm-tag" id="remove_to_<?php echo $i; ?>"><span ><?php echo $item; ?></span><a href="#" class="tm-tag-remove delete_email_to" id="tag_to_remove_<?php echo $i; ?>" tagidtoremove="" title="Remove" email_to_val ="<?php echo $item.',';?>">x</a></span>
															<?php $i++; endforeach; ?>		
                                                        </div>
														<input type="hidden" name="email_val" id="email_val" value="<?php echo $email_to.","; ?>"/> 
                                                    </div>
                                                    
                                                </div>
												
												
							 			<div class="control-group tm-group">
                                                    <p class="control-label" for="">CC: </p>
                                                    <div class="controls">
                                                        <input type="text" id="fetch_email_ids_cc" autocomplete="off" data-items="6" data-provide="typeahead" name="tags_cc" placeholder="Enter Email Id "  class="tm-input_cc tm-tag span8" data-original-title="" >
														
                                                        <div>
															<?php 
																$cc_email_list = explode(",", $email_cc_data);
																$i=0;
															foreach ($cc_email_list as $item_cc): ?>
                                                                <span class="tm-tag" id="remove_<?php echo $i; ?>"><span ><?php echo $item_cc; ?></span><a href="#" class="tm-tag-remove delete_email_cc" id="tag_remove_<?php echo $i; ?>" tagidtoremove="" title="Remove" email_cc_val ="<?php echo $item_cc.',';?>">x</a></span>
															<?php $i++; endforeach; ?>		
                                                        </div>
														<input type="hidden" name="email_val_cc" id="email_val_cc" value="<?php echo $email_cc.","; ?>"/> 
                                                    </div>
                                                    
                                                </div>
							
							
							
					  </div><!--span12 ends here-->
					</div>
					<div id="csv_table">
					</div>
					
					<div class="pull-right">       
					<button class="edit_form_submit_id btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span>   Update</button>
					 <!--<button type="button" class="btn btn-primary" id="generate" name="generate" ><i class="icon-eye-open icon-file icon-white"></i> Anaylse </button>-->
					<a href= "<?php echo base_url('configuration/adequacy_report'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</a>
					</div>
				</form>
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
                  <!-- <th style="font-size:12px;">TLO(Count)</th> -->
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
                  <!-- <td style="font-size:12px;"><?php echo $result['TLO(count)'];?></td> -->
				 
                </tr>
                <?php $i++; endforeach;?>
              </tbody>
            </table>

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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/adequacy_report_edit.js'); ?>" type="text/javascript"> </script>
	<script>
		
			$('.delete_email_to').on('click',function(){
					var tag_to_id = $(this).attr('id');
					var current_to_email = $('#'+tag_to_id).attr('email_to_val');
					var suffix = tag_to_id.match(/\d+/);
					
					$('#remove_to_'+suffix).remove();
			 
			
					var current_email = $(this).attr('email_to_val');
					//alert(current_email);
					var email_to = $('#email_val').val();
					var replaced_val = email_to.replace(current_email,'')
					//alert(replaced_val);
					$('#email_val').val(replaced_val); 
					
			});
			
			$('.delete_email_cc').on('click',function(){
					var tag_id = $(this).attr('id');
					var current_cc_email = $('#'+tag_id).attr('email_cc_val');
					var suffix = tag_id.match(/\d+/);
					
					$('#remove_'+suffix).remove();
					//alert(current_cc_email);
					
					var current_email = $(this).attr('email_cc_val');
					//alert(current_email);
					var email_cc = $('#email_val_cc').val();
					var replaced_val = email_cc.replace(current_email,'')
					//alert(replaced_val);
					$('#email_val_cc').val(replaced_val); 
					
			});
	</script>
