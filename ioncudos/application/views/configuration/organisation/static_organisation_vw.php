<?php
/**
 * Description	:	Static Organization page will display organization name and organization
					description for the guest user. Guest user will not able to make changes
					to the organization name or its description.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 20-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
												naming.
 *
  ------------------------------------------------------------------------------------------------- */
?>

<!--head here -->
    <!-- /TinyMCE -->
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/jscripts/tiny_mce/tiny_mce.js') ?>"></script>
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example" disabled>
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Organization
                                </div>
                            </div>	
                            <br>
                            <form>
                                <div class="controls">
                                    <label> Name: <input class="span7" type="text" <?php echo form_input($org_name); ?>
                                    </label>
                                </div>

                                <!-- Tiny MCE Code -->
                                <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                                <div class="controls">
                                    <label> Description: </label>
                                </div>
								
                                <div class="control-group">
                                    <?php echo form_textarea($org_desc); ?>
								</div>
                                <?php echo form_input($org_id); ?>
								<br>
								
								
                            </form>
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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/organization.js'); ?>" type="text/javascript"> </script>

<!-- End of file edit_organisation_vw.php 
                        Location: .configuration/organisation/edit_organisation_vw.php -->
