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
            <div class="page-title">Sales</div>
            <div class="page-header">
                <ul class="page-menu">
                    
                </ul>
                <div class="page-options">
                    <ul>
                        <li><input type="text" id="keyword" name="keyword" placeholder="Search Keyword" onchange="get_sales(1,document.getElementById('per_page').value)"><i class="fas fa-search fa-s" onclick="get_senders(1,document.getElementById('per_page').value)"></i></li>
                    </ul>
                </div>
            </div>
            <input type="checkbox" name="allocate_credit" id="allocate_credit">
            <div id="allocate_credit_modal">
                <div class="modal-wrapper">
                    <div class="modal-header">
                        <div class="modal-title">
                            Allocate Credits
                        </div>
                        <div class="modal-close">
                            <label for="allocate_credit"><i class="fas fa-times fa-2x"></i></label>
                        </div>
                    </div>
                    <div class="modal-content" id="allocate_credit_modal_content">
                          
                    </div>
                </div>
            </div>
            <div class="page-content" id="page-content">

            </div>
        </div>
    </div>
    <?php include "footer.php";?>
    <script>
        get_sales(1, 25);
    </script>
</body>

</html>