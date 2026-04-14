<?php
include "db/dblink.php";

$reseller_id = mysqli_real_escape_string($conn, $_GET['reseller_id']);


$q = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $reseller_id . "'");
$reseller = mysqli_fetch_assoc($q);

?>
<form id="client_form" action="update_reseller.php?reseller_id=<?php echo $reseller['user_id']; ?>" method="post">
    <div class="form-field">
        <label for="">Reseller Name</label>
        <input type="text" name="business_name" id="business_name" placeholder="" value="<?php echo $reseller['business_name']; ?>">
    </div>
    <div class="form-field">
        <label for="">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="" value="<?php echo $reseller['phone_number']; ?>">
    </div>

    <div class="form-field">
        <label for="">Office Address</label>
        <input type="text" name="business_address" id="business_address" placeholder="" value="<?php echo $reseller['business_address']; ?>">
    </div>

    <div class="form-field">
        <label for="">Email Address</label>
        <input type="text" name="email" id="email" placeholder="" value="<?php echo $reseller['email']; ?>">
    </div>
    <div class="form-field">
        <label for="">Status</label>
        <select name="status" id="status">
            <?php
            $q = mysqli_query($conn, "SELECT * FROM account_status");
            while ($status = mysqli_fetch_assoc($q)) {
                if ($reseller['status'] == $status['status']) {
                    echo "<option selected value=\"" . $status['status'] . "\">" . $status['status'] . "</option>";
                } else {
                     echo "<option value=\"" . $status['status'] . "\">" . $status['status'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <label for="">Pricing Scheme</label>
        <select name="scheme_id" id="scheme_id">
            <option value="">--Select--</option>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE user_id='Administrator' AND account_type='Reseller'");
            while ($scheme = mysqli_fetch_assoc($q)) {
                if ($reseller['scheme_id'] == $scheme['scheme_id']) {
                    echo "<option selected value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                } else {
                    echo "<option value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <label for="">Sender ID</label>
        <input type="text" name="sender_id" id="sender_id" placeholder="" value="<?php echo $reseller['sender_id']; ?>" maxlength="11">
    </div>
    <div class="form-field">
        <div class="send-button" onclick="update_reseller()">Save Changes</div>
    </div>
    <div class="form-field">
        <div class="form-errors" id="form_errors"></div>
    </div>