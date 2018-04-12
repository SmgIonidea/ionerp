<?php
/**
 * Description	:	Tab bar				
 * Created		:	9-29-2015. 
 * Date				Modified By				Description
 * 02-02-2015		Bhagyalaxmi      	
  ----------------------------------------------------------------------------------------------
 */
?>
<?php $class_name = $this->router->fetch_class();
?>

<div class="navbar">
    <div class="navbar-inner-custom">
        Curriculum Settings
    </div>
</div>
<ul class="nav nav-tabs">                                
    <li id="crclm_import_user" abbr="tab_crclm" <?php if ($class_name == 'import_user') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/import_user') ?>"> Import User  </a></li>
    <li id="crclm_dm" abbr="tab_crclm" <?php if ($class_name == 'curriculum_delivery_method') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum_delivery_method') ?>"> Curriculum Delivery Method </a></li>
    <li id="crclm_crsdmn" abbr="tab_crclm" <?php if ($class_name == 'course_domain') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/course_domain') ?>">Course Domain </a></li>
    <li id="assessment_method" abbr="assessment_method" <?php if ($class_name == 'assessment_method') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/assessment_method') ?>">Assessment Method </a></li>
	<li id="crclm_student_list" abbr="tab_crclm" <?php if($class_name=='student_list') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/student_list') ?>">Import Student List</a></li>
</ul></BR>
