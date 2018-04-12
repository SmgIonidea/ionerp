<?php
/**
 * Description	:	Edit View for Unit Module.
 * Created		:	19-3-2014. 
 * Modification History:
 * Date				Modified By				Description
 * 18-3-2014        Jevi V. G.                       Added file headers & indentations.
   --------------------------------------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit Topic Unit
                                </div>
                            </div>	<br><br>
                            <form  class="form-horizontal" method="POST" id="form_edit" name="form_edit" action= "<?php echo base_url('configuration/topic_unit/topic_unit_update_record'); ?>">
                                <div class="control-group">
                                    <p class="control-label" for="t_unit_name">Unit:<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_input($t_unit_name); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls"> 
                                        <?php echo form_input($t_unit_id); ?>
                                    </div>
                                </div>
								<!--Checkbox Modal-->
                                <div id="myModal_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_edit" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Uniqueness Warning 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p > Topic unit with this name already exists. </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
                                <br><br><br><br><br><br><br>
								
								<div id="uniqueness_dialog_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="uniqueness_dialogLabel" aria-hidden="true" style="display: none;" data-controls-modal="uniqueness_dialog_edit" data-backdrop="static" data-keyboard="true">
							</br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Uniqueness Warning ! ! ! !
                                            </div>
                                        </div>
                                    </div>
							<div class="modal-body">
								<p>This topic unit name already exists.</p>
								<br>
							</div>						
							<div class="modal-footer">
							<button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
							</div>
							</div>
						
							
                                <div class="pull-right">
                                    <button class="submit_edit btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
                            </form>
                            <a href="<?php echo base_url('configuration/topic_unit'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
                        </div>
                        <br><br><br>
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
		<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/topic_unit.js');?>"></script>
