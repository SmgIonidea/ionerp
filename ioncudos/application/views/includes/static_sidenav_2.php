<?php
/**
* Description	:	Generic Side Navbar file of the main menu Curriculum Tab contains 
*					static (read only) side menu tabs like Curriculum, PEO, PO, PO to PEO Mapping etc ).
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
----------------------------------------------------------------------------------------------------------
*/
?>
<?php  
		$class_name_folder = $this->uri->segment(1);
		$class_name = $this->uri->segment(2); 
		$class_name_one = $this->uri->segment(3);
		$tab = $class_name_folder.'/'.$class_name.'/'.$class_name_one;
		?>
<div class="span2 bs-docs-sidebar fixed-height">
    <ul class="nav nav-list bs-docs-sidenav">
		<li abbr="crclm_hist" <?php if($tab=='curriculum/publish_curriculum/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/publish_curriculum/static_index') ?>"><i class="icon-chevron-right"></i> Publish Curriculum </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/curriculum/curriculum_static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/curriculum/curriculum_static_index') ?>"><i class="icon-chevron-right"></i> Curriculum </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/peo/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/peo/static_index') ?>"><i class="icon-chevron-right"></i> PEO </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/po/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/po/static_index') ?>"><i class="icon-chevron-right"></i> PO </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/map_po_peo/static_page_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/map_po_peo/static_page_index') ?>"><i class="icon-chevron-right"></i> PO to PEO Mapping </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/pi_and_measures/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/pi_and_measures/static_index') ?>"><i class="icon-chevron-right"></i> PI & Measures</a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/course_domain/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/course_domain/static_index') ?>"><i class="icon-chevron-right"></i> Course Domain </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/course/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/course/static_index') ?>"><i class="icon-chevron-right"></i> Course </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/clo/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/clo/static_index') ?>"><i class="icon-chevron-right"></i> CLO </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/clo_po_map/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/clo_po_map/static_index') ?>"><i class="icon-chevron-right"></i> CLO to PO Mapping (Coursewise) </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/clo_po/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/clo_po/static_index') ?>"><i class="icon-chevron-right"></i> CLO to PO Mapping (Termwise) </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/topic/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/topic/static_index') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_topic'); ?></a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/tlo_list/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/tlo_list/static_index') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_tlo_singular'); ?> </a></li>
		<li abbr="crclm_hist" <?php if($tab=='curriculum/tlo_clo_map/static_map_tlo_clo') echo 'class="active"';?> ><a href="<?php echo base_url('curriculum/tlo_clo_map/static_map_tlo_clo') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('entity_tlo'); ?> to CO Mapping (<?php echo $this->lang->line('entity_topic'); ?>wise) </a></li>     
	</ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js');?>"></script>	
	