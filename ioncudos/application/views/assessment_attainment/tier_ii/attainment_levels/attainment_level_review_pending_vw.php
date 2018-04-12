<?php $this->load->view('includes/head'); ?>
<!--css for animated message display-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<style type="text/css">
	.large_modal{
	width:70%;
	margin-left: -35%; 
	}
</style>

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
				<div id="loading" class="ui-widget-overlay ui-front">
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
				<?php if((!$this->ion_auth->in_group('Course Owner')) || $this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){?>
				<input type="hidden" name="logged_in_user" id="logged_in_user" value="2" />
				<?php }else if($this->ion_auth->in_group('Course Owner')){ ?>
				<input type="hidden" name="logged_in_user" id="logged_in_user" value="1" />
				<?php } ?>
				
				<div class="bs-docs-example">
					<!--content goes here-->
					<div class="navbar">
						<div class="navbar-inner-custom">
							Attainment / Target Levels
						</div>
					</div>
					<div id="loading" class="ui-widget-overlay ui-front">
						<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
					</div>
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>">
					<table style="width:100%;">
						<tr>
							<th style="text-align:-webkit-right;">Curriculum: </th>
                                                        <th><font color="blue"><?php echo $meta_data['crclm_name']; ?></font></th>
							<th style="text-align:-webkit-right;">Term: </th>
                                                        <th><font color="blue"><?php echo $meta_data['term_name']; ?></font></th>
							<th style="text-align:-webkit-right;">Course: </th>
                                                        <th><font color="blue"><?php echo $meta_data['crs_title']; ?></font></th>
						</tr>
					</table>
					<div class="bs-example bs-example-tabs">
					<span id="status_msg"></span>
						<ul id="myTab" class="nav nav-tabs">
							<!--<li class="active"><a href="#bloom_level_tab" data-toggle="tab"> Program Outcome Attainment Level</a></li>-->
							<li id="crs_div_data" class="active"><a href="#course_tab" data-toggle="tab"> Attainment Targets for Individual Course</a></li>
						</ul>
						<div class="navbar">
							<div class="navbar-inner-custom">
								Direct Attainment Targets 
							</div>
						</div>
                                                <!-- select dropdownbox required to regenerate the table after updating the values -->
                                                <div id="select_dropdownbox">

                                                    <select name="course_data" id="course_data" class="course_data form-control" onchange="is_crclm_attainment_exists();" style="display:none;">
                                                        <option value="">Select Course</option>
                                                        <?php echo $select_box; ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="crclm_id" id="crclm_id" class="input-mini curriculum_name"  value="<?php echo $crclm_id; ?>"/>
                                                <input type="hidden" name="term_id" id="term_id" class="input-mini term_data"  value="<?php echo $term_id; ?>"/>
                                                <input type="hidden" name="crs_id" id="crs_id" class="input-mini course_data"  value="<?php echo $crs_id; ?>"/>
						<div class="generate_course_table_view" style="overflow:auto;">
							<?php 
								echo $target_details_data;
							?>
						</div>
					</div>
				</div>
				<br/>
			</div>
		</div><!--End of span10-->
	</div><!--row-fluid-->
</div><!--container-fluid-->


<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?>
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/tier_ii/attainment_level/attainment_level.js'); ?>" type="text/javascript"> </script>