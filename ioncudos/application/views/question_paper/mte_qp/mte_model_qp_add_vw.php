<?php 

/**
 * Description	:	MTE QP List View
 * Created		:	03-02-2017. 
 * Author 		:   Bhagyalaxmi S S
 * Modification History:
 * Date				Modified By				Description
  -------------------------------------------------------------------------------------------------
  **/
?>
<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
    <!--branding here-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
	
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
	<style>
		.notbold{ font-weight:normal }
		input::-moz-placeholder {
			text-align: left;
			  -webkit-transition: opacity 0.3s linear; color: gray;
		}
		input::-webkit-input-placeholder {
			text-align: left;
		}
	</style>
    <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
			<div class="container-fluid">		
			<div class="row-fluid">
                <!--sidenav.php-->
                <div class="span12">
				   <div class="navbar">
						<div class="navbar-inner-custom">
							<?php echo $title;?>						
						</div>
					</div>
				    <div class="bs-docs-example ">
						<div ><b>Curriculum : <font color="#004b99"><?php echo  $crclm_title; ?> </font>
						&nbsp;&nbsp;&nbsp;&nbsp;Term :<font color="#004b99"> <?php echo $term_title;?></font>
						&nbsp;&nbsp;&nbsp;&nbsp;Course :<font color="#004b99"> <?php echo $course_title[0]['crs_title']."[". $course_title[0]['crs_code']."]";?></font></b><br/></div>
					</div>
                    <!-- Contents -->			
                    <section id="contents">
                        <div class="bs-docs-example ">
                            <!--content goes here-->	
                            <div class="brand-custom">
								<a class="brand-custom cursor_pointer" data-toggle="collapse" href="#add_form_id" style="text-decoration:none;">
								  <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
									<?php echo "Add " . $this->lang->line('entity_mte') . " Framework"; ?></b></h5>
								</a>
							</div> <br>
							<div id="loading" class="ui-widget-overlay ui-front">
								<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
							</div>
							<form class="form-horizontal collapse" method="POST" id="add_form_id" name="add_form_id" action= "">
							    <div class="control-group">
									<p class="control-label" for="inputEmail">Question Paper Title<font color="red"><b>*</b></font> :</p>
									<div class="controls">
										<textarea class="required qpaper_title " name="qp_title" id="qp_title" style="margin: 0px; width: 1000px; height: 42px;" rows="3" cols="20" placeholder="Enter Model Question Paper Title"></textarea>
									</div>
								</div>
							  <div class="row-fluid">
								<div class="span12">									
									<div class="span3">
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Total Duration (H:M)<font color="red"><b>*</b></font>:</label>
											<div class="controls">
											  <input type="text" id="total_duration" name="total_duration" class="text_align_right input-mini required total_duration" placeholder="In hrs" value="">
											</div>
										  </div>
									</div>									
									<div class="span4">
										  <div class="control-group">											
											<div class="">Course<font color="red"><b>*</b></font> :
											  <input class="required course_name" type="text" id="course_name" name="course_name" value="<?php echo $course_title[0]['crs_title'].'  -  ('.$course_title[0]['crs_code'].')' ?>" style="width:265px;" readonly />
											</div>
										  </div>
									</div>									
									<div class="span2">
										  <div class="control-group">											
											<div>Maximum Marks<font color="red"><b>*</b></font> :
											  <input type="text" id="max_marks" name="max_marks" class="text_align_right allownumericwithoutdecimal input-mini required max_marks numeric" value ="" placeholder="Max Marks">
											</div>
										  </div>
									</div>
									<div class="span2">
										  <div class="control-group">											
											<div class="">Grand Total <font class="font_color">*</font> :
											  <input type="text" id="Grand_total" name="Grand_total" class="text_align_right allownumericwithoutdecimal input-mini required max_marks" value="" placeholder="Grand Total" />
											</div>
										  </div>
									</div>
									<!-- <div class="span1">
										<div class="control-group">                                        
											<div class="">Model QP:
												<input type="checkbox" name="Model" id="Model" class="mandatory" title="Model Question Paper" style="margin:0px;" /> 
											</div>
										</div>
									</div> -->
								</div>
							</div>
							<div class="control-group">
								<p class="control-label" for="inputEmail">Note <font color="red"><b></b></font>:</p>
								<div class="controls">
								  <textarea class=" qp_notes " name="qp_notes" id="qp_notes" style="margin: 0px; width: 1000px; height: 42px;" rows="3" cols="20" placeholder="Enter Question Paper note here"></textarea>
								</div> <br>												 							
								<div class="form_container FM ">                           
									<button type="button" class="btn btn-primary btn-sm pull-right " id="add_ExpenseRow">
									  <i class="icon-plus-sign icon-white"></i> Add Section / Parts (Units)
									</button><br/><br/>
									<table id="expense_table" cellspacing="0" cellpadding="0" class="table table-bordered table-hover"  aria-describedby="example_info">
										<thead>
											<tr>
												<th>Section / Parts (Units) Name <font color="red"> *</font></th>
												<th>No. of Questions <font color="red"> *</font></th>
												<th>Section / Parts (Units) Max Marks  <font color="red"> *</font></th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="text" id="units_01" name="units_01" maxlength="255" class="required " /></td>
												<td><input type="text" id="ques_no_01" name="ques_no_01" maxlength="255" class="text_align_right required allownumericwithoutdecimal" /></td>
												<td><input type="text" id="marks_01" name="marks_01" maxlength="11" class="text_align_right required unit_max_marks allownumericwithoutdecimal"/></td>                
												<td>&nbsp;</td>
											</tr>
										</tbody>
									</table>
								</div> <!-- END form_container -->						
							</div> <div id="error_msg" class="text-danger"></div>
						   <div class="row-fluid">
									<div class="span12">
										<button type="button" name="save_header" id="save_header"   class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span>Save and Create QP</button>
										<button type="button"  name="update_header" id="update_header" class="btn btn-primary pull-right"><i class="icon-file icon-white"></i><span></span> Update</button>
									</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		<br/>		
	</form>	
	
				<!--	<input type="hidden" name="total_counter" id="total_counter" value="<?php echo $mq_counter;?>" />-->
					<input type="hidden" name="array_data" id="array_data" value="" />
				<!--	<input type="hidden" name="unit_counter" id="unit_counter" value="<?php echo $unit_counter;?>" /> -->
					<input type="hidden" name="qpf_id" id="qpf_id" value="<?php echo $qp_unit_data[0]['qpf_id'];?>" />
					<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id;?>" />
					<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
					<input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id;?>" />
					<input type="hidden" name="pgm_id" id="pgm_id" value="<?php echo $pgm_id;?>" />
					<!--<input type="hidden" name="qpp_id" id="qpp_id" value="<?php //echo $qpd_id?>"/>-->
					<input type="hidden" name="b" id="b" value=""/>
					<input type="hidden" name="qp_mq_id" id="qp_mq_id" value="" />					
					<input type="hidden" name="model_qp_existance" id="model_qp_existance" value="<?php echo $model_qp_existance;?>"/>
					<input type="hidden" name="ao_id" id="ao_id" value="<?php echo $ao_id;?>"/>
	<div class="row-fluid" style="display:none;">   
		<div class="span12">
			<section id="contents">
				<form>
				   <div class="bs-docs-example ">
						<!--content goes here-->	
						<div class="navbar">
							<div class="navbar-inner-custom">
							   <?php echo "view"; ?>		
							</div>
						</div><br><br/>
							<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
							
								<thead>
									<tr role="row">
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Sl No.</th>
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Q.No</th>
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Question</th>
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Marks</th>
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Edit</th>
										<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Delete</th>
										
									</tr>
								</thead>
								<tbody role="alert" aria-live="polite" aria-relevant="all">
									
								</tbody>
							</table><br>										
					</div>
				</form>
			</section>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">							
			<a href= "<?php echo base_url('question_paper/manage_mte_qp'); ?>" id="cancel_button"><b class="btn btn-danger btn-sm pull-right"><i class="icon-remove icon-white"></i><span></span> Close</b></a>																	
		</div>
	</div>
				<!--Do not place contents below this line-->
