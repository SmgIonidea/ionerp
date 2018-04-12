<?php ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.program_list_as_department').trigger('change');
        var dept_id = parseInt($.cookie('cookie_filter_template_type_deptid'));
        var program_id = parseInt($.cookie('cookie_filter_template_type_prgmid'));
        var template_type = parseInt($.cookie('cookie_filter_template_type'));
        var param = [];
        var post_data = {};
        if (dept_id == 0 && program_id == 0 && template_type == 0) {
            return false;
        }
        if (dept_id) {
            post_data['dept_id'] = dept_id;
            //set selected value
            $('.program_list_as_department option[value="' + dept_id + '"]').attr('selected', 'selected');
            //generate program list
            $('.program_list_as_department').trigger('change');
        }
        if (template_type) {
            post_data['temp_id'] = template_type;
            //set selected value
            param['selected'] = template_type;
            param['ele_id'] = 'template_type';
            setAjaxSelectBox(param);
        } else {
            template_type = 0;
        }
        param = [];
        if (program_id) {
            post_data['prgm_id'] = program_id;
            //set selected value
            param['selected'] = program_id;
            param['ele_id'] = 'program_type';
            setAjaxSelectBox(param);
        } else {
            program_id = 0
        }
        controller = 'templates/';
        method = 'templates_list';
        data_type = 'json';
        reloadMe = 0;

        dataTableParam = [];
        dataTableParam['columns'] = [
            {"sTitle": "Survey Type", "mData": "mt_details_name"},
            {"sTitle": "Template Title", "mData": "name"},
            {"sTitle": "Description", "mData": "description"},
            {"sTitle": "Edit", "mData": "edit_temp"},
            {"sTitle": "Status", "mData": "sts_temp"}

        ];
        dataTableAjax_group_by(post_data, dataTableParam, setAjaxSelectBox, param);
        dataTableParam = null;
    });
</script>
<style>
    .peo{
        margin: 0px !important;
        width: 0% !important;
        height: 0px !important;
    }
</style>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="success"><?php //echo @$this->session->flashdata('template_msg_success');  ?></div>
            <div class="error"><?php echo @$this->session->flashdata('template_msg_error'); ?></div>
            <div class="height30">
                <div class="floatL template_cust_label">
                    <?php echo form_label('<b>Department:</b>', 'Department'); ?>
                </div>
                <div class="floatL margin-left5">
                    <?php echo form_dropdown('department', $departments, (@$departmentSelect) ? @$departmentSelect : set_value('department'), "'id'='department' class='program_list_as_department filter_template_type'"); ?>
                    <span id="errorspan_department" class="error help-inline"></span>
                </div>
                <div class="floatL template_cust_label">
                    <?php echo form_label('<b>Program:</b>', 'Program'); ?>
                </div>
                <div class="floatL">
                    <?php echo form_dropdown('program_type', @$programs, (set_value('program_type')) ? set_value('program_type') : '0', "id='program_type' class='input filter_template_type'"); ?>
                    <span id="errorspan_program_type" class="error help-inline"></span>
                </div>
                <div class="floatL template_cust_label">
                    <?php echo form_label('<b>Survey Template Type:</b>', 'template_type'); ?>
                </div>
                <div class="floatL margin-left5">
                    <?php echo form_dropdown('template_type', $template_type, (set_value('template_type')) ? set_value('template_type') : '0', "id='template_type' class='input filter_template_type'"); ?>
                    <span id="errorspan_template_type" class="error help-inline"></span>
                </div>
                <div class="floatR">
                    <?php echo anchor('survey/templates/add_template', "<i class='icon-plus-sign icon-white'></i> Add", array('class' => 'btn btn-primary pull-right')); ?>
                </div>
            </div>
            <br>
            <div id="template_list_table_new"></div>

            <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                <thead>
                    <tr role="row">
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example"> type </th>
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Template Title</th>
                        <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Description</th>
                        <th class="header span1">Edit</th>
                        <th class="header span1">Status</th>

                    </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all" id="template_list_table">
                    <?php /* foreach ($template_list as $listData):
                      ?>
                      <tr class="gradeU even">
                      <td class="sorting_1"><?php echo @$listData['name']; ?> </td>
                      <td class="sorting_1"><?php echo @$listData['description']; ?></td>
                      <td>
                      <center>
                      <?php echo anchor("survey/templates/edit_template/$listData[template_id]", "<i class='icon-pencil'></i>"); ?>
                      </center>
                      </td>
                      <td>
                      <center>
                      <?php echo ($listData['status'] == 0)
                      ? anchor("#myModalenable", "<i class='icon-ban-circle'></i>","data-toggle='modal' class='modal_action_status' sts='1' title='Click to enable' id= modal_$listData[template_id]")
                      : anchor("#myModaldisable", "<i class='icon-ok-circle'></i>","data-toggle='modal' class='modal_action_status' sts='0' title='Click to disable' id= modal_$listData[template_id]");
                      ?>
                      </center>
                      </td>
                      </tr>
                      <?php endforeach; */ ?>
                </tbody>
            </table>

        </div>
        <div class="row">
<?php echo anchor('survey/templates/add_template', "<i class='icon-plus-sign icon-white'></i> Add", array('class' => 'btn btn-primary pull-right')); ?>
        </div>
        <div class="pull-right">

            <!-- Modal to confirm before enabling a user -->
            <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-template-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>

            <!-- Modal to confirm before disabling a user -->
            <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Disable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-template-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>

            <!-- Modal to display delete confirmation message -->
            <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Are you sure  you want to delete the Stakeholder Type? </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_st_type();"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>