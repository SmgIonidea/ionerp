<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: 
 * Modification History:
 * Date				Modified By				Description
 * 02-02-2015		Jyoti       	Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <?php $class_name = $this->router->fetch_class();
            ?>
            <!-- Contents -->
            <section id="contents">
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Curriculum Setting 
                        </div>
                    </div>
                    <div class="tabbable" style="height:430px"> 
                        <ul class="nav nav-tabs">                                
                            <li id="crclm_import_user" abbr="tab_crclm" <?php if ($class_name == 'import_user') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/import_user') ?>"> Import User  </a></li>
                            <li id="crclm_dm" abbr="tab_crclm" <?php if ($class_name == 'curriculum_delivery_method') echo 'class="active"'; ?>  ><a href="<?php echo base_url('curriculum/curriculum_delivery_method') ?>"> Curriculum Delivery Method </a></li>
                            <li id="crclm_crsdmn" abbr="tab_crclm" <?php if ($class_name == 'course_domain') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/course_domain') ?>">Course Domain </a></li>
                            <li id="assessment_method" abbr="assessment_method" <?php if ($class_name == 'assessment_method') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/assessment_method') ?>">Assessment Method </a></li>
                            <li id="crclm_student_list" abbr="tab_crclm" <?php if ($class_name == 'student_list') echo 'class="active"'; ?> ><a href="<?php echo base_url('curriculum/student_list') ?>">Import Student List</a></li>
                        </ul>

                        <b><font color="">&nbsp;&nbsp;&nbsp;&nbsp;Note : Select Curriculum Setting Tab </font></b>
                    </div>

                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>