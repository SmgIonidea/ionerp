<?php
/**
* Description	:	Add View for Course Domain Module.
* Created		:	07-06-2013.. 
* Modification History:
* Date				Modified By				Description
* 10-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 11-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
--------------------------------------------------------------------------------------------------------
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
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add Course Domain
                                </div>
                            </div>	
                            <form  class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('curriculum/course_domain/insert'); ?>">
                                <div class="control-group">
                                    <p class="control-label" for="crs_domain_name">Course Domain Name :<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_input($crs_domain_name); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <p class="control-label" for="crs_domain_description">Course Domain Description :</p>
                                    <div class="controls"> 
                                        <?php echo form_textarea($crs_domain_description); ?>
                                    </div>
                                </div>
                                <br><br>
                                <!--Checkbox Modal-->
                                <div id="add_warning_dialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="add_warning_dialog" data-backdrop="static" data-keyboard="true">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Warning ! ! ! ! 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >This Course Domain name already exists.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
                                <div class="pull-right">       
                                    <button class="add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save</button>
                                    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i><span></span> Reset</button></form>
                            <a href= "<?php echo base_url('curriculum/course_domain'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
                        </div>
                        <br>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/course_domain.js'); ?>" type="text/javascript"> </script>    