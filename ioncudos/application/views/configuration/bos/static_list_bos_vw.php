<?php
/**
* Description	: BoS grid provides the static(read only) list of BoS Users along with their
				  respective department, email id.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 23-08-2013		Abhinay B.Angadi        Added file headers, indentations.
* 30-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
*											Code cleaning.
--------------------------------------------------------------------------------
*/
?>
<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
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
                <?php $this->load->view('includes/static_sidenav_1'); ?>
                <div class="span10">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Board of Studies(BoS) Members List
                                </div>
                            </div>
                            <br />
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >First Name</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Last Name</th>
											<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Designation</th>
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
												<td><?php echo $user->designation_name; ?></td>
                                                <td>
                                                    <?php echo $user->dept_name; ?>
                                                </td>
                                                <td><?php echo $user->email; ?></td>
                                                <td><?php echo $user->user_qualification; ?></td>
                                                <td><?php echo $user->user_experience; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
								<!-- Modal -->
                                <br />
                                <br />
                            </div>
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