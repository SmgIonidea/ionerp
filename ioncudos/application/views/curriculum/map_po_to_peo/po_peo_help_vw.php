<?php
/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
					learning outcomes. 
					Select Curriculum and then select the related term (semester) which 
					will display related course. For each course related CLO's and PO's
					are displayed.
					Write comments if required.
					Send for approve on completion.
 * 					
 * Created		:	02-04-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *    Date               Modified By                			Description
 *  20-05-2014		    Arihant Prasad			File header, function headers, indentation 
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