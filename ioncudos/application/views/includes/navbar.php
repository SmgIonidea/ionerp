	<?php
/**
 * Description	:	Generic Navbar View file of the application contains Main Menu Tabs 
 * 					(main navbar tabs like Dashboard, Curriculum, Configuration, Reports ).
 * Created		:	25-03-2013.
 * Modification History:
 * 	Date				Modified By				Description
 * 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.
 * 17-05-2016		Arihant Prasad			CSS changes, indentation, tooltip
  --------------------------------------------------------------------------------------------- */
$val = $this->ion_auth->user()->row();
$org_name = $val->org_name->org_name;
$org_type = $val->org_name->org_type;
$oe_pi_flag = $val->org_name->oe_pi_flag;
$theory_iso_code = $val->org_name->theory_iso_code;

$dat_log_in = date_create($val->login_time);
$login_date_formated = date_format($dat_log_in, "dS-F-Y h:i:sA");

$mte_flag = $val->org_name->mte_flag;

$methodName=$this->router->fetch_method();
$stakeholderG=array('index','add_stakeholder_group_type','edit_stakeholder_group_type');
$className = $this->router->fetch_class();

?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<script src="<?php echo base_url('twitterbootstrap/js/jquery.js'); ?>"></script>

<?php
$class_name = $this->router->fetch_class();
$class_name_folder = $this->uri->segment(1);
$class_name_one = $this->uri->segment(3);
$tab = $class_name . '/' . $class_name_one;
?>
<style>
#verticle_line_div li { position:relative;  top:-5px; }
</style>
<!-- Navbar -->
<div class="navbar" id="main_navbar">
    <div class="navbar-inner-one" id="second_navbar">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse" style="margin-top: 5px;">
                <div class="span11 pull-left" style="margin:-3px;" id="verticle_line_div">
                    <ul class="nav navbar-hover">
					 <li title="Toggle Side Menu"  class="cursor_pointer">
                            <a class="toggle_side_menu show" title="Toggle Side Menu" > <img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/toggle.png'); ?>" align="middle" border="0">
                            </a>
                        </li>
                        <li title="Home" <?php if ($tab == 'login/' || $tab == 'home/') echo 'class="active dropdown"'; ?> class="">
                            <a class="navbar_select_box" title="Home" href="<?php echo base_url('home') ?>"><p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/home_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Home</p>
                            </a>
							
                        </li>

                        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('BOS')) { ?>
                            <li title="Dashboard" <?php if ($tab == 'dashboard/' && $class_name_folder == 'dashboard') echo 'class="active"'; ?>>
                                <a class="navbar_select_box" title="Dashboard" href="<?php echo base_url('dashboard/dashboard') ?>">
                                    <p align="center">	
                                        <img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/dashboard_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Dashboard
                                    </p>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($this->ion_auth->in_group('admin')) { ?>
                            <li title="Configuration" id="config"<?php if ($tab == 'organisation/') echo 'class="active dropdown_menu "'; ?> class="dropdown_menu " >
                                <a class="navbar_select_box" title="Configuration" href="<?php echo base_url('configuration/organisation') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/config_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Configuration</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;">
								<?php if ($this->ion_auth->in_group('admin')) : ?>
									<li abbr="config" <?php if ($class_name == 'organisation') echo 'class="active"'; ?>  id="config_org" ><a href="<?php echo base_url('configuration/organisation') ?>"><i class="icon-chevron-right"></i> Organization </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'department' || $class_name == 'add_department') echo 'class="active"'; ?>id="config_dept"><a href="<?php echo base_url('configuration/department') ?>"><i class="icon-chevron-right"></i> Department </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'programtype') echo 'class="active"'; ?> id="config_pgmtype"><a href="<?php echo base_url('configuration/programtype') ?>"><i class="icon-chevron-right"></i> Program Type </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'program_mode') echo 'class="active"'; ?> id="config_pgmmode" ><a href="<?php echo base_url('configuration/program_mode') ?>"><i class="icon-chevron-right"></i> Program Mode </a></li>
									<li class="divider"></li>
									<!--<li abbr="config" <?php //if($class_name=='unit') echo 'class="active"'; ?> id="config_unit"><a href="<?php //echo base_url('configuration/unit')  ?>"><i class="icon-chevron-right"></i> Duration </a></li>-->
									<!--<li abbr="config" <?php //if($class_name=='topic_unit') echo 'class="active"'; ?> id="config_t_unit" ><a href="<?php //echo base_url('configuration/topic_unit')  ?>"><i class="icon-chevron-right"></i> Topic Unit </a></li>-->
									<li abbr="config" <?php if ($class_name == 'program') echo 'class="active"'; ?> id="config_pgm"><a href="<?php echo base_url('configuration/program') ?>"><i class="icon-chevron-right"></i> Program </a></li>
									<li class="divider"></li>
									<!-- <li abbr="config" <?php // if($class_name=='user_designation') echo 'class="active"'; ?> id="config_dsgn" ><a href="<?php // echo base_url('configuration/user_designation')  ?>"><i class="icon-chevron-right"></i> Designation </a></li> -->
									<li abbr="config" <?php if ($class_name == 'users') echo 'class="active"'; ?>id="config_user" ><a href="<?php echo base_url('configuration/users/list_users') ?>"><i class="icon-chevron-right"></i> Users </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'bos' || $class_name == 'addbos') echo 'class="active"'; ?> id="config_bos" ><a href="<?php echo base_url('configuration/bos') ?>"><i class="icon-chevron-right"></i> BoS Members </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'graduate_attributes') echo 'class="active"'; ?> id="config_ga" ><a href="<?php echo base_url('configuration/graduate_attributes') ?>"><i class="icon-chevron-right"></i> Manage GAs</a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'po_type') echo 'class="active"'; ?> id="config_accreditationtype" ><a href="<?php echo base_url('configuration/po_type') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Type </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'accreditation_type') echo 'class="active"'; ?> id="config_accreditationtype" ><a href="<?php echo base_url('configuration/accreditation_type') ?>"><i class="icon-chevron-right"></i>Generic <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></a></li>
									<li class="divider"></li>
									<!-- <li abbr="config" <?php // if ($class_name == 'curriculum_component') echo 'class="active"'; ?> id="config_crscomp"><a href="<?php // echo base_url('configuration/curriculum_component') ?>"><i class="icon-chevron-right"></i> Curriculum Component</a></li> -->
									<li abbr="config" <?php if ($class_name == 'course_type') echo 'class="active"'; ?> id="config_crstype"><a href="<?php echo base_url('configuration/course_type') ?>"><i class="icon-chevron-right"></i> Course Type </a></li>
									<li class="divider"></li>
									<!-- <li abbr="config" <?php // if ($class_name == 'ao_method') echo 'class="active"'; ?> id="config_crstype"><a href="<?php // echo base_url('configuration/ao_method') ?>"><i class="icon-chevron-right"></i> Assessment Method </a></li> -->
									<li abbr="config" <?php if ($class_name == 'delivery_method') echo 'class="active"'; ?> id="config_crstype"><a href="<?php echo base_url('configuration/delivery_method') ?>"><i class="icon-chevron-right"></i> Delivery Method </a></li> 
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'lab_category') echo 'class="active"'; ?> id="config_lab_category" ><a href="<?php echo base_url('configuration/lab_category') ?>"><i class="icon-chevron-right"></i> Lab Category </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'bloom_domain') echo 'class="active"'; ?> id="config_bloom_domain" ><a href="<?php echo base_url('configuration/bloom_domain') ?>"><i class="icon-chevron-right"></i> Bloom's Domain </a></li> 
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'bloom_level') echo 'class="active"'; ?> id="config_bloom" ><a href="<?php echo base_url('configuration/bloom_level') ?>"><i class="icon-chevron-right"></i> Bloom's Level </a></li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($class_name == 'map_level_weightage') echo 'class="active"'; ?> id="config_map_level_weightage"><a href="<?php echo base_url('configuration/map_level_weightage') ?>"><i class="icon-chevron-right"></i> Map Level Weightage </a></li>
									<li class="divider"></li>
									<!--<li abbr="config" <?php // if($class_name=='user_groups') echo 'class="active"'; ?> id="config_grp" ><a href="<?php // echo base_url('configuration/user_groups')  ?>"><i class="icon-chevron-right"></i> Groups (Roles) </a></li> -->
									<li abbr="config" <?php if ($class_name == 'help_content') echo 'class="active"'; ?> id="config_help" ><a href="<?php echo base_url('configuration/help_content') ?>"><i class="icon-chevron-right"></i> Content Specific Guidelines </a></li>
									<li class="divider"></li>
									<!-- <li abbr="config" <?php // if ($class_name == 'adequacy_report') echo 'class="active"'; ?> id="config_adequacy" ><a href="<?php // echo base_url('configuration/adequacy_report') ?>"><i class="icon-chevron-right"></i> Adequacy Report </a></li> -->
									<!-- <li abbr="config" <?php // if ($class_name == 'bulk_email') echo 'class="active"'; ?> id="config_bulk_email" ><a href="<?php // echo base_url('configuration/bulk_email') ?>"><i class="icon-chevron-right"></i> Send Bulk Emails </a></li> -->
									<?php endif ?>
							</ul>
                            </li>
                        <?php } ?>

                        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman')) { ?>
                            <li title="Curriculum" id="tab_crclm" <?php if ($tab == 'curriculum/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Curriculum" href="<?php echo base_url('curriculum/curriculum') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/design_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Curriculum</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >		
									<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ) { ?>
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>
										<li id="config_dept_mission" abbr="tab_crclm" <?php if($class_name=='dept_mission_vision') echo 'class="active"'; ?>  ><a href="<?php echo base_url('configuration/dept_mission_vision') ?>"><i class="icon-chevron-right"></i> Department Vision/Mission  </a></li>
										<li class="divider"></li>
										<li id="crclm_crclm" abbr="tab_crclm" <?php if($class_name=='curriculum') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum') ?>"><i class="icon-chevron-right"></i> Curriculum (Regulation)</a></li>
										<li class="divider"></li>
										<li id="crclm_peo" abbr="tab_crclm" <?php if($class_name=='peo') echo 'class="active"'; ?>><a href="<?php echo base_url('curriculum/peo') ?>"><i class="icon-chevron-right"></i> Program Educational Objectives (PEOs) </a></li>
										<li class="divider"></li>
										<li id="crclm_po" abbr="tab_crclm" <?php if($class_name=='po') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/po') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_po_ga" abbr="tab_crclm" <?php if($class_name=='map_po_ga') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_ga') ?>"><i class="icon-chevron-right"></i> GA to <?php echo $this->lang->line('so'); ?> Mapping </a></li>
										<li class="divider"></li>
										<li id="crclm_po_peo_map" abbr="tab_crclm" <?php if($class_name=='map_po_peo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_peo') ?>"><i class="icon-chevron-right"></i>  <?php echo $this->lang->line('so'); ?> to PEO Mapping </a></li>
										<li class="divider"></li>

									<?php } ?>
									
												
									<?php if($this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('admin')){ ?> 
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i>  Manage Users </a></li>
										<li class="divider"></li>										
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>

										<li id="crclm_setting" abbr="tab_crclm" <?php if($class_name=='setting' || $class_name=='import_user' ||  $class_name=='curriculum_delivery_method' || $class_name=='course_domain' || $class_name=='assessment_method' || $class_name=='student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/setting') ?>"><i class="icon-chevron-right"></i> Curriculum Settings </a></li>
										<li class="divider"></li>
										<!-- OE & PI Reports -->
										<?php if( $oe_pi_flag == 1 ) { ?>
											<li id="crclm_pi" abbr="tab_crclm" <?php if($class_name=='pi_and_measures') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/pi_and_measures') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator (PI) </a></li>
											<li class="divider"></li>
										<?php  } ?>			
										<li id="crclm_crs" abbr="tab_crclm" <?php if($class_name=='course') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/course') ?>"><i class="icon-chevron-right"></i> Course </a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to <?php echo $this->lang->line('so'); ?> Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic' || $class_name=='tlo_new_edit') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if( !($this->ion_auth->in_group('Program Owner')) && $this->ion_auth->in_group('Course Owner') ) { ?>	
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to PO Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>	
								</ul>
                            </li>
                        <?php } ?>

                        <?php if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && $this->ion_auth->in_group('Program Owner')) { ?>
                            <!-- OE & PI If Mandatory : Side menu will be visible (1 means mandatory) -->
                            <?php if ($oe_pi_flag == 1) { ?>
                                <li title="Curriculum" id="tab_crclm" <?php if ($tab == 'curriculum/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                    <a class="navbar_select_box" title="Curriculum" href="<?php echo base_url('curriculum/pi_and_measures') ?>">
                                        <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/design_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Curriculum</p>
                                    </a>
									<ul class="ul_dropdown" style="display:none;" >		
									<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ) { ?>
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>
										<li id="config_dept_mission" abbr="tab_crclm" <?php if($class_name=='dept_mission_vision') echo 'class="active"'; ?>  ><a href="<?php echo base_url('configuration/dept_mission_vision') ?>"><i class="icon-chevron-right"></i> Department Vision/Mission  </a></li>
										<li class="divider"></li>
										<li id="crclm_crclm" abbr="tab_crclm" <?php if($class_name=='curriculum') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum') ?>"><i class="icon-chevron-right"></i> Curriculum (Regulation)</a></li>
										<li class="divider"></li>
										<li id="crclm_peo" abbr="tab_crclm" <?php if($class_name=='peo') echo 'class="active"'; ?>><a href="<?php echo base_url('curriculum/peo') ?>"><i class="icon-chevron-right"></i> Program Educational Objectives (PEOs) </a></li>
										<li class="divider"></li>
										<li id="crclm_po" abbr="tab_crclm" <?php if($class_name=='po') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/po') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_po_ga" abbr="tab_crclm" <?php if($class_name=='map_po_ga') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_ga') ?>"><i class="icon-chevron-right"></i> GA to <?php echo $this->lang->line('so'); ?> Mapping </a></li>
										<li class="divider"></li>
										<li id="crclm_po_peo_map" abbr="tab_crclm" <?php if($class_name=='map_po_peo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_peo') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> to PEO Mapping </a></li>
										<li class="divider"></li>

									<?php } ?>
									
												
									<?php if($this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('admin')){ ?> 
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>										
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>

										<li id="crclm_setting" abbr="tab_crclm" <?php if($class_name=='setting' || $class_name=='import_user' ||  $class_name=='curriculum_delivery_method' || $class_name=='course_domain' || $class_name=='assessment_method' || $class_name=='student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/setting') ?>"><i class="icon-chevron-right"></i> Curriculum Settings </a></li>
										<li class="divider"></li>
										<!-- OE & PI Reports -->
										<?php if( $oe_pi_flag == 1 ) { ?>
											<li id="crclm_pi" abbr="tab_crclm" <?php if($class_name=='pi_and_measures') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/pi_and_measures') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator (PI) </a></li>
											<li class="divider"></li>
										<?php  } ?>			
										<li id="crclm_crs" abbr="tab_crclm" <?php if($class_name=='course') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/course') ?>"><i class="icon-chevron-right"></i> Course </a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to <?php echo $this->lang->line('so'); ?> Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic' || $class_name=='tlo_new_edit') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if( !($this->ion_auth->in_group('Program Owner')) && $this->ion_auth->in_group('Course Owner') ) { ?>	
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to PO Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>	
								</ul>
                                </li>
                            <?php } else { ?>
                                <li title="Curriculum" id="tab_crclm" <?php if ($tab == 'curriculum/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                    <a class="navbar_select_box" title="Curriculum" href="<?php echo base_url('curriculum/course') ?>">
                                        <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/design_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Curriculum</p>
                                    </a>
									<ul class="ul_dropdown" style="display:none;" >		
									<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ) { ?>
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>
										<li id="config_dept_mission" abbr="tab_crclm" <?php if($class_name=='dept_mission_vision') echo 'class="active"'; ?>  ><a href="<?php echo base_url('configuration/dept_mission_vision') ?>"><i class="icon-chevron-right"></i> Department Vision/Mission  </a></li>
										<li class="divider"></li>
										<li id="crclm_crclm" abbr="tab_crclm" <?php if($class_name=='curriculum') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum') ?>"><i class="icon-chevron-right"></i> Curriculum (Regulation)</a></li>
										<li class="divider"></li>
										<li id="crclm_peo" abbr="tab_crclm" <?php if($class_name=='peo') echo 'class="active"'; ?>><a href="<?php echo base_url('curriculum/peo') ?>"><i class="icon-chevron-right"></i> Program Educational Objectives (PEOs) </a></li>
										<li class="divider"></li>
										<li id="crclm_po" abbr="tab_crclm" <?php if($class_name=='po') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/po') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_po_ga" abbr="tab_crclm" <?php if($class_name=='map_po_ga') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_ga') ?>"><i class="icon-chevron-right"></i> GA to <?php echo $this->lang->line('so'); ?> Mapping </a></li>
										<li class="divider"></li>
										<li id="crclm_po_peo_map" abbr="tab_crclm" <?php if($class_name=='map_po_peo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_peo') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> to PEO Mapping </a></li>
										<li class="divider"></li>

									<?php } ?>
									
												
									<?php if($this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('admin')){ ?> 
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>	
										<li class="divider"></li>
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>

										<li id="crclm_setting" abbr="tab_crclm" <?php if($class_name=='setting' || $class_name=='import_user' ||  $class_name=='curriculum_delivery_method' || $class_name=='course_domain' || $class_name=='assessment_method' || $class_name=='student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/setting') ?>"><i class="icon-chevron-right"></i> Curriculum Settings </a></li>
										<li class="divider"></li>
										<!-- OE & PI Reports -->
										<?php if( $oe_pi_flag == 1 ) { ?>
											<li id="crclm_pi" abbr="tab_crclm" <?php if($class_name=='pi_and_measures') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/pi_and_measures') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator (PI) </a></li>
											<li class="divider"></li>
										<?php  } ?>			
										<li id="crclm_crs" abbr="tab_crclm" <?php if($class_name=='course') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/course') ?>"><i class="icon-chevron-right"></i> Course </a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to <?php echo $this->lang->line('so'); ?> Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic' || $class_name=='tlo_new_edit') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if( !($this->ion_auth->in_group('Program Owner')) && $this->ion_auth->in_group('Course Owner') ) { ?>	
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to PO Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>	
								</ul>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <?php if (!$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner') && $this->ion_auth->in_group('Course Owner')) { ?>
                            <li title="Curriculum" id="tab_crclm" <?php if ($tab == 'curriculum/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Curriculum" href="<?php echo base_url('curriculum/clo') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/design_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Curriculum</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >		
									<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ) { ?>
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>
										<li id="config_dept_mission" abbr="tab_crclm" <?php if($class_name=='dept_mission_vision') echo 'class="active"'; ?>  ><a href="<?php echo base_url('configuration/dept_mission_vision') ?>"><i class="icon-chevron-right"></i> Department Vision/Mission  </a></li>
										<li class="divider"></li>
										<li id="crclm_crclm" abbr="tab_crclm" <?php if($class_name=='curriculum') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum') ?>"><i class="icon-chevron-right"></i> Curriculum (Regulation)</a></li>
										<li class="divider"></li>
										<li id="crclm_peo" abbr="tab_crclm" <?php if($class_name=='peo') echo 'class="active"'; ?>><a href="<?php echo base_url('curriculum/peo') ?>"><i class="icon-chevron-right"></i> Program Educational Objectives (PEOs) </a></li>
										<li class="divider"></li>
										<li id="crclm_po" abbr="tab_crclm" <?php if($class_name=='po') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/po') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_po_ga" abbr="tab_crclm" <?php if($class_name=='map_po_ga') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_ga') ?>"><i class="icon-chevron-right"></i> GA to <?php echo $this->lang->line('so'); ?> Mapping </a></li>
										<li class="divider"></li>
										<li id="crclm_po_peo_map" abbr="tab_crclm" <?php if($class_name=='map_po_peo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_peo') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> to PEO Mapping </a></li>
										<li class="divider"></li>

									<?php } ?>
									
												
									<?php if($this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('admin')){ ?> 
										<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
										<li class="divider"></li>										
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>

										<li id="crclm_setting" abbr="tab_crclm" <?php if($class_name=='setting' || $class_name=='import_user' ||  $class_name=='curriculum_delivery_method' || $class_name=='course_domain' || $class_name=='assessment_method' || $class_name=='student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/setting') ?>"><i class="icon-chevron-right"></i> Curriculum Settings </a></li>
										<li class="divider"></li>
										<!-- OE & PI Reports -->
										<?php if( $oe_pi_flag == 1 ) { ?>
											<li id="crclm_pi" abbr="tab_crclm" <?php if($class_name=='pi_and_measures') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/pi_and_measures') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator (PI) </a></li>
											<li class="divider"></li>
										<?php  } ?>			
										<li id="crclm_crs" abbr="tab_crclm" <?php if($class_name=='course') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/course') ?>"><i class="icon-chevron-right"></i> Course </a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to <?php echo $this->lang->line('so'); ?> Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic' || $class_name=='tlo_new_edit') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>
									
									<?php if( !($this->ion_auth->in_group('Program Owner')) && $this->ion_auth->in_group('Course Owner') ) { ?>	
										<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
										<li class="divider"></li>
										<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to PO Mapping (Course wise) </a></li>
										<li class="divider"></li>
										<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
										<li class="divider"></li>
										<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i> Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
										<li class="divider"></li>
									<?php } ?>	
								</ul>
                            </li>
                        <?php } ?>	

                        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner') || $this->ion_auth->in_group('BoS')) { ?>
                            <li title="Reports" id="report" <?php if ($tab == 'curriculum_details/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Reports" href="<?php echo base_url('curriculum/curriculum_details/curriculum_details') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/report_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Reports</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >	
									<?php if (!$this->ion_auth->in_group('Student')) : ?>

										<!-- <li id="pgm_articulation_report" abbr="report" <?php // if ($class_name == 'pgm_articulation_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/pgm_articulation_report')  ?>"><i class="icon-chevron-right"></i> New Program Articulation Matrix Report </a></li> <li class="divider"></li> -->

										<li id="report_faculty_history" abbr="report" <?php  if ($class_name == 'faculty_history') echo 'class="active"'; ?> ><a href="<?php  echo base_url('report/faculty_history') ?>"><i class="icon-chevron-right"></i> Faculty Profile </a></li> 
										<li class="divider"></li>
										<li id="curriculum_details" abbr="report" <?php if ($class_name == 'curriculum_details') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum_details/curriculum_details') ?>"><i class="icon-chevron-right"></i> Department / Curriculum Report </a></li>
										<li class="divider"></li>
										<li id="mapping_report" abbr="report" <?php if ($class_name == 'mapping_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/mapping_report') ?>"><i class="icon-chevron-right"></i> Mapping Report</a></li>
										<li class="divider"></li>

								<!-- <li id="report_po_peo" abbr="report" <?php // if ($class_name == 'po_peo_map_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/po_peo_map_report')  ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('so');  ?> to PEO Mapped Report </a></li> -->

										<li id="report_pgm_art" abbr="report" <?php if ($class_name == 'program_articulation_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/program_articulation_matrix') ?>"><i class="icon-chevron-right"></i> Program Articulation Matrix Report (Term-wise) </a></li> 
										<li class="divider"></li>
										<li id="report_pgm_art" abbr="report" <?php if ($class_name == 'co_po_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/co_po_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - PO Matrix </a></li>
										<li class="divider"></li>
										<li id="report_trn_pgm_art" abbr="report" <?php if ($class_name == 'transpose_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/transpose_report') ?>"><i class="icon-chevron-right"></i> Transpose of Program Articulation Matrix</a></li>
										<li class="divider"></li>
										<li id="report_crs_strm" abbr="report" <?php if ($class_name == 'crs_domain_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/crs_domain_report') ?>"><i class="icon-chevron-right"></i> Course Domain (Vertical) Report </a></li>
										<li class="divider"></li>
										<li id="course_report" abbr="report" <?php if ($class_name == 'course_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_report') ?>"><i class="icon-chevron-right"></i> Term-wise Course Report </a></li>
										<li class="divider"></li>
									<?php endif ?>
									<li id="report_course_delivery" abbr="report" <?php if ($class_name == 'course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_delivery_report') ?>"><i class="icon-chevron-right"></i><b><font color="maroon"> Theory Lesson Plan (Course-wise) Report </font></b></a></li>
									<li class="divider"></li>
									<li id="lab_report_course_delivery" abbr="report" <?php if ($class_name == 'lab_course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/lab_course_delivery_report') ?>"><i class="icon-chevron-right"></i> Lab Lesson Plan (Course-wise) Report </a></li>
									<li class="divider"></li>
									<li id="text_reference_book_list" abbr="report" <?php if ($class_name == 'text_reference_book_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/text_reference_book_list') ?>"><i class="icon-chevron-right"></i> Text / Reference Book List </a></li>
									<li class="divider"></li>
									<?php if (!$this->ion_auth->in_group('Student')) : ?>

														<!-- <li  id="report_lesson" abbr="report" <?php // if($class_name=='lesson_plan') echo 'class="active"';   ?> ><a href="<?php //  echo base_url('report/lesson_plan')    ?>"><i class="icon-chevron-right"></i> Partwise Lesson Plan </a></li> -->

											<!-- <li id="report_clo_po" abbr="report" <?php // if($class_name=='clo_po_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/clo_po_map_report')    ?>"><i class="icon-chevron-right"></i> CO to <?php // echo $this->lang->line('so');    ?> Mapped Report (Course wise) </a></li> -->

								<!-- <li id="report_tlo_clo" abbr="report" <?php // if($class_name=='tlo_clo_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/tlo_clo_map_report')    ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('entity_tlo');    ?> to CO Mapped Report (<?php // echo $this->lang->line('entity_topic_singular');    ?> wise) </a></li> -->
								<?php if($mte_flag == 1){ $mte = $this->lang->line('entity_mte').' , '; }else{ $mte = '';}?>
										<!--<li  id="course_clo_attainment" abbr="analysis" <?php if ($class_name == 'course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/course_clo_attainment/') ?>"><i class="icon-chevron-right"></i>Course - CO Attainment (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>-->
										<li id="internal_final_exam" abbr="report" <?php if ($class_name == 'internal_final_exam') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/internal_final_exam') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?> Question Paper Report Course-wise</a></li>
										<li class="divider"></li>
										<li id="report_ao_clo" abbr="report" <?php if ($class_name == 'ao_clo_map_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/ao_clo_map_report') ?>"><i class="icon-chevron-right"></i> AO to CO Mapping Report Course-wise </a></li>
										<li class="divider"></li>
										<li id="report_un_po" abbr="report" <?php if ($class_name == 'unmapped_po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_po_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('so'); ?> Report </a></li>
										<li class="divider"></li>

										<!-- OE & PI If Mandatory : Reports will be visible (1 means mandatory) -->
										<?php if ($oe_pi_flag == 1) { ?>
											<li id="report_po" abbr="report" <?php if ($class_name == 'po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/po_report') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('sos') . ' , ' . $this->lang->line('outcome_element_plu_full') . ' & ' . $this->lang->line('measures_short'); ?> Report</a></li>
											<li class="divider"></li>
											<li id="report_un_pi" abbr="report" <?php if ($class_name == 'unmapped_pi_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_pi_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('outcome_element_sing_full'); ?> Report </a></li>
											<li class="divider"></li>
											<li id="report_un_msr" abbr="report" <?php if ($class_name == 'unmapped_msr_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_msr_report') ?>"><i class="icon-chevron-right"></i> Unmapped Performance Indicator (PI) Report </a></li>
											<li class="divider"></li>
										<?php } ?>
								<!-- <li id="report_edit_profile" abbr="report" <?php // if($class_name=='edit_profile')echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/edit_profile/index/');    ?>"><i class="icon-chevron-right"></i> Edit Profile </a></li> -->

										<li id="improvement_plan" abbr="report" <?php if ($class_name == 'improvement_plan') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/improvement_plan') ?>"><i class="icon-chevron-right"></i> Student Improvement Plan Report </a></li>
										<li class="divider"></li>

								<!-- <li id="faculty_contri" abbr="faculty_contribution" <?php // if($class_name=='faculty_contribution') echo 'class="active"';   ?> ><a href="<?php // echo base_url('faculty_contribution/faculty_contributions')    ?>"><i class="icon-chevron-right"></i> Faculty Contribution </a></li> -->

									<?php endif ?>
								</ul>
                            </li>
                        <?php } else if ($this->ion_auth->in_group('Student')) { ?>
                            <li title="Reports" id="report" <?php if ($tab == 'report/course_delivery_report') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Reports" href="<?php echo base_url('report/course_delivery_report') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/report_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Reports</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >	
									<?php if (!$this->ion_auth->in_group('Student')) : ?>

										<!-- <li id="pgm_articulation_report" abbr="report" <?php // if ($class_name == 'pgm_articulation_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/pgm_articulation_report')  ?>"><i class="icon-chevron-right"></i> New Program Articulation Matrix Report </a></li> -->

										<li id="report_faculty_history" abbr="report" <?php  if ($class_name == 'faculty_history') echo 'class="active"'; ?> ><a href="<?php  echo base_url('report/faculty_history') ?>"><i class="icon-chevron-right"></i> Faculty Profile </a></li> 
										<li class="divider"></li>
										<li id="curriculum_details" abbr="report" <?php if ($class_name == 'curriculum_details') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum_details/curriculum_details') ?>"><i class="icon-chevron-right"></i> Department / Curriculum Report </a></li>
										<li class="divider"></li>
										<li id="mapping_report" abbr="report" <?php if ($class_name == 'mapping_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/mapping_report') ?>"><i class="icon-chevron-right"></i> Mapping Report</a></li>
										<li class="divider"></li>

								<!-- <li id="report_po_peo" abbr="report" <?php // if ($class_name == 'po_peo_map_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/po_peo_map_report')  ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('so');  ?> to PEO Mapped Report </a></li> -->

										<li id="report_pgm_art" abbr="report" <?php if ($class_name == 'program_articulation_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/program_articulation_matrix') ?>"><i class="icon-chevron-right"></i> Program Articulation Matrix Report (Term-wise) </a></li> 
										<li id="report_pgm_art" abbr="report" <?php if ($class_name == 'co_po_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/co_po_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - PO Matrix </a></li>
										<li class="divider"></li>
										<li id="report_trn_pgm_art" abbr="report" <?php if ($class_name == 'transpose_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/transpose_report') ?>"><i class="icon-chevron-right"></i> Transpose of Program Articulation Matrix</a></li>
										<li class="divider"></li>
										<li id="report_crs_strm" abbr="report" <?php if ($class_name == 'crs_domain_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/crs_domain_report') ?>"><i class="icon-chevron-right"></i> Course Domain (Vertical) Report </a></li>
										<li class="divider"></li>
										<li id="course_report" abbr="report" <?php if ($class_name == 'course_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_report') ?>"><i class="icon-chevron-right"></i> Term-wise Course Report </a></li>
										<li class="divider"></li>
									<?php endif ?>
									<li id="report_course_delivery" abbr="report" <?php if ($class_name == 'course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_delivery_report') ?>"><i class="icon-chevron-right"></i><b><font color="maroon"> Theory Lesson Plan (Course-wise) Report </font></b></a></li>
									<li class="divider"></li>
									<li id="lab_report_course_delivery" abbr="report" <?php if ($class_name == 'lab_course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/lab_course_delivery_report') ?>"><i class="icon-chevron-right"></i> Lab Lesson Plan (Course-wise) Report </a></li>
									<li class="divider"></li>
									<li id="text_reference_book_list" abbr="report" <?php if ($class_name == 'text_reference_book_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/text_reference_book_list') ?>"><i class="icon-chevron-right"></i> Text / Reference Book List </a></li>
									<li class="divider"></li>
									<?php if (!$this->ion_auth->in_group('Student')) : ?>

														<!-- <li  id="report_lesson" abbr="report" <?php // if($class_name=='lesson_plan') echo 'class="active"';   ?> ><a href="<?php //  echo base_url('report/lesson_plan')    ?>"><i class="icon-chevron-right"></i> Partwise Lesson Plan </a></li> -->

											<!-- <li id="report_clo_po" abbr="report" <?php // if($class_name=='clo_po_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/clo_po_map_report')    ?>"><i class="icon-chevron-right"></i> CO to <?php // echo $this->lang->line('so');    ?> Mapped Report (Course wise) </a></li> -->

								<!-- <li id="report_tlo_clo" abbr="report" <?php // if($class_name=='tlo_clo_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/tlo_clo_map_report')    ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('entity_tlo');    ?> to CO Mapped Report (<?php // echo $this->lang->line('entity_topic_singular');    ?> wise) </a></li> -->
								<?php if($mte_flag == 1){ $mte = $this->lang->line('entity_mte').' , '; }else{ $mte = '';}?>
										<!--<li  id="course_clo_attainment" abbr="analysis" <?php if ($class_name == 'course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/course_clo_attainment/') ?>"><i class="icon-chevron-right"></i>Course - CO Attainment (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>-->
										<li id="internal_final_exam" abbr="report" <?php if ($class_name == 'internal_final_exam') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/internal_final_exam') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?> Question Paper Report Course-wise</a></li>
										<li class="divider"></li>
										
										
										<li id="report_ao_clo" abbr="report" <?php if ($class_name == 'ao_clo_map_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/ao_clo_map_report') ?>"><i class="icon-chevron-right"></i> AO to CO Mapping Report Course-wise </a></li>
										<li class="divider"></li>
										<li id="report_un_po" abbr="report" <?php if ($class_name == 'unmapped_po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_po_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('so'); ?> Report </a></li>
										<li class="divider"></li>

										<!-- OE & PI If Mandatory : Reports will be visible (1 means mandatory) -->
										<?php if ($oe_pi_flag == 1) { ?>
											<li id="report_po" abbr="report" <?php if ($class_name == 'po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/po_report') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('sos') . ' , ' . $this->lang->line('outcome_element_plu_full') . ' & ' . $this->lang->line('measures_short'); ?> Report</a></li>
											<li class="divider"></li>
											<li id="report_un_pi" abbr="report" <?php if ($class_name == 'unmapped_pi_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_pi_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('outcome_element_sing_full'); ?> Report </a></li>
											<li class="divider"></li>
											<li id="report_un_msr" abbr="report" <?php if ($class_name == 'unmapped_msr_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_msr_report') ?>"><i class="icon-chevron-right"></i> Unmapped Performance Indicator (PI) Report </a></li>
											<li class="divider"></li>
										<?php } ?>
								<!-- <li id="report_edit_profile" abbr="report" <?php // if($class_name=='edit_profile')echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/edit_profile/index/');    ?>"><i class="icon-chevron-right"></i> Edit Profile </a></li> -->

										<li id="improvement_plan" abbr="report" <?php if ($class_name == 'improvement_plan') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/improvement_plan') ?>"><i class="icon-chevron-right"></i> Student Improvement Plan Report </a></li>
										<li class="divider"></li>

								<!-- <li id="faculty_contri" abbr="faculty_contribution" <?php // if($class_name=='faculty_contribution') echo 'class="active"';   ?> ><a href="<?php // echo base_url('faculty_contribution/faculty_contributions')    ?>"><i class="icon-chevron-right"></i> Faculty Contribution </a></li> -->

									<?php endif ?>
								</ul>
                            </li>
                        <?php } ?>

                        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) { ?>
                            <li title="Assessment Planning" id="qp"<?php if ($tab == 'cia/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Assessment Planning" href="<?php echo base_url('assessment_attainment/cia'); ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/assessment_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Assessment</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >
									<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || 
												$this->ion_auth->in_group('Program Owner'))  { ?>
										<li id="crclm_ext" abbr="qp" <?php if($class_name=='Extra_curricular_activities') echo 'class="active"'; ?>  ><a href="<?php echo base_url('Extra_curricular_activities/Extra_curricular_activities/index') ?>"><i class="icon-chevron-right"></i>Extracurricular / Co-curricular Activity</a></li>
										<li class="divider"></li>
										<li id="qp_framework" abbr="qp" <?php if($class_name=='manage_qp_framework') echo 'class="active"';?> ><a href="<?php echo 	base_url('question_paper/manage_qp_framework') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Framework (Program-wise)</a></li>
										<li class="divider"></li>
									<?php } ?>
								<li  id="manage_cia" abbr="qp" <?php if($class_name=='cia') echo 'class="active"';?> ><a href="<?php echo base_url('assessment_attainment/cia/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_cie'); ?> Occasion</a></li>
								<li class="divider"></li>
								<li  id="manage_cia_qp" abbr="qp" <?php if($class_name=='manage_cia_qp' || $class_name=='cia_rubrics') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_cia_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_cie'); ?> Question Paper & Rubrics</a></li>
								<li class="divider"></li>
									<?php if($mte_flag == 1) { ?>
										<li  id="mte_qp" abbr="qp" <?php if($class_name=='manage_mte_qp' || $class_name=='upload_mte_question_papers') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_mte_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_mte'); ?> Question Paper</a></li>
										<li class="divider"></li>
									<?php } ?>
									<?php if( $org_type != 'TIER-II') { ?>	
										<li  id="model_qp" abbr="qp" <?php if($class_name=='manage_model_qp') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_model_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Model Question Paper</a></li>
										<li class="divider"></li>
									<?php } ?>
								<li  id="tee_qp" abbr="qp" <?php if($class_name=='tee_qp_list' || $class_name=='upload_question_papers') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/tee_qp_list') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Question Paper</a></li>
								<li class="divider"></li>
								 <!--<li id="crclm_lab" abbr="qp" <?php  if($class_name=='upload_question_papers') echo 'class="active"'; ?>  ><a href="<?php echo base_url('assessment_attainment/upload_question_papers/index') ?>"><i class="icon-chevron-right"></i>Upload Question Paper</a></li>-->
										
							</ul>
                            </li>
                        <?php } ?>

                        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) { ?>
                            <li title="Attainment Analysis" id="analysis"<?php if ($tab == 'import_cia_data/') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
                                <a class="navbar_select_box" title="Attainment Analysis" href="<?php echo base_url('assessment_attainment/import_cia_data') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/attainment_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Attainment</p>
                                </a>
								<ul class="ul_dropdown" style="display:none;" >
									<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) { ?>
										<?php if ($org_type != 'TIER-II') { ?>	
											<li id="threshold" abbr="analysis" <?php if ($class_name == 'bl_po_co_threshold') echo 'class="active"'; ?>><a href="<?php echo base_url('assessment_attainment/bl_po_co_threshold/') ?>"><i class="icon-chevron-right"></i> Threshold / Target </a></li>
											<li class="divider"></li>
										<?php } else { ?>
											<li  id="assessment_level" abbr="analysis" <?php if ($class_name == 'attainment_level') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/attainment_level') ?>"><i class="icon-chevron-right"></i> Attainment Level Vs. Target </a></li>
											<li class="divider"></li>
										<?php } ?>
									<?php } ?>
									<li  id="cia_import" abbr="analysis" <?php if ($class_name == 'import_cia_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_cia_data/') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_cie'); ?> Data Import </a></li>
									<li class="divider"></li>
									<?php if ($mte_flag == 1) { ?>
										<li  id="mte_import" abbr="analysis" <?php if ($class_name == 'import_mte_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_mte_data/') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_mte'); ?> Data Import </a></li>
										<li class="divider"></li>
									<?php } ?>

							<!-- <li  id="edit_import" abbr="analysis" <?php // if($class_name=='imported_student_data_edit') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/imported_student_data_edit/')      ?>"><i class="icon-chevron-right"></i> Imported Student Data Edit </a></li> -->
									<!-- Tier II Attainment Modules Side menu -->
									<?php if ($org_type != 'TIER-II') { ?>		
										<li id="tee_import" abbr="analysis" <?php if ($class_name == 'import_assessment_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_assessment_data') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_see'); ?> Data Import </a></li>
										<li class="divider"></li>
										<!--<li  id="co_level" abbr="analysis" <?php // if ($class_name == 'tier1_clo_overall_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tier1_clo_overall_attainment/')     ?>"> <!--<?php // echo base_url('assessment_attainment/tier1_clo_overall_attainment/')     ?>"<i class="icon-chevron-right"></i> CO Attainment (Section Wise)</a></li>-->
										<li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_section_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_section_clo_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>
										<li class="divider"></li>
										<?php if ($mte_flag == 1) { ?>
											<li  id="mte_level_attainment" abbr="analysis" <?php if ($class_name == 'tier1_mte_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_mte_clo_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_mte'); ?>)</a></li>
											<li class="divider"></li>
										<?php } ?>
										<?php
										if ($mte_flag == 1) {
											$mte = $this->lang->line('entity_mte') . ' ,';
										} else {
											$mte = '';
										}
										?>
										<li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_course_clo_attainment/') ?>"><i class="icon-chevron-right"></i> Course - CO Attainment <br />(<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
										<li class="divider"></li>
										<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
											<li  id="po_level" abbr="analysis" <?php if ($class_name == 'tier1_po_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> Attainment</a></li>
											<li class="divider"></li>
											<li  id="crs_po_attain_mtrx" abbr="analysis" <?php if ($class_name == 'course_po_attainment_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/course_po_attainment_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - <?php echo $this->lang->line('so'); ?> & <?php echo $this->lang->line('entity_pso'); ?> Attainment</a></li>
											<li class="divider"></li>
											<li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'tier1_po_bacth_wise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_bacth_wise_attainment/') ?>"><i class="icon-chevron-right"></i> Individual <?php echo $this->lang->line('so'); ?> Attainment <br/> (Within a Batch)</a></li>
											<li class="divider"></li>
											<li  id="po_level_attainment" abbr="analysis" title="Current Academic Year <?php echo $this->lang->line('so'); ?> Attainment." <?php if ($class_name == 'tier1_po_attainment_academic_year_wise') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment_academic_year_wise/') ?>"><i class="icon-chevron-right"></i> CAY <?php echo $this->lang->line('so'); ?> Attainment <br/></a></li>
											<li class="divider"></li>
											
										<?php } ?>
										<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
											<!-- <li  id="po_level" abbr="analysis" <?php // if ($class_name == 'tier1_peo_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tier1_peo_attainment')     ?>"><i class="icon-chevron-right"></i>PEO Attainment</a></li> -->
										<?php } ?>
										<!-- <li  id="tee_cia_level" abbr="analysis" <?php // if ($class_name == 'tee_cia_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tee_cia_attainment')     ?>"><i class="icon-chevron-right"></i> Overall Course Attainment (Term wise - <?php // echo $this->lang->line('entity_see');     ?> & <?php // echo $this->lang->line('entity_cie');     ?>) </a></li> -->
									<?php } else { ?>
										<li id="tee_import" abbr="analysis" <?php if ($class_name == 'import_coursewise_tee') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/import_coursewise_tee') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_see'); ?> Data Import </a></li>
										<li class="divider"></li>
										<!-- Tier II Attainment Modules Side menu -->
										<li  id="co_section_level_attainment" abbr="analysis" <?php if ($class_name == 'co_section_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/co_section_level_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>	
											<li class="divider"></li>										
										<?php if ($mte_flag == 1) { ?>
											<li  id="mte_level_attainment" abbr="analysis" <?php if ($class_name == 'mte_co_section_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/mte_co_section_level_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_mte'); ?>)</a></li>
											<li class="divider"></li>
										<?php } ?>

										<?php
										if ($mte_flag == 1) {
											$mte = $this->lang->line('entity_mte') . ' , ';
										} else {
											$mte = '';
										}
										?>
										<li  id="course_clo_attainment" abbr="analysis" <?php if ($class_name == 'course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/course_clo_attainment/') ?>"><i class="icon-chevron-right"></i> Course - CO Attainment (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
										<li class="divider"></li>
										<li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'po_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/po_level_attainment/') ?>"><i class="icon-chevron-right"></i> PO Attainment</a></li>
										<li class="divider"></li>
										<li  id="crs_po_attain_mtrx" abbr="analysis" <?php if ($class_name == 'course_po_attainment_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/course_po_attainment_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - <?php echo $this->lang->line('so'); ?> & <?php echo $this->lang->line('entity_pso'); ?> Attainment</a></li>
										<li class="divider"></li>
										<li  id="po_level_attainment" abbr="analysis" title="Current Academic Year <?php echo $this->lang->line('so'); ?> Attainment." <?php if ($class_name == 'tier1_po_attainment_academic_year_wise') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment_academic_year_wise/') ?>"><i class="icon-chevron-right"></i> CAY <?php echo $this->lang->line('so'); ?> Attainment <br/></a></li>
										<li class="divider"></li>
										<li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'tier2_po_bacth_wise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/tier2_po_bacth_wise_attainment/') ?>"><i class="icon-chevron-right"></i> Individual <?php echo $this->lang->line('so'); ?> Attainment <br/> (Within a Batch)</a></li>
										<li class="divider"></li>
										
								<!-- <li  id="peo_level_attainment" abbr="analysis" <?php // if ($class_name == 'peo_level_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('tier_ii/peo_level_attainment/')     ?>"><i class="icon-chevron-right"></i>PEO Attainment</a></li> -->
								<!-- <li  id="ga_level_attainment" abbr="analysis" <?php if ($class_name == 'ga_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/ga_level_attainment/') ?>"><i class="icon-chevron-right"></i>GA Attainment</a></li>
								<li  id="ga_level_yearwise_attainment" abbr="analysis" <?php if ($class_name == 'ga_level_yearwise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/ga_level_yearwise_attainment/') ?>"><i class="icon-chevron-right"></i>GA Attainment (Year wise)</a></li> -->
									<?php } ?>

							<!--<li id="report_course_delivery" abbr="analysis" <?php // if($class_name=='co_report') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/co_report/')      ?>"><i class="icon-chevron-right"></i> Overall CO Attainment </a></li>
							<li id="report_course_delivery" abbr="analysis" <?php // if($class_name=='po_report') echo 'class="active"';    ?> ><a href="<?php // echo base_url('assessment_attainment/po_report/')     ?>"><i class="icon-chevron-right"></i> Overall PO Attainment </a></li> -->

									<li  id="data_series" abbr="analysis" <?php if ($class_name == 'data_series') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/data_series/') ?>"><i class="icon-chevron-right"></i> Data Analysis (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
									<li class="divider"></li>
									<li  id="student_attainment" abbr="analysis" <?php if ($class_name == 'student_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/student_attainment/') ?>"><i class="icon-chevron-right"></i> Student CO Attainment</a></li>
									<li class="divider"></li>
									<!-- <li  id="stud_po_level_attainment" abbr="analysis" <?php if ($class_name == 'stud_po_level_assessment_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/stud_po_level_assessment_data/') ?>"><i class="icon-chevron-right"></i>Student <?php echo $this->lang->line('so'); ?> Attainment</a></li> -->
								  

								  <li  id="student_threshold" abbr="analysis" <?php if ($class_name == 'student_threshold') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/student_threshold/') ?>"><i class="icon-chevron-right"></i> Student Improvement Plan <br/>(<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>	
								  <li class="divider"></li>
											<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('CoE')) { ?>
										<!-- <li  id="student_marks_upload" abbr="analysis" <?php // if ($class_name == 'student_marks_upload') echo 'class="active"';     ?> ><a href="<?php // echo base_url('scheduler/student_marks_upload/')     ?>"><i class="icon-chevron-right"></i>CoE Bulk Import <br/><?php // echo $this->lang->line('entity_see');     ?> Marks</a></li> -->
									<?php } ?>
								</ul>
                            </li>

                            <li title="Survey" id="survey"<?php if ($class_name_folder == 'survey') echo 'class="active dropdown_menu"'; ?> class="dropdown_menu ">
								<?php if ($this->ion_auth->in_group('admin') ) { 
									$url = 'survey/questions';
									}else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
									$url = 'survey/stakeholders/stakeholder_list';
									}else if($this->ion_auth->in_group('Course Owner')){
									$url = 'survey/templates';
									}else{
									 $url = 'survey/templates';
									}
								?>
                                <a class="navbar_select_box" title="Survey" href="<?php echo base_url($url) ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/survey_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Survey</p>
                                </a>
								
								<ul class="ul_dropdown" style="display:none;" >
								<?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
									<li abbr="config" <?php if ($className == 'dashboard') echo 'class="active"'; ?>  id="config_org" >
										<a href="<?php echo base_url('survey/dashboard') ?>">
											<i class="icon-chevron-right"></i> Survey Dashboard 
										</a>
									</li>
									<li class="divider"></li>
									<?php if ($this->ion_auth->in_group('admin')){ ?>
									<li abbr="config" <?php if ($className == 'questions') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/questions') ?>">
											<i class="icon-chevron-right"></i> Manage Survey Question Type 
										</a>
									</li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($className == 'answer_templates') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/answer_templates') ?>">
											<i class="icon-chevron-right"></i> Manage Response Templates 
										</a>
									</li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($className == 'stakeholders' && in_array($methodName,$stakeholderG)) echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/stakeholders') ?>">
											<i class="icon-chevron-right"></i> Manage Stakeholder Group 
										</a>
									</li>
									<li class="divider"></li>
									<?php } if ( $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
										<!-- <li abbr="config" <?php if ($className == 'import_student_data' || $className == 'import_student_data_excel') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('curriculum/student_list') ?>">
											<i class="icon-chevron-right"></i> Manage Student Stakeholder 
										</a>
										</li> -->
										<li abbr="config" <?php if ($className == 'stakeholders' && !in_array($methodName,$stakeholderG)) echo 'class="active"'; ?> id="config_pgmtype">
											<a href="<?php echo base_url('survey/stakeholders/stakeholder_list') ?>">
												<i class="icon-chevron-right"></i> Manage Stakeholder 
											</a>
										</li>
										<li class="divider"></li>
									<?php } //if ( $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
									<li abbr="config" <?php if ($className == 'templates') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/templates') ?>">
											<i class="icon-chevron-right"></i> Manage Survey Templates 
										</a>
									</li>
									<li class="divider"></li>
									<?php //} ?>
									<li abbr="config" <?php if ($className =='surveys' && $methodName!='view_survey' && $methodName!='indirect_attainment_report') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/surveys') ?>">
											<i class="icon-chevron-right"></i> Create Survey
										</a>
									</li>
									<li class="divider"></li>
									<li abbr="config" <?php if ($className =='host_survey') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/host_survey') ?>">
											<i class="icon-chevron-right"></i> Host Survey
										</a>
									</li>
									<li class="divider"></li>
									
									<li abbr="config" <?php if ($className =='reports' && $methodName=='hostedSurvey' || $className =='surveys' && $methodName=='view_survey' || $methodName =='indirect_attainment_report') echo 'class="active"'; ?> id="config_pgmtype">
										<a href="<?php echo base_url('survey/reports/hostedSurvey') ?>">
											<i class="icon-chevron-right"></i> Survey Report
										</a>
									</li>
									<li class="divider"></li>
								 <?php endif ?>
							</ul>
								
                            </li>

                            <li title="InoCUDOS Web Help" >
                                <a class="navbar_select_box" title="Help" href="<?php echo base_url('help') ?>">
                                    <p align="center"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/help_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Help</p>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (!$this->ion_auth->in_group('admin')) { ?>
                            <li title="Change Department" id="switch_dept" style="display:none;">
                                <a id="multi_dept" title="Switch Department" class="cursor_pointer" user_id="<?php echo $this->ion_auth->user()->row()->id; ?>">
                                    <img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/switch_mini_v1.png'); ?>" align="middle" border="0">&nbsp;Switch
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="span1 pull-right" style="margin:0px;">
					
                    <ul class="nav pull-right" style="margin-top: -1px; font-size:9px; left:20px;">
						<li class="dropdown">
							  <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 0;padding-right:12px;"><img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/userimage_mini_v2.png'); ?>" align="middle" border="0"> <b class="caret"></b></a>
							  <ul class="dropdown-menu">
								<li align="center" style="margin-right:2px;" class="cursor_pointer" title="Role: <?php
							if ($this->ion_auth->in_group('admin')) {
                                    echo "Admin ";
                                }
                                if ($this->ion_auth->in_group('Chairman')) {
                                    echo "- Chairman(HoD) ";
                                }
                                if ($this->ion_auth->in_group('Program Owner')) {
                                    echo "- " . $this->lang->line('program_owner_full');
                                }
                                if ($this->ion_auth->in_group('Course Owner')) {
                                    echo " - " . $this->lang->line('course_owner_full');
                                }
                                if ($this->ion_auth->in_group('BOS')) {
                                    echo " - BoS";
                                }
                                if ($this->ion_auth->in_group('Member')) {
                                    echo " - Member";
                                }
                                if ($this->ion_auth->in_group('Student')) {
                                    echo " - Student";
                                }
                                if ($this->ion_auth->in_group('CoE')) {
                                    echo " - CoE";
                                }
                        ?>">Role:<?php
						
						
						 if ($this->ion_auth->in_group('admin')) {
                            echo character_limiter('Admin', 5);
                        }
                        if ($this->ion_auth->in_group('Chairman')) {
                            echo character_limiter("- Chairman(HoD)", 5);
                        }
                        if ($this->ion_auth->in_group('Program Owner')) {
                            echo "- " . character_limiter($this->lang->line('program_owner_full'), 5); 
                        }
                        if ($this->ion_auth->in_group('Course Owner')) {
                            echo " - " . character_limiter($this->lang->line('course_owner_full'), 5);
						}
                        if ($this->ion_auth->in_group('BOS')) {
                            echo character_limiter(" - BoS", 5);
                        }
                        if ($this->ion_auth->in_group('Member')) {
                            echo character_limiter(" - Member", 5);
                        }
                        if ($this->ion_auth->in_group('Student')) {
                            echo character_limiter(" - Student", 5);
                        }
                        if ($this->ion_auth->in_group('CoE')) {
                            echo character_limiter(" - CoE", 5);
                        }
                                
                                ?>

                            <p style = "font-size:12px; align : left"><i class="icon-user"></i> &nbsp; Welcome : 						
                                   <?php if (($this->ion_auth->in_group('Student'))) { 
								   $name  =  $this->ion_auth->user()->row()->title . ' ' . ucfirst($this->ion_auth->user()->row()->first_name); 
								   ?>
                                    <a  class="navbar_select_box" href="<?php echo base_url('login/student_user_edit'); ?>" class="myTagRemover" title="Edit Profile &#013;User : <?php echo $this->ion_auth->user()->row()->title . ' ' . ucfirst($this->ion_auth->user()->row()->first_name) . ' ' . ucfirst($this->ion_auth->user()->row()->last_name); ?>">
								  <?php echo  character_limiter($name, 10) ;?> 
								   </a>
                                <?php } else { 
								$name =   $this->ion_auth->user()->row()->title . ' ' . ucfirst($this->ion_auth->user()->row()->first_name); 
								?>
                                   <a class="navbar_select_box" href="<?php echo base_url('report/edit_profile/index/'); ?>" class="myTagRemover" title="Edit Profile &#013;User : <?php echo $this->ion_auth->user()->row()->title . ' ' . ucfirst($this->ion_auth->user()->row()->first_name) . ' ' . ucfirst($this->ion_auth->user()->row()->last_name); ?> &#013;Current login time : <?php echo $login_date_formated; ?>"> 
									
                                    <!-- <a class="navbar_select_box" href="<?php // echo base_url('login/user_edit_profile/' . $this->ion_auth->user()->row()->id); ?>" class="myTagRemover" title="Edit Profile &#013;User : <?php // echo $this->ion_auth->user()->row()->title . ' ' . ucfirst($this->ion_auth->user()->row()->first_name) . ' ' . ucfirst($this->ion_auth->user()->row()->last_name); ?> &#013;Current login time : <?php // echo $login_date_formated; ?>"> -->
									<?php echo character_limiter($name, 10) ;?>
									</a>
<?php } ?>
                            </p>
                        </li>
						<li class="divider"></li>
						<li align="left">
							<a title="Click to Logout" class="" href="<?php echo site_url('logout'); ?>" style="font-size:12px;">
									<i class="icon-off"></i> &nbsp; Log-out <!--<img src="<?php echo base_url('twitterbootstrap/img/navbar_icons/logout_mini_v1.png'); ?>">-->
							</a>
						</li>
								
							  </ul>
							</li>
                        
                        
                    </ul>

                </div>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<!-- Modal to display Confirmation message before sending for Approval -->
<div id="multi_dept_modal_show" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="request_password_dialog_id" data-backdrop="static" data-keyboard="true"></br>
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Switch Department
            </div>
        </div>
    </div>
    <div class="modal-body">
        <form name="multi_dept" id="multi_dept_data" method="post" action="">
            <div class="row-fluid ">
                <div class="span8">
                    <div class="control-group">
                        <div class="controls" id="place_multi_dept_modal">

                        </div>
                    </div>
                </div>

                <div class="span4 pull-right">
                    <div class="control-group">
                        <div class="controls pull-right">
                            <label>Home Department</label>
                            <button type="button" id="log_in_my_dept" base_dept_id = "<?php echo $this->ion_auth->user()->row()->base_dept_id; ?>" user_id="<?php echo $this->ion_auth->user()->row()->id; ?>"class="btn btn-success"><i class="icon-home icon-white"></i> Log-in</button>  
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <p><b>Note:</b> As you are Associated with more than one department, select the department and click on log-in under which you would like to work.</p>
            </div>
        </form>
    </div>

    <div class="control-group" id="place_multi_dept_modal">
    </div>

    <div class="modal-footer">
        <div class="pull-left">
            <button type="button" id="submit_button_id" class="btn btn-success"><i class="icon-user icon-white"></i> Log-in </button>
            <button data-dismiss="modal" type="button" class="btn btn-danger" ><i class="icon-remove icon-white"></i><span></span> Cancel </button>
        </div>
    </div>
</div>
<script src="<?php echo base_url('twitterbootstrap/js/custom/login/multi_dept_modal_show.js'); ?>" type="text/javascript"></script>
