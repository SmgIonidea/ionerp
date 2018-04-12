<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Permission List Static view Page.	  
* Modification History:
* Date				Modified By				Description
* 20-08-2013                    Mritunjay B S                           Added file headers, function headers & comments.
* 03-09-2013                    Mritunajy B S                           Changed Function name and Variable names. 
---------------------------------------------------------------------------------------------------------------------------------
*/
?>

    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>

        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_1'); ?>
                <div class="span10">
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Permission List
                                </div>
                            </div>	
                            <br>
                            <br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Function</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Description(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($permission_list as $permission): ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $permission['permission_function']; ?> </td>
                                                <td><?php echo $permission['description']; ?> </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <br><br>
                            </div>
                            <br>
                            <br><br><br><br><br><br><br><br> 
                            <!--data table closed-->
                        </div><!--bs ex closed-->
                    </section>
                </div><!--span closed-->
            </div> <!--row fuild closed-->
        </div><!--cont fuild closed-->
        <?php $this->load->view('includes/footer'); ?>
        <?php $this->load->view('includes/js'); ?> 
    </body>
</html>