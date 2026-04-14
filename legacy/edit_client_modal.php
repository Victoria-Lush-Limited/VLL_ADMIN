<?php
include "db/dblink.php";

$client_id = mysqli_real_escape_string($conn, $_GET['client_id']);


$q = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $client_id . "' AND reseller_id='Administrator'");
$client = mysqli_fetch_assoc($q);

?>
<form id="client_form" action="update_client.php?client_id=<?php echo $client['user_id']; ?>" method="post">
    <div class="form-field">
        <label for="">Client Name</label>
        <input type="text" name="client_name" id="client_name" placeholder="" value="<?php echo $client['client_name']; ?>">
    </div>
    <div class="form-field">
        <label for="">Phone Number</label>
        <input type="text" name="contact_phone" id="contact_phone" placeholder="" maxlength="12" value="<?php echo $client['contact_phone']; ?>">
    </div>
    <div class="form-field">
        <label for="">Email Address</label>
        <input type="text" name="email" id="email" placeholder="" value="<?php echo $client['email']; ?>">
    </div>

    <div class="form-field">
        <label for="">Pricing Scheme</label>
        <select name="scheme_id" id="scheme_id">
            <option value="">--Select--</option>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE user_id='Administrator' AND account_type='Broadcaster'");
            while ($scheme = mysqli_fetch_assoc($q)) {
                if ($client['scheme_id'] == $scheme['scheme_id']) {
                    echo "<option selected value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                } else {
                    echo "<option value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <label for="">Username</label>
        <input type="text" name="username" id="username" placeholder="" value="<?php echo $client['username']; ?>">
    </div>
    <div class="form-field">
        <label for="">Status</label>
        <select name="status" id="status">
            <?php
            $q = mysqli_query($conn, "SELECT * FROM account_status");
            while ($status = mysqli_fetch_assoc($q)) {
                if ($client['status'] == $status['status']) {
                    echo "<option selected value=\"" . $status['status'] . "\">" . $status['status'] . "</option>";
                } else {
                    echo "<option value=\"" . $status['status'] . "\">" . $status['status'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    
    <div class="form-field">
        <div class="send-button" onclick="update_client('<?php echo $client['user_id'];?>')">Save Changes</div>
    </div>
    <div class="form-field">
        <div class="form-errors" id="edit_form_errors"></div>
    </div>