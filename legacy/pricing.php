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
            <div class="page-title">Pricing Schemes</div>
            <div class="page-header">
                <ul class="page-menu">
                    <li><label for="create_scheme"><i class="fas fa-plus fa-s"></i>New Pricing Scheme</label></li>
                </ul>
                <div class="page-options">
                    <ul>
                        <li><input type="text" id="keyword" name="keyword" placeholder="Search Keyword" onchange="get_pricing(1,document.getElementById('per_page').value)"><i class="fas fa-search fa-s" onclick="get_senders(1,document.getElementById('per_page').value)"></i></li>
                    </ul>
                </div>
            </div>
            <input type="checkbox" name="create_scheme" id="create_scheme">
            <div id="create_scheme_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            New Pricing Scheme
                        </div>
                        <div class="modal-close">
                            <label for="create_scheme"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="create_scheme_modal_content">
                        <div class="form-field">
                            <label for="">Scheme Name</label>
                            <input type="text" name="scheme_name" id="scheme_name" placeholder="">
                        </div>
                        <div class="form-field">
                            <label for="">Account Type</label>
                            <select name="account_type" id="account_type">
                                <option value="">--Select--</option>
                                <?php
                                $q = mysqli_query($conn, "SELECT * FROM account_types");
                                while ($type = mysqli_fetch_assoc($q)) {
                                    echo "<option value='".$type['account_type']."'>".$type['account_type']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <div class="send-button" onclick="save_scheme(document.getElementById('start_row').value,document.getElementById('per_page').value)">Submit</div>
                        </div>
                        <div class="form-field">
                            <div class="form-errors" id="form_errors"></div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="checkbox" name="edit_scheme" id="edit_scheme">
            <div id="edit_scheme_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Edit Pricing Scheme
                        </div>
                        <div class="modal-close">
                            <label for="edit_scheme"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="edit_scheme_modal_content">
                        
                    </div>
                </div>
            </div>

            <div class="page-content" id="page-content">

            </div>
        </div>
    </div>
    <?php include "footer.php";?>
    <script>
        get_schemes(1, 10);
    </script>
</body>

</html>