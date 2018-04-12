<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO Lesson Schedule view page, provides the facility to Add the Lesson Schedule, Review and Assignment Contents.
 * Modification History:
 * Date							Modified By								Description
 * 05-05-2014                   Jevi V G                          Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
	<!-- /TinyMCE -->
  
    <?php  $this->load->view('includes/head'); ?>
	  <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
	<style>
	.input-small {
width: 99px;
}
</style>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php  $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example" >
						<form  class="form-horizontal" id="lesson_schedule_edit_form"  action="<?php echo base_url('curriculum/tlo_list/update_lesson_schedule'); ?>" name="lesson_schedule_edit_form"  method="POST" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                   Chapter wise Plan
                                </div>
							</div>
							
								<div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="pgm_title" style="padding-top:0px;">Curriculum :</label>
                                                    <div class="controls">
                                                        <b><?php 
														echo  $tlo_data_one[0]['crclm_name'];
													
													?></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="term" style="padding-top:0px;">Term :</label>
                                                    <div class="controls">
                                                        <b><?php 
														echo  $tlo_data_two[0]['term_name'];
													
													?></b>
                                                    </div>
                                                </div>
                                            </div>
										</div>
										<div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="course" style="padding-top:0px;">Course :</label>
                                                    <div class="controls">
                                                              <b> <?php
															   echo $tlo_data_three[0]['crs_title'];
															?></b>
                                                    </div>
                                                </div>
                                            </div>
											
											<div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label" for="course" style="padding-top:0px;"><?php echo $this->lang->line('entity_topic'); ?> :</label>
                                                    <div class="controls">
                                                        <b><?php
												//var_dump($tlo_data);
												echo $tlo_data_four[0]['topic_title'];
												?></b>
                                                    </div>
                                                </div>
                                            </div>
										</div>
										
										<table class="table table-bordered">
              <thead>
                <tr>
                  
                  <th style="padding:5px;">Lesson Contents</th>
                  <th style="padding:5px;"><?php echo $this->lang->line('entity_topic'); ?> Hours</th>
                 
                </tr>
              </thead>
              <tbody>
                <tr>
                  
                  <td style="padding:5px;">
				  <b><?php
					echo $tlo_data_four[0]['topic_content'];
					?></b>
                                                    
                  </td>
                  <td style="padding:5px;">
				  <?php 
					 echo $tlo_data_four[0]['topic_hrs'];
					 ?>
				  </td>
				  <input type = "hidden" name="topic_hrs" id="topic_hrs" value ="<?php echo $tlo_data_four[0]['topic_hrs'];?>"/>
                </tr>
              </tbody>
            </table>
			</div>
			</div>
			<br>
                            <!-- here-->
									<input type = "hidden" name="lesson_curriculum_id" id="lesson_curriculum_id" value ="<?php echo $curriculum_id;?>"/>
									<input type = "hidden" name="lesson_term_id" id="lesson_term_id" value ="<?php echo $term_id;?>"/>
									<input type = "hidden" name="lesson_course_id" id="lesson_course_id" value ="<?php echo $course_id;?>"/>
									<input type = "hidden" name="lesson_topic_id" id="lesson_topic_id" value ="<?php echo $topic_id;?>"/>
									<div class="navbar">
										<div class="navbar-inner-custom">
										   Lesson Schedule
										</div>
									</div>
									 
										<table class="table table-bordered">
									
										<tr>
										<th> Class No.</th>
										
										<th> Portion to be covered per hour</th>
										</tr>
											<?php
							$lessonCntr = 1;
							foreach ($portion as $portion_per_hr):
								$portion_per_hour['value'] = 	$portion_per_hr['portion_per_hour'];
								$portion_per_hour['id'] = 	'portion_per_hour'.$lessonCntr;
								$portion_per_hour['name'] = 	'portion_per_hour'.$lessonCntr;
								$portion_per_hour['class'] = 	'input-xxlarge required';
							
								?> 
										<tr>
										<td>
											<input type = "text" class="input-mini" name="lesson_schedule_id" id="lesson_schedule_id" value ="<?php echo $lessonCntr; ?>" readonly>
										</td>
										<td>
												<?php echo form_input($portion_per_hour); 
												?>
											<!--<input type = "text" class="input-xxlarge required" style="width:900px;" name="portion_per_hour" id="portion_per_hour" />-->
										</td>
										</tr>
										 <?php $lessonCntr++;
										endforeach; ?>
										</table>
										
									
								
                                <div><font color="red"><?php echo validation_errors(); ?></font></div>
                                
							<br>
						
						
						
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                          Review Question
										  <a href="http://math.typeit.org/" target="_blank" class="pull-right" style="text-decoration:none; font-size:12px; color:white;"> On-line Mathematical Editor </a>
                                        </div>
									</div>
							<?php
							
                                    $cloneCntr = 1;
									foreach ($review_question_data as $ids):
									 $review_question['value'] = $ids['review_question'];
                                     $review_question['id'] = "review_question_" . $cloneCntr;
                                     $review_question['name'] = "review_question_" . $cloneCntr;
						?>
							<div class="row-fluid " >
							
                              <div class="span12 add_review_question" id ="question_<?php echo $cloneCntr; ?>">
								 <!-- Tiny MCE Code
                                <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                                <!-- Field to display the description of the organization -->
										<div class="control-group">
											<label class="control-label" for="review_question_1" style="width:0px;">Question:<font color="red"><b>*</b></font></label>
											<div class="controls" style=" margin-left:90px;">
												<?php echo form_textarea($review_question); ?> 
											</div>
										</div>
							  </div>
							  
								<div class="row-fluid" id="question_details">
								<div class="row-fluid">
									<div class="span8">
														
														<!--<button type="button" id="upload-btn<?php echo $cloneCntr; ?>" name="upload_btn"  abbr="1" class="btn btn-primary btn-small clearfix upload_btn" value="upload" style=" margin-left:90px;"><i class ="icon-upload icon-white"></i>upload</button>-->
														<span style="font-size:8px;padding-left:20px;vertical-align:middle;"><i><b>Note:</b> PNG, JPG, or GIF (500K max file size)</i></span>
														<div id="errormsg" class="clearfix redtext">
														</div>	              
														<div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;">
														</div>	
														
														<div id="picbox" class="clear" style="padding-top:0px;padding-bottom:10px;">
														</div>
								
								<!--<div id="image_show_<?php echo $cloneCntr; ?>" class="controls span12" style=" margin-left:90px;">
									<?php $imgcntr = 1;
									if(!empty($image_name_data[$cloneCntr-1])){
									foreach($image_name_data[$cloneCntr-1] as $image_name){ 
								 ?>
								<?php if($image_name['image_name'] != NULL){ ?>
									<div class="controls span1" id="img_thmb_<?php echo $cloneCntr.$imgcntr;?>">
										<table width="100%" class="imgtbleclass">
											<tr>
												<td>
													
													<img src="<?php echo base_url('uploads/'.$image_name['image_name']);?>" class="imgclass"/><i id="romove_image<?php echo $cloneCntr.$imgcntr;?>"class="icon-remove image_remove img_float_rght"></i>
												</td>
											</tr>
										</table>
									</div>
									<?php }else{
									} ?>
									<?php 
									$imgcntr++;
											}
									
										}else{ 
									}
									?>
									
								</div>-->
								
								<!--<div id="image_insert_<?php echo $cloneCntr; ?>">
								<?php $img_cntr = 1;
								
									if(!empty($image_name_data[$cloneCntr-1])){
									foreach($image_name_data[$cloneCntr-1] as $image_name){ 
									if($image_name['image_name'] != NULL){
								 ?>
								 <input type="hidden" name="image_hidden_<?php echo $cloneCntr; ?>[]" id="image_hidden_<?php echo $cloneCntr.$img_cntr; ?>" value="<?php echo $image_name['image_name']; ?>" />
									<?php 
									}else {
									?>
									
									<?php
									}
									$img_cntr++;
											}
									
										}else{ 
									}
									?>
								<input type="hidden" name="edit_img_cntr_<?php echo $cloneCntr; ?>" id="edit_img_cntr_<?php echo $cloneCntr; ?>" value="<?php echo $imgcntr-1; ?>"/>
								
								<!--<input type="text" id="image_counter" name="image_counter[]" value="<?php// echo $imgcntr; ?>" />
								</div>-->
								</div>
								<!--<div id="image_show" class="controls">			
								</div>-->
								
		  
								
							</div>
							 <?php
									//	foreach ($tlo_data as $tlo):
										//$tlo_id['value'] = $tlo['tlo_id'];
										// $tlo_id['id'] = "tlo_id" . $cloneCntr;
										// $tlo_id['name'] = "tlo_id" . $cloneCntr;
										 
							?>
							<div class="row-fluid">
							
								<div class="span4" >
							 
								
										<div class="control-group">
											<label class="control-label" for="tlo_id_1" style="width:100px;"><?php echo $this->lang->line('entity_tlo'); ?>:<font color="red"><b>*</b></font></label>
										<div class="controls" style=" margin-left:90px;">
													 <?php echo form_dropdown('tlo_id'.$cloneCntr, $tlo_id, $ids['tlo_id'], 'id =tlo_id' . $cloneCntr.   '   class="tlo_bloom_level required"'); ?>
											 												 
										</div>
									</div>
									 
							
								</div>
								<div class="span4" >
							 
								
										<div class="control-group">
											<label class="control-label" for="bloom_ids_1" style="width:100px;">Bloom's Level:<font color="red"><b>*</b></font></label>
										<div class="controls" style=" margin-left:90px;">
													
														 <?php echo form_dropdown('bloom_id'.$cloneCntr, $bloom_level, $ids['bloom_id'], 'id =bloom_id' . $cloneCntr.  '  class="bl_pi_codes required"'); ?>
													
																							 
										</div>
									</div>
									 
							
								</div>
							<div class="span4" >
							 
								
										<div class="control-group">
											<label class="control-label" for="pi_codes_1" style="width:100px;">PI Codes:</label>
										<div class="controls" style=" margin-left:90px;">
													 <?php echo form_dropdown('pi_code'.$cloneCntr, $pi_code, $ids['pi_codes'], 'id =pi_code' . $cloneCntr); ?>
																							 
										</div>
										
									</div>
									 
							
								</div>
								<a id="remove_field<?php echo $cloneCntr; ?>" class="Delete_question" href="#"><i class="icon-remove pull-right"></i> </a>
							</div>
							</div>
							 <?php $cloneCntr++; ?>
                                    <?php endforeach; ?>
								<div id="add_before"></div>
								<div id="clone_data"></div>
									<input type="hidden" name="questions_counter" id="questions_counter" value="<?php echo --$cloneCntr; ?>"/>
									<input type="hidden" name="edit_counter_array" id="edit_counter_array" value=""/>
								</div>
								
								
								
								<div class="pull-right">
								<button id="add_question" class="btn btn-primary add_question" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button></div>
						   <br>
						
						<br>
						
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                          Assignment Question
                                        </div>
									</div>
						<?php $assign_counter = 1;
						foreach($assignment as $assign){ 
									$assignment_question['value'] = $assign['assignment_content'];
                                     $assignment_question['id'] = "assignment_question_" . $assign_counter;
                                     $assignment_question['name'] = "assignment_question_" . $assign_counter;?>		
							<div class="row-fluid">
                              <div class="span12 add_me1">
								<div class="row-fluid">
									  <div class="span12">
										<div class="control-group">
											<label class="control-label" for="assignment_question_1" style="width:0px;">Assignment:</label>
											<div class="controls" style="margin-left:90px;">
												<?php echo form_textarea($assignment_question); ?> 
												
											</div>
										</div>
									  </div>
									  
								<!-- ends here-->
								</div>
								</div>
								
								</div>
								<?php $assign_counter++;
								}
								?>
								
								<div id="assignment_question_insert">
								</div>
								<input type="hidden" id="assignment_counter" name="assignment_counter" value="<?php echo --$assign_counter ;?>" />
								<input type="hidden" id="assignment_array" name="assignment_array" value="" />
								<button id="" class="btn btn-primary add_assignment_question pull-right" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
						   <br>
						
						<br>
						<div class="pull-right row">
								
                                <button type="submit" id="update"  class="add_details btn btn-primary" ><i class="icon-file icon-white"></i> Update </button>
								<button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
                                <a href="<?php echo base_url('curriculum/tlo_list'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i> Cancel </a>	
                            </div>
						</div>
							<br>
							
						</form>
						</div>
        </div>
		
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
        
    </body>
	<!--<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "></script>-->
	<!--<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>-->
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/lesson_schedule_edit.js'); ?> "> </script>
    <!--<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js'); ?> "> </script>-->
    <!--<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/tinymce/plugins/tinymce_equation_editor/mathquill.min.js'); ?> "> </script>-->
</html>