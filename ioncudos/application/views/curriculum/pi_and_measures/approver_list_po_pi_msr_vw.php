<?php
/**
 * Description	:	Approved Program Outcome along with its corresponding Outcome Elements and 
					Performance Indicators

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 30-09-2013		    Arihant Prasad			File header, function headers, indentation 
												and comments.
 ----------------------------------------------------------------------------------------------*/
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
                <?php //$this->load->view('includes/static_sidenav_2'); ?>
                <div class="span12">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs)
                                </div>
								
								
								<div id="loading" class="ui-widget-overlay ui-front">
									<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
								</div>
                            </div>
							
                            <div class="span12">
							<input type="hidden" name="crclm_id_val" id="crclm_id_val" value="<?php echo $crclm_id; ?>"/>
                                <p> Curriculum:
                                    <select name="curriculum_list" id="curriculum_list" onChange = "bos_select_curriculum();" disabled="disabled">
                                        <option value=""> Select the curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum) { ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>" <?php if ($crclm_id == $curriculum['crclm_id']) {
                                            echo "selected=selected";
                                        } ?> >
											<?php echo $curriculum['crclm_name']; ?></option>
										<?php } ?>
                                    </select>
								</p>
                            </div>

                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:95px">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Approved <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> Selected <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> Comments </th>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">
                                        <?php $count = 1;
										foreach ($po_list as $po_row) { ?>
                                            <tr>
												<td>
													<?php echo $po_row['po_reference'] . '. ' . $po_row['po_statement']; ?>
                                                </td>
                                                <td>
                                                    <a data-toggle="modal" href="#myModal_pi_msr" onclick="pi_list(this.id);" id="<?php echo $po_row['po_id']; ?>"> <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs </a>
                                                </td>
                                                <td>
													<center><textarea id="pi_cmt_<?php echo $count; ?>" name="pi_cmt_<?php echo $count; ?>" rows="4" cols="5" class="required pi_cmt_add" abbr="<?php echo $po_row['po_id']; ?>" style="margin: 0px 0px 10px; width: 324px; height: 40px;"><?php 
													if(!empty($po_comment_result)){
													foreach($po_comment_result as $po_comment){
													if($po_comment['po_id'] == $po_row['po_id']){
													echo $po_comment['cmt_statement'];
													}else{
													
													}
													}
													}else{
													}?></textarea>
														<!--<a href="#" class="icon-comment comment" rel="popover" data-content='
															<form id="mainForm" name="mainForm">
																<p>
																	<textarea id="pi_cmt" name="pi_cmt" rows="4" cols="5" class="required"></textarea>
																	<input type="hidden" name="po_id" id="po_id" value="<?php echo $po_row['po_id']; ?>"/>
																   
																</p>
																<p>
																	<div class="pull-right">
																		<a class="btn btn-primary cmt_submit" href="#"><i class="icon-file icon-white"></i> Save </a>
																		<a class="btn btn-danger close_btn" href="#"><i class="icon-remove icon-white"></i> Close </a>
																	</div>
																</p>
															</form>' data-placement="top" data-original-title="Add Comments Here">
														</a>-->
													</center>
												</td>
											</tr>
										<?php } ?>
                                    </tbody>
                                </table>
                                <!--Do not place contents below this line-->	
                            </div>
							
                            <div class="clear">
                            </div>

                            <div class="pull-right">
                                <button id="rework" class="btn btn-danger"><i class="icon-refresh icon-white"></i> Rework</button>
                                <button id="accept" class="btn btn-success"><i class="icon-ok icon-white"></i> Accept</button>
                            </div><br><br>
					</section>

							<!-- Modal to display Program outcome and its corresponding Outcome Elements & Performance Indicators -->
							<div id="myModal_pi_msr" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pi_msr" data-backdrop="static" data-keyboard="true">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											<?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) & its corresponding <?php echo $this->lang->line('outcome_element_plu_full'); ?> & PIs
										</div>
									</div>
								</div>
								<div class="modal-body">
									<div id="list"> 
									</div>
								</div>

								<div class="modal-footer">
									<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close</button>

								</div>
							</div>
							
							<!-- Modal to display re-work confirmation message -->
							<div id="myModal_rework" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_rework" data-backdrop="static" data-keyboard="false">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Rework confirmation 
										</div>
									</div>
								</div>
								
								<div class="modal-body">
									<p> <b> Current State: </b> Review of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators has been completed. </p>
									<p> <b> Next State: </b> Rework of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs). </p>
									<p> An email will be sent to <?php echo $this->lang->line('program_owner_full'); ?>: <b id="pgm_owner_user_rework" style="color:rgb(230, 122, 23);"></b> </p>
								
									<h4><center> Current status of curriculum: <b id="crclm_name_oe_pi_rework" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
									<img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/oe_pi_rework_accept.png'); ?>">
								</div>

								<div class="modal-body">
									<p> Are you sure you want to send <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators for rework ? </p>
								</div>

								<div class="modal-footer">
									<button class="btn btn-primary" data-dismiss="modal" onClick="rework();"><i class="icon-ok icon-white"></i> Ok</button> 
									<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button> 
								</div>
							</div>
							
							<!-- Modal to display accept confirmation message -->
							<div id="myModal_accept" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_accept" data-backdrop="static" data-keyboard="false">
								<div class="modal-header">
									<div class="navbar">
										<div class="navbar-inner-custom">
											Accept confirmation
										</div>
									</div>
								</div>
								
								<div class="modal-body">
									<p> <b> Current State: </b> Review of <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators has been completed. </p>
									<p> <b> Next State: </b> Addition of all courses. </p>
									<p> An email will be sent to <?php echo $this->lang->line('program_owner_full'); ?>: <b id="pgm_owner_user_accept" style="color:rgb(230, 122, 23);"></b> </p>
								
									<h4><center> Current status of curriculum: <b id="crclm_name_oe_pi_accept" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
									<img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/oe_pi_rework_accept.png'); ?>">
								</div>
								
								<div class="modal-body">
									<p> Are you sure you want to accept <?php echo $this->lang->line('outcome_element_plu_full'); ?> & its corresponding Performance Indicators? </p>
								</div>

								<div class="modal-footer">
									<button class="btn btn-primary" data-dismiss="modal" onClick="accept();"><i class="icon-ok icon-white"></i> Ok</button> 
									<button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel</button> 
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_msr_approver.js'); ?>" type="text/javascript"> </script>


<!-- End of file approver_list_po_pi_msr_vw.php 
                        Location: .curriculum/pi_measures/approver_list_po_pi_msr_vw.php -->
