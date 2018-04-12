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
 *    Date               Modified By                			Description
 *  05-09-2013		    Arihant Prasad			File header, function headers, indentation 
												and comments.
 *
 ---------------------------------------------------------------------------------------------- */
?>

	<!-- head here -->
	<?php $this->load->view('includes/head'); ?>
		<!-- branding here -->
		<?php $this->load->view('includes/branding'); ?>
		<br><br>
		<div class="container-fluid" >
			<div class="row-fluid" >
				<!-- sidenav.php -->
				<div class="span12">
					<!-- Contents -->
					<section id="contents">
						<div class="bs-docs-example" style="padding-bottom:248px;">
							<!-- content goes here -->
							<div class="navbar">
								<div class="navbar-inner-custom">
									<?php foreach ($help_content as $help) { ?>
										<?php echo $help['entity_data'] . " "; ?> Data 
										<a href="#help" class="" data-toggle="modal" onclick="show_help();"style="text-decoration: underline;"></a>
								</div>
							</div>
							<div>
								<p>
									<?php echo $help['help_desc'];
									} ?>
								</p>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	<!-- place footer.php here -->
	<?php $this->load->view('includes/footer'); ?> 
	<!---place js.php here -->
	<?php $this->load->view('includes/js'); ?>

<!-- End of file peo_help_vw.php 
                 Location: .curriculum/peo/peo_help_vw.php -->