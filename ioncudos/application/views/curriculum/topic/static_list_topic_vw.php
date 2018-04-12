
<!DOCTYPE html>
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                     Topic List 
                                </div>
                            </div><br>
                            <form class="form-inline">
                                <fieldset>
                                    <!-- Form Name -->
                                    <!-- Select Basic -->
                                    <div class="control-group ">
                                        <label class="control-label">Curriculum<font color="red">*</font>:</label>
                                        <select id="curriculum" name="curriculum" class="input-xlarge span3" onchange="select_term();" onLoad="select_term();">
                                            <option value="">Curriculum</option>
                                            <?php foreach ($crclm_name_data as $listitem): ?>
                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="control-label">Term<font color="red">*</font>:</label>
                                        <select id="term" name="term" class="input-xlarge span2" onchange="select_course();">
                                            <option>Terms</option>
                                        </select> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label class="control-label">Course<font color="red">*</font>:</label>
                                        <select id="course" name="course" class="input-xlarge span3" onchange="GetSelectedValue();">
                                            <option>Course</option>
                                        </select>
                                    </div>
                                    <!-- Select Basic -->
                                </fieldset>
                            </form>
                            <div class="row">                  
                            </div>
                            <div>
                                </br>
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic'); ?>(s)</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic'); ?> Content</th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    </tbody>
                                </table>
                            </div>
                            </br>
                            </br>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
</body>
 <script type="text/javascript" src ="<?php echo base_url('twitterbootstrap/js/custom/curriculum/static_topic.js'); ?> "> </script>
</html>