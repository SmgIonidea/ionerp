<div class="container">
    <h2>Edit rbac roles</h2>
    <?php
    $form_attribute = array(
        "name" => "rbac_roles",
        "id" => "rbac_roles",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_roles/edit";
    echo form_open($form_action, $form_attribute);
    ?>
    <?php
    $attribute = array(
        "name" => "role_id",
        "id" => "role_id",
        "class" => "",
        "title" => "",
        "required" => "",
        "type" => "hidden",
        "value" => (isset($data["role_id"])) ? $data["role_id"] : ""
    );
    echo form_error("role_id");
    echo form_input($attribute);
    ?><div class = 'form-group row'>
        <label for = 'name' class = 'col-sm-2 col-form-label'>Name</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "name",
                "id" => "name",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => (isset($data["name"])) ? $data["name"] : ""
            );
            echo form_error("name");
            echo form_input($attribute);
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'code' class = 'col-sm-2 col-form-label'>Code</label>
        <div class = 'col-sm-10'>
            <?php
            $attribute = array(
                "name" => "code",
                "id" => "code",
                "class" => "",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => (isset($data["code"])) ? $data["code"] : ""
            );
            echo form_error("code");
            echo form_input($attribute);
            ?>
        </div>
    </div>

    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_roles/index">
                <span class="glyphicon glyphicon-th-list"></span> Cancel
            </a>
        </div>
        <div class = 'col-sm-1'>
            <input type="submit" id="submit" value="Update" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>