<?php
include "db/dblink.php";

$agent_id = mysqli_real_escape_string($conn, $_GET['agent_id']);


$q = mysqli_query($conn, "SELECT * FROM agents WHERE user_id='" . $agent_id . "'");
$agent = mysqli_fetch_assoc($q);

?>
<form id="agent_form" action="update_agent.php?agent_id=<?php echo $agent['user_id']; ?>" method="post">
    <div class="form-field">
        <label for="">Agent Name</label>
        <input type="text" name="agent_name" id="agent_name" placeholder="" value="<?php echo $agent['agent_name']; ?>">
    </div>

    <div class="form-field">
        <label for="">Region</label>
        <input type="text" name="region" id="region" placeholder="" value="<?php echo $agent['region']; ?>">
    </div>

    <div class="form-field">
        <label for="">Address</label>
        <input type="text" name="agent_address" id="agent_address" placeholder="" value="<?php echo $agent['agent_address']; ?>">
    </div>


    <div class="form-field">
        <label for="">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="" value="<?php echo $agent['phone_number']; ?>">
    </div>

    <div class="form-field">
        <label for="">Email Address</label>
        <input type="text" name="email" id="email" placeholder="" value="<?php echo $agent['email']; ?>">
    </div>
    <div class="form-field">
        <label for="">Status</label>
        <select name="status" id="status">
            <?php
            $q = mysqli_query($conn, "SELECT * FROM account_status");
            while ($status = mysqli_fetch_assoc($q)) {
                if ($agent['status'] == $status['status']) {
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
            $q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE user_id='Administrator' AND account_type='Agent'");
            while ($scheme = mysqli_fetch_assoc($q)) {
                if ($agent['scheme_id'] == $scheme['scheme_id']) {
                    echo "<option selected value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                } else {
                    echo "<option value=\"" . $scheme['scheme_id'] . "\">" . $scheme['scheme_name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <div class="send-button" onclick="update_agent()">Save Changes</div>
    </div>
    <div class="form-field">
        <div class="form-errors" id="form_errors"></div>
    </div>