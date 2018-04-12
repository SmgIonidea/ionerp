<!DOCTYPE html>
<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Curriculum view page, provides the list of curriculums  and progress of each curriculum.	  
* Modification History:
* Date				Modified By				Description
* 25-06-2015		Jyoti					List page of Lab expt. 
---------------------------------------------------------------------------------------------------------------------------------
*/
?>
<html lang="en">
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<body data-spy="scroll" data-target=".bs-docs-sidebar">
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
<div class="row-fluid">
    <!--sidenav.php-->
<?php $this->load->view('includes/sidenav_2'); ?>
    <div class="span10">
        <!-- Contents -->
        <section id="contents">
		<div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
			</div>
            <div class="bs-docs-example">
                <!--content goes here-->
                <div class="navbar">
                    <div class="navbar-inner-custom">
						Lab Expt
					   
						<a href="#help" class="pull-right" data-toggle="modal" onclick="show_help();" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
                    </div>
                </div>				
                <div class="row">
                    <a  class="btn btn-primary pull-right"" href="<?php echo base_url('curriculum/lab_experiment/add_lab_experiment'); ?>"><i class="icon-plus-sign icon-white"></i> Add</a>
                </div>
                <br />
				<div id="loading" class="ui-widget-overlay ui-front">
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
                <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                    
                </div>
                <br/>
                <br/>
            </div>
    </div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
</body>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum.js'); ?>"></script>
</html> 