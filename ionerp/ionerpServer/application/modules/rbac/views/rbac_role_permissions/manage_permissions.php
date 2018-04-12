<form action="<?= base_url('/rbac/rbac_role_permissions/save_role_permission') ?>" name="role_permission_form" id="role_permission_form" method="post">
    <div class="row-fluid">
        <div class="col-sm-12" style="padding-left: 0px;">
            <label for="manage_permission_role_list">Roles</label>        
            <?php echo form_dropdown('manage_permission_role_list', $role_list, '', 'id="manage_permission_role_list"'); ?>
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="checkbox col-lg-3" style="padding-left: 0px;">
            <label for="check_all">
                <input type="checkbox" name="check_all" id="check_all">Select all
            </label>
        </div>
        <div class="checkbox col-lg-5" style="padding-left: 0px; margin-top: 6px;">
            <div class="col-lg-9" id="error_msg" style="color: red;"></div>
        </div>

    </div>
    <table class="table table-sm table-bordered responsive table-striped" id="role_permission_table">
        <?= $permission_table ?>            
    </table>
    <div style="float:right;">
        <input class="btn btn-primary" type="submit" name="save_role_permission" id="save_role_permission" value="Save">
    </div>
</form>