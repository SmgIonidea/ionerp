<?php
/**
* Description	:	Import Assessment Data List View
* Created		:	30-09-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
* 01-10-2014	   Arihant Prasad		Import and Export features,
										indentation
* 23-Jan-2015		Jyoti				Added snippets to view CIA QP						
-------------------------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/sidenav_5'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
				    Overall Course Attainment (<?php echo $this->lang->line('entity_see'); ?> & <?php echo $this->lang->line('entity_cie'); ?>) List
                                </div>
                            </div>
                           <table>
								<tr>
									<td align="left">
										Department:<font color='red'>*</font> 
										<select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();" >
											<option value="">Select Department</option>
											<?php foreach ($dept_data as $listitem): ?>
												<option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
											<?php endforeach; ?>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Program:<font color='red'>*</font>
										<select id="program" name="program" class="input-medium" onchange="select_crclm_list();">
											<option>Select Program</option>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Curriculum:<font color='red'>*</font> 
										<select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-large" onchange="select_termlist();" >
											<option value="">Select Curriculum</option>
										</select> <?php echo str_repeat('&nbsp;', 8); ?>
									</td>
									<td align="left">
										Term:<font color='red'>*</font> 
										<select id="term" name="term" class="input-medium" onchange="GetSelectedValue();">
											<option>Select Term</option>
										</select>
									</td>
								</tr>
							</table>
							<br>
							<div>
							<div id="generate_table">
							</div>
								</br></br>
								
								
								
							<div id="myModalQPdisplay" class="modal hide fade myModalQPdisplay modal-admin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
									  data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" data-width="1200">
									<div class="modal-header">
										<div class="navbar">
											<div class="navbar-inner-custom">
												<?php echo $this->lang->line('entity_cie'); ?> Question Paper
											</div>
										</div>
									</div>
									<input type="hidden" value="" name="values_data" id="values_data" />
									<div class="modal-body" id="qp_content_display" width="100%" height="auto">
										
									</div>									
									<div id="loading" class="ui-widget-overlay ui-front">
											<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
										</div>
							
									<div class="modal-footer">
										<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export </a>
										
										<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
									</div>
								</div>
                            <!--Do not place contents below this line-->
                    </section>	
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <!---place js.php here -->
		<script>
		var entity_topic = "<?php echo $this->lang->line('entity_topic'); ?>";
		var entity_clo = "<?php echo $this->lang->line('entity_clo'); ?>";
		</script>
		<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick.js'); ?>"></script>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tee_cia_attainment.js'); ?>" type="text/javascript"> </script>
		
<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.min.js'); ?>"></script>
		<script src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.dateAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.mekkoAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasTextRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisLabelRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasAxisTickRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.categoryAxisRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.barRenderer.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.highlighter.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pointLabels.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.canvasOverlay.min.js'); ?>"></script>
