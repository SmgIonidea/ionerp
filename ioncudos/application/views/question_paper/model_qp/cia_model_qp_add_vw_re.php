
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
		.notbold{
    font-weight:normal
}
	input::-moz-placeholder {
        text-align: left;
		  -webkit-transition: opacity 0.3s linear; color: gray;
    }
    input::-webkit-input-placeholder {
        text-align: left;
    }
	</style>
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
                <?php //$this->load->view('includes/sidenav_1'); ?>
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
                    <!-- Contents
    ================================================== -->
			
                    <section id="contents">
                        <div class="bs-docs-example ">
                            <!--content goes here-->	
                            	<div class="brand-custom">
									<a class="brand-custom cursor_pointer" data-toggle="collapse" href="#add_form_id" style="text-decoration:none;">
									  <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
										<?php echo "Add CIA Framework"; ?></b></h5>
									</a>
							</div>	
                            <br>
						
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
									 <div class="span1">
                                    <div class="control-group">                                        
                                        <div class="">Model QP:
											<input type="checkbox" name="Model" id="Model" class="mandatory" title="Model Question Paper" style="margin:0px;" /> 
                                        </div>
                                    </div>
                                </div>
								</div>
							  </div>
							  

							<div class="control-group">
								<p class="control-label" for="inputEmail">Note <font color="red"><b></b></font>:</p>
								<div class="controls">
								  <textarea class=" qp_notes " name="qp_notes" id="qp_notes" style="margin: 0px; width: 1000px; height: 42px;" rows="3" cols="20" placeholder="Enter Question Paper note here"></textarea>
								</div>
								
								
		 
                            <!--content goes here-->	
                     
                            <br>												 							
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
					</div><br/>
		
	</form>	
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
                            </div>
                            <br><br/>
						
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
											<a href= "<?php echo base_url('question_paper/manage_cia_qp'); ?>" id="cancel_button"><b class="btn btn-danger btn-sm pull-right"><i class="icon-remove icon-white"></i><span></span> Close</b></a>																	
										</div>
									</div>
		<!--==============================================================================================================================================================-->
  

							 <?php // Dynamic Loading of UNIT and Questions 
							$unit_counter = 1;
							
							/* foreach($qp_unit_data as $unit_details){
							$counter = 'a';} */
						 ?>	 
					<div id="edit_qp"  id="edit_qp" class="row-fluid modal hide fade local-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; margin-left: -685px;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">              
					    <div class="span12">
						    <section id="contents">
						        <div class="bs-docs-example ">
								 <div class="navbar">
                                <div class="navbar-inner-custom">
                                 <!-- <button type="button" class="close" >&times;</button>-->
								   <a role = "button" href="#" id='close' onclick="javascript:window.location.reload()" class="  pull-right" data-dismiss="modal"><i class="icon-remove icon-white"> </i></a>
                                </div>
                            </div>	
                            <br>
									<!--<table id="tabl" class="table table-bordered qp_table" >
	  <?php //Generating Main Questions with reference to the QP framework
								$mq_counter = 1;
								$sub_counter = 1;
					
							?>									
									<tbody>
									<tr class="ch1">
										<td><label class="inline_class font_bold" >Section<font class="font_color"></font>: </label>
										<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="unit_list_1_1" id="unit_list_1_1" class="input-small unit_onchange required">
																	<option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
																	<?php foreach($qp_unit_data as $topic){
																	?>
																	<option value="<?php echo $topic['qpf_unit_id']; ?>" abbr="<?php echo $topic['qpf_unit_code']; ?>" ><?php echo $topic['qpf_unit_code']; ?></option>	
																	
																	<?php } ?>
												</select></td>
									
										<td><label class="inline_class font_bold" >Q.No<font class="font_lor">*</font> :</label>
										<select     onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  name="Q_No_1_1" id="Q_No_1_1" class="input-small pi_code_data">
																	<option value="" abbr="Select Q.No">Select</option>
																
										</select>						
										<td>
										<!--<label class="inline_class font_bold" >Select<font class="font_color">*</font> :</label>-->
										<!--<select   onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  name="" id="sec_id2" class="input-small pi_code_data">
																	<option value="" abbr="Select ">Select</option>
																	<option>a</option><option>b</option>
																	<option>c</option><option>d</option>
																	<option>e</option><option>f</option>
										</select>
										</td>
										</tr>
											<tr class="ch2">
										<td><label class="inline_class font_bold" >Section<font class="font_color"></font>: </label>
										<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="unit_list_1_1" id="unit_list_1_1" class="input-small unit_onchange required">
																	<option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
																	<?php foreach($qp_unit_data as $topic){
																	?>
																	<option value="<?php echo $topic['qpf_unit_id']; ?>" abbr="<?php echo $topic['qpf_unit_code']; ?>" ><?php echo $topic['qpf_unit_code']; ?></option>	
																	
																	<?php } ?>
												</select></td>
									
										<td><label class="inline_class font_bold" >Q.No<font class="font_lor">*</font> :</label>
										<select     onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  name="Q_No_1_1" id="Q_No_1_1" class="input-small pi_code_data">
																	<option value="" abbr="Select Q.No">Select</option>
																
										</select>						
										<td>
										<!--<label class="inline_class font_bold" >Select<font class="font_color">*</font> :</label>-->
										<!--<select   onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom"  name="" id="sec_id2" class="input-small pi_code_data">
																	<option value="" abbr="Select ">Select</option>
																	<option>a</option><option>b</option>
																	<option>c</option><option>d</option>
																	<option>e</option><option>f</option>
										</select>
										</td>
										</tr>
										<tr><td colspan="10"><hr/></td></tr>
										<tr>
											<td>
												<input type="text" name="ques_nme" id="ques_nme" class="ques_nme"  placeholder="Question Number"/>
												<input type="hidden" name="question_name_<?php echo $mq_counter;?>_1" id="question_name" value="<?php echo 'Q_No_'.$mq_counter.'_'.$counter; ?>" class="input-mini question_name_<?php echo $mq_counter;?>" readonly />
											</td>
											<?php foreach($qp_entity as $qp_entity_config){ 
													if($qp_entity_config['entity_id'] == 11){?>
											<td>
												<label class="inline_class font_bold" >CO<font color="red"><b>*</b></font> :</label>
													<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_1_1" id="co_list_1_1"  class="input-small co_onchange required co_list_data" >
																	<option value="" abbr="Select CO">Select</option>
																	<?php foreach($co_details as $co) { ?>
																	<option value="<?php echo $co['clo_id']; ?>" title="<?php echo $co['clo_statement'];?>" abbr="<?php echo $co['clo_statement'];?>"><?php echo $co['clo_code']; ?></option>
																	<?php } ?>
															</select>				
											</td><?php }
											if($qp_entity_config['entity_id'] == 6 ){?>
											<td>
												<label class="inline_class font_bold" >PO<font color="red"><b>*</b></font> :</label>
													<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_1_1" id="po_list_1_1" class="input-small required po_list_data">
																	<option value="" abbr="Select CO">Select</option>
															</select>
											</td>
											<?php }if($qp_entity_config['entity_id'] == 10 ){?>
											<td>
												<label class="inline_class font_bold" >Topic<font color="red"><b>*</b></font> :</label>
												<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_1_1" id="topic_list_1_1" class="input-small topic_list_data required">
																	<option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
																	<?php foreach($topic_details as $topic) {
																	?>
																	<option value="<?php echo $topic['topic_id']; ?>" abbr="<?php echo $topic['topic_title']; ?>" ><?php echo $topic['topic_title']; ?></option>
																	<?php } ?>
												</select>
											</td><?php }if($qp_entity_config['entity_id'] == 23 ){?>
												
											<td>
												<label class="inline_class font_bold" >Level<font color="red"><b>*</b></font> :</label>
												<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_1_1" id="bloom_list_1_1" class="input-small required bloom_list_data">
																	<option value="" abbr="Select Level">Select</option>
																	<?php foreach($bloom_data as $blevel) {
																	?>
																	<option value="<?php echo $blevel['bloom_id']; ?>" abbr="<?php echo $blevel['bloom_actionverbs']; ?>" ><?php echo $blevel['level']; ?></option>
																	<?php } ?>
															</select>
											</td><?php } if($qp_entity_config['entity_id'] == 22){?>
											
											<td>
												<label class="inline_class font_bold" >PI Code<font color="red"><b>*</b></font> :</label>
												<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_1_1" id="pi_code_1_1" class="input-small pi_code_data">
																	<option value="" abbr="Select PI Code">Select</option>
																	<?php foreach($pi_list as $pi) {
																	?>
																	<option value="<?php echo $pi['msr_id']; ?>" abbr="<?php echo $pi['msr_statement']; ?>" ><?php echo $pi['pi_codes']; ?></option>
																	<?php } ?>
												</select>
											</td><?php }
											}?>
											
								<td>
								<label class="inline_class font_bold" >Marks<font color="red"><b>*</b></font> :</label>
								<div class="input-append marks">
								<input type="text" name="mark" id="mark" class="input-mini required mq_marks numeric"/>
								
								</div>
								</td>
							</tr>							
							<tr>
								<td colspan="12"><textarea class="question_textarea" name="question_1_1" id="question_1_1" style="margin: 0px; width: 551px; height: 40px;" ></textarea>
								<!--<td><textarea></textarea></td>-->
													<!--<input type="hidden" name="sub_que_count<?php echo $mq_counter;?>" id="sub_que_count<?php echo $mq_counter;?>" value="1"/>
								</td>
							</tr>
							</tbody>

							</table> -->
					<input type="hidden" name="total_counter" id="total_counter" value="<?php echo --$mq_counter;?>" />
					<input type="hidden" name="array_data" id="array_data" value="" />
					<input type="hidden" name="unit_counter" id="unit_counter" value="<?php echo --$unit_counter;?>" />
					<input type="hidden" name="qpf_id" id="qpf_id" value="<?php echo $qp_unit_data[0]['qpf_id'];?>" />
					<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id;?>" />
					<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
					<input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id;?>" />
					<input type="hidden" name="pgm_id" id="pgm_id" value="<?php echo $pgm_id;?>" />
					<!--<input type="hidden" name="qpp_id" id="qpp_id" value="<?php //echo $qpd_id?>"/>-->
					<input type="text" name="b" id="b" value=""/>
					<input type="hidden" name="qp_mq_id" id="qp_mq_id" value="" />
					<input type="text" name="FM" id="FM" value="<?php echo $data[0]['FM'];?>" />
					<input type="hidden" name="model_qp_existance" id="model_qp_existance" value="<?php echo $model_qp_existance;?>"/>
					<input type="text" name="ao_id" id="ao_id" value="<?php echo $ao_id;?>"/>
					
								<div class="pan12">
									<div class="control-group">
										<div class=" controls">
											<div class="form-inline pull-right">
												<label for="qp_max_marks"><b>Grand Total Marks</b></label>
												<input type="text" id="qp_max_marks" name="qp_max_marks" class="input-mini required max_marks_validation" value="<?php echo $qp_mq_data[0]['qpf_gt_marks'];?>" readonly />
											</div>		
										</div>		
									</div>
									</div><br/><br/>		
							<div class="row-fluid">
								<div class="span12">									
									<div class="form-inline pull-right">       
									<!--<button type="button" name="save_data" id="save_data" class="btn btn-primary"><i class="icon-ok icon-white"></i><span></span> Save</button>-->
									 
									<button type="button" name="update" id="update" class="btn btn-primary"><i class="icon-ok icon-white"></i><span></span> update</button>
									<button type="button" name="fetch" id="fetch" class="btn btn-primary"><i class="icon-ok icon-white"></i><span></span> Save</button>
									<button id="modal_cancel" class="btn btn-danger" ><i class="icon-remove icon-white"></i>Cancel</button>								
								</div></div></div>
							</section>
							
						</div>
					</div>
					</div>
					</br>
					</form>
						
					
			
				
