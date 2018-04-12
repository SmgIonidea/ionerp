<?php
/**
 * Description	:	To add new Bloom's level, level of learning, characteristics of learning and 
					bloom action verb.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 27-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
												naming.
 *16-10-2014			Jyoti					Added snippets for validation
 * 10-11-2015		   Shayista Mulla			Hard code(entities) change by Language file labels.
  ------------------------------------------------------------------------------------------------- */
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
                <?php $this->load->view('includes/sidenav_4'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit Term End Evaluation (<?php echo $this->lang->line('entity_see'); ?>) Framework 
                                </div>
                            </div><br>

                            <form class="form-horizontal" method="POST" id="qp_frmwrk_form" name="qp_frmwrk_form" action= "<?php echo base_url('question_paper/manage_qp_framework/update_qp_framework'); ?>">
                            <div class="span6">
								<div class="control-group">
									<label class="control-label" for="pgm_title">Program Acronym<font color='red'> * </font>: </label>
									<div class="controls">
									<select id="program_type" name="program_type" class="required enable_section_gen" disabled="disabled" >
										<!--<option value="">Select Program Acronym</option>-->
										<?php foreach($program_type as $type){
										if($type['pgm_id'] == $qp_metadata[0]['pgmtype_id']){?>
										<option value="<?php echo $type['pgm_id']; ?>" selected="selected"><?php echo $type['pgm_acronym'];?></option> <?php } else{ ?>
										<option value="<?php echo $type['pgm_id']; ?>" ><?php echo $type['pgm_acronym'];?></option>
										<?php } ?>
										<?php } ?>
										</select>
									</div>
									<input type="hidden" id="program_type_h" name="program_type_h" value="<?php echo $qp_metadata[0]['pgmtype_id']; ?>" />
                                </div>
								<div class="control-group">
                                    <label class="control-label" for="level"> Grand Total Marks<font color="red"> * </font>: </label>
                                    <div class="controls">
                                        <?php echo form_input($grand_total); ?>
                                        <?php echo form_input($grand_total_hidden); ?>
                                    </div>
                                </div>
                            </div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label" for="qp_sections">No. of Parts (Units)<font color='red'> * </font>: </label>
									<div class="controls">
									<?php echo form_input($section); ?>
									<?php echo form_input($section_hidden); ?>
										
									</div>
                                </div>
								<div class="control-group">
                                    <label class="control-label" for="level_marks">Max Attemptable Marks<font color="red"> * </font>: </label>
                                    <div class="controls">
                                        <?php echo form_input($max_marks); ?>
                                    </div>
									<div class="controls">
                                        <span id="marks_exceed_error" ></span>
                                    </div>
                                </div>
                            </div>
					     
								<div class="control-group">
                                    <label class="control-label" for="level_marks">Title :</label>
                                    <div class="controls">
                                        <input type="text" name="qp_title" id="qp_title" value="<?php echo $qp_metadata[0]['qpf_title']; ?>" readonly class="input-xxlarge" style="font-weight:bold;" />
                                    </div>
                                </div>
								
                                <div class="control-group">
                                    <label class="control-label" for="description"> Note: </label>
                                    <div class="controls"> 
                                        <?php echo form_textarea($note); ?>
                                    </div>
                                </div>
								
                                <div class="control-group">
                                    <label class="control-label" for="bloom_actionverbs">Important Instructions: </label>
                                    <div class="controls">
                                        <?php echo form_textarea($instruction); ?>
                                    </div>
                                </div>
								<!-- Botton to generate section-->
								<input type="hidden" name="clear_question_section" id="clear_question_section" value="0" disabled />
								<div class="control-group">
                                    <div class="controls pull-right">
										<button name="section_generate" id="section_generate" type="button" class="btn btn-primary"><i class="icon-th-large icon-white"></i>Generate Section</button>
                                    </div>
                                </div>
								<!---->
							<div class="bs-docs-example">
								<div class="navbar">
									<div class="navbar-inner-custom">
									  Questions Distribution
									</div>
										<div class="control-group" id="qp_units">
										<table id="question_distribution" class="table table-bordered">
										<th id="qp_sections"><center>Section / Parts (Units)<font color="red"> * </font></center></th>
										<th id="question_nums"><center>No. of Questions<font color="red"> * </font></center></th>
										<th id="section_marks"><center>Section Marks<font color="red"> * </font></center></th>
										<tbody id="qp_section_data">
										<?php //var_dump($main_qdata); var_dump($sub_qdata);
											$k =1;
											$mq_total = 0;				
											foreach($main_qdata as $mqdata){ 
											$mq_total = $mq_total + $mqdata['qpf_utotal_marks'];
											$m = 1; ?>
											<tr class="info" id="section_data_<?php echo $k; ?>">
											<td><center><input type="text" name="no_section_<?php echo $k; ?>" id="no_section_<?php echo $k; ?>" value="<?php echo $mqdata['qpf_unit_code'];?>" class="input-medium required qp_unit alpha_dash"></center></td>
											<td><center><input type="text" name="question_<?php echo $k; ?>" id = "question_<?php echo $k; ?>" abbr="<?php echo $k; ?>" value="<?php echo $mqdata['qpf_num_mquestions'];?>" maxlength="2" class="input-mini required question_no onlyNumbers "></center></td>
											
											<td><center><input type="text" name ="marks_<?php echo $k; ?>" id="marks_<?php echo $k; ?>" abbr="<?php echo $k; ?>" maxlength="3" class="input-mini required qp_unit_marks onlyNumbers" value="<?php echo $mqdata['qpf_utotal_marks'];?>" ></center></td>
											</tr>
											<tr id="sub_section_data_<?php echo $k; ?>"></tr>
											<tr id="tb_head_<?php echo $k; ?>" class="tb_sub_head">
											<th><center>Question No.</center></th>
											<th><center>Sl No.</center></th>
											<th><center>Question Marks</center></th>
											</tr>
											<?php 
			
											foreach($sub_qdata as $sub_question){ 		
												if($sub_question['qpf_unit_id'] == $mqdata['qpf_unit_id']){
												?>
												<tr id="sub_section_<?php echo $k; ?>" class="sub_section" >
												<td><center><input type="text" name ="main_question_num_<?php echo $k; ?>_<?php echo $m; ?>" id="main_question_num_<?php echo $k; ?>_<?php echo $m; ?>" abbr="<?php echo $m; ?>" class="input-medium required main_questions"  value="<?php echo $sub_question['qpf_mq_code']; ?>" readonly /></center></td>
												<td><center><?php echo $m; ?></center></td>
												<td><center><input type="text" name ="que_marks_<?php echo $k; ?>_<?php echo $m; ?>" id="que_marks_<?php echo $k; ?>_<?php echo $m; ?>" abbr="<?php echo $m; ?>" maxlength="3" class="input-mini required question_marks" value ="<?php echo $sub_question['qpf_mtotal_marks']; ?>"/></center></td>
												
												<?php
												$m++;
												}
												
											}
											$k++;
											} ?>
										</tbody>
										</table>
										<div class="control-group pull-right">
											<p class="control-label" for="grant_total_marks">Grand Total Marks :</p>
											<div class="controls ">
											<input type="text" name="grand_total_marks" id="grand_total_marks" value="<?php echo $mq_total.' / '.$mq_total; ?>" class="input-mini" readonly />
											</div>
										</div>
									</div>
								</div>	
								
								<div id="sub_section_generation" class="pull-right">
									<button id="gen_sub_section" class="btn btn-primary" type="button"><i class="icon-th icon-white"></i>Generate Question Section</button>
								</div>
								
								<br><br>
	
								<div class="navbar">
									<div class="navbar-inner-custom">
									 Bloom's Level Percentage
									</div>
									<table id="bloom_level_data" class="table table-bordered">
										<th id="Level"><center>Bloom's Level</center></th>
										<th id="action_verbs"><center>Action Verbs</center></th>
										<th id="percentage"><center>Desired % of distribution</center></th>
										<tbody id="level_data">
										<?php $counter = 1; 
										foreach($bloom_data as $level_data){ ?>
											<tr>
											<td><?php echo $level_data ['level'].' - '.$level_data ['description']; ?>
											<input type="hidden" name="bloom_id_<?php echo $counter;?>" id="bloom_id_<?php echo $counter;?>" value="<?php echo $level_data ['bloom_id']; ?>"/>
											</td>
											<td><?php echo $level_data ['bloom_actionverbs']; ?></td>
											<td><center><input style="text-align: right;" type="text" name="level_percent_<?php echo $counter; ?>" id="level_percent_<?php echo $counter; ?>" class="input-mini required level_percent onlyNumbers" value="<?php echo $level_data['qpf_bl_weightage']; ?>"  abbr="<?php echo $counter; ?>">%<br><span id="bloom_percent_error_<?php echo $counter; ?>"></span></center></td>
											</tr>
										<?php $counter++;
										} ?>
										<input type="hidden" name="bloom_percent" id="bloom_percent" value="<?php echo $counter-1; ?>" />
										<tr>														
											<td colspan ="2"><p style="float:right"><!--Planned Total : <input type="text" name="total_percent" id="total_percent" class="input-mini" value="100" class="required " readonly />%--> Actual Total</p></td>
											<td><center><input type="text" name="actual_total_percent" id="actual_total_percent" class="input-mini" value="100 / 100" class="required " readonly />%</center></td>
										</tr>
										</tbody>
									</table>
								</div>
						
								<?php echo form_input($qpf_id); ?>
							</div></br>
						
                                <!-- Modal to display the uniqueness message -->
                                <div id="myModal_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Uniqueness Warning ! ! ! !
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p> Bloom's Level with this Level Name already exists. </p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
								<div id="myModal_bloom_distribution_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Bloom's Distribution Mismatch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p>Bloom's Level Planned Distribution is not equal to Bloom's Level Actual distribution.</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
								<div id="myModal_total_marks_section_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Marks Distribution Mismatch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p>Please check that marks distributed for Units is equal to Grand Total Marks or please enable the Generate Question Section.</p>										
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
									<div id="myModal_grand_total_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">



                                            <div class="navbar-inner-custom">
                                                Grand Total Marks Mismatch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p>Please check there is mismatch of Grand Total Marks distribution. </p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
								<div id="myModal_total_marks_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Total Marks Mismatch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p> Total of Section marks is not Equal to the Total marks of Question Paper </p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
								<div id="myModal_clear_question_distribution" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                               Discard Questions Distribution
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p>Are you sure you want to discard Question Distributions in order to proceed ?</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary clear_distribution" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
										<button class="btn btn-danger close1 restore_data" data-dismiss="modal"><i class="icon-remove icon-white"></i><span></span> Cancel </button>
                                    </div>
                                </div>
								
								 <div id="section_duplicate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Uniqueness Warning ! ! ! !
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-body" id="comments">
                                        <p> Same name defined for Section / Parts (Units). </p>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
								
								
								<br>
                                <div class="pull-right">   		
                                    <button id="qp_data_save" class="save_qp_data btn btn-primary" type="button"><i class="icon-file icon-white"></i><span></span>Update</button>
									<!--<button class="submit1 btn btn-success" type="submit"><i class="icon-eye-open icon-white"></i><span></span> QP Framework Preview</button>-->
                            </form>
									<a class="btn btn-danger"href= "<?php echo base_url('question_paper/manage_qp_framework'); ?>"><i class="icon-remove icon-white"></i><span></span> Cancel</a>
								</div>
                        <br>
                        <!--Do not place contents below this line-->	
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/jquery.validate.js'); ?>" type="text/javascript"> </script>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/edit_qp_framework.js'); ?>" type="text/javascript"> </script>

<!-- End of file bloom_add_vw.php 
                        Location: .configuration/standards/bloom_level/bloom_add_vw.php -->
