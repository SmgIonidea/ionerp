<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO Static list view page, provides the fecility to view the TLO's.
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013		Mritunjay B S       	Added file headers, function headers & comments. 
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
                        <?php $this->load->view('includes/static_sidenav_2'); ?>
                        <div class="span10">
                            <!-- Contents
                    ================================================== -->
                            <section id="contents">
                                <div class="bs-docs-example fixed-height" >
                                    <!--content goes here-->
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            <?php echo $this->lang->line('entity_tlo_full'); ?>(<?php echo $this->lang->line('entity_tlo'); ?>s) List
                                        </div>
                                    </div>
                                    <form class="form-horizontal">
										<!-- Form Name -->
										<!-- Select Basic -->
										<div class="row-fluid">
											<div class="span12">
												<div class="row-fluid">
													
													<table>
														<tr>
															<td>
													   
															<label> Curriculum:<font color='red'>*</font>
																<select id="curriculum" name="curriculum" class="required" onchange="static_select_term();">
																	<option value="">Select Curriculum</option>
																	<?php foreach ($crclm_name_data as $listitem): ?>
																		<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
																	<?php endforeach; ?>
																</select>
																</label>
																<input type="hidden" name="curriculum_hidden" id="curriculum_hidden">
																
														</td>
														
														<td>
															<label>Term:<font color='red'>*</font>
																<select id="term" name="term" class="required"  onchange="static_select_course();"></select>
																<input type="hidden" name="term_hidden" id="term_hidden">
															</label>
														</td>
														
														<td>
															<label>Course:<font color='red'>*</font>
																<select id="course" name="course" class=" required" onchange="static_select_topic();"></select>
																<input type="hidden" name="course_hidden" id="course_hidden"> 
															</label>
														</td>
														
														<td>
														<label><?php echo $this->lang->line('entity_topic'); ?>:<font color='red'>*</font>
														  <select id="topic" name="topic" class=" required" onchange="static_GetSelectedValue();"></select>
															 <input type="hidden" name="topic_hidden" id="topic_hidden">
														</label>
														</td>
														</tr>
														</table>
														
													   
													</div><!--span6 ends here-->
												
											<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
												<thead>
													<tr role="row">
														<th class="header " role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_tlo'); ?>s</th>
													</tr>
												</thead>
												<tbody id="table_data">
												</tbody>
											</table>
										</div>
									</div>                                          
								</div>
							</form>
                        </div>
                    </div>
                
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
        <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/static_tlo.js'); ?>"></script>
    </body>
</div>

</html>



