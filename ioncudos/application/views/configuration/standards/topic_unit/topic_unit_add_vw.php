<?php
/**
 * Description	:	Add View for Unit Module.
 * Created		:	18-3-2014
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
                                    Add Topic Unit
                                </div>
                            </div>	<br><br><br>
                            <form  class="form-horizontal" method="POST" id="form1" name="form1" action= "">
                                <div class="control-group">
                                    <p class="control-label" for="unit_name"> Topic Unit:<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_input($t_unit_name); ?>
                                    </div>
                                </div>
                                <!--Checkbox Modal-->
                                <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Uniqueness Warning 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >Topic unit with this name already exists. </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div>
                                <br><br><br><br><br><br><br>
                                <div class="pull-right">       
                                    <button  class="submit1 btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save</button>
                                    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i><span></span> Reset</button> 
                            </form>
                            <a href= "<?php echo base_url('configuration/topic_unit'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
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