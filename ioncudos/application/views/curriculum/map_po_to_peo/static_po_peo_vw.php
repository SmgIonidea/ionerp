<?php
/**
* Description	:	Static List View of PO(Program Outcomes) to PEO(Program Educational Objectives) Mapping Module.
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 24-09-2013		Abhinay B.Angadi		File header, function headers, indentation and comments.
* ------------------------------------------------------------------------------------------------------
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
                <?php // $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Map <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> to Program Educational Objectives (PEOs)
                                </div>
                            </div>
							<div class="span12">
                                <label> Curriculum:
                                    <select name="curriculum_list" id="curriculum_list" onChange = "static_select_curriculum();">
                                        <option value="Select the curriculum"> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>"> <?php echo $curriculum['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select><font color="red" ><p id="error"></p></font>
								</label>
								<h4><center><b id="po_peo_map_current_state1"></b></center></h4>
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
                                                <div>
                                                    <label> Justification </label>
                                                    <textarea id="po_peo_comment_box_id" rows="5" cols="5" placeholder="Enter text here..." maxlength="200" disabled="disabled"></textarea>
                                                </div>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po_peo_map.js'); ?>" type="text/javascript"> </script>
