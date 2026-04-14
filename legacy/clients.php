<?php
include "db/dblink.php";

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("location:signout.php");
} else {
    $q = mysqli_query($conn, "SELECT * FROM administrators WHERE user_id='" . $_SESSION['user_id'] . "'");
    $found = mysqli_num_rows($q);
    if ($found) {
        $user = mysqli_fetch_assoc($q);
        if ($user['status'] == "Pending") {
            header("location:verification.php");
        }
    } else {
        header("location:signout.php");
    }
}

$from_date = strtotime(date("d-m-Y", time()));
$to_date = time();

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
            <div class="page-title">Clients</div>
            <div class="page-header">
                <ul class="page-menu">
                    <li><label for="create_client"><i class="fas fa-plus fa-s"></i>New Client</label></li>
                </ul>
                <div class="page-options">
                    <ul>
                        <li><input type="text" id="keyword" name="keyword" placeholder="Search Keyword" onchange="get_clients(1,document.getElementById('per_page').value)"><i class="fas fa-search fa-s" onclick="get_senders(1,document.getElementById('per_page').value)"></i></li>
                    </ul>
                </div>
            </div>
            <input type="checkbox" name="create_client" id="create_client">
            <div id="create_client_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            New Client
                        </div>
                        <div class="modal-close">
                            <label for="create_client"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="create_sender_modal_content">
                        <form id="client_form" action="save_client.php" method="post">
                            <div class="form-field">
                                <label for="">Client Name</label>
                                <input type="text" name="client_name" id="client_name" placeholder="">
                            </div>
                            <div class="form-field">
                                <label for="">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" placeholder="" maxlength="12">
                            </div>
                            <div class="form-field">
                                <label for="">Email Address</label>
                                <input type="text" name="email" id="email" placeholder="">
                            </div>
                            
                            <div class="form-field">
                                <label for="">Pricing Scheme</label>
                                <select name="scheme_id" id="scheme_id">
                                    <option value="">--Select--</option>
                                    <?php
                                    $q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE user_id='Administrator' AND account_type='Broadcaster'");
                                    while ($scheme = mysqli_fetch_assoc($q)) {
                                        echo "<option value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            
                            <div class="form-field">
                                <label for="">Username</label>
                                <input type="text" name="username" id="username" placeholder="">
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
                                <div class="send-button" onclick="save_client()">Submit</div>
                            </div>
                            <div class="form-field">
                                <div class="form-errors" id="form_errors"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="page-content" id="page-content">

            </div>
        </div>
    </div>
    <?php include "footer.php";?>

    <script>
        get_clients(1, 10);
    </script>

<?php
    if (isset($_GET['r'])) {
        echo "<script>alert('" . $_GET['r'] . "')</script>";
    }
    ?>
</body>

</html>