</div>

<!-- /*      <div id="model_qp_actual_qp" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
			<div class="container-fluid">
				<div class="navbar">
					<div class="navbar-inner-custom">
						Confirmation
					</div>
				</div>
			</div>
			<div class="modal-body">
				<p class="notbold">Do you want to set this question paper as Model question paper ? <br/>
                                  
				If yes click <b>Ok</b> else click <b>Cancel.</b></p>
			</div>

			<div class="modal-footer">
				<a class="btn btn-primary notbold" data-dismiss="modal" id="click_ok" ><i class="icon-ok icon-white"></i> Ok </a>
				<a class="btn btn-danger notbold" data-dismiss="modal" id="click_cancel" ><i class="icon-remove icon-white"></i> Cancel </a>

			</div>
     </div>
     <div id="unset_model_qp_actual_qp" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
        <div class="container-fluid">
            <div class="navbar">
                <div class="navbar-inner-custom">
                    Confirmation
                </div>
            </div>
        </div>
        <div class="modal-body">
            	<p class="notbold">Do you want to unset this question paper as Model question paper ? <br/>
				If yes click <b> Ok </b> else click <b>Cancel.</b></p>
        </div>

        <div class="modal-footer">
            <a class="btn btn-primary notbold" data-dismiss="modal" id="unset_click" ><i class="icon-ok icon-white"></i> Ok </a>
            <a class="btn btn-danger notbold" data-dismiss="modal" id="unset_click_cancel" ><i class="icon-remove-circle icon-white"></i> Cancel </a>

        </div>
     </div> */ -->
		 
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
		
		<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
		<!--<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/FM.js'); ?>" type="text/javascript"> </script>-->
		<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/mte_add_qp.js'); ?>" type="text/javascript"> </script>

	<!---------------------------------------------------------Edit-page------------------------------------------------------------------------>
		<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
	
		
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisLabelRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

