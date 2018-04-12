<?php ?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in ionerp.css and _all-skins.css -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview">                    
                <a href="#">
                    <i class="fa fa-dashboard"></i> 
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_actions"><i class="fa fa-circle-o text-aqua text-aqua"></i> Actions</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_modules"><i class="fa fa-circle-o text-aqua"></i> Modules</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_permissions/create"><i class="fa fa-circle-o text-aqua"></i> Permissions</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_roles"><i class="fa fa-circle-o text-aqua"></i> Roles</a></li>                    
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_role_permissions/manage_permissions/1"><i class="fa fa-circle-o text-aqua"></i> Assign Permissions</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_users"><i class="fa fa-circle-o text-aqua"></i> Users</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_user_roles"><i class="fa fa-circle-o text-aqua"></i> User Roles</a></li>
                    <li class=""><a href="<?= APP_BASE ?>rbac/rbac_custom_permissions"><i class="fa fa-circle-o text-aqua"></i> Custom Permissions</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?= APP_BASE ?>cruds">
                    <i class="fa fa-gears"></i> <span>MENU-1</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class=""><a href=""><i class="fa fa-circle-o text-aqua text-aqua"></i> SUB-MENU-1</a></li>
                    <li class=""><a href=""><i class="fa fa-circle-o text-aqua text-aqua"></i> SUB-MENU-2</a></li>
                    <li class=""><a href=""><i class="fa fa-circle-o text-aqua text-aqua"></i> SUB-MENU-3</a></li>
                    <li class=""><a href=""><i class="fa fa-circle-o text-aqua text-aqua"></i> SUB-MENU-4</a></li>
                </ul>
            </li>
        </ul>
        <?php //$this->rbac->show_user_menu_left();?>
    </section>
    <!-- /.sidebar -->
</aside>
