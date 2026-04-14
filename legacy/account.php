<?php
include "db/dblink.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("location:signout.php");
} else {
    $q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
    $found = mysqli_num_rows($q);
    if ($found) {
        $user = mysqli_fetch_assoc($q);
    } else {
        header("location:signout.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app['app_name']; ?></title>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <?php include "header.php"; ?>
    <div class="container">
        <div class="menu">
            <?php include "menu.php"; ?>
        </div>
        <div class="content-wrapper">
            <div class="page-title">My Account</div>
            <div class="page-header">
                <div class="page-options">
                    <span class="account-name"><i class="fas fa-id-card fa-lg"></i><?php echo $user['full_name']; ?></span>
                </div>
                <ul class="page-menu">
                    <li><a href="signout.php"><i class="fas fa-lock fa-s"></i>Sign Out</a></li>
                </ul>
            </div>
            <input type="checkbox" name="change_password" id="change_password">
            <div id="change_password_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Change Password
                        </div>
                        <div class="modal-close">
                            <label for="change_password"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="change_password_modal_content">
                        <form id="user_password_form" action="change_password.php" method="post">
                            <div class="form-field">
                                <label for="">Current Password</label>
                                <input type="password" name="current_password" id="current_password" placeholder="">
                            </div>
                            <div class="form-field">
                                <label for="">New Password</label>
                                <input type="password" name="new_password" id="new_password">
                            </div>
                            <div class="form-field">
                                <label for="">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password">
                            </div>
                            <div class="form-field">
                                <div class="send-button" onclick="change_password()">Change Password</div>
                            </div>
                            <div class="form-field">
                                <div class="form-errors" id="user_form_errors"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="page-content" id="page-content">
                <div class="profile-info">
                    <div><label>Email Address:</label></div>
                    <div><?php echo $user['user_id']; ?></div>
                </div>
                <div class="profile-info">
                    <div><label>Status:</label></div>
                    <div><?php echo $user['status']; ?></div>
                </div>

                <div class="profile-info">
                    <div><label>Password:</label></div>
                    <div>
                        <label for="change_password">
                            <span class="change-password">
                                <i class="fas fa-edit fa-small"></i>Change Password
                            </span>
                        </label>
                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Incorrect password") {
                        ?>
                            <span class="password-error">Incorrect Password</span>
                        <?php } ?>


                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Invalid password") {
                        ?>
                            <span class="password-error">Invalid Password</span>
                        <?php } ?>

                        <?php
                        if (isset($_GET['r']) && $_GET['r'] == "Password changed") {
                        ?>
                            <span class="password-changed">Password changed</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php";?>
</body>

</html>