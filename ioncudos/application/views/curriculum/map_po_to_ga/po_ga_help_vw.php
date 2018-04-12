<?php
/**
* Description	:	View Logic for help entity of GAs to POs Mapping Module.
* Created		:	22-12-2014 
* Modification History:
* Date				Modified By				Description
* 22-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
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