<?php
/**
* Description	:	Add View for Program Type Module.
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.
* 27-08-2013		Abhinay B.Angadi		Variable naming, Function naming & 
*											Code cleaning.
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
									NBA Self Assessment Report
								</div>
								<div>
									<font color= "red" ><?php echo validation_errors(); ?></font>
								</div>
							</div><br><br><br>
							<div class="tabbable"> <!-- Only required for left/right tabs -->
							  <ul class="nav nav-tabs">
								<li class="active"><a href="#tab1" data-toggle="tab">PART A</a></li>
								<li><a href="#tab2" data-toggle="tab">PART B</a></li>
							  </ul>
							  <div class="tab-content">
								<div class="tab-pane active" id="tab1">
								  <p><a href="<?php echo base_url('report/nba_sar/institute_info');?>"><b><i class="icon-play"></i>&nbsp;&nbsp;Institutional Information</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Departmental Information</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Programme Specific Information</b></a></p>
								  							  
								</div>
								<div class="tab-pane" id="tab2">
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Vision, Mission and Programme Educational Objectives</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Programme Outcomes</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Programme Curriculum</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Students’ Performance</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Faculty Contributions</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Facilities and Technical Support</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Academic Support Units and Teaching-Learning Process</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Governance, Institutional Support and Financial Resources</b></a></p>
								  <p><a href><b><i class="icon-play"></i>&nbsp;&nbsp;Continuous Improvement</b></a></p>
								</div>
							  </div>
							</div>
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