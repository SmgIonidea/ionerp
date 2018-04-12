<?php
/** 
* Description	:	Static (read only) List View for TLO(Topic Learning Outcomes) to 
*					CLO(Course Learning Outcomes) Module. Select Curriculum, Term, 
*					Course & Topic then its corresponding TLOs to CLOs mapping grid  
*					is displayed for mapping process.
* Created		:	29-04-2013. 
* Modification History:
* Date				Modified By				Description
* 18-09-2013		Abhinay B.Angadi        Added file headers, indentations variable naming, 
*											function naming & Code cleaning.
-------------------------------------------------------------------------------------------------
*/
?>
    <!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
        <!--branding here-->
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Mapping of <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>s) to Course Learning Outcomes (CLOs) <?php echo $this->lang->line('entity_topic'); ?>wise
                                    </div>
                            </div>
                            <form class="form-horizontal">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <table>
											<tr>
											<td>
                                                <label>Curriculum:<font color='red'>*</font>
													<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "static_select_term();">
													<option value="" selected>Select Curriculum</option>
														<?php foreach ($results as $listitem): ?>
														<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
														<?php endforeach; ?>
													</select> 
												</label>
											</td>
											<td>
                                                <label>Term:<font color='red'>*</font>
                                                        <select size="1" id="term" name="term" aria-controls="example" onChange = "static_select_course();">
                                                        </select> 
												</label>
											</td>
											<td>
												<label>Course:<font color='red'>*</font>
                                                        <select size="1" id="course" name="course" aria-controls="example" onChange = "static_select_topic();">
                                                        </select> 
												</label>
											</td>
											<td>
                                                <label><?php echo $this->lang->line('entity_topic'); ?>:<font color='red'>*</font>
                                                    <select size="1" id="topic" name="topic" aria-controls="example" onChange = "  static_func_grid();">
                                                    </select> 
												</label>
											</td>
											</tr>
											</table>											
											<div class="span6">
                                            </div><!--span6 ends here-->
                                            <div id="table1" data-spy="scroll" data-target="#navbarExample" class="bs-docs-example scrollspy-example span8 scrollspy-example" style="width: 775px; height:100%; overflow:auto;">
                                            </div>
                                            <div class="span3">
                                                <div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:180px;">	
                                                    <div >
                                                        <label>  Course Learning Outcome (CLO) </label>
                                                        <textarea id="clo_statement_id" rows="5" cols="5" disabled>
                                                        </textarea>
                                                    </div>	
                                                    </br>
                                                </div><!--span4 ends here-->
                                            </div>
                                        </div><!--row-fluid ends here-->
                                        </form>			
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo_clo_map.js'); ?>" type="text/javascript"> </script>