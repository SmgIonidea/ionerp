<div class="container">
    <h2>Edit rbac role permissions</h2>
    <?php
    $form_attribute = array(
        "name" => "rbac_role_permissions",
        "id" => "rbac_role_permissions",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_role_permissions/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "role_permission_id",
        "id" => "role_permission_id",
        "class" => "",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["role_permission_id"])) ? $data["role_permission_id"] : ""
    );
    echo form_error("role_permission_id");
    echo form_input($attribute);
    ?><div class = 'form-group row'>
        <label for = 'role_id' class = 'col-sm-2 col-form-label'>Role id</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "role_id",
                "id" => "role_id",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $role_id = (isset($data['role_id'])) ? $data['role_id'] : '';
            echo form_error("role_id");
            echo form_dropdown($attribute, $role_id_list, $role_id);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'permission_id' class = 'col-sm-2 col-form-label'>Permission id</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "permission_id",
                "id" => "permission_id",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $permission_id = (isset($data['permission_id'])) ? $data['permission_id'] : '';
            echo form_error("permission_id");
            echo form_dropdown($attribute, $permission_id_list, $permission_id);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_role_permissions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>