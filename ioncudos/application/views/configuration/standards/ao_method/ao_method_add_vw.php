<?php
/**
 * Description	:	Assessment Method add page will allow the admin to add assessment methods and their criterias.
 * 					
 * Created		:	14-08-2014
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 *
  ------------------------------------------------------------------------------------------------- */
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
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add Assessment Method
                                </div>
                            </div>	
                            <br>
                            <form  class="form-horizontal" method="POST" id="ao_method_add_form" name="ao_method_add_form" action= "<?php echo base_url('configuration/ao_method/ao_method_insert_record'); ?>">
								<div class="control-group">
                                    <p class="control-label inline" for="">Program Title: <font color="red">* </font></p>
                                    <div class="controls">
										<select size="1" id="pgm_id" name="pgm_id" class="span5" >
										<option value="0" selected>Select Program Title</option>
										<?php foreach($results as $type){
										if($type['pgm_id'] == $pgm_id_val){?>
										<option value="<?php echo $type['pgm_id']; ?>" selected="selected"><?php echo $type['pgm_title'];?></option> <?php } else{ ?>
										<option value="<?php echo $type['pgm_id']; ?>" ><?php echo $type['pgm_title'];?></option>
										<?php } ?>
										<?php } ?>
										</select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <p class="control-label" for="ao_method_name">Assessment Method Name :<font color="red">*</font>
									</p>
                                    <div class="controls">
                                        <?php echo form_input($ao_method_name); ?>  
                                    </div>
                                </div>
								<div class="control-group">
                                    <p class="control-label" for="ao_method_description">Outcome Assessed :</p>
                                    <div class="controls">
                                        <?php echo form_textarea($ao_method_description); ?>
                                        <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                    </div>
                                </div>		
								
								<div class="pull-right"> 
									<a id="define_rubrics" class="btn btn-primary global" href="#">Define Rubrics</a>										
									<input type="hidden" name="range_count" id="range_count" value="4" />
									<input type="hidden" name="is_define_rubrics" id="is_define_rubrics" value="0" readonly />
								</div>
								<br><br>
								
								<div id="check_main" class="bs-docs-example" style="width:1030px;height:100%;overflow:auto;" >
									<div id="check"><!--style="width:1100px;height:100%;overflow:auto;">-->
									
									</div>
								</div>
								<!--Checkbox Modal-->
                                <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Warning 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >Assessment Method Name already exists under the selected Program.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                                    </div>
                                </div>
														
								<div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
									<div class="modal-header">
										<div class=" navbar-inner-custom">
											Delete Confirmation 
										</div>
									</div>
										<div class="modal-body">
											<p>Are you sure you want to delete?</p>
											<input type="hidden" value="" name="deleteId" id="deleteId" readonly />
										</div>
										<div class="modal-footer">
											<button class="delete_confirm btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
											<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
										</div>
								</div>
								
								<div class="pull-right"><br>
                                    <button class="ao_method_add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save </button>
                                    <button class="clear_all btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset </button>
									<a href= "<?php echo base_url('configuration/ao_method'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
								</div>
							<br><br>
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
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/ao_method.js'); ?>"></script>
<!-- End of file ao_method_add_vw.php 
                        Location: .configuration/standards/course_type/ao_method_add_vw.php -->
