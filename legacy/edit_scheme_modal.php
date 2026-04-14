<?php
include "db/dblink.php";

$scheme_id = mysqli_real_escape_string($conn, $_GET['scheme_id']);
$q = mysqli_query($conn, "SELECT * FROM pricing_schemes WHERE scheme_id='" . $scheme_id . "'");

$scheme = mysqli_fetch_assoc($q);
?>
<div class="form-field">
    <label for=""><?php echo $scheme['scheme_name']; ?></label>

</div>
<table>
    <tr style="background-color:whitesmoke; font-size:16px; font-weight:900;">
        <td>SMS Quantity</td>
        <td align="center">Price (TSH)</td>
        <td style="width:5%;"></td>
    </tr>
    <?php
    $q = mysqli_query($conn, "SELECT * FROM pricing WHERE scheme_id='" . $scheme['scheme_id'] . "' ORDER BY min_sms ASC");
    while ($price = mysqli_fetch_assoc($q)) {

        if ($price['max_sms'] == 0) {
            $max_sms = "Above";
        } else {
            $max_sms = number_format($price['max_sms']);
        }
    ?>
        <tr>
            <td><?php echo number_format($price['min_sms']) . " - " . $max_sms; ?></td>
            <td align="center"><?php echo round($price['price']) ?></td>
            <td>
                <div class="row-options"><i class="fas fa-times fa-s" style="color:red;" onclick="delete_pricing('<?php echo $price['pricing_id']; ?>','<?php echo $price['scheme_id']; ?>')"></i></div>
            </td>
        </tr>
    <?php } ?>
</table>
<div class="form-field">
    <div class="pricing-box">
        <div>
            <label for="">Start</label>
            <input type="text" id="min_sms">
        </div>
        <div>
            <label for="">End</label>
            <input type="text" id="max_sms">
        </div>

        <div>
            <label for="">Price</label>
            <input type="text" id="price">
        </div>
    </div>
</div>
<div class="form-field">
    <div class="send-button" onclick="save_pricing(<?php echo $scheme['scheme_id']; ?>)">Submit</div>
</div>
<div class="form-field">
    <div class="form-errors" id="form_errors"></div>
</div>