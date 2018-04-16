<div class="container">    
    <?php
    $form_attribute = array(
        "name" => "rbac_create_users",
        "id" => "rbac_create_users",
        "method" => "POST"
    );
    $form_action = "/rbac/rbac_users/create";
    echo form_open($form_action, $form_attribute);
    ?>    
     <div class = 'form-group row'>
        <label for = 'email' class = 'col-sm-2 col-form-label'>Email</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "email",
                "id" => "email",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => set_value('email')
            );            
            echo form_input($attribute);
            echo form_error("email");
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'password' class = 'col-sm-2 col-form-label'>Password</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "password",
                "id" => "password",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "password",
                "value" => ""
            );            
            echo form_input($attribute);
            echo form_error("password");
            ?>
        </div>
    </div>  
    <div class = 'form-group row'>
        <label for = 'password' class = 'col-sm-2 col-form-label'>Re-type Password</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "re_password",
                "id" => "re_password",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "password",
                "value" =>""
            );            
            echo form_input($attribute);
            echo form_error("re_password");
            ?>
        </div>
    </div>  
    <div class = 'form-group row'>
        <label for = 'mobile' class = 'col-sm-2 col-form-label'>Mobile</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "mobile",
                "id" => "mobile",
                "class" => "form-control",
                "title" => "",
                "required" => "",
                "type" => "text",
                "value" => set_value('mobile')
            );            
            echo form_input($attribute);
            echo form_error("mobile");
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'roles' class = 'col-sm-2 col-form-label'>Role</label>
        <div class = 'col-sm-3'>
            <?php
            $attribute = array(
                "name" => "roles",
                "id" => "roles",
                "class" => "form-control",
                "title" => "",
                "required" => "",                
            );            
            echo form_dropdown('roles',$roles,set_value('roles'),$attribute);
            echo form_error("roles");
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <label for = 'status' class = 'col-sm-2 col-form-label'>Status</label>
        <div class = 'col-sm-3'>
            <?php
            $status_list=  get_user_status_list();
            $attribute = array(
                "name" => "status",
                "id" => "status",
                "class" => "form-control",
                "title" => "",
                "required" => "",                
            );            
            echo form_dropdown('status',$status_list,set_value('status'),$attribute);
            echo form_error("status");
            ?>
        </div>
    </div>
    <div class = 'form-group row'>
        <div class = 'col-sm-1'>
            <a class="text-right btn btn-default" href="<?= APP_BASE ?>rbac/rbac_users/index">
                <span class="glyphicon glyphicon-th-list"></span> Go to list
            </a>
        </div>
        <div class = 'col-sm-2 padl25'>
            <input type="submit" id="rbac_create_users_submit" value="Save" class="btn btn-primary">
        </div>
    </div>
    <?= form_close() ?>
</div>