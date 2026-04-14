<?php
include "db/dblink.php";

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);


$q = mysqli_query($conn, "SELECT * FROM sms_orders WHERE order_id='" . $order_id . "'");
$order = mysqli_fetch_assoc($q);

?>

<div class="form-field">
    <label for="">Receipt No.</label>
    <input type="text" name="receipt_number" id="receipt_number">
</div>
<div class="form-field">
    <label for="">Payment Method</label>
    <select name="payment_method" id="payment_method">
        <option value="">--Select--</option>
        <?php
        $q = mysqli_query($conn, "SELECT * FROM payment_methods WHERE reseller_id='Administrator'");
        while ($method = mysqli_fetch_assoc($q)) {
            echo "<option value=\"" . $method['payment_method'] . "\">" . $method['payment_method'] . "</option>";
        }
        ?>
    </select>
</div>
<div class="form-field">
    <div class="send-button" onclick="save_payment('<?php echo $order['order_id']; ?>')">Allocate Credits</div>
</div>
<div class="form-field">
    <div class="form-errors" id="form_errors"></div>
</div>