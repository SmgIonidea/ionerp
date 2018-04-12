<div class="container">
    <h2>View rbac users</h2>
    <div class = 'form-group row'>
        <label for = 'mobile' class = 'col-sm-2 col-form-label'>Mobile</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["mobile"])) ? $data["mobile"] : "--" ?>
        </div>
    </div>    
    <div class = 'form-group row'>
        <label for = 'email' class = 'col-sm-2 col-form-label'>Email</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["email"])) ? $data["email"] : "--" ?>
        </div>
    </div>   
    <div class = 'form-group row'>
        <label for = 'status' class = 'col-sm-2 col-form-label'>Status</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["status"])) ? $data["status"] : "--" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'created' class = 'col-sm-2 col-form-label'>Created</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["created"])) ? $data["created"] : "--" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'modified' class = 'col-sm-2 col-form-label'>Modified</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["modified"])) ? $data["modified"] : "--" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'created_by' class = 'col-sm-2 col-form-label'>Created by</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["parent"])) ? $data["parent"] : "Admin" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'modified_by' class = 'col-sm-2 col-form-label'>Modified by</label>
        <div class = 'col-sm-10'>
            <?= (isset($data["modified_by"])) ? $data["modified_by"] : "--" ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_users/index">
                <span class="glyphicon glyphicon-th-list"></span> Go to list
            </a>
        </div>
    </div>

</div>