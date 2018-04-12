<?php
/**
* Description	:	Generic Side Navbar View file of the main menu Configuration Tab contains 
*					side menu tabs like Organization, Department, Program Type etc ).
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
-----------------------------------------------------------------------------------------------
*/
?>
<?php $class_name = $this->router->fetch_class();
?>
<div class="span2 bs-docs-sidebar">
    <ul class="nav nav-list bs-docs-sidenav">
		<?php if($this->ion_auth->in_group('admin')) : ?>
			<li abbr="config" <?php if($class_name=='organisation') echo 'class="active"';?>  id="config_org" ><a href="<?php echo base_url('configuration/organisation') ?>"><i class="icon-chevron-right"></i> Organization </a></li>
			<li abbr="config" <?php if($class_name=='department' || $class_name=='add_department') echo 'class="active"';?>id="config_dept"><a href="<?php echo base_url('configuration/department') ?>"><i class="icon-chevron-right"></i> Department </a></li>
			<li abbr="config" <?php if($class_name=='programtype') echo 'class="active"';?> id="config_pgmtype"><a href="<?php echo base_url('configuration/programtype') ?>"><i class="icon-chevron-right"></i> Program Type </a></li>
			<li abbr="config" <?php if($class_name=='program') echo 'class="active"';?> id="config_pgm"><a href="<?php echo base_url('configuration/program') ?>"><i class="icon-chevron-right"></i> Program </a></li>
			<li abbr="config" <?php if($class_name=='users') echo 'class="active"';?>id="config_user" ><a href="<?php echo base_url('configuration/users/list_users') ?>"><i class="icon-chevron-right"></i> Users </a></li>
			<li abbr="config" <?php if($class_name=='user_groups') echo 'class="active"';?> id="config_grp" ><a href="<?php echo base_url('configuration/user_groups') ?>"><i class="icon-chevron-right"></i> Groups </a></li>
		<!--	<li abbr="config" <?php if($class_name=='permissions') echo 'class="active"';?> id="config_prmsn" ><a href="<?php echo base_url('configuration/permissions') ?>"><i class="icon-chevron-right"></i> Permissions </a></li>  -->
			<li abbr="config" <?php if($class_name=='bos' || $class_name=='addbos' ) echo 'class="active"';?> id="config_bos" ><a href="<?php echo base_url('configuration/bos') ?>"><i class="icon-chevron-right"></i> BOS Members </a></li>
			<li abbr="config" <?php if($class_name=='help_content') echo 'class="active"';?> id="config_help" ><a href="<?php echo base_url('configuration/help_content') ?>"><i class="icon-chevron-right"></i> Management </a></li>
			<li abbr="config" <?php if($class_name=='program_mode') echo 'class="active"';?> id="config_pgmmode" ><a href="<?php echo base_url('configuration/program_mode') ?>"><i class="icon-chevron-right"></i> Program Mode </a></li>
			<li abbr="config" <?php if($class_name=='po_type') echo 'class="active"';?> id="config_potype" ><a href="<?php echo base_url('configuration/po_type') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> Type </a></li>
			<li abbr="config" <?php if($class_name=='course_type') echo 'class="active"';?> id="config_crstype"><a href="<?php echo base_url('configuration/course_type') ?>"><i class="icon-chevron-right"></i> Course Type </a></li>
			<li abbr="config" <?php if($class_name=='user_designation') echo 'class="active"';?> id="config_dsgn" ><a href="<?php echo base_url('configuration/user_designation') ?>"><i class="icon-chevron-right"></i> Designation </a></li>
			<li abbr="config" <?php if($class_name=='bloom_level') echo 'class="active"';?> id="config_bloom" ><a href="<?php echo base_url('configuration/bloom_level') ?>"><i class="icon-chevron-right"></i> Bloom's Level </a></li>
			<li abbr="config" <?php if($class_name=='unit') echo 'class="active"';?> id="config_unit"><a href="<?php echo base_url('configuration/unit') ?>"><i class="icon-chevron-right"></i> Unit </a></li>
		<?php endif ?>
    </ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js');?>"></script>
	  
	  

	  