<?php
/**
 *
 * Description	:	Displaying the list of users for the guest user. Guest user cannot perform
					any operation such as add or edit.
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 02-09-2013		   Arihant Prasad			File header, indentation, comments and variable 
												naming.
 *
  ------------------------------------------------------------------------------------------------- */
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
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Users List
                                </div>
                            </div>
							
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >First Name</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Last Name</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Department</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Email</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Qualification</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Experience</th>
                                        </tr>
                                    </thead>

                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($users as $user): ?>
                                            <tr class="gradeU even">
                                                <td class="  sorting_1"><?php echo $user->title.' '.$user->first_name; ?></td>
                                                <td><?php echo $user->last_name; ?></td>
                                                <td>
                                                    <?php foreach ($user->dept_name as $dept_name): ?>
                                                        <?php echo $dept_name['dept_name']; ?>
                                                    <?php endforeach ?>
                                                </td>
                                                <td><?php echo $user->email; ?></td>
                                                <td><?php echo $user->user_qualification; ?></td>
                                                <td><?php echo $user->user_experience; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table><br/><br/>
                            </div><br><br>	
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

<!-- End of file static_list_user_vw.php
        Location: .configuration/users/static_list_user_vw.php -->