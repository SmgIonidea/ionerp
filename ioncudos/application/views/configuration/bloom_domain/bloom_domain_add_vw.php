<?php
/**
 * Description          :       Add View for Bloom's Domain Module.
 * Created		:	31-05-2015. 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description

  --------------------------------------------------------------------------------
 */
?>
<!--head here -->
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
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Bloom's Domain
                        </div>
                        <div>
                            <font color= "red" ><?php echo validation_errors(); ?></font>
                        </div>
                    </div><br/><br/>
                    <form  class="form-horizontal" method="POST"  id="add_form" name="add_form" action="<?php echo base_url('configuration/bloom_domain/insert_bloom_domain'); ?>">
                        <div class="control-group">
                            <p class="control-label">Bloom's Domain : <font color="red">*</font></p>
                            <div class="controls">
                                <?php echo form_input($bloom_domain); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Bloom's Domain Acronym : <font color="red">*</font></p>
                            <div class="controls">
                                <?php echo form_input($bloom_domain_acronym); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label">Description :</p>
                            <div class="controls"> 
                                <?php echo form_textarea($description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div><br/><br/>
                        <div class="pull-right">       
                            <button id="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-info" id="reset" type="reset"><i class="icon-refresh icon-white"></i> Reset</button></form>
                    <a class="btn btn-danger" href= "<?php echo base_url('configuration/bloom_domain'); ?>"><i class="icon-remove icon-white"></i> Cancel</a>
                </div>
                <!-- Uniqueness warning Modal-->
                <div id="uniqueness_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="uniqueness_dialogLabel" aria-hidden="true" style="display: none;" data-controls-modal="uniqueness_dialog" data-backdrop="static" data-keyboard="true">                   
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning 
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>This Bloom's Domain already Exists.</p>
                    </div>						
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    </div>
                </div><br/>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bloom_domain.js'); ?>" type="text/javascript"></script>
<!-- End of file bloom_domain_add_vw.php 
                      Location: .configuration/bloom_domain/bloom_domain_add_vw.php -->