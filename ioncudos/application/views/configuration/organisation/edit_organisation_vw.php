<?php
/**
 * Description          :	Organization allows the admin to add or edit the content of the login page. Organization makes use of tinymce for editing the content
  of the login page.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                    Modified By                			Description
 * 20-08-2013		   Arihant Prasad			File header, indentation, comments and variable naming.
 * 15-05-2014		   Jevi V G				Added wysiwyg editor from v3.x to v4.x 
  ------------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<!-- /TinyMCE -->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>

<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Organization
                        </div>
                    </div>

                    <form method="POST" id="form">
                        <!-- Field to display the name of the society -->
                        <div class="control-group">
                            <p class="control-label" for="org_society" data-key="lg_society_name"> Society Name: </p>
                            <div class="controls">
                                <?php echo form_input($org_society); ?>
                            </div>
                        </div>

                        <!-- Field to display the name of the organization -->
                        <div class="control-group">
                            <p class="control-label" for="org_name" data-key="lg_org_name"> Organization Name: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php echo form_input($org_name); ?>
                            </div>
                        </div>
                        <?php if ($user_data[0]['organization_name'] == 1) { ?>
                            <div class="row-fluid">                                
								<div class="span3">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society" data-key="lg_org_type"> Organization Type: <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('org_type', $org_type_options, $org_selected_type, "class='input required' id='org_type' required readonly=readonly"); ?>
                                        </div>
                                    </div>
                                </div>	
                                <div class="span4">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society"> Competencies(C) & Performance Indicators(PIs): <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('oe_pi', $comp_pi_options, $comp_pi_selected, "class='input required' id='oe_pi' style='width:250px;' required readonly=readonly"); ?>
                                        </div>
                                    </div>
                                </div>	
                                <div class="span3">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society"> Individual Mapping Justify Flag: <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('indv_map_just', $comp_pi_options, $ind_map_just_selected, "class='input required' style='width:200px;' id='indv_map_just' required readonly=readonly"); ?>
                                        </div>
                                    </div>
                                </div>    

								<div class="span2">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society"> Mid-Term : <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('mte_flag', $mte_options, $mte_flag_selected, "class='input required' id='mte_flag' style='width:150px;' required readonly=readonly"); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="row-fluid">
                               <!-- <div class="span4">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society" data-key="lg_org_type"> Organization Type: <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('org_type', $org_type_options, $org_selected_type, "class='input required' id='org_type' required readonly=readonly disabled"); ?>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="span4">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society"> Competencies(C) & Performance Indicators(PIs): <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('oe_pi', $comp_pi_options, $comp_pi_selected, "class='input required' id='oe_pi' required readonly=readonly disabled"); ?>
                                        </div>
                                    </div>
                                </div>	
                                <div class="span4">
                                    <div class="control-group">
                                        <p class="control-label" for="org_society"> Individual Mapping Justify Flag: <font color="red"><b>*</b></font></p>
                                        <div class="controls">
                                            <?php echo form_dropdown('indv_map_just', $comp_pi_options, $ind_map_just_selected, "class='input required' id='indv_map_just' required readonly=readonly disabled"); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <?php } ?>

                        <!-- Tiny MCE Code
                        <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                        <!-- Field to display the description of the organization -->
                        <div class="controls">
                            <p> Description: </p>
                        </div>
                        <?php echo form_textarea($org_desc); ?>
                        <?php echo form_hidden($org_id); ?>
                        <br>
                        <div class="controls">
                            <p> Vision: </p>
                        </div>
                        <?php echo form_textarea($vision); ?>
                        <br>
                        <div class="controls">
                            <p> Mandate: </p>
                        </div>
                        <?php echo form_textarea($mandate); ?>
                        <br>
                        <div class="controls">
                            <p> Mission: </p>
                        </div>
                        <?php echo form_textarea($mission); ?>
                        <br>
                        <!--Please do not delete kept for future use. -->
                        <!--<div class="controls">
                            <p> Mission Elements: </p>
                        </div>

                        <?php
                        if ($missions) {
                            $mission_counter = 1;
                            //var_dump($missions);
                            $count = 0;
                            foreach ($missions as $me) {

                                $missions['value'] = $me['mission_element']; //var_dump($me);
                                ?>	
                                        <div class="row-fluid">
                                            <div class="span12 add_me1">
                                                <div class="row-fluid">
                                                    <div class="span12">
                                                        <div class="control-group">
                                                            <div class="controls input-append" style=" margin-left:90px;">
                                <?php if ($me['mission_element'] != '0') { ?> 
                                                                    <textarea name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>"	 cols="80" rows="2" style = "height: 60px;"	class = "noSpecialChars input-xxlarge org_me_text_size"><?php echo $me['mission_element']; ?></textarea>
                                <?php } else { ?>
                                                                    <textarea name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>"	 cols="80" rows="2" style = "height: 60px;"	class = "noSpecialChars input-xxlarge org_me_text_size"></textarea>
                                <?php } ?>

                                <?php if ($count > 0) { ?>
                                                                    <button id="remove_field<?php echo $mission_counter; ?>" class="btn btn-danger delete_mission_element" type="button"><i class="icon-minus-sign icon-white"></i> Delete</button>
                                <?php } else {
                                    ?>
                                                                    <button id="add_mission_element" class="btn btn-primary add_mission_element" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                <?php } ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                <!-- ends here-->
                                <!--</div>

                            </div>

                        </div>

                                <?php
                                $mission_counter++;
                                $count++;
                            }
                        } else {
                            echo form_textarea($mission_element);
                        }
                        ?>
        <div id="mission_element_insert">

        </div>-->

<!--<button id="" class="btn btn-primary add_mission_element pull-right" type="button"><i class="icon-plus-sign icon-white"></i> Add More</button>-->
                        <br>
                        <?php echo form_input($mission_counter_val); ?>
                        <?php echo form_input($list); ?>

                        <br>

                        <div class="pull-right">
                            <a href="#" id="update" class="btn btn-primary" data-toggle="modal"><i class="icon-file icon-white"></i> Save</a>
                            <button type="reset"  class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset</button>
                        </div>
                        <br><br>

                        <!-- Modal to display the confirmation message on save -->
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Confirmation Message 
                                </div>
                            </div>

                            <div class="modal-body">
                                <p></p>
                            </div>

                            <div class="modal-footer">
                                <a href="<?php echo site_url('configuration/organisation/preview_login_page'); ?>" target="_blank" class="btn btn-success"><i class="icon-eye-open icon-white"></i> Preview </a>
                                <button class="btn btn-danger" id="close_btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>
                    </form>
                    <div id="error_message" style="color:red">
                    </div>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/organization.js'); ?>" type="text/javascript"></script>

<!-- End of file edit_organisation_vw.php 
Location: .configuration/organisation/edit_organisation_vw.php -->
