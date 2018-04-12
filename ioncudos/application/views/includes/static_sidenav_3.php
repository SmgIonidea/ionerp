<?php
/**
* Description	:	Generic Side Navbar file of the main menu Reports Tab contains 
*					static (read only) side menu tabs like PO to PEO Mapped Report, 
*					CLO to PO Mapped Report (Coursewise) etc ).
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
--------------------------------------------------------------------------------------
*/
?>
<?php  
		$class_name_folder = $this->uri->segment(1);
		$class_name = $this->router->fetch_class(); 
		$class_name_one = $this->uri->segment(3);
		$tab = $class_name.'/'.$class_name_one;
		?>
<div class="span2 bs-docs-sidebar">
	<ul class="nav nav-list bs-docs-sidenav">
		<li abbr = "repo_hist" <?php if($tab=='po_peo_map_report/report_static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/po_peo_map_report/report_static_index') ?>"><i class="icon-chevron-right"></i>PO to PEO Mapped Report</a></li>
		<li abbr="repo_hist" <?php if($tab=='clo_po_map_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/clo_po_map_report/static_index') ?>"><i class="icon-chevron-right"></i>CO to PO Mapped Report (Coursewise)</a></li>
		<li abbr="repo_hist" <?php if($tab=='crs_articulation_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/crs_articulation_report/static_index') ?>"><i class="icon-chevron-right"></i>Program Articulation Matrix Report (Termwise)</a></li>
		<li abbr="repo_hist" <?php if($tab=='crs_domain_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/crs_domain_report/static_index') ?>"><i class="icon-chevron-right"></i>Course Stream Report////</a></li>
		<li abbr="repo_hist" <?php if($tab=='tlo_clo_map_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/tlo_clo_map_report/static_index') ?>"><i class="icon-chevron-right"></i><?php echo $this->lang->line('entity_tlo'); ?> to CO Mapped Report (<?php echo $this->lang->line('entity_topic'); ?>wise)</a></li>
		<li abbr="repo_hist" <?php if($tab=='unmapped_po_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/unmapped_po_report/static_index') ?>"><i class="icon-chevron-right"></i>Unmapped PO Report</a></li>
		<li abbr="repo_hist" <?php if($tab=='unmapped_pi_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/unmapped_pi_report/static_index') ?>"><i class="icon-chevron-right"></i>Unmapped PI Report</a></li>
		<li abbr="repo_hist" <?php if($tab=='unmapped_msr_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/unmapped_msr_report/static_index') ?>"><i class="icon-chevron-right"></i>Unmapped Measures Report</a></li>
		<li abbr="repo_hist" <?php if($tab=='transpose_report/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/transpose_report/static_index') ?>"><i class="icon-chevron-right"></i> Transpose of Program Articulation Matrix </a></li>
		<li abbr="repo_hist" <?php if($tab=='lesson_plan/static_index') echo 'class="active"';?> ><a href="<?php echo base_url('report/lesson_plan/static_index') ?>"><i class="icon-chevron-right"></i> Lesson Plan </a></li>
	</ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js');?>"></script>	


