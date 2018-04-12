<?php
/*-----------------------------------------------------------------------------------------------------------------------------
 * Description	: Static View for Department.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013                   Mritunjay B S                   	Added file headers, function headers & comments. 
 --------------------------------------------------------------------------------------------------------------------------
 */
?>
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
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Department List
                                </div>
                            </div>
                            <br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown span2" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Name </th>
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Acronym </th>
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Head of Department </th>
											<th class="header span1" role="columnheader" tabindex="0" aria-controls="example"> Professional Bodies</th>	
                                            <th class="header span2" role="columnheader" tabindex="0" aria-controls="example"> Offer Program </th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php
                                        foreach ($result1 as $data_value):
                                            ?>
                                            <tr class="gradeU even">
                                                <td title ="<?php echo $data_value['dept_description']; ?>" class=" cursor_pointer sorting_1 table-left-align"><?php echo $data_value['dept_name']; ?> </td>
                                                <td class="sorting_1"><?php echo $data_value['dept_acronym']; ?> </td>
                                                <td class="sorting_1"><?php echo $data_value['first_name'] . " " . $data_value['last_name']; ?> </td>
												<td class="sorting_1"><?php echo $data_value['professional_bodies']; ?> </td>
                                                <?php
                                                if ($data_value['ispgm'] != 0) {
                                                    ?>
                                                    <td><a  data-toggle="modal" href="#myModal" id="<?php echo $data_value['dept_id']; ?>" class="get_programes_dept1" style="text-decoration: underline;"><i class="icon-filter icon-black"></i> Program(s)</a></td>
                                                <?php } else { ?>
                                                    <td>N\A</td>
                                                <?php } ?>
                                                <!-- Status -->
                                                <!-- Status -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <!--Do not place contents below this line-->
                            </div>
                            <br>
                            <br>
                        </div>
                    </section>
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                List of Programs offered by the Department.
                            </div>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-hover">
                                <thead align = "center">
                                    <tr align = "center">
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Duration(in years)</th>
                                    </tr>
                                </thead>
                                <tbody id="div111">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Enable Confirmation 
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to enable?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary enable_dept btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Disable Confirmation
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to disable?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary disable_dept btn " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>
                    <!--################### Modal #####################-->
                    <div id="Cntdisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Disable Failure 
                            </div>
                        </div>
                        <div class="modal-body" id="comment">
                            <p> You cannot disable the Department, as there are Programs being running under this Department. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?>
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>



<script language="javascript">
    var data_val;
    function getdeptid(id)
    {
        data_val = id;
    }
    $('.get_id').click(function(e)
    {
        data_val = $(this).attr("id");
    });
    $(document).ready(function() {
        $('.enable_dept').click(function(e) {
            e.preventDefault();
            var post_data = {
                'dept_id': data_val,
                'status': '1',
            }
            $.ajax({type: "POST",
                url: "<?php echo base_url('configuration/add_department/department_delete'); ?>",
                data: post_data,
                datatype: "JSON",
                success: function(msg) {
                    $('#data_val').removeClass('icon-ok-circle').addClass('icon-ban-circle');
                    location.reload();
                }
            });
        });
        $('.disable_dept').click(function(e) {
            e.preventDefault();
            var post_data = {
                'dept_id': data_val,
                'status': '0',
            }
            $.ajax({type: "POST",
                url: "<?php echo base_url('configuration/add_department/check_for_pgm'); ?>",
                data: post_data,
                success: function(msg) {
                    if ($.trim(msg) == 'valid')
                    {
                        $.ajax({type: "POST",
                            url: "<?php echo base_url('configuration/add_department/department_delete'); ?>",
                            data: post_data,
                            datatype: "JSON",
                            success: function(msg) {
                                location.reload();
                            }
                        });
                    }
                    //---------------------------------------------------------------------------------------------
                    else
                    {
                        $('#Cntdisable').modal('show');
                        document.getElementById('comment').innerHTML;
                    }
                }
            });
        });

        $('.edit_dept').click(function(e) {
            e.preventDefault();
            var data_val1 = $(this).attr('id').replace('edit', '');
            var post_data = {
                'dept_id1': data_val1
            }
            $.ajax({type: "POST",
                url: "<?php echo base_url('configuration/add_department/deparment_edit'); ?>",
                data: post_data,
                datatype: "JSON",
                success: function(msg) {
                }
            });
        });
 // This is the ajax call to load the related pgm of the department.
    });
    $('.get_programes_dept1').click(function(e) {
        e.preventDefault();
        var data_val = $(this).attr('id');
        var post_data = {
            'dept_id': data_val,
        }
        $.ajax({type: "POST",
            url: "<?php echo base_url('configuration/add_department/search_for_department_program'); ?>",
            data: post_data,
            datatype: "JSON",
            success: function(msg) {
				
                document.getElementById('div111').innerHTML = msg;
            }
        });
    });

</script>
