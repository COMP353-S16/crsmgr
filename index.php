
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <form role="form" id="loginForm">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="E-mail" name="username" type="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <button id="login" name="login" type="submit" class="btn btn-lg btn-primary btn-block">
                                Login
                            </button>
                        </fieldset>
                    </form>

                    <div id="results"></div>

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

<script>
    $(function(){
        // Validate the login form
        $('form#loginForm').validate({
            errorClass: "text-danger",
            validClass: "text-success",
            success: function (label) {
                label.closest('div').removeClass('has-error').addClass('has-success');
            },
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            errorPlacement: function (error, element) {
                element.closest('div').addClass('has-error');
                error.insertAfter(element);
            },
            submitHandler: function (form) {

                $clicker = $('#login');
                var serialized = $('#loginForm :input').serialize();
                var originalText = $clicker.text();
                $clicker.text('Logging in...');

                // send data to server to check for credentials
                $.ajax({
                    url: 'ajax/login.php',
                    data: serialized,
                    type: 'POST',
                    error: function()
                    {
                        console.log('An error occured');
                        $clicker.text(originalText);
                    },
                    dataType: 'html',
                    success: function(data)
                    {
                        $clicker.text(originalText);
                        $('#results').html(data);

                    }

                });


            }
        });


    });
</script>

</body>

</html>
