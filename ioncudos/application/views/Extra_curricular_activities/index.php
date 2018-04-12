<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
<link href="<?php echo base_url('twitterbootstrap/css/jsgrid.min.css'); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('twitterbootstrap/css/jsgrid-theme.min.css'); ?>" rel="stylesheet"  type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-multiselect.css'); ?>" media="screen" />

<style type="text/css">
     .outcome{
        min-height: 30px;
    }
    .width100{
        width:113px !important;
    }
/*    .outcome{
        min-height: 30px;
    }
    .width100{
        width:100% !important;
    }*/
</style>
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
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <!-- Contents-->
            <section id="contents">  
                <div id="loading" class="ui-widget-overlay ui-front">
                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                </div>
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?= $this->lang->line('extra_curricular_head') ?>
                        </div>
                    </div>                    
                    <div class="row-fluid">
                        <form action="#" method="post" name="filter_form" id="filter_form">
                            <div class="span4">
                                <label>Program:<font color="red">*</font>
                                    <?php echo form_dropdown('program', $program, '', 'id="program"'); ?>
                                </label>

                            </div>
                            <div class="span4">
                                <label>Curriculum:<font color="red">*</font>
                                    <span id='curriculum_box'>
                                        <select name="curriculum" id="curriculum">
                                            <option value="0">Select Curriculum</option>
                                        </select>
                                    </span>   
                                </label>                                                         
                            </div>
                            <div class="span4">
                                <label>Term:<font color="red">*</font>
                                    <span id='item_box'>
                                        <select name="item" id="item">
                                            <option value="0">Select Term</option>
                                        </select>
                                    </span> 
                                </label>                                  
                            </div>
                        </form>
                    </div> 
                    <br>
                    <div class="dataTables_wrapper" role="grid">                        
                        <table class="table table-bordered table-hover dataTable" id="myTable" aria-describedby="example_info" >
                            <thead >
                                <tr class="gradeU even" role="row">
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Sl No.</th>                                    
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Activity Name </th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Conduction Date </th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">
                                        <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Reference
                                    </th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Conduction Place</th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Manage Rubrics</th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">View Rubrics</th>
                                    <th class="header" role="columnheader" tabindex="0" aria-controls="myTable">Template</th>
                                </tr>
                            </thead>  
                            <tbody role="alert" aria-live="polite" aria-relevant="all">    
                            </tbody>
                        </table>
                        <br><br><br>

                    </div>	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?= $this->lang->line('add_activity') ?>
                        </div>
                    </div>	

                    <!--  Insert Assessment Method ----->
                    <div id="ao_insert_div">
                        <form  class="form-horizontal"  id="add_extra_curricular_activities_form" name="add_extra_curricular_activities_form" method="post" action="save_activity">
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="span4">
                                        <label>Activity Name:<font color="red">*</font></label>
                                    </div>
                                    <div class="span7">
                                        <input type="text" class="form-control " id="activity_name" name="activity_name">
                                    </div>
                                </div>
                                <div class="span5">
                                    <div class="span4">
                                        <label>Conduction Date:<font color="red">*</font></label>
                                    </div>
                                    <div class="span8">
                                        <div class="cal_date input-group input-prepend date ">
                                            <span class="input-group-addon add-on"><span class="icon-calendar"></span></span>
                                            <input type="text" class="form-control span12" name="conduct_date" id="conduct_date" />                                            
                                        </div>                                        
                                    </div>
                                </div>
                            </div><br>
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="span4">
                                        <label>Description:</label>
                                    </div>
                                    <div class="span8">                                        
                                        <textarea name ='activity_desc' id= 'activity_desc' rows  = '3' cols  = '50' style = "margin: 0px;"></textarea>
                                    </div>
                                </div>  
                                <div class="span5">
                                    <div class="span4">
                                        <label>Conduction Place:</label>
                                    </div>
                                    <div class="span8">
                                        <textarea name ='organised_addr' id= 'organised_addr' rows  = '3' cols  = '50' style = "margin: 0px;"></textarea>
                                    </div>
                                </div> 
                            </div><br>                            
                            <div class="pull-right"><br>
                                <input type="hidden" name="activity_id" id='activity_id' value=''>                                
                                <input type="hidden" name="program_id" id='program_id' value=''>
                                <input type="hidden" name="curriculum_id" id='curriculum_id' value=''>
                                <input type="hidden" name="item_id" id='item_id' value=''>
                                <a class="ao_method_add_form_submit btn btn-primary" id='add_extra_curricular_activities_submit' type="button"><i class="icon-file icon-white" onclick="return false"></i> Save </a>                                
                                <button class="clear_all btn btn-primary hide" type="button" id='update_extra_curricular_activities_submit'><i class="icon-file icon-white"></i> Update </button>
                                <button class="clear_all btn btn-info" type="reset" id="activity_reset"><i class="icon-refresh icon-white"></i> Reset </button>
                            </div>
                        </form>
                        <br><br>
                    </div>
                    <!--Do not place contents below this line-->

                    <div id="rubrics_modal" class="modal hide fade" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; width:1100px;left:400px;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Manage Rubrics Definition
                            </div>
                        </div>
                        <div class="modal-body bs-docs-example" >
                            <div id="loading_data" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                                <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                    <b> Program: </b>
                                    <a id="display_pgm_name" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span3">
                                    <b> Curriculum: </b>
                                    <a id="display_crclm_name" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span2">
                                    <b> Term: </b>
                                    <a id="display_term_name" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span3">
                                    <b>Activity:</b> 
                                    <a id="display_activity_name" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>                                
                            </div>

                            <div class="row-fluid" >
                                <div class="span5">
                                    <div id='rubrics_note' class="alert alert-danger" role="alert" style="padding:3px; font-size: 11px;display: none;">
                                        <span class="sr-only" style="color:#000;"><b>Note:</b></span>
                                        Click "Rubrics Finalize" button after entering all rubrics data!
                                    </div>                                    
                                </div>
                                <div class="span7 pull-right">
                                    <form id="rubrics">	
                                        <div  id="generate_rb_btn">
                                            <div class="control-group pull-right span4">
                                                <div class="controls">													
                                                    <button type="button" id="generate_rubrics" name="generate_rubrics" class="btn btn-primary">Generate Rubrics</button>
                                                    <button type="button" id="regenerate_rubrics" name="regenerate_rubrics" class="btn btn-primary">  Re-Generate Rubrics</button>
                                                </div>
                                            </div>
                                            <div class="span8 pull-right rubrics_count_holder">Enter No. of Columns (Scale of Assessment) for Rubrics <font color="red">*</font> 
                                                <input type="text" id="rubrics_count"  name="rubrics_count" class="input-mini" required/>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>                            
                            
                                
                                <div id="rubrics_data_holder">
                                    
                                </div>
                                
                                    <br><br>
                                <form id='save_rubrics_form'>
                                    <div id="rubrics_data_form">                                    
                                    </div>
                                </form>
                            
                            
                            
                        </div>
                        <div class="modal-footer">                            
                            <button class="btn btn-primary" id="save_rubrics" > <i class="icon-file icon-white"></i> Save</button> 
                            <button class="btn btn-primary" id="update_rubrics" > <i class="icon-file icon-white"></i> Update</button> 
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>                            
                        </div>
                    </div>
                    <div id="rubrics_view_modal" class="modal hide fade" tabindex="" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none; width:1100px;left:400px;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                View Rubrics Definition
                            </div>
                        </div>
                        <div class="modal-body">   
                            <div class="row-fluid">
                                <div class="row-fluid">
                                <div class="span4">
                                    <b> Program: </b>
                                    <a id="display_pgm_name_one" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span3">
                                    <b> Curriculum: </b>
                                    <a id="display_crclm_name_one" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span2">
                                    <b> Term: </b>
                                    <a id="display_term_name_one" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>
                                <div class="span3">
                                    <b>Activity:</b> 
                                    <a id="display_activity_name_one" style="text-decoration: none;color: black;font-size: 14;"></a>
                                </div>                                
                            </div>
                            </div>                                                                                   
                            <div class="bs-docs-example" id="rubrics_view_data_holder" style="width:1030px;height:100%;overflow:auto;">

                            </div>                            
                        </div>
                        <div class="modal-footer">                            
                            <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>                            
                        </div>
                    </div>
                    <div id="delete_criteria_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="criteria_id" id="criteria_id" value="" />
                            <p id="modal_msg"></p>
                            <br>
                            <p>Press Ok to continue.</p>
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" id="delete_criteria_data" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>
                    
                    <div id="regenerate_criteria_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="criteria_id" id="criteria_id" value="" />
                            <p id="re_generate_modal_msg"></p>
                            <br>
                            <p>Press Ok to continue.</p>
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" id="regenerate_delete_criteria_data" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>
                    <div id="finailize_rubrics_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you, want to finalize the rubrics? </p>
                            <br>
                            <p>Press Ok to continue.</p>
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" id="finailize_rubrics_data" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>
                    <div id="cancel_rubrics_finailize_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Rubrics is already finalized for <?php echo $this->lang->line('so') ?> assessment import and you might have uploaded the assessment data.
                                If you want to edit this criteria then all the assessment data will be erased and you need to refinalize the rubrics.</p>
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" id="cancel_rubrics_finailize_data" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>

                    <div id="delete_activity_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="check_framework" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Warning
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="activity_id" id="activity_id" value="" />
                            <input type="hidden" name="finalized" id="finalized" value="" />
                            <p id="delete_activity_msg"></p>                            
                        </div>
                        <div id ="modal_footer" class="modal-footer">
                            <button class="btn btn-primary" id="delete_activity_confirm" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button> 
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>
            </section>	
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript">
    function generate_activity_table(post_data) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/fetch_activity',
            data: post_data,
            success: function (data) {
                var dataTableParam = [];

                dataTableParam['columns'] = [
                    {"sTitle": "Sl No.", "mData": "srl_no"},
                    {"sTitle": "Activity Name", "mData": "activity_name"},
                    {"sTitle": "Conduction Date", "mData": "conducted_date"},
                    {"sTitle": "<?= $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') ?> Reference", "mData": "program_ref"},
                    {"sTitle": "Conduction Place", "mData": "address"},
                    {"sTitle": "Manage Rubrics", "mData": "manage_rubrics"},
                    {"sTitle": "View Rubrics", "mData": "view_rubrics"},
                    {"sTitle": "Template Actions", "mData": "template_actions"},
                    {"sTitle": "Actions", "mData": "actions"}
                ];
                data = JSON.parse(data);
                populateDataTable('#myTable', data, dataTableParam);
                $('#myTable').show();
                $('#loading').hide();
            }
        });

    }
    function populateDataTable(table_ele, data, dataTableParam) {

        if (!dataTableParam['paginationType']) {
            dataTableParam['paginationType'] = "full_numbers";

        }
        if (!dataTableParam['displayLength']) {
            dataTableParam['displayLength'] = 20;

        }
        $(table_ele).dataTable().fnDestroy();
        $(table_ele).empty();
        $(table_ele).dataTable({
            "sPaginationType": dataTableParam['paginationType'],
            "iDisplayLength": dataTableParam['displayLength'],
            "aoColumns": dataTableParam['columns'],
            "aaData": data,
            "sPaginationType": "bootstrap"
        });
        table = $(table_ele).dataTable();
    }
</script>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "></script>
<script src="<?php echo base_url('twitterbootstrap/js/jsgrid.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<!--Datepicker-->
<script src=<?= base_url("/twitterbootstrap/js/bootstrap-datepicker.js") ?>></script>
<!-- Mulitselect-->
<script src="<?= base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url('twitterbootstrap/js/custom/extra_curricular_activities.js') ?>"></script>           