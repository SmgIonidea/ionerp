<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO Bulk Edit view page, provides the fecility to edit the TLO's in bulk.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <div class="container-fluid">
                                <!--content goes here-->	
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Edit <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>)
                                    </div>
                                </div>
                                <div id="error_message" style="color:red"></div>
                                <form name="update_tlo" id="update_tlo" method="post" action="<?php echo base_url('curriculum/tlo/tlo_update'); ?>" class="form-horizontal form-inline "  >                
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="row-fluid">
                                                <div class="span5">
                                                    <div class="control-group">
                                                        <label class="control-label" for="curriculum">Curriculum: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_dropdown('crclm_id', $crclm_name_data); ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="term">Term: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_dropdown('term_id', $term_data); ?>
                                                        </div>
                                                    </div>
                                                </div><!--span 6 -->
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label" for="course">Course: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_dropdown('course_id', $course_data); ?>
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="topic"><?php echo $this->lang->line('entity_topic'); ?>: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_dropdown('topic_id', $topic_data); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!--row-fluid ends here-->
                                        </div><!--span12 ends here-->
                                    </div>
                                    <?php
                                    $cloneCntr = 1;
                                    foreach ($tlo_result_data as $item):
                                        $tlo_statement['value'] = $item['tlo_statement'];
                                        $tlo_id['value'] = $item['tlo_id'];
                                        $tlo_statement['id'] = "tlo_statement" . $cloneCntr;
                                        $tlo_statement['name'] = "tlo_statement" . $cloneCntr;
                                        ?>
                                        <br><br>
                                        <div id="add_tlo">
                                            <div id="remove" >
                                               	<div id="tlo_statement<?php echo $cloneCntr; ?>" name="tlo_statement" data-spy="scroll" class="bs-docs-example" style="width:auto; height:220px; padding-top: 30px;">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <div class="span5">
                                                        <div class="control-group">
                                                            <label class="control-label" for="crclm_id"><?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statement: <font color='red'>*</font></label>
                                                            <div class="controls clone" id="tlo_id<?php echo $cloneCntr; ?>">
															<input type="hidden" value="<?php echo $cloneCntr; ?>" name="clone_counter" id="clone_counter" class="clone_counter">
                                                                <?php echo form_textarea($tlo_statement); ?>
																<?php echo form_input($topic_id); ?> 
																<?php echo form_input($tlo_id); ?> 
																
                                                            </div>
															<input type="hidden" value="<?php echo $item['curriculum_id']; ?>" name="crclm_id" />
                                                        </div>
                                                    </div>
                                                    <div class="span7">
                                                        <div class="control-group">
                                                            <label class="control-label" for="crclm_term_id">Bloom's Level: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                              <?php echo form_dropdown('bloom_level'.$cloneCntr, $bloom_level, $item['bloom_id'], 'id =bloom_level' . $cloneCntr.  '  class="input-medium tlo_bloom_level required fetch_action_verbs"'); ?>
                                                                <a id="remove_field_<?php echo $cloneCntr; ?>" class="Delete" href="#"><i class="icon-remove"></i> </a>
                                                            </div>
                                                        </div>
														<!--<div class="control-group">
                                                            <label class="control-label" for="crclm_term_id">Assessment Method: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                                <select id="assessment" name="assessment[]" class="input-large " align="center" multiple="multiple">
                                                                    <option value="" selected>Select Assessment Method</option>
                                                                    <option value="1">Quiz</option>
																	<option value="2">Seminars</option>
																	<option value="3">Paper Publish</option>
																	<option value="4">Practical Assignment</option>
																	<option value="5">Mini Project</option>
                                                                </select>
                                                            </div>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
											<div class="span12 control-group" id="" >
												<div class="span4">
												</div>
											<div class="span7">
											<div class="row-fluid form-horizontal" id="">
											<div class="control-group" id="action_verb_display_<?php echo $cloneCntr;?>">
												<?php echo '<b>Bloom\'s Level Action Verbs : </b>'.$item['bloom_actionverbs']; ?>
											</div>
											</div>
											</div>
											</div>
											<div class="span12 control-group" id="" >
												<div class="span5">
													<div class="control-group">
                                                            <label class="control-label" for="delivery_methods">Delivery Method: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                                <?php echo form_dropdown('delivery_methods'.$cloneCntr, $delivery_mthd_list,$item['delivery_mtd_id'], 'id =delivery_methods' . $cloneCntr.  '  class="input-large  required"'); ?>
                                                            </div>
                                                        </div>
                                                        </div>
											<div class="span5">
													<div class="control-group">
                                                            <label class="control-label" for="delivery_approach">Delivery Approach: </label>
                                                            <div class="controls">
                                                              <textarea class="" style="width:100%; height:62px;"cols="40" rows="4" name="delivery_approach<?php echo $cloneCntr; ?>" id="delivery_approach<?php echo $cloneCntr; ?>"><?php echo $item['delivery_approach'];?></textarea>
                                                            </div>
                                                        </div>
                                                        </div>
											</div>
                                        </div>
										<br>
                                            </div>
											
                                        </div>
										
                                        <?php $cloneCntr++; ?>
                                    <?php endforeach; ?>
                                  
                                    <div id="add_before">
                                    </div>
									<input type="hidden" value="" name="counter" id="counter" class="counter">
                                    <div class="control-group pull-right">
                                        <div class="controls">
                                            <a id="add_field" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('entity_tlo'); ?></a>
                                        </div>
                                    </div>
                                  <!-- <div class="row-fluid">
                                    <div id="exercise_question" name="exercise_question" data-spy="scroll" class="bs-docs-example span3" style="width:1040px; height:110px;">	
                                        <div class="span12">
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label" for="exercise_question">Exercise Questions: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php //echo form_textarea($exercise_question);?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label" for="crclm_term_id">Review Questions: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php //echo form_textarea($review_question);?>
                                                        </div>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                                    <?php echo form_input($crclm_id); ?>
                                    <?php echo form_input($term_id); ?>
                                    <?php echo form_input($course_id); ?>
                                    <?php echo form_input($topic_id); ?>
                                </form>
								<br>
								<br>
								<br>
                                <div class="pull-right">
									<button type="submit" value="Update" id="tlo" class="btn btn-primary update" ><i class="icon-file icon-white"></i> Update</button>
                                    <a class="btn btn-danger" id="cancel" href="<?php echo base_url('curriculum/tlo_list'); ?>"><i class="icon-remove icon-white"></i> Cancel</a>
                                </div>
                            </div>		
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
        <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo_edit.js'); ?>"></script>
    </body>
</html>