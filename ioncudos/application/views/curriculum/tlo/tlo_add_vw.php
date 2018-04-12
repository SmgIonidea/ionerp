<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO Add view page, provides the fecility to add the details of TLO's.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
    <?php $this->load->view('includes/head'); ?>
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
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>s)
                                    <a href="#help" class="pull-right" data-toggle="modal" onclick="show_help();"style="text-decoration: underline; color: white; font-size: 14px;">Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
                                </div>
								
                            </div>	
                            <br>
                            <form class="form-horizontal" method="POST" id="tlo_add_form"  name="courseform" action="<?php echo base_url('curriculum/tlo/tlo_insert'); ?>">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <div class="span5">
                                                <div class="control-group">
                                                    <label class="control-label" for="curriculum">Curriculum: <font color='red'>*</font></label>
                                                    <div class="controls">
                                                        <?php echo form_input($crclm); ?>
														
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="term">Term: <font color='red'>*</font></label>
                                                    <div class="controls">
                                                        <?php echo form_input($term_name); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6 ends here-->
                                            <div class="span5">
                                                <div class="control-group">
                                                    <label class="control-label" for="course">Course: <font color='red'>*</font></label>
                                                    <div class="controls">
                                                        <?php echo form_input($course_name); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="topic"><?php echo $this->lang->line('entity_topic'); ?>: <font color='red'>*</font></label>
                                                    <div class="controls">
                                                        <?php echo form_input($topic_name); ?>
                                                    </div>
                                                </div>
                                            </div><!--span6 ends here-->
                                        </div><!--row-fluid ends here-->
                                    </div><!--span12 ends here-->
                                </div>
								
								<div id="list_tlo">
                                    <table class="table table-bordered">
										<tr>
											<!--<th>Sl No.</th>-->
											<th><?php echo $tlo_code; ?></th>
											<th><?php echo $tlo_title; ?></th>
											<th><?php echo $bloom; ?></th>
											<th><?php echo "Delivery Method";?></th>
										</tr>
										<tbody>
										<?php 
										//$sl_no = 1;
										if(!empty($tlo_list)){
											foreach($tlo_list as $list_tlo ){ //foreach( $delivery_mthd as $dd){
										?>
										<tr>
												<td>
													<?php echo $list_tlo['tlo_code']; ?>
												</td>
												<td>
														<?php echo $list_tlo['tlo_statement']; ?>
												</td>
												<td>
														<?php echo "<b>".$list_tlo['level']." - ".$list_tlo['description']." </b> :".$list_tlo['bloom_actionverbs']; ?>
												</td>
												<td>
														<?php echo "";?>
												</td>
										</tr>
										<?php
										//$sl_no++;
										}//}
										}else{
											?>
											<tr>
												<td colspan="3">
													<b>No <?php echo $this->lang->line('entity_tlo'); ?> present for this topic.</b>
												</td>
											</tr>
											<?php
										}
										?>
										</tbody>
									</table>
                                </div><!-- add_tlo-->
								
								
                                <div id="add_tlo">
                                    <div id="remove" class="tlo">
                                      <div id="tlo_statement" name="tlo_statement" data-spy="scroll" class="bs-docs-example" style="width:auto; height:220px; padding-top:30px;">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <div class="span5">
                                                        <div class="control-group">
                                                            <label class="control-label" for="crclm_id"><?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statement: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                                <?php echo form_textarea($tlo_name); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span7">
													<br>
                                                        <div class="control-group">
                                                            <label class="control-label" for="crclm_term_id">Bloom's Level: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                                <select id="bloom_level1" name="bloom_level1" class="input-large required fetch_action_verbs" align="center">
                                                                    <option value="" selected>Select Bloom's Level</option>
                                                                    <?php foreach ($bloom_level as $bloom): ?>
                                                                        <option value="<?php echo $bloom['bloom_id']; ?>"><?php echo $bloom['level'] . ' : ' . $bloom['description']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <a id="remove_field_1" class="Delete" href="#"><i class="icon-remove"></i> </a>
                                                            </div>
                                                        </div>
															
                                                    </div>

                                                </div>
                                            </div>
											<div class="span12 control-group" id="" >
												<div class="span4">
												</div>
											<div class="span5">
											<div class="row-fluid form-horizontal" id="">
											<div class="control-group" id="action_verb_display_1">
												Note : Select Bloom's Level to view its respective Action Verbsx
											</div>
											</div>
											</div>
											</div>
											<div class="span12 control-group" id="" >
												<div class="span5">
													<div class="control-group">
                                                            <label class="control-label" for="delivery_methods">Delivery Method: <font color='red'>*</font></label>
                                                            <div class="controls">
                                                                <select id="delivery_methods1" name="delivery_methods1" class="required input-large">
                                                                    <option value="" selected>Select Delivery Method</option>
																	<?php foreach($delivery_method as $method){ ?>
																	<option value="<?php echo $method['crclm_dm_id']; ?> " title = "<?php echo $method['delivery_mtd_name']; ?>"><?php echo $method['delivery_mtd_name']; ?> </option>
																	<?php } ?>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        </div>
											<div class="span5">
													<div class="control-group">
                                                            <label class="control-label" for="delivery_approach">Delivery Approach: </label>
                                                            <div class="controls">
                                                              <textarea class="" style="width:100%; height:62px;"cols="40" rows="4" name="delivery_approach1" id="delivery_approach"></textarea>
                                                            </div>
                                                        </div>
                                                        </div>
											</div>
                                        </div><br><!-- tlo_statement-->
                                    </div><!-- remove-->
                                </div><!-- add_tlo-->
                                <div id="insert_before">
                                </div>
								<input type="hidden" name="counter" id="counter" value="1">								
                                <br>
                                <div class="pull-right">
                                    <a id="add_field" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i>Add More <?php echo $this->lang->line('entity_tlo'); ?></a>
                                </div>
                                <br></br>
                               <!-- <div class="row-fluid">
                                    <div id="exercise_question" name="exercise_question" data-spy="scroll" class="bs-docs-example span3" style="width:1040px; height:110px;">	
                                        <div class="span12">
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label" for="exercise_question">Exercise Questions: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_textarea($exercise_question); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label class="control-label" for="crclm_term_id">Review Questions: <font color='red'>*</font></label>
                                                        <div class="controls">
                                                            <?php echo form_textarea($review_question); ?>
                                                        </div>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </div>-->
                                <br>
                                <!--Modal to display help content-->
                                <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                                    <div class="modal-header">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                <?php echo $this->lang->line('entity_tlo'); ?> and Bloom's Taxonomy guideline files
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="help1">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                                    </div>
                                </div>
                                <div id="error" style="color:red">
                                </div>
                                <div class="control-group pull-right">
                                    <button  class="save btn btn-primary" type="submit" id="save" onclick="return valid()"><i class="icon-file icon-white"></i><span></span> Save</button>
                                    <button  class="submit1 btn btn-info" type="reset" ><i class="icon-refresh icon-white"></i><span></span> Reset</button>
                                    <a href= "<?php echo base_url('curriculum/topic'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
                                </div>
                                <br>
                                </div>
                                </div>	
                                </div>	
                                <?php echo form_input($crclm_id); ?>
								<?php echo form_input($curriculum); ?>
                                <?php echo form_input($term_id); ?>
                                <?php echo form_input($course_id); ?>
                                <?php echo form_input($topic_id); ?>
                            </form>	
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>

       <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo.js'); ?>"></script>