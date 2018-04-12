<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Static Help list view Page.	  
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
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
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Help Management
                                </div>
                            </div>	
                            <br>
                            <br><br>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead align = "center">
                                        <tr class="gradeU even" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Serial No</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Page Name</th> 
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php $i = 0;
                                        foreach ($help_content as $help):
                                            ?>
                                            <tr>
                                                <td class="sorting_1 table-left-align"><?php echo++$i; ?></td>  
                                                <td class="sorting_1 table-left-align"><?php echo $help['entity_data']; ?></td>        
                                            </tr>
                                               <?php endforeach; ?>	
                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                        </div>
                        <!--Do not place contents below this line-->	
                </div>
                </section>
            </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
    <!---place js.php here -->
    <script src="<?php echo base_url('js/setup.js'); ?>" type="text/javascript"></script>

<script language="javascript">
 $("#hint a").tooltip();
    var currentID;
    var serial_no;
    var table_row;
    var id;
    function currentIDSt(id)
    {
        currentID = id;
    }

    $('.get_topic_id').live("click", function() {
        serial_no = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    function delete_data()
    {
        $.ajax({type: "POST",
            url: "<?php echo base_url('configuration/help_content/delete_data'); ?>" + "/" + currentID,
            
            success: function(msg) {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            }
        });
    }

    $('.delbutton').click(function(e) {
        e.preventDefault();
        var oTable = $('#example').dataTable();
        var row = $(this).closest("tr").get(0);
        oTable.fnDeleteRow(oTable.fnGetPosition(row));
    });

</script>


</html>