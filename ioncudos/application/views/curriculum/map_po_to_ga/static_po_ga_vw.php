<?php
/**
* Description	:	Static List View of GA(Graduate Attributes) to PO(Program Outcomes) Mapping Module.
* Created		:	24-03-2015. 
* Modification History:
* Date				Author			Description
* 24-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.

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
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Map Graduate Attributes(GAs) to <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> 
                                </div>
                            </div>
							<div class="span12">
                                <label> Curriculum:
                                    <select name="curriculum_list" id="curriculum_list" onChange = "javaScript:static_select_curriculum();">
                                        <option value="Select the curriculum"> Select the curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>"> <?php echo $curriculum['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select><font color="red" ><p id="error"></p></font>
								</label>
								
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        
                                            <div class="bs-docs-example span8 scrollspy-example" style="width: 775px; height:100%; overflow:auto;" >
                                                <form method="post" id="frm" name="frm" onsubmit="return validate()">
                                                    <input type="hidden" id="crclm_id" name="crclm_id" >
                                                    <div id="mapping_table">
                                                    </div>
                                                </form>	
                                            </div>
                                        
                                        <div class="span3">
                                            <div data-spy="scroll" class="bs-docs-example span3" style="width:260px; height:325px;">	
                                                <div>
                                                    <label> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> </label>
                                                    <textarea id="po_display_textbox_id" rows="5" cols="5" disabled></textarea>
                                                </div>	
                                                </br>
                                               
                                            </div>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po_ga_map.js'); ?>" type="text/javascript"> </script>