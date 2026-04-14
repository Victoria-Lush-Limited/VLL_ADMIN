<?php
include "db/dblink.php";

$client_id=mysqli_real_escape_string($conn,$_GET['client_id']);


$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $client_id . "'");
$client = mysqli_fetch_assoc($q);

?>
<form id="transfer_form" action="transfer_client.php?client_id=<?php echo $client['user_id'];?>" method="post">
<div class="form-field">
    <label for="">Client Name</label>
    <input type="text" readonly disabled value="<?php echo $client['client_name']; ?>">
</div>
<div class="form-field">
    <label for="">Reseller</label>
    <select name="transfer_reseller_id" id="transfer_reseller_id">
        <option value="Administrator">Administrator</option>
        <?php
        $q = mysqli_query($conn, "SELECT * FROM resellers ORDER BY business_name ASC");
        while ($reseller = mysqli_fetch_assoc($q)) {
            if ($client['reseller_id'] == $reseller['user_id']) {
                echo "<option selected value=\"" . $reseller['user_id'] . "\">" . $reseller['business_name'] . "</option>";
            } else {
                echo "<option value=\"" . $reseller['user_id'] . "\">" . $reseller['business_name'] . "</option>";
            }
        }
        ?>
    </select>
</div>
<div class="form-field">
    <div class="send-button" onclick="save_transfer()">Transfer</div>
</div>
<div class="form-field">
    <div class="form-errors" id="transfer_form_errors"></div>
</div>