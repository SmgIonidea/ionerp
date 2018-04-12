<?php
/**
* Description	:	Add View for Program Outcomes(POs) Type Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 03-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
--------------------------------------------------------------------------------------------------------
*/
?>

<!--head here -->
    <?php
   // $this->load->view('includes/head');
    ?>
    <!--branding here-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
        <?php
      //  $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
       // $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php //$this->load->view('includes/sidenav_1'); ?>
                <div class="span12">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                   <?php echo $title; ?>
                                </div>
                            </div>	
							<div id="loading" class="ui-widget-overlay ui-front">
								<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
							</div>
                            <br>
							<!--<form  class="" method="POST" id="add_form_id" name="add_form_id" action= "">
							<div class="control-group">
							<p class="control-label" for="qp_title">Question Paper Title :</p>
								<div class="controls">
									<textarea name="qp_title" id="qp_title" style="" rows="3" cols="20"></textarea>
								</div>
							</div>
							
                            </form>-->
							<form class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('question_paper/manage_model_qp/cia_add_qp_data/3'); ?>">
							  <div class="control-group">
								<p class="control-label" for="inputEmail">Question Paper Title<font color="red"><b>*</b></font> :</p>
								<div class="controls">
								  <textarea class="required qpaper_title" name="qp_title" id="qp_title" style="margin: 0px; width: 889px; height: 60px;" rows="3" cols="20"></textarea>
								</div>
							  </div>
							  <div class="row-fluid">
								<div class="span12">
									
									<div class="span3">
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Total Duration (H:M)<font color="red"><b>*</b></font> :</label>
											<div class="controls">
											  <input type="text" id="total_duration" name="total_duration" class="input-mini required total_duration">
											</div>
										  </div>
									</div>
									
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Course<font color="red"><b>*</b></font> :</label>
											<div class="controls">
											  <input class="required course_name input-xlarge" type="text" id="course_name" name="course_name" value="<?php echo $course_title[0]['crs_title'].'  -  ('.$course_title[0]['crs_code'].')';?>" style="width:320px" readonly />
											</div>
										  </div>
									</div>
									
									<div class="span3">
										  <div class="control-group">
											<label class="control-label" for="max_marks">Maximum Marks<font color="red"><b>*</b></font> :</label>
											<div class="controls">
											  <input type="text" id="max_marks" name="max_marks" class="input-mini required max_marks numeric" value ="">
											</div>
										  </div>
									</div>
								</div>
							  </div>
							<div class="control-group">
								<p class="control-label" for="inputEmail">Note<font color="red"><b>*</b></font> :</p>
								<div class="controls">
								  <textarea class="required qp_notes" name="qp_notes" id="qp_notes" style="margin: 0px; width: 889px; height: 40px;" rows="3" cols="20"></textarea>
								</div>
							  </div>
					<div id="qp_unit_table">
						 <?php // Dynamic Loading of UNIT and Questions 
							$unit_counter = 1;
							
							// foreach($qp_unit_data as $unit_details){
							// $counter = 'a';
						 ?>
							<div class="control-group">
								<div class="span12">
								<div class="controls">
								  <center> 
									  
									  <input type="text" name="unit_code_<?php echo $unit_counter; ?>" id="unit_code_<?php echo $unit_counter; ?>" value="" placeholder="Unit Name Ex: Unit A" class="input-medium required " />
								  </center>
								  <center>
								  <input type="checkbox" name="unit_<?php echo $unit_counter; ?>" id="unit_<?php echo $unit_counter; ?>" class="main_unit" abbr="unit_<?php echo $unit_counter; ?>" value="1"></input> &nbsp; All Questions Mandatory</center>
								</div>
								</div>
							</div>
						
						
						
							<div class="row-fluid">
								<div class="span12">
									<div class="span6 ">
										  <div class="control-group pull-left">
											<label class="control-label" for="total_question">Total No. of Questions<font color="red"><b>*</b></font> :</label>
											<div class="controls">
											  <input type="text" id="total_question_<?php echo $unit_counter; ?>" name="total_question_<?php echo $unit_counter; ?>" class="input-mini required total_question" value="1" readonly />
											</div>
										  </div>
									</div>
									<div class="span6 ">
										  <div class="control-group pull-right">
											<label class="control-label" for="attempt_question">No. of Questions to Attempt<font color="red"><b>*</b></font> :</label>
											<div class="controls">
											  <input type="text" id="attempt_question_<?php echo $unit_counter; ?>" name="attempt_question_<?php echo $unit_counter; ?>" class="input-mini required attempt_question numeric">
											</div>
										  </div>
									</div>
								</div>
							  </div>
								
									<table class="table table-bordered qp_table" id="qp_table_load">
										<thead>
											<tr>
												<th style="white-space:nowrap;" ></th>
												<th>Question<font color="red"><b>*</b></font></th>
												<th>Upload</th>
												<?php foreach($qp_entity as $qp_config){ 
												if($qp_config['entity_id'] == 11 ){?>
												<th>CO<font color="red"><b>*</b></font></th>
												<?php } ?>
												<?php
												if($qp_config['entity_id'] == 6){?>
												<th>PO<font color="red"><b>*</b></font></th>
												<?php } ?>
												<?php
												if($qp_config['entity_id'] == 10){?>
												<th><?php echo $this->lang->line('entity_topic'); ?></th>
												<?php } ?>
												<?php  
												if($qp_config['entity_id'] == 23){?>
												<th>Level<font color="red"><b>*</b></font></th>
												<?php } ?>
												<?php 
												if($qp_config['entity_id'] == 22){?>
												<th>PI Code<font color="red"><b>*</b></font></th>
												<?php } 
												}?>
												<th>Marks<font color="red"><b>*</b></font></th>
											</tr>
										</thead>
										
										<tbody>
						<?php //Generating Main Questions with reference to the QP framework
								$mq_counter = 1;
								$sub_counter = 1;
								// foreach($qp_mq_data as $mq_data){ 
								// if($mq_data['qpf_unit_id'] == $unit_details['qpf_unit_id'])
									// {
							?>
											<tr id= "row_<?php echo $mq_counter; ?>_<?php echo $sub_counter; ?>" abbr="main_que_<?php echo $mq_counter; ?>" class="row_<?php echo $mq_counter; ?>">
												<td style="white-space:nowrap;" class="textwrap">
													<input type="checkbox" name="unit_<?php echo $unit_counter; ?>_<?php echo $mq_counter;?>" id="unit_<?php echo $unit_counter; ?>_<?php echo $mq_counter;?>" class="unit_<?php echo $unit_counter; ?>" value="1"></input> &nbsp; <input type="text" name="ques_nme_<?php echo $mq_counter;?>_1" id="ques_nme_<?php echo $mq_counter;?>_1" value="Q No 1-a" class="input-mini ques_nme_<?php echo $mq_counter;?>" readonly />
													<input type="hidden" name="question_name_<?php echo $mq_counter;?>_1" id="question_name_<?php echo $mq_counter;?>_1" value="Q_No_1_a" class="input-mini question_name_<?php echo $mq_counter;?>" readonly />
												</td>
												<td style="width: 43%;">
													<textarea class="required span12 text_area" name="question_<?php echo $mq_counter;?>_1" id="question_<?php echo $mq_counter;?>_1" rows="3" ></textarea>
													<input type="hidden" name="sub_que_count<?php echo $mq_counter;?>" id="sub_que_count<?php echo $mq_counter;?>" value="1"/>
													<!--<input type="text" name="sub_que_stack<?php //echo $mq_counter;?>" id="sub_que_stack<?php //echo $mq_counter;?>" value="1"/>-->
												</td>
												<td>
													<!--<button type="button" name="upload" id="upload"  class="btn btn-primary"><i class="icon-upload icon-white"></i></button>-->
													
														<button type="button" id="upload-btn<?php echo $mq_counter;?>_1" name="upload"  abbr="1" class=" btn btn-success btn-small clearfix test" value=""><i class="icon-upload icon-white"></i></button>
														<!--<div id="image_show_1" class="form-horizontal span12" style=" margin-left:90px;">		
														</div>-->	
														<!--<div id="image_append_1" class="controls span12" style=" margin-left:90px;"></div>-->
														<!--<div id="errormsg" class="clearfix redtext">
														</div>	              
														<div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;">
														</div>	
														
														<div id="picbox" class="clear" style="padding-top:0px;padding-bottom:10px;">
														</div>-->
								
												</td>
												<?php foreach($qp_entity as $qp_entity_config){ 
													if($qp_entity_config['entity_id'] == 11){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_<?php echo $mq_counter;?>_1" id="co_list_<?php echo $mq_counter;?>_1"  class="input-small co_onchange required co_list_data" >
																	<option value="" abbr="Select CO">Select</option>
																	<?php foreach($co_details as $co) { ?>
																	<option value="<?php echo $co['clo_id']; ?>" title="<?php echo $co['clo_statement'];?>" abbr="<?php echo $co['clo_statement'];?>"><?php echo $co['clo_code']; ?></option>
																	<?php } ?>
															</select>
															
														</td>
												<?php } ?>
												<?php 
													if($qp_entity_config['entity_id'] == 6 ){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_<?php echo $mq_counter;?>_1" id="po_list_<?php echo $mq_counter;?>_1" class="input-mini required po_list_data">
																	<option value="" abbr="Select CO">Select</option>
															</select>
														</td>
												<?php } ?>
												<?php 
													if($qp_entity_config['entity_id'] == 10 ){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_<?php echo $mq_counter;?>_1" id="topic_list_<?php echo $mq_counter;?>_1" class="input-small topic_list_data required">
																	<option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
																	<?php foreach($topic_details as $topic) {
																	?>
																	<option value="<?php echo $topic['topic_id']; ?>" abbr="<?php echo $topic['topic_title']; ?>" ><?php echo $topic['topic_title']; ?></option>
																	<?php } ?>
															</select>
														</td>
												<?php } ?>
												<?php if($qp_entity_config['entity_id'] == 23 ){ ?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_<?php echo $mq_counter;?>_1" id="bloom_list_<?php echo $mq_counter;?>_1" class="input-mini required bloom_list_data">
																	<option value="" abbr="Select Level">Select</option>
																	<?php foreach($bloom_data as $blevel) {
																	?>
																	<option value="<?php echo $blevel['bloom_id']; ?>" abbr="<?php echo $blevel['bloom_actionverbs']; ?>" ><?php echo $blevel['level']; ?></option>
																	<?php } ?>
															</select>
														</td>
												<?php } ?>
												<?php if($qp_entity_config['entity_id'] == 22){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_<?php echo $mq_counter;?>_1" id="pi_code_<?php echo $mq_counter;?>_1" class="input-small pi_code_data">
																	<option value="" abbr="Select PI Code">Select</option>
																	<?php foreach($pi_list as $pi) {
																	?>
																	<option value="<?php echo $pi['msr_id']; ?>" abbr="<?php echo $pi['msr_statement']; ?>" ><?php echo $pi['pi_codes']; ?></option>
																	<?php } ?>
															</select>
														</td>
												<?php }
													}?>
												
												<td>
												<div class="input-append marks">
													<input type="text" name="mq_marks_<?php echo $mq_counter;?>_1" id="mq_marks_<?php echo $mq_counter;?>_1" class="input-mini required mq_marks numeric "/>
													
													<button class="btn btn-primary add_subque error_add" type="button" id="add_subque_<?php echo $mq_counter;?>_1" name="add_subque_<?php echo $mq_counter;?>_1" abbr="QNO_<?php echo $mq_counter;?>_a" title="Add Sub Questions" main_que_abbr="QNO_<?php echo $mq_counter;?>_a" main_q_num="<?php echo $mq_counter;?>"><i class="icon-plus-sign icon-white" ></i></button>
													
													<button class="btn btn-danger del_main_que error_add" type="button" id="del_main_que_<?php echo $mq_counter;?>" name="del_main_que_<?php echo $mq_counter;?>" abbr="QNO_<?php echo $mq_counter;?>_a" title="Delete Main Questions" del_main_count ="<?php echo $unit_counter; ?>" del_main_que ="row_<?php echo $mq_counter;?>"><i class="icon-minus-sign icon-white"></i></button>
													
													</div>
													<span></span>
												</td>
											</tr>
											<tr id="img_placing_row_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="row_<?php echo $mq_counter; ?>">
												<td id=""></td>
												<td id="place_img_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" colspan="6"></td>
											</tr>
											<tr id="img_name_text_fields_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="row_<?php echo $mq_counter; ?>">
											<td id="placed_img_name_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" colspan="8"></td>
											</tr>
											<tr id="sub_que_ref_div_<?php echo $mq_counter;?>" class="row_<?php echo $mq_counter; ?>">
												
											</tr>
											
											
											 <?php 
											//} 
											 // $mq_counter++; 
											 // }?>
											 <tr id="ref_add_main"><tr>
										</tbody>
									</table>
									
									<div class="row-fluid">
									<div class="span12">
										  <div class="control-group pull-right" id="main_question_add_btn">
											<div class="controls">
											  <button name="main_que_<?php echo $unit_counter; ?>" id="main_que_<?php echo $unit_counter; ?>" type="button" unit_count="<?php echo $unit_counter; ?>" abbr="unit_<?php echo $unit_counter; ?>" class="btn btn-primary add_main_que"><i class="icon-plus-sign icon-white"></i>Add Main Question </button>
											</div>
										  </div>
								</div>
						</div>
									
						<?php
						 // $counter++; 
						 // $unit_counter++;
						 // } ?>
					</div>	
					<input type="hidden" name="total_counter" id="total_counter" value="1" class="input-mini"/>
					<input type="hidden" name="main_que_counter" id="main_que_counter" value="1" class="input-mini"/>
					<input type="hidden" name="main_que_array" id="main_que_array" value="1" class="input-mini"/>
					<input type="hidden" name="array_data" id="array_data" value="" class="input-mini"/>
					<input type="hidden" name="unit_counter" id="unit_counter" value="<?php echo $unit_counter; ?>" class="input-mini"/>
					<!--<input type="hidden" name="qpf_id" id="qpf_id" value="<?php echo $qp_unit_data[0]['qpf_id'];?>" class="input-mini"/>-->
					<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id;?>" class="input-mini" />
					<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" class="input-mini"/>
					<input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id;?>" class="input-mini"/>
					<input type="hidden" name="ao_type_id" id="ao_type_id" value="" class="input-mini"/>
					<input type="hidden" name="ao_id" id="ao_id" value="<?php echo $ao_id; ?>" class="input-mini"/>
						
					<div class="pan12">
					<div class="control-group">
					<div class=" controls">
					<div class="form-inline pull-right">
					<!--<label for="qp_max_marks"><b>Grand Total Marks</b></label>
					<input type="text" id="qp_max_marks" name="qp_max_marks" class="input-mini required max_marks_validation" value="<?php // echo $qp_mq_data[0]['qpf_gt_marks'];?>" readonly />-->
					</div>		
					</div>		
					</div>		
					</div><br>
						<div class="pull-right">       
							<!--<button type="button" name="save_data" id="save_data" class="btn btn-primary"><i class="icon-ok icon-white"></i><span></span> Save</button>
							
							<a href= "<?php //echo base_url('question_paper/manage_model_qp'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>-->
						</div><br><br>
						</form>
                    </div>
                        <!--Do not place contents below this line-->
				</section>
			</div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php //$this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php //$this->load->view('includes/cia_model_qp_js_data.php'); ?>
	
	<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/cia_model_qp.js'); ?>"></script>
	
 <?php $inline_script ="<script>$(document).ready(function(){
						var counter = $('#total_counter').val();

						for( var i=1 ; i<=counter; i++){
						counter_val = i+'_1';
						main_que_array.push(i);
						sub_que_array.push({index:i, value:1});
						register_button(counter_val);
						}
						$('#main_que_array').val(main_que_array)
						});
					</script>"; 
		echo $inline_script;
		//echo $script_data;
			?>
