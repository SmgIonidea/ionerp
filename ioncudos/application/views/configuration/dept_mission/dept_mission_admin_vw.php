<?php
/**
 * Description	:	View for Department Mission Module.
 * Created		:	22-12-2014 
 * Modification History:
 * Date				Modified By				Description
 * 27-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
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
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <form  name="form_id" id="form_id" method="POST" >
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Department Vision/Mission
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Department <font color="red"> *</font>:
                                        <select size="1" id="dept" name="dept" autofocus = "autofocus" aria-controls="example" onChange = "select_dept_mission_vision();">
                                            <option value="Department" selected>Select Department</option>
                                            <?php foreach ($results as $listitem): ?>
                                                <option value="<?php echo $listitem['dept_id']; ?>"> <?php echo $listitem['dept_name']; ?> </option>
                                            <?php endforeach; ?>
                                        </select> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                    </label>

                                    <div id="dept_mission_vw_id" >
                                    </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--Do not place contents below this line-->
                        </section>			
                </div>					
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/dept_mission.js'); ?>" type="text/javascript"></script>