<div class="container">
    <h2>Edit rbac custom permissions</h2>
    <?php
    $form_attribute = array(
        "name" => "rbac_custom_permissions",
        "id" => "rbac_custom_permissions",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_custom_permissions/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "custom_permission_id",
        "id" => "custom_permission_id",
        "class" => "",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["custom_permission_id"])) ? $data["custom_permission_id"] : ""
    );
    echo form_error("custom_permission_id");
    echo form_input($attribute);
    ?><div class = 'form-group row'>
        <label for = 'user_id' class = 'col-sm-2 col-form-label'>User id</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "user_id",
                "id" => "user_id",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "number",
                "value" => (isset($data["user_id"])) ? $data["user_id"] : ""
            );
            echo form_error("user_id");
            echo form_input($attribute);
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
        <label for = 'assigned_by' class = 'col-sm-2 col-form-label'>Assigned by</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "assigned_by",
                "id" => "assigned_by",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $assigned_by = (isset($data['assigned_by'])) ? $data['assigned_by'] : '';
            echo form_error("assigned_by");
            echo form_dropdown($attribute, $assigned_by_list, $assigned_by);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'status' class = 'col-sm-2 col-form-label'>Status</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "status",
                "id" => "status",
                "class" => "",
                "title" => "",
                "required" => "",
            );
            $status = (isset($data['status'])) ? $data['status'] : '';
            echo form_error("status");
            echo form_dropdown($attribute, $status_list, $status);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'created' class = 'col-sm-2 col-form-label'>Created</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "created",
                "id" => "created",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "datetime ",
                "value" => (isset($data["created"])) ? $data["created"] : ""
            );
            echo form_error("created");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'modified' class = 'col-sm-2 col-form-label'>Modified</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "modified",
                "id" => "modified",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "datetime ",
                "value" => (isset($data["modified"])) ? $data["modified"] : ""
            );
            echo form_error("modified");
            echo form_input($attribute);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_custom_permissions/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>