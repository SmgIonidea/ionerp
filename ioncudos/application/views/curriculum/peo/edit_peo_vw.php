<?php
/**
 * Description	:	PEO grid displays the PEO statements and PEO type.
					PEO statements can be deleted individually and can be edited in bulk.
					Notes can be added, edited or viewed.
					PEO statements will be published for final approval.
 * 					
 * Created		:	02-04-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                	    Description
 *  05-09-2013		    Arihant Prasad			File header, function headers, indentation 
												and comments.
 *  02-01-2016		   Shayista Mulla 			Added loading image and cokie.
  ---------------------------------------------------------------------------------------------- */
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

                    <!-- Content -->
		    <div id="loading" class="ui-widget-overlay ui-front">
                	<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            	    </div>
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit Program Educational Objectives (PEOs)
                                </div>
                                <div id="error_message" style="color:red"></div>
                            </div>
                            <?php
                            $cloneCntr = 1;
                            foreach ($peo_data as $item):
                                ?>
                                <form class="form-horizontal" id="peo_edit" method="POST" action="<?php echo base_url('curriculum/peo/peo_update');?>">
                                    <!-- Form Name -->
                                    <?php
                                    $peo_statement['value'] = $item['peo_statement'];
                                    $peo_id['value'] = $item['peo_id'];
                                    $peo_statement['id'] = "peo_statement" . $cloneCntr;
                                    $peo_statement['name'] = "peo_statement" . $cloneCntr;
                                    $peo_statement['abbr'] = $cloneCntr;
									
									$peo_reference['value'] = $item['peo_reference'];
                                    $peo_reference['id'] = "peo_reference" . $cloneCntr;
                                    $peo_reference['name'] = "peo_reference" . $cloneCntr;
                                    $peo_reference['abbr'] = $cloneCntr;
                                    ?>
                                    <div id="add_peo">
                                        <div id="remove" >
                                            <div id="peo_statement_div" class="bs-docs-example span3" style="width:1040px; height:180px;">
												<div class="control-group">
													<label class="control-label" for="peo_reference"> Program Educational Objective(PEO) Reference: <font color="red"> * </font></label>
													<div class="controls">
													  <?php echo form_input($peo_reference); ?>
													</div>
												  </div>
                                                <p class="control-label" for="peo_statement"> Program Educational Objective(PEO) Statement: <font color="red"> * </font></p>
                                                <div class="controls clone" id="peo_clone">
                                                    <?php echo form_textarea($peo_statement); ?> &nbsp; &nbsp;
													<input type="hidden" value="<?php echo $item['peo_id']; ?>" name="peo_id[]">
                                                    <a id="remove_field<?php echo $cloneCntr; ?>" class="Delete" href="#"><i class="icon-remove"></i> </a>
												</div>
                                            </div>
                                        </div>	
                                    </div>
								<input type="hidden" value="<?php echo $item['crclm_id']; ?>" name="crclm_id">
									<?php $cloneCntr++; ?>
                            <?php endforeach; ?>
							
							<div id="add_before">
							</div>
							

							<div class="control-group">
								<div class="controls">
									<a id="edit_field" class="btn btn-primary pull-right sticky_button"><i class="icon-plus-sign icon-white"></i> Add More PEO</a>
								</div>
							</div>
							
							<input id="add_more_peo_counter" name="add_more_peo_counter" type="hidden" value=""/>
							
							<div id="attendees" class="control-group">
								<p class="control-label" for="attendees"> Attendees Name: <font color="red"> * </font></p>

								<div class="controls">
									<?php echo form_textarea($attendees_name); ?>
								</div>
							</div>

							<div id="notes" class="control-group">
								<p class="control-label" for="notes"> Attendees Notes: </p>
								<div class="controls">
									<?php echo form_textarea($attendees_notes); ?>
								</div>
							</div>
							
							<div class="pull-right">   
								<button class="btn btn-primary peo_update" type="submit"><i class="icon-file icon-white"></i> Update</button>
								<a href= "<?php echo base_url('curriculum/peo/peo'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
							</div><br>
                        </div>
								</form>
                            <!--Do not place contents below this line-->
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo.js'); ?>" type="text/javascript"> </script>
	
<!-- End of file edit_peo_vw.php 
                Location: .curriculum/peo/edit_peo_vw.php -->
