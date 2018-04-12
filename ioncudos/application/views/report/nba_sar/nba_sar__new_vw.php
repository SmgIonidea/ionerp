<?php
/**
* Description	:	Add View for Program Type Module.
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 20-08-2013		Mritunjay B S       NBA Self Assessment report
--------------------------------------------------------------------------------
*/
?>
<!--head here -->
	<?php 	$this->load->view('includes/head'); ?>
		<!--branding here-->
		<?php  $this->load->view('includes/branding'); ?>
		<!-- Navbar here -->
		<?php  $this->load->view('includes/navbar'); ?> 
		<div class="container-fluid">
			<div class="row-fluid">
				<!--sidenav.php-->
				<?php  $this->load->view('includes/sidenav_3'); ?>
				<div class="span10">
					<!-- Contents
			================================================== -->
					<section id="contents">
						<div class="bs-docs-example fixed-height">
							<!--content goes here-->	
							<div class="navbar">
								<div class="navbar-inner-custom">
									NBA Self Assessment Report hi
								</div>
								<div>
									<font color= "red" ><?php echo validation_errors(); ?></font>
								</div>
							</div>
							<div id="nba_selection">
							<label for="nba_select_part_a" id="nba_select_part_a">PART A<input type="radio" name="nba_select_part" id="nba_select_part_a" /></label>
							<label for="nba_select_part_b" id="nba_select_part_b">PART B<input type="radio" name="nba_select_part" id="nba_select_part_b" /></label>
							</div>
							<!-- PART A Tab -->
							<div class="tabbable" id="part_a" style="display:none;"> <!-- Only required for left/right tabs -->
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">PART A</a></li>
								<li><a href="#tab2" data-toggle="tab">PART B</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1">
								  							  
								</div>
								<div class="tab-pane" id="tab2">
								</div>
							  </div>
							</div>
							<!-- PART A Tab Ends Here -->
							<!-- PART B Tab --> 
							<div class="tabbable" id="part_b" style="display:none;"> <!-- Only required for left/right tabs -->
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">PART A</a></li>
								<li><a href="#tab2" data-toggle="tab">PART B</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1">
								  							  
								</div>
								<div class="tab-pane" id="tab2">
								</div>
							  </div>
							</div>
							<!-- PART B Tab Ends Here-->
				</div>
				</div>				
				</section>
			</div>
		</div>
		<!---place footer.php here -->
		<?php  $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php  $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_type.js'); ?>" type="text/javascript"></script>