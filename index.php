<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/dbc.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo CoreConfig::settings()['appname']; ?></title>

    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />
    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// --><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
    <style>
        body
        {
            background: #1e3c72; /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, rgba(30, 60, 114, .98), rgba(0, 98, 152, 0.98)); /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, rgba(30, 60, 114, .98), rgba(0, 98, 152, 0.98)); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .AppName {
            position: absolute;
            font-size: 72px;;
            color: #FFF;
            cursor: default;
            margin-left: 20px;
            margin-top: 10px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            font-family: Gill Sans, Verdana;
            -ms-user-select: none;
            user-select: none;
        }
        .AppName .logo {
            content: url('images/favicon.png');
            position: relative;
            margin: inherit;
            width: 80px;
        }
        .clear {
            padding-top: 70px;
        }
    </style>
</head>
<body>
<div class="AppName"><span id="name"><?php echo CoreConfig::settings()['appname']; ?><span class="logo"></span></span>
</div>
<div class="clear"></div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <br>
            <div class="login-panel panel panel-primary">
                <div class="panel-heading">

                    <h3 class="panel-title"><i class="glyphicon glyphicon-log-in"></i> Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <form role="form" id="loginForm">
                        <fieldset>

                            <div class="form-group has-feedback">
                                <input class="form-control" placeholder="Username" name="username" type="text">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input class="form-control" placeholder="Password" name="password" type="password">
                                <span class="help-block"></span>
                            </div>
                            <!-- not working
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>
                            -->
                            <!-- Change this to a button or input when using this as a form -->
                            <button id="login" name="login" type="submit" class="btn btn-lg btn-primary btn-block">
                                Login
                            </button>
                        </fieldset>
                        <div id="results">
                            <?php
                            $time = time();
                            // display not logged in message
                            if (isset($_GET['l']) && isset($_GET['u']) && $_GET['l'] == 0 && $time == $_GET['u'])
                            {
                                ?>
                                <br>
                                <div class="alert alert-danger"><p class="text-center">You are not logged in</p></div>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>
<!-- Validator -->
<script src="bower_components/validator/dist/jquery.validate.min.js"></script>
<!-- CRS -->
<script src="js/crs.js"></script>
<script>
    $(function ()
    {
        // Validate the login form
        $('form#loginForm').validate({
            rules : {
                username :
                {
                    required : true
                },
                password :
                {
                    required : true
                }
            },
            submitHandler : function (form)
            {

                $clicker = $('#login');
                var serialized = $(form).find(':input').serialize();
                var originalText = $clicker.text();
                $clicker.text('Logging in...');
                // send data to server to check for credentials
                $.ajax({
                    url : 'ajax/login.php',
                    data : serialized,
                    type : 'POST',
                    error : function ()
                    {
                        $clicker.text(originalText);
                    },
                    dataType : 'html',
                    success : function (data)
                    {

                        $('#results').html(data);
                        $clicker.text(originalText);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
