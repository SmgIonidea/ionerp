<?php
/**
 * Description          :	View page for Apllication setting Module.
 * Created              :	28-03-2017
 * Author               :	Shayista Mulla  
 * Modification History :
 * Date                  	Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
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
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Application Settings
                        </div>
                    </div>
                    <form action="" class="form-inline" method="POST" id="application_setting_form" name="application_setting_form">
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
                                            <?php echo form_dropdown('mte_flag', $comp_pi_options, $mte_flag_selected, "class='input required' id='mte_flag' style='width:150px;' required readonly=readonly"); ?>
                                        </div>
                                    </div>
                                </div>
						</div>                       
                        <div class="pull-right">
                            <button id="save" name="save" class="btn btn-primary" type="button" ><i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-info" type="reset" id="reset_form" ><i class="icon-refresh icon-white"></i> Reset</button>
                        </div>
                    </form>
                    <br/>
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/application_setting.js'); ?>" type="text/javascript"></script>
<!-- End of file application_setting_list_vw.php 
                        Location: .configuration/application_setting/application_setting_list_vw.php  -->