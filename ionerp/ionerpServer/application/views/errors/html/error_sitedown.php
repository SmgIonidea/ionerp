<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$heading?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?= APP_BASE ?>assets/layouts/AdminLTE-2.4.0-rc/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= APP_BASE ?>assets/layouts/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="<?= APP_BASE ?>layout/admin/source_sans_pro_font/css/fonts.css">
        <link rel="stylesheet" href="<?= APP_BASE ?>/assets/layouts/AdminLTE-2.4.0-rc/bower_components/Ionicons/css/ionicons.min.css">        
        <link rel="stylesheet" href="<?= APP_BASE ?>assets/layouts/AdminLTE-2.4.0-rc/dist/css/AdminLTE.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="hold-transition login-page">
        <div class="row-fluid" style="margin-top: 10%;">
            <div class="col-sm-4"></div>
            <div class="alert alert-warning alert-dismissible col-sm-4">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                <?=SITE_DOWN_MSG?>
            </div>
            <div class="col-sm-4"></div>
        </div>               

    </body>
</html>
