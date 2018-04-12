<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
 * Description	: Program Articulation Matrix view page, provides the term all courses mapping with po details.	  
 * Author : Abhinay B.Angadi
 * Modification History:
 * Date							Modified By								Description
 * 28-10-2015                   Abhinay B.Angadi                        Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
<!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar');   ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_3'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/crs_articulation_report/export_pdf'); ?>">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Program Articulation Matrix Report (Termwise)
                                    </div>
                                </div>
								<!--
								<div class="pull-right">
									<a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i>Export</a>   
								</div>

								-->
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div>
                                            <div class="row-fluid">
												<table style="width:70%">
													<tr>
														<td>
															<p>
																Curriculum:<font color="red"> * </font>
																<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();
																		fetch_crclm();">
																	<option value="Curriculum" selected>Select Curriculum</option>
																	<?php foreach ($results as $listitem): ?>
																		<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
																	<?php endforeach; ?>
																</select>
															</p>
														</td>
														<td>
															<p>
																Term:<font color="red"> * </font>
																<select size="1" id="term" name="term" aria-controls="example" onChange = "func_grid();">
																<option value="Curriculum" selected>Select Curriculum</option>
																</select>
															</p>
														</td>
														<!--<td>
															<p>
																<input type="checkbox" name="course" id="course" onChange= "func_grid();" value="core" >&nbsp;Core Course
															</p>
														</td>-->
													</tr>
												</table>
												
											 <div class="span12">
                                                    <div id="main_table" class="bs-docs-example" style="overflow:auto; width:auto;">
                                                        <div id="course_articulation_matrix_grid">
                                                        </div>
                                                    </div>
                                                </div><!-- span12 ends here-->
                                            </div> <br />
                                            <div class="row-fluid">
												<div class="span12" id= "po_stmt">
                                                    <div class="bs-docs-example span3" style="overflow:auto; width:100%;" >	
                                                        <div >
                                                            <p> <b><font color="blue"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></font></b></p>
                                                            <div style="overflow:auto;" id="text1">
                                                            </div>
                                                        </div>	
                                                    </div><!--spa12 ends here-->
                                                </div>
											</div>
											
                                            <input type="hidden" name="pdf" id="pdf" />
                                            <input type="hidden" name="stmt" id="stmt" />
                                            <input type="hidden" name="curr" id="curr"/>
                                            <input type="hidden" name="term_name" id="term_name"/>
											<br>
                                            <div class="pull-right">
                                                <a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i>Export</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!--Do not place contents below this line-->
                    </section>			
                </div>					
            </div>
        </div>

        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/pgm_articulation_report.js'); ?>" ></script>