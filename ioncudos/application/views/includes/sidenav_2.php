<?php
	/**
	 * Description	:	Generic Side Navbar View file of the main menu Curriculum Tab contains 
	 *					side menu tabs like Curriculum, PEO, PO, PO to PEO Mapping etc ).
	 *					
	 * Created		:	25-03-2013. 
	 *		  
	 * Modification History:
	 *   Date			  Modified By				    Description
	 * 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.
	 * 27-03-2016		Arihant Prasad			Added New Module - Department Users list
	---------------------------------------------------------------------------------------------- */
		
	$val =  $this->ion_auth->user()->row();
	$org_name = $val->org_name->org_name;
	$org_type = $val->org_name->org_type;
	$oe_pi_flag = $val->org_name->oe_pi_flag;
	$theory_iso_code = $val->org_name->theory_iso_code;
?>
<?php $class_name = $this->router->fetch_class(); ?>
<div class="span2 bs-docs-sidebar fixed-height">
	<ul class="nav nav-list bs-docs-sidenav">		
		<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') ) { ?>
			<li id="send_mail" abbr="tab_crclm" <?php if ($class_name == 'send_mail')
			echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/send_mail') ?>"><i class="icon-chevron-right"></i> Send Mail </a></li>
			<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>
			<li id="config_dept_mission" abbr="tab_crclm" <?php if($class_name=='dept_mission_vision') echo 'class="active"'; ?>  ><a href="<?php echo base_url('configuration/dept_mission_vision') ?>"><i class="icon-chevron-right"></i> Department Vision/Mission  </a></li>
			<li id="crclm_crclm" abbr="tab_crclm" <?php if($class_name=='curriculum') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum') ?>"><i class="icon-chevron-right"></i> Curriculum (Regulation)</a></li>
			<li id="crclm_peo" abbr="tab_crclm" <?php if($class_name=='peo') echo 'class="active"'; ?>><a href="<?php echo base_url('curriculum/peo') ?>"><i class="icon-chevron-right"></i> Program Educational Objectives (PEOs) </a></li>
			<li id="crclm_po" abbr="tab_crclm" <?php if($class_name=='po') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/po') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </a></li>
			<li id="crclm_po_ga" abbr="tab_crclm" <?php if($class_name=='map_po_ga') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_ga') ?>"><i class="icon-chevron-right"></i> GA to <?php echo $this->lang->line('so'); ?> Mapping </a></li>
			<li id="crclm_po_peo_map" abbr="tab_crclm" <?php if($class_name=='map_po_peo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/map_po_peo') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> to PEO Mapping </a></li>

		<?php } ?>
		
					
		<?php if($this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('admin')){ ?> 
			<li id="dept_users" abbr="tab_crclm" <?php if($class_name=='dept_users') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/dept_users') ?>"><i class="icon-chevron-right"></i> Manage Users </a></li>		
		<?php } ?>
		
		<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>

			<li id="crclm_setting" abbr="tab_crclm" <?php if($class_name=='setting' || $class_name=='import_user' ||  $class_name=='curriculum_delivery_method' || $class_name=='course_domain' || $class_name=='assessment_method' || $class_name=='student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/setting') ?>"><i class="icon-chevron-right"></i> Curriculum Settings </a></li>
			<!-- OE & PI Reports -->
			<?php if( $oe_pi_flag == 1 ) { ?>
				<li id="crclm_pi" abbr="tab_crclm" <?php if($class_name=='pi_and_measures') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/pi_and_measures') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('outcome_element_sing_full'); ?> & Performance Indicator (PI) </a></li>
			<?php  } ?>			
			<li id="crclm_crs" abbr="tab_crclm" <?php if($class_name=='course') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/course') ?>"><i class="icon-chevron-right"></i> Course </a></li>
		<?php } ?>
		
		<?php if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner') ) { ?>
			<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
			<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to <?php echo $this->lang->line('so'); ?> Mapping (Course wise) </a></li>
			<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic' || $class_name=='tlo_new_edit') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i>Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
			<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i>Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
		<?php } ?>
		
		<?php if( !($this->ion_auth->in_group('Program Owner')) && $this->ion_auth->in_group('Course Owner') ) { ?>	
			<li id="crclm_clo" abbr="tab_crclm" <?php if($class_name=='clo') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo') ?>"><i class="icon-chevron-right"></i> Course Outcome (CO) </a></li>
			<li id="crclm_clo_po_crs" abbr="tab_crclm" <?php if($class_name=='clo_po_map') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/clo_po_map/map_po_clo') ?>"><i class="icon-chevron-right"></i> CO to PO Mapping (Course wise) </a></li>
			<li id="crclm_topic" abbr="tab_crclm" <?php if($class_name=='topic') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/topic') ?>"><i class="icon-chevron-right"></i>Manage <?php echo $this->lang->line('entity_topic'); ?> & <?php echo $this->lang->line('entity_tlo'); ?> </a></li>
			<li id="crclm_lab" abbr="tab_crclm" <?php if($class_name=='lab_experiment') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/lab_experiment') ?>"><i class="icon-chevron-right"></i>Manage Lab Experiment & <?php echo $this->lang->line('entity_tlo'); ?></a></li>
		<?php } ?>	
	</ul>
	<?php echo str_repeat('<br>', 1); ?>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/tab.js'); ?>"></script>