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
    <form action="send_rcode.php" method="post">
        <div class="login-form">
            <div class="form-title">Forgot your password?</div>

            <div class="form-field">
                <label>
                    <div class="error-message"><?php echo $_GET['r']; ?></div>
                </label>
            </div>
            <div class="form-field">
                <label for="">We will send you a reset code by SMS.</label>
            </div>
            <div class="form-field">
                <label for="user_id">Enter your registered Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number">
            </div>
            <div class="form-field">
                <input class="submit-button" type="submit" value="Submit">
            </div>
        </div>
    </form>
</body>

</html>