<!--=======================================================================================================================================-->

<!--=======================================================================================================================================--->
				<!--Do not place contents below this line-->
				
								<div id="myModal_qp" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
								 style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Create Question Paper
										</div>
									</div>
								</div>
								<div class="modal-body">
								<div class="control-group">
								
								<input type="radio" name="FM" value="4"/>&nbsp;&nbsp;&nbsp;<b>With Framework<b>
								
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								
								<input type="radio" name="FM" value="0"/>&nbsp;&nbsp;&nbsp;<b>WithOut  Framework<b>
								
								</div>
								</div>
								<div class="modal-footer">
									<button  class="btn btn-primary generate_fm" data-dismiss="modal" style="display:none;" aria-hidden="true" id="ok"><i class="icon-ok icon-white"></i> Ok </button>
									<a href= "<?php echo base_url('question_paper/manage_cia_qp'); ?>" id="del"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>								
								</div>
							</div>
							<div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Delete confirmation
                                            </div>
                                        </div>
                                    </div>
									<div class="modal-body">									
											Are you sure you want to delete this question?
									</div>
                                  
                                    <div class="modal-footer">
                                        <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
										 <a class="btn btn-primary" data-dismiss="modal" id="btnYes"><i class="icon-ok icon-white"></i> Ok </a> 
                                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
                                    </div>
                                </div>
								
						
								
							<div id="myModal_suc" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Save
                                            </div>
                                        </div>
                                    </div>
									<div class="modal-body">									
										<h4><center>Your data Has been saved.<font color="brown"><b id="crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
									</div>
                                  
                                    <div class="modal-footer">
                                        <a class="btn btn-primary" data-dismiss="modal" onClick="publish();"><i class="icon-ok icon-white"></i> Ok </a> 
                                      
                                    </div>
                            </div>
							
							
				 
