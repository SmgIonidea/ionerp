<?php
/**
 * Description	:	Delivery Method add page will allow to add assessment methods and their criteria. 					
 * Created		:	25-05-2015
 * Author		:	Arihant Prasad
 * Modification History:
 *   Date                Modified By                			Description
 *	29-09-2015			 Bhagyalaxmi							Merged Add and list_view file
  ---------------------------------------------------------------------------------------------------- */
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
							<?php $this->load->view('includes/crclm_tab'); ?>
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add Delivery Method
                                </div>
                            </div><br>
							
                            <form class="form-horizontal" method="POST" id="delivery_method_add_form" name="delivery_method_add_form" action= "<?php echo base_url('curriculum/curriculum_delivery_method/crclm_delivery_method_insert_record'); ?>">
								<input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $crclm_id; ?>" />
								
								<!-- delivery method name -->
                                <div class="control-group">
                                    <label class="control-label" for="delivery_method_name"> 
										Delivery Method Name : <font color="red"> * </font>
									</label>
									
                                    <div class="controls">
                                        <?php echo form_input($delivery_method_name); ?>  
                                    </div>
                                </div>
								
								<!-- delivery method guidelines -->
								<div class="control-group">
									<label class="control-label" for="delivery_method_description"> 
										Delivery Method Guidelines :
									</label>
									
                                    <div class="controls">
                                        <?php echo form_textarea($delivery_method_description); ?>
                                    </div>
                                </div>
								
								<!-- bloom's level dropdown -->
								<div class="control-group">
									<label class="control-label" for="delivery_method_bloomslevel">
										Bloom's Level : 
									</label>
									
									<div class="controls">
										<select class="example-getting-started required bloom_level" name="bloom_level_1[]" id="bloom_level_1" multiple="multiple">
										
											<?php echo $bloom_level_options; ?>
										</select>
									</div>
								</div>
								
								<div class="pull-right"><br>
                                    <button class="delivery_method_add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <button class="clear_all btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
									<a href= "<?php echo base_url('curriculum/curriculum_delivery_method'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel </b></a>
								</div><br><br>
						</div>
								
								<!-- warning modal message - same delivery name -->
                                <div id="myModal_exists" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_exists" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Warning 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >This Delivery Method Name already Exists.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    </div>
                                </div>
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
		
    <script src="" type="text/javascript"> </script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_delivery_method.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"> </script>
<!-- End of file curriculum_delivery_method_add_vw.php 
                        Location: .curriculum/curriculum_delivery_method/curriculum_delivery_method_add_vw.php -->