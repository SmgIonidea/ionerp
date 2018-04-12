
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<style>
/* Base class */
.bs-docs-example-one {
  position: relative;
  margin: 0 0;
  padding: 10px 20px 50px;
  *padding-top: 19px;
  background-color: rgb(245, 243, 243);
  box-shadow: 0 3px 5px 1px #979797;
  border: 1px solid #ddd;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

</style>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example-one">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                           Extra Curricular/ Co-Curricular Assessment Data Import Details
                        </div>
                    </div>

                    <!-- to display loading image when mail is being sent -->
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>

                    <table>
                        <tbody>
                            <tr>    
                                <td>
                                    <label class="cursor_default">
                                        Curriculum: <font color="blue"><?php echo @$meta_data['crclm_name']; ?></font>
                                        <?php echo str_repeat('&nbsp;', 13); ?>
                                    </label>
                                </td>
                                <td>
                                    <label class="cursor_default">
                                        Term: <font color="blue"><?php echo @$meta_data['term_name']; ?></font>
										<?php echo str_repeat('&nbsp;', 13); ?>
                                    </label>
                                </td>                  
                                <td colspan="6">
                                    <label class="cursor_default">
                                        Activity Name: <font color="blue"><?php echo @$activity_name['activity_name']; ?></font>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <div id="imprt_file_data_display" >
					<?php echo $uploaded_marks_view; ?>
				</div>
				</br>
				<div class="pull-right">
					<a class="btn btn-danger" href="<?php echo base_url('Extra_curricular_activities/Extra_curricular_activities/index'); ?>" id="close_btn"><i class="icon-remove icon-white"></i> Close</a>
				</div>
            </section>
        </div>
    </div>
</div>
<!-- Modal to display the file not uploaded yet  -->

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<!---place js.php here -->
