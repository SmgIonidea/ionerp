<!DOCTYPE html>
<html lang="en">
<!--head here -->
<?php  $this->load->view('includes/head'); ?>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!--branding here-->
<?php  $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php  $this->load->view('includes/navbar'); ?> 
<div class="container-fluid fixed-height">
	<div class="row-fluid">
		<!--sidenav.php-->
		<?php //  $this->load->view('includes/static_sidenav_1'); ?>
		<div class="span10 gray-block">
			<h3>Welcome</h3>
				<p>
					Curriculum design is an aspect of the education profession which focuses on developing curricula for students. Some education professionals specialize in curriculum design, and may spend all of their time working on curricula, rather than teaching in the classroom, while in other cases working teachers develop their own curricula. Curriculum design is also practiced by parents who homeschool their children, sometimes with the guidance of an experienced education professional who can provide advice and suggestions, and sometimes with the assistance of experienced homeschoolers. In many nations, specific benchmark standards are set for education to ensure that children across the nation achieve a similar level of education. For example, a government may dictate when children should start to learn multiplication and division, set standards for reading ability, and so forth. One aspect of curriculum design involves reviewing these standards and determining how they can be met or exceeded.
				</p>
		</div>
	</div>
</div>
<!---place footer.php here -->
<?php  $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php  $this->load->view('includes/js'); ?>
</body>
</html>