<?php
/**
 * Description	:	Generic Side Navbar file of the main menu Reports Tab contains 
 * 					side menu tabs like PO to PEO Mapped Report, CLO to PO Mapped Report (Coursewise) etc ).
 * 					
 * Created		:	25-03-2013. 
 * 		  
 * Modification History:
 * Date				Modified By				Description
 *
 * 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
 * 14-12-2017	 	Abhinay B.Angadi        Included new side menu and Label changes & indentations.   		
  ---------------------------------------------------------------------------------------------------------------
 */
$val = $this->ion_auth->user()->row();
$org_name = $val->org_name->org_name;
$org_type = $val->org_name->org_type;
$oe_pi_flag = $val->org_name->oe_pi_flag;
$mte_flag = $val->org_name->mte_flag;
$theory_iso_code = $val->org_name->theory_iso_code;
?>

<?php $class_name = $this->router->fetch_class();
?>
<div class="span2 bs-docs-sidebar">
    <ul class="nav nav-list bs-docs-sidenav">

        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) { ?>
            <?php if ($org_type != 'TIER-II') { ?>	
                <li id="threshold" abbr="analysis" <?php if ($class_name == 'bl_po_co_threshold') echo 'class="active"'; ?>><a href="<?php echo base_url('assessment_attainment/bl_po_co_threshold/') ?>"><i class="icon-chevron-right"></i> Threshold / Target </a></li>
            <?php } else { ?>
                <li  id="assessment_level" abbr="analysis" <?php if ($class_name == 'attainment_level') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/attainment_level') ?>"><i class="icon-chevron-right"></i> Attainment Level Vs. Target </a></li>
            <?php } ?>
        <?php } ?>
        <li  id="cia_import" abbr="analysis" <?php if ($class_name == 'import_cia_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_cia_data/') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_cie'); ?> Data Import </a></li>
        <?php if ($mte_flag == 1) { ?>
            <li  id="mte_import" abbr="analysis" <?php if ($class_name == 'import_mte_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_mte_data/') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_mte'); ?> Data Import </a></li>
        <?php } ?>

<!-- <li  id="edit_import" abbr="analysis" <?php // if($class_name=='imported_student_data_edit') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/imported_student_data_edit/')      ?>"><i class="icon-chevron-right"></i> Imported Student Data Edit </a></li> -->
        <!-- Tier II Attainment Modules Side menu -->
        <?php if ($org_type != 'TIER-II') { ?>		
            <li id="tee_import" abbr="analysis" <?php if ($class_name == 'import_assessment_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/import_assessment_data') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_see'); ?> Data Import </a></li>
            <!--<li  id="co_level" abbr="analysis" <?php // if ($class_name == 'tier1_clo_overall_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tier1_clo_overall_attainment/')     ?>"> <!--<?php // echo base_url('assessment_attainment/tier1_clo_overall_attainment/')     ?>"<i class="icon-chevron-right"></i> CO Attainment (Section Wise)</a></li>-->
            <li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_section_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_section_clo_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>
            <?php if ($mte_flag == 1) { ?>
                <li  id="mte_level_attainment" abbr="analysis" <?php if ($class_name == 'tier1_mte_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_mte_clo_attainment/') ?>"><i class="icon-chevron-right"></i>CO Attainment (<?php echo $this->lang->line('entity_mte'); ?>)</a></li>
            <?php } ?>
            <?php
            if ($mte_flag == 1) {
                $mte = $this->lang->line('entity_mte') . ' ,';
            } else {
                $mte = '';
            }
            ?>
		 <li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_first_year_co_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_first_year_co_attainment/') ?>"><i class="icon-chevron-right"></i> First Year CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>
            <li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_course_clo_attainment/') ?>"><i class="icon-chevron-right"></i> Course - CO Attainment <br />(<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
            <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
                <li  id="po_level" abbr="analysis" <?php if ($class_name == 'tier1_po_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('so'); ?> Attainment</a></li>
                <li  id="crs_po_attain_mtrx" abbr="analysis" <?php if ($class_name == 'course_po_attainment_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/course_po_attainment_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - <?php echo $this->lang->line('so'); ?> & <?php echo $this->lang->line('entity_pso'); ?> Attainment</a></li>
                <li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'tier1_po_bacth_wise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_bacth_wise_attainment/') ?>"><i class="icon-chevron-right"></i>Individual <?php echo $this->lang->line('so'); ?> Attainment <br/> (Within a Batch)</a></li>
                <li  id="po_level_attainment" abbr="analysis" title="Current Academic Year <?php echo $this->lang->line('so'); ?> Attainment." <?php if ($class_name == 'tier1_po_attainment_academic_year_wise') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment_academic_year_wise/') ?>"><i class="icon-chevron-right"></i>CAY <?php echo $this->lang->line('so'); ?> Attainment <br/></a></li>
                
            <?php } ?>
            <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
                <!-- <li  id="po_level" abbr="analysis" <?php // if ($class_name == 'tier1_peo_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tier1_peo_attainment')     ?>"><i class="icon-chevron-right"></i>PEO Attainment</a></li> -->
            <?php } ?>
            <!-- <li  id="tee_cia_level" abbr="analysis" <?php // if ($class_name == 'tee_cia_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/tee_cia_attainment')     ?>"><i class="icon-chevron-right"></i> Overall Course Attainment (Term wise - <?php // echo $this->lang->line('entity_see');     ?> & <?php // echo $this->lang->line('entity_cie');     ?>) </a></li> -->
        <?php } else { ?>
            <li id="tee_import" abbr="analysis" <?php if ($class_name == 'import_coursewise_tee') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/import_coursewise_tee') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_see'); ?> Data Import </a></li>
            <!-- Tier II Attainment Modules Side menu -->
            <li  id="co_section_level_attainment" abbr="analysis" <?php if ($class_name == 'co_section_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/co_section_level_attainment/') ?>"><i class="icon-chevron-right"></i> CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>					
            <?php if ($mte_flag == 1) { ?>
                <li  id="mte_level_attainment" abbr="analysis" <?php if ($class_name == 'mte_co_section_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/mte_co_section_level_attainment/') ?>"><i class="icon-chevron-right"></i>CO Attainment (<?php echo $this->lang->line('entity_mte'); ?>)</a></li>
            <?php } ?>

            <?php
            if ($mte_flag == 1) {
                $mte = $this->lang->line('entity_mte') . ' , ';
            } else {
                $mte = '';
            }
            ?>
			<li  id="co_level" abbr="analysis" <?php if ($class_name == 'tier1_first_year_co_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_first_year_co_attainment/') ?>"><i class="icon-chevron-right"></i> First Year CO Attainment (<?php echo $this->lang->line('entity_cie'); ?>)</a></li>
            <li  id="course_clo_attainment" abbr="analysis" <?php if ($class_name == 'course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/course_clo_attainment/') ?>"><i class="icon-chevron-right"></i>Course - CO Attainment (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
            <li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'po_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/po_level_attainment/') ?>"><i class="icon-chevron-right"></i>PO Attainment</a></li>
            <li  id="crs_po_attain_mtrx" abbr="analysis" <?php if ($class_name == 'course_po_attainment_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/course_po_attainment_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - <?php echo $this->lang->line('so'); ?> & <?php echo $this->lang->line('entity_pso'); ?> Attainment</a></li>
			<li  id="po_level_attainment" abbr="analysis" title="Current Academic Year <?php echo $this->lang->line('so'); ?> Attainment." <?php if ($class_name == 'tier1_po_attainment_academic_year_wise') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/tier1_po_attainment_academic_year_wise/') ?>"><i class="icon-chevron-right"></i>CAY <?php echo $this->lang->line('so'); ?> Attainment <br/></a></li>
            <li  id="po_level_attainment" abbr="analysis" <?php if ($class_name == 'tier2_po_bacth_wise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/tier2_po_bacth_wise_attainment/') ?>"><i class="icon-chevron-right"></i>Individual <?php echo $this->lang->line('so'); ?> Attainment <br/> (Within a Batch)</a></li>
            
    <!-- <li  id="peo_level_attainment" abbr="analysis" <?php // if ($class_name == 'peo_level_attainment') echo 'class="active"';     ?> ><a href="<?php // echo base_url('tier_ii/peo_level_attainment/')     ?>"><i class="icon-chevron-right"></i>PEO Attainment</a></li> -->
    <!-- <li  id="ga_level_attainment" abbr="analysis" <?php if ($class_name == 'ga_level_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/ga_level_attainment/') ?>"><i class="icon-chevron-right"></i>GA Attainment</a></li>
    <li  id="ga_level_yearwise_attainment" abbr="analysis" <?php if ($class_name == 'ga_level_yearwise_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/ga_level_yearwise_attainment/') ?>"><i class="icon-chevron-right"></i>GA Attainment (Year wise)</a></li> -->
        <?php } ?>

