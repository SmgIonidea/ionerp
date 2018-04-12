<div class="container">
    <h2>View rbac permissions</h2>
    <div class = 'form-group row'>
        <label for = 'module_id' class = 'col-sm-2 col-form-label'>Module id</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["module_id"])) ? $data["module_id"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'action_id' class = 'col-sm-2 col-form-label'>Action id</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["action_id"])) ? $data["action_id"] : "" ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_permissions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
    </div>

</div>