<!---====================================================================================================================-->
<!-- <form class="form-horizontal">
							<input type="hidden" name="crs" id="crs" value="<?php echo $crs_id;?>"  /> 
							<input type="hidden" name="qid" id="qid" value="<?php echo $meta_data[0]['qpd_id'];?>"  /> 
								
								<div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Blooms Level Planned vs. Coverage Distribution 
                                            </div>
											
                                </div>
								<div class="row-fluid">
									<div class="span12">
									<div class="span6">
									<div id="chart1"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
									</div>
										
									</div>
									<div class="span6" style="overflow:auto;">
										<br>
										<table id="bloomslevelplannedcoveragedistribution" border=1 class="table table-bordered">
										<thead></thead>
										<tbody></tbody>
										</table>
									</div>
									</div>
									<div class="span12">
									<div id="bloomslevelplannedcoveragedistribution_note"></div>
								</div>
								</div>
								
								<br>
								
								<div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Blooms Level Marks Distribution 
                                            </div>
											
                                </div>
								<div class="row-fluid">
									<div class="span12">
									<div class="span6">
									<div id="chart2"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
									</div>
										
									</div>
									<div class="span6" style="overflow:auto;">
										<br>
										<table id="bloomslevelplannedmarksdistribution" border=1 class="table table-bordered">
										<thead></thead>
										<tbody></tbody>
										</table>
									</div>
									</div>
									<div class="span12">
									<div id="bloomslevelplannedmarksdistribution_note"></div>
									</div>
								</div>
								
								<br>
								
								<div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Course Outcome Planned Coverage Distribution 
                                            </div>
											
                                </div>
								<div class="row-fluid">
									<div class="span12">
									<div class="span6">
									<div id="chart3"  style="height:35%; width: 100%; position:relative;" class="jqplot-target" >
									</div>
										
									</div>
									<div class="span6" style="overflow:auto;">
										<br>
										<table id="coplannedcoveragesdistribution" border=1 class="table table-bordered">
										<thead></thead>
										<tbody></tbody>
										</table>
									</div>
									</div>
									<div class="span12">
									<div id="coplannedcoveragesdistribution_note"></div>
								</div>
								</div>
								
                                <div class="row">
                                </div>
                                
                                </br>
							</form>
	-->
<!--=======================================================================================================================================-->

		</div>

     </div>
     <div id="model_qp_actual_qp" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
     </div>
		 
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
		
		<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
		<!--<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/FM.js'); ?>" type="text/javascript"> </script>-->
		<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/cia_qp_re.js'); ?>" type="text/javascript"> </script>

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

