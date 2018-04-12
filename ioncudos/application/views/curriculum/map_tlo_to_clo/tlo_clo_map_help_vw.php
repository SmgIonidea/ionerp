<?php
/**
 * Description	:	Help for Curriculum.
 * 					
 * Created		:	10-04-2014
 *
 * Author		:	
 * 		  
 * Modification History:
 *    Date               Modified By                			Description
 *  10-04-2014		Jevi V G     	        	File header, function headers, indentation 
												and comments.
 *
 ---------------------------------------------------------------------------------------------- */
?>

    <!-- TinyMCE -->
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/jscripts/tiny_mce/tiny_mce.js'); ?>"></script>
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
                 Location: .curriculum/curriculum/curriculum_help_vw.php -->