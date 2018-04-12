<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Permission Add view Page.	  
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                    Mritunjay B S                           Added file headers, function headers & comments.
 * 03-09-2013                    Mritunajy B S                           Changed Function name and Variable names. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container fixed-height">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
                    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <a class="brand-custom" name= "course_add_title_heading" id="course_add_title_heading" type="text" style="text-decoration: none"> Add Permissions</a>
                                </div>
                            </div>
                            <br/>
                            <form class="form-horizontal" method="POST" id="add_curr" action="<?php echo base_url('configuration/permissions/add_permission'); ?>" name="frm" id="add_crclm">
                                <div class="control-group ">
                                    <p class="control-label inline" for="inputSpecialization">Function<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_input($permission_function); ?> 
                                    </div>
                                </div>
                                <div class="control-group ">
                                    <p class="control-label inline" for="inputSpecialization">Description<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_textarea($description); ?> 
                                    </div>
                                </div>
                                <br>
                                <div class="pull-right">       
                                    <button class="submit1 btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <a href= "<?php echo base_url('configuration/permissions'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span>Cancel</b></a>
                                </div>
                                <br><br><br>
                            </form>
                            <!--Do not place contents below this line-->	
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
        <script type="text/javascript" >
            $(document).ready(function() {
    /*
     * Function is to validate the permission add form. It checks against special chracters(# @ ' "  . - etc).
     * @param - -----------.
     * returns the error message if any of the special characters appears.
     */
                $.validator.addMethod("loginRegex", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\_\-\s\.\']+$/i.test(value);
                }, "Field must contain only letters, spaces, ' or dashes or dot");

                $("#add_curr").validate({
                    rules: {
                        permission_function: {
                            loginRegex: true,
                        },
                        description: {
                            loginRegex: true,
                        },
                    },
                    errorClass: "help-inline",
                    errorElement: "span",
                    highlight: function(element, errorClass, validClass) {
                        $(element).parent().parent().addClass('error');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parent().parent().removeClass('error');
                        $(element).parent().parent().addClass('success');
                    }
                });
            });

        </script>
    </body>
</html>