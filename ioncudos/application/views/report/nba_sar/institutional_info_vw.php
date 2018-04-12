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
						<div class="bs-docs-example" style="padding-bottom:1008px;">
							<!--content goes here-->	
							<div class="navbar">
								<div class="navbar-inner-custom">
									Institutional Information
								</div>
								<div>
									<font color= "red" ><?php echo validation_errors(); ?></font>
								</div>
							</div><br>
							<div class="control-group">
							<label class="control-label" for="institute_info">Name and address of the institution and affiliating university:</label>
							<div class="controls"> 
								<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="Enter Details Here"></textarea>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="institute_info">Name, designation, telephone number, and e­mail address of the contact 
person for the NBA:</label>
							<div class="controls"> 
							<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="The name of the contact person, with other details, has to be 
listed here"></textarea>
							</div>
						</div>
							<div class="control-group">
							<div class="span7">
							<label class="control-label" for="institute_info">History of the institution (including the date of introduction and number of 
seats of various programmes of study alongwith the NBA accreditation, if any) in a tabular form:</label><div>
							<div class="controls"> 
							<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="History of the institution and its chronological development along 
with the past accreditation records need to be listed here 
listed here"></textarea>
							</div>
						</div><br>
							
				</div>
				</div>

							<div class="control-group">
							<div class="span7">
							<label class="control-label" for="institute_info">Ownership status: Govt. (central/state) / trust / society (Govt./NGO/private) / 
private/ other:</label><div>
							<div class="controls"> 
							<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="Ownership status of the institute has to be listed here."></textarea>
							</div>
						</div><br>
							
				</div>
				</div>	
								<div class="control-group">
							<div class="span7">
							<label class="control-label" for="institute_info">Mission of the Institution:</label><div>
							<div class="controls"> 
							<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="Enter Mission of the Institution."></textarea>
							</div>
						</div><br>
							
				</div>
				</div>	
					<div class="control-group">
							<div class="span7">
							<label class="control-label" for="institute_info">Vision of the Institution:</label><div>
							<div class="controls"> 
							<textarea id="institute_info" name="institute_info" rows="3" cols="20" style="margin: 0px 0px 10px; width: 614px; height: 66px;" placeholder="Enter vision of the Institution."></textarea>
							</div>
						</div><br>
							
				</div>
				</div>	
				</section>
			</div>
		</div>
		</div>
		</div>
		<!---place footer.php here -->
		<?php  $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php  $this->load->view('includes/js'); ?>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_type.js'); ?>" type="text/javascript"></script>