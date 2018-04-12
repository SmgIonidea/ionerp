<div class="container">
    <h2>Edit rbac permissions</h2>
    <?php
    $form_attribute = array(
        "name" => "rbac_permissions",
        "id" => "rbac_permissions",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_permissions/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "permission_id",
        "id" => "permission_id",
        "class" => "",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["permission_id"])) ? $data["permission_id"] : ""
    );
    echo form_error("permission_id");
    echo form_input($attribute);
    ?><div class = 'form-group row'>
        <label for = 'module_id' class = 'col-sm-2 col-form-label'>Module id</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "module_id",
                "id" => "module_id",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $module_id = (isset($data['module_id'])) ? $data['module_id'] : '';
            echo form_error("module_id");
            echo form_dropdown($attribute, $module_id_list, $module_id);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'action_id' class = 'col-sm-2 col-form-label'>Action id</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "action_id",
                "id" => "action_id",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $action_id = (isset($data['action_id'])) ? $data['action_id'] : '';
            echo form_error("action_id");
            echo form_dropdown($attribute, $action_id_list, $action_id);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_permissions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>