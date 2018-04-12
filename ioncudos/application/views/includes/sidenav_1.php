<?php
/**
 * Description          :	Generic Side Navbar View file of the main menu Configuration Tab contains 
 * 					side menu tabs like Organization, Department, Program Type etc ).
 * 					
 * Created		:	25-03-2013. 
 * 		  
 * Modification History:
 * Date				Modified By				Description
 *
 * 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
  -----------------------------------------------------------------------------------------------
 */
?>
<?php $class_name = $this->router->fetch_class();
$val = $this->ion_auth->user()->row();
$org_name = $val->organization_name;
?>
<div class="span2 bs-docs-sidebar">
    <ul class="nav nav-list bs-docs-sidenav">
        <?php if ($this->ion_auth->in_group('admin')) : ?>
			<li abbr="config" <?php if ($class_name == 'bulk_email') echo 'class="active"'; ?> id="config_bulk_email" ><a href="<?php echo base_url('configuration/bulk_email') ?>"><i class="icon-chevron-right"></i> Send Bulk Email </a></li>
			<li abbr="config" <?php if ($class_name == 'application_backup')
            echo 'class="active"'; ?>  id="config_application_backup" ><a href="<?php echo base_url('configuration/application_backup') ?>"><i class="icon-chevron-right"></i> IonCUDOS Data Backup </a></li>
			 <?php if ($org_name == 1) { ?>
                <li abbr="config" <?php if ($class_name == 'application_setting')
                echo 'class="active"'; ?>  id="config_application_setting" ><a href="<?php echo base_url('configuration/application_setting') ?>"><i class="icon-chevron-right"></i> Organization Level Setting </a></li>
                <?php } ?>
            <li abbr="config" <?php if ($class_name == 'organisation') echo 'class="active"'; ?>  id="config_org" ><a href="<?php echo base_url('configuration/organisation') ?>"><i class="icon-chevron-right"></i> Organization </a></li>
            <li abbr="config" <?php if ($class_name == 'department' || $class_name == 'add_department') echo 'class="active"'; ?>id="config_dept"><a href="<?php echo base_url('configuration/department') ?>"><i class="icon-chevron-right"></i> Department </a></li>
            <li abbr="config" <?php if ($class_name == 'programtype') echo 'class="active"'; ?> id="config_pgmtype"><a href="<?php echo base_url('configuration/programtype') ?>"><i class="icon-chevron-right"></i> Program Type </a></li>
            <li abbr="config" <?php if ($class_name == 'program_mode') echo 'class="active"'; ?> id="config_pgmmode" ><a href="<?php echo base_url('configuration/program_mode') ?>"><i class="icon-chevron-right"></i> Program Mode </a></li>
            <!--<li abbr="config" <?php //if($class_name=='unit') echo 'class="active"'; ?> id="config_unit"><a href="<?php //echo base_url('configuration/unit')  ?>"><i class="icon-chevron-right"></i> Duration </a></li>-->
            <!--<li abbr="config" <?php //if($class_name=='topic_unit') echo 'class="active"'; ?> id="config_t_unit" ><a href="<?php //echo base_url('configuration/topic_unit')  ?>"><i class="icon-chevron-right"></i> Topic Unit </a></li>-->
            <li abbr="config" <?php if ($class_name == 'program') echo 'class="active"'; ?> id="config_pgm"><a href="<?php echo base_url('configuration/program') ?>"><i class="icon-chevron-right"></i> Program </a></li>
            <!-- <li abbr="config" <?php // if($class_name=='user_designation') echo 'class="active"'; ?> id="config_dsgn" ><a href="<?php // echo base_url('configuration/user_designation')  ?>"><i class="icon-chevron-right"></i> Designation </a></li> -->
            <li abbr="config" <?php if ($class_name == 'users') echo 'class="active"'; ?>id="config_user" ><a href="<?php echo base_url('configuration/users/list_users') ?>"><i class="icon-chevron-right"></i> Users </a></li>
            <li abbr="config" <?php if ($class_name == 'bos' || $class_name == 'addbos') echo 'class="active"'; ?> id="config_bos" ><a href="<?php echo base_url('configuration/bos') ?>"><i class="icon-chevron-right"></i> BoS Members </a></li>
            <li abbr="config" <?php if ($class_name == 'graduate_attributes') echo 'class="active"'; ?> id="config_ga" ><a href="<?php echo base_url('configuration/graduate_attributes') ?>"><i class="icon-chevron-right"></i> Manage GAs</a></li>
            <li abbr="config" <?php if ($class_name == 'po_type') echo 'class="active"'; ?> id="config_accreditationtype" ><a href="<?php echo base_url('configuration/po_type') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Type </a></li>
            <li abbr="config" <?php if ($class_name == 'accreditation_type') echo 'class="active"'; ?> id="config_accreditationtype" ><a href="<?php echo base_url('configuration/accreditation_type') ?>"><i class="icon-chevron-right"></i>Generic <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></a></li>
            <!-- <li abbr="config" <?php // if ($class_name == 'curriculum_component') echo 'class="active"'; ?> id="config_crscomp"><a href="<?php // echo base_url('configuration/curriculum_component') ?>"><i class="icon-chevron-right"></i> Curriculum Component</a></li> -->
            <li abbr="config" <?php if ($class_name == 'course_type') echo 'class="active"'; ?> id="config_crstype"><a href="<?php echo base_url('configuration/course_type') ?>"><i class="icon-chevron-right"></i> Course Type </a></li>
            <!-- <li abbr="config" <?php // if ($class_name == 'ao_method') echo 'class="active"'; ?> id="config_crstype"><a href="<?php // echo base_url('configuration/ao_method') ?>"><i class="icon-chevron-right"></i> Assessment Method </a></li> -->
            <li abbr="config" <?php if ($class_name == 'delivery_method') echo 'class="active"'; ?> id="config_crstype"><a href="<?php echo base_url('configuration/delivery_method') ?>"><i class="icon-chevron-right"></i> Delivery Method </a></li> 
            <li abbr="config" <?php if ($class_name == 'lab_category') echo 'class="active"'; ?> id="config_lab_category" ><a href="<?php echo base_url('configuration/lab_category') ?>"><i class="icon-chevron-right"></i> Lab Category </a></li>
            <li abbr="config" <?php if ($class_name == 'bloom_domain') echo 'class="active"'; ?> id="config_bloom_domain" ><a href="<?php echo base_url('configuration/bloom_domain') ?>"><i class="icon-chevron-right"></i> Bloom's Domain </a></li> 
            <li abbr="config" <?php if ($class_name == 'bloom_level') echo 'class="active"'; ?> id="config_bloom" ><a href="<?php echo base_url('configuration/bloom_level') ?>"><i class="icon-chevron-right"></i> Bloom's Level </a></li>
            <li abbr="config" <?php if ($class_name == 'map_level_weightage') echo 'class="active"'; ?> id="config_map_level_weightage"><a href="<?php echo base_url('configuration/map_level_weightage') ?>"><i class="icon-chevron-right"></i> Map Level Weightage </a></li>
            <!--<li abbr="config" <?php // if($class_name=='user_groups') echo 'class="active"'; ?> id="config_grp" ><a href="<?php // echo base_url('configuration/user_groups')  ?>"><i class="icon-chevron-right"></i> Groups (Roles) </a></li> -->
            <li abbr="config" <?php if ($class_name == 'help_content') echo 'class="active"'; ?> id="config_help" ><a href="<?php echo base_url('configuration/help_content') ?>"><i class="icon-chevron-right"></i> Content Specific Guidelines </a></li>
            <!-- <li abbr="config" <?php // if ($class_name == 'adequacy_report') echo 'class="active"'; ?> id="config_adequacy" ><a href="<?php // echo base_url('configuration/adequacy_report') ?>"><i class="icon-chevron-right"></i> Adequacy Report </a></li> -->
            <!-- <li abbr="config" <?php // if ($class_name == 'bulk_email') echo 'class="active"'; ?> id="config_bulk_email" ><a href="<?php // echo base_url('configuration/bulk_email') ?>"><i class="icon-chevron-right"></i> Send Bulk Emails </a></li> -->
            <?php endif ?>
    </ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js'); ?>"></script>