<!--<li id="report_course_delivery" abbr="analysis" <?php // if($class_name=='co_report') echo 'class="active"';     ?> ><a href="<?php // echo base_url('assessment_attainment/co_report/')      ?>"><i class="icon-chevron-right"></i> Overall CO Attainment </a></li>
<li id="report_course_delivery" abbr="analysis" <?php // if($class_name=='po_report') echo 'class="active"';    ?> ><a href="<?php // echo base_url('assessment_attainment/po_report/')     ?>"><i class="icon-chevron-right"></i> Overall PO Attainment </a></li> -->

        <li  id="data_series" abbr="analysis" <?php if ($class_name == 'data_series') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/data_series/') ?>"><i class="icon-chevron-right"></i> Data Analysis (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>
        <li  id="student_attainment" abbr="analysis" <?php if ($class_name == 'student_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/student_attainment/') ?>"><i class="icon-chevron-right"></i> Student CO Attainment</a></li>
        <!-- <li  id="stud_po_level_attainment" abbr="analysis" <?php if ($class_name == 'stud_po_level_assessment_data') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/stud_po_level_assessment_data/') ?>"><i class="icon-chevron-right"></i>Student <?php echo $this->lang->line('so'); ?> Attainment</a></li> -->
      

	  <li  id="student_threshold" abbr="analysis" <?php if ($class_name == 'student_threshold') echo 'class="active"'; ?> ><a href="<?php echo base_url('assessment_attainment/student_threshold/') ?>"><i class="icon-chevron-right"></i>Student Improvement Plan <br/>(<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte; ?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>	
                <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('CoE')) { ?>
            <!-- <li  id="student_marks_upload" abbr="analysis" <?php // if ($class_name == 'student_marks_upload') echo 'class="active"';     ?> ><a href="<?php // echo base_url('scheduler/student_marks_upload/')     ?>"><i class="icon-chevron-right"></i>CoE Bulk Import <br/><?php // echo $this->lang->line('entity_see');     ?> Marks</a></li> -->
        <?php } ?>
        <?php echo str_repeat('<br \>', 7); ?>
    </ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js'); ?>"></script>	  

