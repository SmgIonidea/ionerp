<?php
/**
 * Description      :	Generic Side Navbar for NBA				
 * Created		:	14-06-2016
 * Modification History:
 * Date				Modified By				Description
   28-12-2016	  Arihant Prasad		Code cleanup, NBA report section included
  ---------------------------------------------------------------------------------- */
$val = $this->ion_auth->user()->row();
$org_type = $val->org_name->org_type;
$class_name = $this->router->fetch_class();
?>

<div class="span2 bs-docs-sidebar">
    <ul class="nav nav-list bs-docs-sidenav">
        <?php if (!$this->ion_auth->in_group('Student')) { ?>
			<li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'faculty_contribution') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('report/edit_profile/index') ?>"><i class="icon-chevron-right"></i> Faculty Contribution </a>
            </li>
		
            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'companies_list') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/companies_list') ?>"><i class="icon-chevron-right"></i> <?php echo $this->lang->line('industry_sing'); ?> List </a>
            </li>
            
            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'univ_colg_list') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/univ_colg_list') ?>"><i class="icon-chevron-right"></i> University / College List </a>
            </li>
            
            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'prof_societies_chapter') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/prof_societies_chapter') ?>"><i class="icon-chevron-right"></i> Professional Societies / Chapter </a>
            </li>
            
            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'publ_tech_magazine') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/publ_tech_magazine') ?>"><i class="icon-chevron-right"></i> Publication of Technical Magazines / Newsletter </a>
            </li>

            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'placement') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/placement') ?>"><i class="icon-chevron-right"></i> Placement </a>
            </li>
            
            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'internship_training') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/internship_training') ?>"><i class="icon-chevron-right"></i> Internship / Summer Training </a>
            </li>

            <li id="report_po_peo" abbr="nba_sar" <?php if ($class_name == 'curriculum_student_info') echo 'class="active"'; ?> >
                <a href="<?php echo base_url('nba_sar/curriculum_student_info') ?>"><i class="icon-chevron-right"></i> Student Performance </a>
            </li>

            <li id="report_pgm_art" abbr="nba_sar" <?php if ($class_name == 'publications_awards') echo 'class="active"'; ?>>
                <a href="<?php echo base_url('nba_sar/publications_awards') ?>"><i class="icon-chevron-right"></i> Publication & Awards </a>
            </li>

            <li id="report_pgm_art" abbr="nba_sar" <?php if ($class_name == 'facilities_and_technical_support') echo 'class="active"'; ?>>
                <a href="<?php echo base_url('nba_sar/facilities_and_technical_support') ?>"><i class="icon-chevron-right"></i> Facilities & Technical Support </a>
            </li>
			
			<li id="report_pgm_art" abbr="nba_sar" <?php if ($class_name == 'nba_list') echo 'class="active"'; ?>>
                <a href="<?php echo base_url('nba_sar/nba_list') ?>"><i class="icon-chevron-right"></i> NBA-SAR Report </a>
            </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap\js\custom\tab.js'); ?>"></script>
