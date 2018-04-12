<?php
/**
 * Description	:-	Delivery Method add page will allow to edit delivery methods and their description. 					
 * Created		:-	25-05-2015
 * Author		:	Arihant Prasad
 * Modification History:-
 *   Date                Modified By                			Description
 * 23-05-2015			Abhinay Angadi							Edit view functionalities
  ----------------------------------------------------------------------------------------------------- */
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
                        <div class="bs-docs-example ">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit Curriculum Delivery Method
                                </div>
                            </div>
							<p><h4 style="font-size:12px;"> <?php echo str_repeat('&nbsp;',10); ?> Curriculum : <?php echo $crclm_name; ?><h4></p><br>
                            <form class="form-horizontal" method="POST" id="delivery_method_edit_form" name="delivery_method_edit_form" action= "<?php echo base_url('curriculum/curriculum_delivery_method/crclm_delivery_method_update_record'); ?>">
                                <div class="control-group">
                                    <label class="control-label" for="delivery_method_id">Delivery Method Name:<font color="red"> * </font>
									</label>
									 <div class="controls">
                                        <?php echo form_input($crclm_id); ?>	
                                        <?php echo form_input($delivery_method_id); ?>	
									</div>
                                    <div class="controls">
                                        <?php echo form_input($delivery_method_name); ?>
                                    </div>
                                </div>
								<div class="control-group">
                                    <label class="control-label" for="delivery_mtd_description"> Delivery Method Guidelines : </label>
                                    <div class="controls">
                                        <?php echo form_textarea($delivery_method_description); ?>
                                    </div>
                                </div>			
								
								<div class="control-group">
                                    <label class="control-label" for="delivery_mtd_description"> Bloom's Level  : </label>
									<div class="controls">
										<div class="span12">
											<div class="span4">
												<?php echo form_dropdown('bloom_level[]', $bloom_level_data, $mapped_bloom_level_data, 'class="example-getting-started bloom_level_edit" multiple="multiple" id="bloom_level"', $bloom_level_data_title); ?>
											</div>
											
											<div class="span8">
												<div class="selected_bloom">
													<?php if($mapped_bloom_level_data_title ) {
														foreach($mapped_bloom_level_data_title as $mapped_bloom_level_title) {
															echo $mapped_bloom_level_title."<br>";
														}
													} ?>
												</div>
												
												<div class="bloom_level_edit_actionverbs span7" id="bloom_level_edit_actionverbs"></div>
											</div>
										</div>
									</div>
                                </div>
								
								<div class='pull-right'>
									<button class='delivery_method_edit_form_submit btn btn-primary'><i class='icon-file icon-white'></i><span></span> Update</button>&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="<?php echo base_url('curriculum/curriculum_delivery_method'); ?>" ><b class='btn btn-danger'><i class='icon-remove icon-white'></i><span></span> Cancel</b>
									</a>
								</div>
								<br><br>
									
								<!--Checkbox Modal-->
                                <div id="myModal_edit_view" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_edit_view" data-backdrop="static" data-keyboard="false">
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
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
							</form><br>
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

	<script src="" type="text/javascript"> </script>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_delivery_method_edit.js'); ?>" type="text/javascript">
	</script>
	<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"> </script>
	
<!-- End of file curriculum_delivery_method_edit_vw.php 
                        Location: .curriculum/curriculum_delivery_method/curriculum_delivery_method_edit_vw.php -->