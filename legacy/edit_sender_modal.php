<?php
include "db/dblink.php";
$id = mysqli_real_escape_string($conn, $_GET['id']);

$q = mysqli_query($conn, "SELECT * FROM senders WHERE id='" . $id . "'");
$sender = mysqli_fetch_assoc($q);

?>
<form id="sender_form" action="update_sender.php?id=<?php echo $sender['id']; ?>" method="post">
    <div class="form-field">
        <label for="">Sender ID</label>
        <input type="text" name="sender_id" id="sender_id" placeholder="" maxlength="11" value="<?php echo $sender['sender_id']; ?>" readonly>
    </div>
    <div class="form-field">
        <label for="">Sample Message</label>
        <textarea name="message" id="message" readonly><?php echo $sender['sender_id']; ?></textarea>
    </div>
    <div class="form-field">
        <label for="">Id Type</label>
        <select name="id_type" id="id_type">
            <option value="">--Select--</option>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM id_types");
            while ($type = mysqli_fetch_assoc($q)) {
                if ($sender['id_type'] == $type['id_type']) {
                    echo "<option selected value=\"" . $type['id_type'] . "\">" . $type['id_type'] . "</option>";
                } else {
                    echo "<option value=\"" . $type['id_type'] . "\">" . $type['id_type'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-field">
        <label for="">Status</label>
        <select name="id_status" id="id_status">
            <option value="">--Select--</option>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM id_status");
            while ($status = mysqli_fetch_assoc($q)) {
                if ($sender['id_status'] == $status['id_status']) {
                    echo "<option selected value=\"" . $status['id_status'] . "\">" . $status['id_status'] . "</option>";
                } else {
                    echo "<option value=\"" . $status['id_status'] . "\">" . $status['id_status'] . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="form-field">
        <div class="send-button" onclick="update_sender(document.getElementById('start_row').value,document.getElementById('per_page').value)">Submit</div>
    </div>
    <div class="form-field">
        <div class="form-errors" id="form_errors"></div>
    </div>
</form>