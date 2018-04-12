<?php
/**
* Description	:	Generic Side Navbar file of the main menu Reports Tab contains 
*					side menu tabs like PO to PEO Mapped Report, CLO to PO Mapped Report (Coursewise) etc ).
*					
*Created		:	24-07-2014. 
*		  
* Modification History:
* Date							Modified By					Description
*
* 24-07-2014					Abhinay B.Angadi        	Added file headers & indentations.   
* 14-12-2017					Abhinay B.Angadi            Included new side menu and Label changes & indentations.   
---------------------------------------------------------------------------------------------------------------
*/
$val =  $this->ion_auth->user()->row();
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
			<?php if( $this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || 
						$this->ion_auth->in_group('Program Owner'))  { ?>
				<li id="crclm_ext" abbr="qp" <?php if($class_name=='Extra_curricular_activities') echo 'class="active"'; ?>  ><a href="<?php echo base_url('Extra_curricular_activities/Extra_curricular_activities/index') ?>"><i class="icon-chevron-right"></i>Extracurricular / Co-curricular Activity</a></li>
				<li id="qp_framework" abbr="qp" <?php if($class_name=='manage_qp_framework') echo 'class="active"';?> ><a href="<?php echo 	base_url('question_paper/manage_qp_framework') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Framework (Program-wise)</a></li>
			<?php } ?>
		<li  id="manage_cia" abbr="qp" <?php if($class_name=='cia') echo 'class="active"';?> ><a href="<?php echo base_url('assessment_attainment/cia/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_cie'); ?> Occasion</a></li>
		<li  id="manage_cia_qp" abbr="qp" <?php if($class_name=='manage_cia_qp' || $class_name=='cia_rubrics') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_cia_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_cie'); ?> Question Paper & Rubrics</a></li>
			<?php if($mte_flag == 1) { ?>
				<li  id="mte_qp" abbr="qp" <?php if($class_name=='manage_mte_qp' || $class_name=='upload_mte_question_papers') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_mte_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_mte'); ?> Question Paper</a></li>
			<?php } ?>
			<?php if( $org_type != 'TIER-II') { ?>	
				<li  id="model_qp" abbr="qp" <?php if($class_name=='manage_model_qp') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/manage_model_qp/') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Model Question Paper</a></li>
			<?php } ?>
		<li  id="tee_qp" abbr="qp" <?php if($class_name=='tee_qp_list' || $class_name=='upload_question_papers') echo 'class="active"';?> ><a href="<?php echo base_url('question_paper/tee_qp_list') ?>"><i class="icon-chevron-right"></i> Manage <?php echo $this->lang->line('entity_see'); ?> Question Paper</a></li>
		 <!--<li id="crclm_lab" abbr="qp" <?php  if($class_name=='upload_question_papers') echo 'class="active"'; ?>  ><a href="<?php echo base_url('assessment_attainment/upload_question_papers/index') ?>"><i class="icon-chevron-right"></i>Upload Question Paper</a></li>-->
		<?php echo str_repeat('<br>', 15); ?>
                
	</ul>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js');?>"></script>	  
	  
