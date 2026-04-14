<?php include "db/dblink.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app['app_name']; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <form action="save_user.php" method="post">
        <div class="login-form">
            <div class="form-title">Create your new account</div>

            <div class="form-field">
                <label>
                    <div class="error-message"><?php echo $_GET['r']; ?></div>
                </label>
            </div>

            <div class="form-field">
                <label for="user_id">Your Name</label>
                <input type="text" id="client_name" name="client_name">
            </div>

            <div class="form-field">
                <label for="user_id">Mobile Number</label>
                <input type="text" id="mobile_number" name="mobile_number">
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-field">
                <input class="submit-button" type="submit" value="Register">
            </div>
            <div class="form-field">
                <label for="">
                    Already have an account? <a href="login.php">Sign In</a>
                </label>
            </div>
        </div>
    </form>
</body>

</html>