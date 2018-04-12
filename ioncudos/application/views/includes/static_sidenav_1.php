<?php
/**
* Description	:	Generic Side Navbar View file of the main menu Configuration History Tab contains 
*					static (read only) side menu tabs like Organization, Department, Program Type etc ).
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
-----------------------------------------------------------------------------------------------------------
*/
?>
<?php $class_name = $this->router->fetch_class(); 
		$class_name_folder = $this->uri->segment(1);
		$class_name_one = $this->uri->segment(3);
		$tab = $class_name.'/'.$class_name_one;
		?>
<div class="span2 bs-docs-sidebar">
	<ul class="nav nav-list bs-docs-sidenav">
		<li abbr="config_hist" <?php if($tab=='organisation/org_static_index') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/organisation/org_static_index') ?>"><i class="icon-chevron-right"></i> Organization</a></li>
		<li abbr="config_hist" <?php if($tab=='department/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/department/static_index') ?>"><i class="icon-chevron-right"></i> Department </a></li>
		<li abbr="config_hist" <?php if($tab=='programtype/static_list_programtype') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/programtype/static_list_programtype') ?>"><i class="icon-chevron-right"></i> Program Type </a></li>
		<li abbr="config_hist" <?php if($tab=='program/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/program/static_index') ?>"><i class="icon-chevron-right"></i> Program </a></li>
		<li abbr="config_hist" <?php if($tab=='users/static_users_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/users/static_users_list') ?>"><i class="icon-chevron-right"></i> Users </a></li>
		<li abbr="config_hist" <?php if($tab=='user_groups/static_list_usergroups') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/user_groups/static_list_usergroups') ?>"><i class="icon-chevron-right"></i> Groups </a></li>
	<!--	<li abbr="config_hist" <?php if($tab=='permissions') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/permissions') ?>"><i class="icon-chevron-right"></i> Permissions </a></li> -->
		<li abbr="config_hist" <?php if($tab=='bos/static_list_bos') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/bos/static_list_bos') ?>"><i class="icon-chevron-right"></i> BOS Members </a></li>
		<li abbr="config_hist" <?php if($tab=='help_content/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/help_content/static_index') ?>"><i class="icon-chevron-right"></i> Management </a></li>
		<li abbr="config_hist" <?php if($tab=='program_mode/static_program_mode_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/program_mode/static_program_mode_list') ?>"><i class="icon-chevron-right"></i> Program Mode </a></li>
		<li abbr="config_hist" <?php if($tab=='po_type/static_po_type_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/po_type/static_po_type_list') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> Type </a></li>
		<li abbr="config_hist" <?php if($tab=='course_type/static_course_type_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/course_type/static_course_type_list') ?>"><i class="icon-chevron-right"></i> Course Type </a></li>
		<li abbr="config_hist" <?php if($tab=='user_designation/static_designation_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/user_designation/static_designation_list') ?>"><i class="icon-chevron-right"></i> Designation </a></li>
		<li abbr="config_hist" <?php if($tab=='bloom_level/static_bloom_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/bloom_level/static_bloom_list') ?>"><i class="icon-chevron-right"></i> Bloom's Level </a></li>
		<li abbr="config_hist" <?php if($tab=='unit/static_unit_list') echo 'class="active"';?> ><a href="<?php echo base_url('configuration/unit/static_unit_list') ?>"><i class="icon-chevron-right"></i> Unit </a></li>
	</ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js');?>"></script>	  
	  


