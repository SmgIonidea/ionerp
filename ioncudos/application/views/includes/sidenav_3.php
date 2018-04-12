<?php
/**
 * Description          :	Generic Side Navbar file of the main menu Reports Tab contains 
 * 					side menu tabs like SO to PEO Mapped Report, CLO to SO Mapped Report (Coursewise) etc ).
 * 					
 * Created		:	25-03-2013. 
 * 		  
 * Modification History :
 * Date							Modified By							Description
 * 20-08-2013					Abhinay B.Angadi                      Added file headers & indentations.   
 * 14-12-2017					Abhinay B.Angadi                      Included new side menu and Label changes & indentations.   
  -------------------------------------------------------------------------------------------------------------------------------------
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
        <?php if (!$this->ion_auth->in_group('Student')) : ?>

            <!-- <li id="pgm_articulation_report" abbr="report" <?php // if ($class_name == 'pgm_articulation_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/pgm_articulation_report')  ?>"><i class="icon-chevron-right"></i> New Program Articulation Matrix Report </a></li> -->

            <li id="report_faculty_history" abbr="report" <?php  if ($class_name == 'faculty_history') echo 'class="active"'; ?> ><a href="<?php  echo base_url('report/faculty_history') ?>"><i class="icon-chevron-right"></i> Faculty Profile </a></li> 
            <li id="curriculum_details" abbr="report" <?php if ($class_name == 'curriculum_details') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum_details/curriculum_details') ?>"><i class="icon-chevron-right"></i> Department / Curriculum Report </a></li>
            <li id="mapping_report" abbr="report" <?php if ($class_name == 'mapping_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/mapping_report') ?>"><i class="icon-chevron-right"></i> Mapping Report</a></li>

    <!-- <li id="report_po_peo" abbr="report" <?php // if ($class_name == 'po_peo_map_report') echo 'class="active"';  ?> ><a href="<?php // echo base_url('report/po_peo_map_report')  ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('so');  ?> to PEO Mapped Report </a></li> -->

            <li id="report_pgm_art" abbr="report" <?php if ($class_name == 'program_articulation_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/program_articulation_matrix') ?>"><i class="icon-chevron-right"></i> Program Articulation Matrix Report (Term-wise) </a></li> 
            <li id="report_pgm_art" abbr="report" <?php if ($class_name == 'co_po_matrix') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/co_po_matrix') ?>"><i class="icon-chevron-right"></i> Program Level Course - PO Matrix </a></li>
            <li id="report_trn_pgm_art" abbr="report" <?php if ($class_name == 'transpose_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/transpose_report') ?>"><i class="icon-chevron-right"></i> Transpose of Program Articulation Matrix</a></li>
            <li id="report_crs_strm" abbr="report" <?php if ($class_name == 'crs_domain_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/crs_domain_report') ?>"><i class="icon-chevron-right"></i> Course Domain (Vertical) Report </a></li>
            <li id="course_report" abbr="report" <?php if ($class_name == 'course_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_report') ?>"><i class="icon-chevron-right"></i> Term-wise Course Report </a></li>
        <?php endif ?>
        <li id="report_course_delivery" abbr="report" <?php if ($class_name == 'course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/course_delivery_report') ?>"><i class="icon-chevron-right"></i><b><font color="maroon"> Theory Lesson Plan (Course-wise) Report </font></b></a></li>
        <li id="lab_report_course_delivery" abbr="report" <?php if ($class_name == 'lab_course_delivery_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/lab_course_delivery_report') ?>"><i class="icon-chevron-right"></i> Lab Lesson Plan (Course-wise) Report </a></li>
        <li id="text_reference_book_list" abbr="report" <?php if ($class_name == 'text_reference_book_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/text_reference_book_list') ?>"><i class="icon-chevron-right"></i> Text / Reference Book List </a></li>
        <?php if (!$this->ion_auth->in_group('Student')) : ?>

                            <!-- <li  id="report_lesson" abbr="report" <?php // if($class_name=='lesson_plan') echo 'class="active"';   ?> ><a href="<?php //  echo base_url('report/lesson_plan')    ?>"><i class="icon-chevron-right"></i> Partwise Lesson Plan </a></li> -->

                <!-- <li id="report_clo_po" abbr="report" <?php // if($class_name=='clo_po_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/clo_po_map_report')    ?>"><i class="icon-chevron-right"></i> CO to <?php // echo $this->lang->line('so');    ?> Mapped Report (Course wise) </a></li> -->

    <!-- <li id="report_tlo_clo" abbr="report" <?php // if($class_name=='tlo_clo_map_report') echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/tlo_clo_map_report')    ?>"><i class="icon-chevron-right"></i> <?php // echo $this->lang->line('entity_tlo');    ?> to CO Mapped Report (<?php // echo $this->lang->line('entity_topic_singular');    ?> wise) </a></li> -->
	<?php if($mte_flag == 1){ $mte = $this->lang->line('entity_mte').' , '; }else{ $mte = '';}?>
			<!--<li  id="course_clo_attainment" abbr="analysis" <?php if ($class_name == 'course_clo_attainment') echo 'class="active"'; ?> ><a href="<?php echo base_url('tier_ii/course_clo_attainment/') ?>"><i class="icon-chevron-right"></i>Course - CO Attainment (<?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?>)</a></li>-->
            <li id="internal_final_exam" abbr="report" <?php if ($class_name == 'internal_final_exam') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/internal_final_exam') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_cie'); ?> , <?php echo $mte ;?> <?php echo $this->lang->line('entity_see'); ?> Question Paper Report Course-wise</a></li>
			
			
            <li id="report_ao_clo" abbr="report" <?php if ($class_name == 'ao_clo_map_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/ao_clo_map_report') ?>"><i class="icon-chevron-right"></i> AO to CO Mapping Report Course-wise </a></li>
            <li id="report_un_po" abbr="report" <?php if ($class_name == 'unmapped_po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_po_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('so'); ?> Report </a></li>

            <!-- OE & PI If Mandatory : Reports will be visible (1 means mandatory) -->
            <?php if ($oe_pi_flag == 1) { ?>
                <li id="report_po" abbr="report" <?php if ($class_name == 'po_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/po_report') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('sos') . ' , ' . $this->lang->line('outcome_element_plu_full') . ' & ' . $this->lang->line('measures_short'); ?> Report</a></li>
                <li id="report_un_pi" abbr="report" <?php if ($class_name == 'unmapped_pi_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_pi_report') ?>"><i class="icon-chevron-right"></i> Unmapped <?php echo $this->lang->line('outcome_element_sing_full'); ?> Report </a></li>
                <li id="report_un_msr" abbr="report" <?php if ($class_name == 'unmapped_msr_report') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/unmapped_msr_report') ?>"><i class="icon-chevron-right"></i> Unmapped Performance Indicator (PI) Report </a></li>
            <?php } ?>
    <!-- <li id="report_edit_profile" abbr="report" <?php // if($class_name=='edit_profile')echo 'class="active"';   ?> ><a href="<?php // echo base_url('report/edit_profile/index/');    ?>"><i class="icon-chevron-right"></i> Edit Profile </a></li> -->

            <li id="improvement_plan" abbr="report" <?php if ($class_name == 'improvement_plan') echo 'class="active"'; ?> ><a href="<?php echo base_url('report/improvement_plan') ?>"><i class="icon-chevron-right"></i> Student Improvement Plan Report </a></li>

    <!-- <li id="faculty_contri" abbr="faculty_contribution" <?php // if($class_name=='faculty_contribution') echo 'class="active"';   ?> ><a href="<?php // echo base_url('faculty_contribution/faculty_contributions')    ?>"><i class="icon-chevron-right"></i> Faculty Contribution </a></li> -->

        <?php endif ?>
    </ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js'); ?>"></script>	  

