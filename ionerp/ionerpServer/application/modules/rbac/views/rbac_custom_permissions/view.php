<div class="container">
    <h2>View rbac custom permissions</h2>
    <div class = 'form-group row'>
        <label for = 'user_id' class = 'col-sm-2 col-form-label'>User id</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["user_id"])) ? $data["user_id"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'permission_id' class = 'col-sm-2 col-form-label'>Permission id</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["permission_id"])) ? $data["permission_id"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'assigned_by' class = 'col-sm-2 col-form-label'>Assigned by</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["assigned_by"])) ? $data["assigned_by"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'status' class = 'col-sm-2 col-form-label'>Status</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["status"])) ? $data["status"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'created' class = 'col-sm-2 col-form-label'>Created</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["created"])) ? $data["created"] : "" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'modified' class = 'col-sm-2 col-form-label'>Modified</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["modified"])) ? $data["modified"] : "" ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_custom_permissions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
    </div>

</div>