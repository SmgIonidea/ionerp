<?php
/**
* Description	:	Static List View of PEO(Program Educational Objectives) to ME(Mission Elements) Mapping Module.
* Created		:	22-12-2014 
* Modification History:
* Date				Modified By				Description
* 23-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                     Mapping between Program Educational Objectives (PEOs) and Mission Elements(MEs)
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
                                                    <label> Mission Elements (MEs) </label>
                                                    <textarea id="me_display_textbox_id" rows="5" cols="5" disabled></textarea>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo_me_map.js'); ?>" type="text/javascript"> </script>