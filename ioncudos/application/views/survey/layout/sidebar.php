<?php 
    $className = $this->router->fetch_class(); 
    $methodName=$this->router->fetch_method();
    $stakeholderG=array('index','add_stakeholder_group_type','edit_stakeholder_group_type');
 ?>
<div class="span2 bs-docs-sidebar">
    <ul class="nav nav-list bs-docs-sidenav">
        <?php if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')) : ?>
            <li abbr="config" <?php if ($className == 'dashboard') echo 'class="active"'; ?>  id="config_org" >
                <a href="<?php echo base_url('survey/dashboard') ?>">
                    <i class="icon-chevron-right"></i> Survey Dashboard 
                </a>
            </li>
            <?php if ($this->ion_auth->in_group('admin')){ ?>
            <li abbr="config" <?php if ($className == 'questions') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/questions') ?>">
                    <i class="icon-chevron-right"></i> Manage Survey <br/>Question Type 
                </a>
            </li>
            <li abbr="config" <?php if ($className == 'answer_templates') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/answer_templates') ?>">
                    <i class="icon-chevron-right"></i> Manage Response Templates 
                </a>
            </li>
            <li abbr="config" <?php if ($className == 'stakeholders' && in_array($methodName,$stakeholderG)) echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/stakeholders') ?>">
                    <i class="icon-chevron-right"></i> Manage Stakeholder Group 
                </a>
            </li>
            <?php } if ( $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
				<!-- <li abbr="config" <?php if ($className == 'import_student_data' || $className == 'import_student_data_excel') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('curriculum/student_list') ?>">
                    <i class="icon-chevron-right"></i> Manage Student Stakeholder 
                </a>
				</li> -->
				<li abbr="config" <?php if ($className == 'stakeholders' && !in_array($methodName,$stakeholderG)) echo 'class="active"'; ?> id="config_pgmtype">
					<a href="<?php echo base_url('survey/stakeholders/stakeholder_list') ?>">
						<i class="icon-chevron-right"></i> Manage Stakeholder 
					</a>
				</li>
			<?php } //if ( $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
            <li abbr="config" <?php if ($className == 'templates') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/templates') ?>">
                    <i class="icon-chevron-right"></i> Manage Survey Templates 
                </a>
            </li>
			<?php //} ?>
            <li abbr="config" <?php if ($className =='surveys' && $methodName!='view_survey' && $methodName!='indirect_attainment_report') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/surveys') ?>">
                    <i class="icon-chevron-right"></i> Create Survey
                </a>
            </li>
			<li abbr="config" <?php if ($className =='host_survey') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/host_survey') ?>">
                    <i class="icon-chevron-right"></i> Host Survey
                </a>
            </li>
			
            <li abbr="config" <?php if ($className =='reports' && $methodName=='hostedSurvey' || $className =='surveys' && $methodName=='view_survey' || $methodName =='indirect_attainment_report') echo 'class="active"'; ?> id="config_pgmtype">
                <a href="<?php echo base_url('survey/reports/hostedSurvey') ?>">
                    <i class="icon-chevron-right"></i> Survey Report
                </a>
            </li>
			
			<?php echo str_repeat('<br>', 6); ?>

            
         <?php endif ?>
    </ul>
</div>