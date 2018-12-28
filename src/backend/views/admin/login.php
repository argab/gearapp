<!DOCTYPE html>
<html lang="ru" style="height: 100%;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="GearApp login">
    <title>GearApp login</title>
    <style>.file-input-wrapper {
            overflow: hidden;
            position: relative;
            cursor: pointer;
            z-index: 1;
        }

        .file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover {
            position: absolute;
            top: 0;
            left: 0;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
            z-index: 99;
            outline: 0;
        }

        .file-input-name {
            margin-left: 8px;
        }</style>
    <link rel="stylesheet" href="/login/jquery-ui-1.10.3.custom.min.css" id="style-resource-1">
    <link rel="stylesheet" href="/login/entypo.css" id="style-resource-2">
    <link rel="stylesheet" href="/login/css" id="style-resource-3">
    <link rel="stylesheet" href="/login/bootstrap.css" id="style-resource-4">
    <link rel="stylesheet" href="/login/neon-core.css" id="style-resource-5">
    <link rel="stylesheet" href="/login/neon-theme.css" id="style-resource-6">
    <link rel="stylesheet" href="/login/neon-forms.css" id="style-resource-7">
    <link rel="stylesheet" href="/login/custom.css" id="style-resource-8">
    <script src="/login/jquery-1.11.3.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://demo.neontheme.com/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --> <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> <![endif]-->
    <!-- TS1526225034: Neon - Responsive Admin Template created by Laborator -->
    <link type="text/css" rel="stylesheet" charset="UTF-8" href="/login/translateelement.css">
    <script type="text/javascript" charset="UTF-8" src="/login/main_ru.js"></script>
    <script type="text/javascript" charset="UTF-8" src="/login/element_main.js"></script>
</head>
<body class="page-body login-page login-form-fall loaded login-form-fall-init"
      style="position: relative; min-height: 100%; top: 0px;">

<div class="login-container">
    <div class="login-header login-caret">
        <div class="login-content"><h1 style="color: #fff; font-size: 50pt">Gear App</h1>
            <div class="login-progressbar-indicator"><h3>0%</h3> <span>logging in...</span></div>
        </div>
    </div>
    <div class="login-progressbar">
        <div></div>
    </div>
    <div class="login-form">
        <div class="login-content">
            <div class="form-login-error"><h3>Invalid login</h3>
                </div>
            <form method="post" action="/admin/login" role="form" id="form_login" novalidate="novalidate">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="entypo-user"></i></div>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="phone"
                               autocomplete="off"></div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="entypo-key"></i></div>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                               autocomplete="off"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-login"><i class="entypo-login"></i>
                        Login In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/login/TweenMax.min.js" id="script-resource-1"></script>
<script src="/login/jquery-ui-1.10.3.minimal.min.js" id="script-resource-2"></script>
<script src="/login/bootstrap.js" id="script-resource-3"></script>
<script src="/login/joinable.js" id="script-resource-4"></script>
<script src="/login/resizeable.js" id="script-resource-5"></script>
<script src="/login/neon-api.js" id="script-resource-6"></script>
<script src="/login/cookies.min.js" id="script-resource-7"></script>
<script src="/login/jquery.validate.min.js" id="script-resource-8"></script>
<script src="/login/neon-login.js" id="script-resource-9"></script>
<!-- JavaScripts initializations and stuff -->
<script src="/login/neon-custom.js" id="script-resource-10"></script> <!-- Demo Settings -->
<script src="/login/neon-demo.js" id="script-resource-11"></script>
<script src="/login/neon-skins.js" id="script-resource-12"></script>
</body>
</html>