<?php
include "db/dblink.php";

$start_row = $_GET['start_row'] - 1;
$per_page = $_GET['per_page'];

$previous_start_row = $start_row - $per_page;
$table_rows = 0;

$keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

$q = mysqli_query($conn, "SELECT * FROM sms_orders WHERE  (reference LIKE '%" . $keyword . "%' OR receipt LIKE '%" . $keyword . "%' OR user_id LIKE '%" . $keyword . "%') ORDER BY order_date DESC");

$found = mysqli_num_rows($q);
?>
<table>
    <tr class="table-header">
        <td>Order No.</td>
        <td>Date</td>
        <td>Account Name</td>
        <td>Qty</td>
        <td style="text-align:right;">Price</td>
        <td style="text-align:right;">Amount</td>
        <td style="text-align:center;">Status</td>
        <td>Receipt No.</td>
        <td>Payment Method</td>
        <td></td>
    </tr>
    <?php
    $q = mysqli_query($conn, "SELECT * FROM sms_orders WHERE (reference LIKE '%" . $keyword . "%' OR receipt LIKE '%" . $keyword . "%' OR user_id LIKE '%" . $keyword . "%') ORDER BY order_date DESC LIMIT " . $start_row . "," . $per_page);
    if ($found) {
        while ($order = mysqli_fetch_assoc($q)) {
            $table_rows += 1;

            $account_name = "";

            if ($order['account_type'] == "Broadcaster") {
                $c = mysqli_query($conn, "SELECT * FROM users WHERE user_id='" . $order['user_id'] . "'");
                $client = mysqli_fetch_assoc($c);
                $account_name = $client['client_name'];
            }

            if ($order['account_type'] == "Reseller") {
                $c = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $order['user_id'] . "'");
                $reseller = mysqli_fetch_assoc($c);
                $account_name = $reseller['business_name'];
            }


            if ($order['account_type'] == "Agent") {
                $c = mysqli_query($conn, "SELECT * FROM agents WHERE user_id='" . $order['user_id'] . "'");
                $agent = mysqli_fetch_assoc($c);
                $account_name = $agent['agent_name'];
            }

    ?>
            <tr>
                <td><?php echo $order['reference']; ?></td>
                <td><?php echo date("d-m-Y H:i", $order['order_date']); ?></td>
                <td><?php echo $account_name; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td align="right"><?php echo round($order['price']); ?></td>
                <td align="right"><?php echo number_format($order['amount']); ?></td>
                <td align="center"><?php echo $order['order_status']; ?></td>
                <td><?php echo $order['receipt']; ?></td>
                <td><?php echo $order['payment_method']; ?></td>
                <td>
                    <div class="row-options">
                        <?php
                        if ($order['order_status']=="Pending" AND ($order['account_type'] == "Reseller" || $order['account_type']=='Agent' || ($order['account_type'] == "Broadcaster" && $client['reseller_id'] == "Administrator"))) { ?>
                            <i class="fas fa-money-bill-wave fa-lg" style="color:orange;" onclick="allocate_credit('<?php echo $order['order_id']; ?>')"></i>
                            <i class="fas fa-trash fa-lg" onclick="delete_sms_order('<?php echo $order['order_id']; ?>')"></i>
                        <?php }  ?>
                    </div>
                </td>
            </tr>
    <?php
        }
    }

    $next_start_row = $start_row + $table_rows + 1;
    $previous_start_row = $start_row - $per_page + 1;

    if ($found) {
        $showing_from = $start_row + 1;
    } else {
        $showing_from = 0;
    }

    $showing_to = $start_row + $table_rows;
    ?>
</table>

<div class="pagination">
    <div class="page-nav">
        <i class="fas fa-chevron-left fa-s" <?php if ($start_row > 0) { ?> onclick="get_sales(<?php echo $previous_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
        <i class="fas fa-chevron-right fa-s" <?php if ($next_start_row <= $found) { ?> onclick="get_sales(<?php echo $next_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
        <div class="page-records">
            Showing
            <b><?php echo number_format($showing_from); ?> - <?php echo number_format($showing_to); ?> </b>
            of
            <b><?php echo $found; ?></b>
            records
        </div>
    </div>
    <ul class="page-rows">
        <li><label>Per Page:</label></li>
        <li>
            <select name="per_page" id="per_page" onchange="get_sales(<?php echo ($start_row + 1); ?>,this.value)">
                <option value="25">25 rows</option>
                <option value="50">50 rows</option>
                <option value="100">100 rows</option>
                <option value="250">250 rows</option>
                <option value="500">500 rows</option>
            </select>
            <input type="hidden" name="start_row" id="start_row" value="<?php echo $start_row + 1; ?>">
        </li>
    </ul>
</div>