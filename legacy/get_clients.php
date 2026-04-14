<?php
include "db/dblink.php";

$start_row = $_GET['start_row'] - 1;
$per_page = $_GET['per_page'];

$previous_start_row = $start_row - $per_page;
$table_rows = 0;

$keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

$q = mysqli_query($conn, "SELECT * FROM users ORDER BY user_date_created DESC");

$found = mysqli_num_rows($q);
?>
<table>
    <tr class="table-header">
        <td>Username</td>
        <td>Client Name</td>
        <td>Phone Number</td>
        <td>Email</td>
        <td style="text-align:right;">Balance</td>
        <td>Reseller</td>
        <td>Status</td>
        <td></td>
    </tr>
    <?php
    $q = mysqli_query($conn, "SELECT * FROM users ORDER BY user_date_created DESC LIMIT " . $start_row . "," . $per_page);
    if ($found) {
        while ($client = mysqli_fetch_assoc($q)) {
            $table_rows += 1;
            $r = mysqli_query($conn, "SELECT * FROM resellers WHERE user_id='" . $client['reseller_id'] . "'");
            $reseller = mysqli_fetch_assoc($r);

            $b = mysqli_query($conn, "SELECT (SUM(allocated)-SUM(consumed)) AS balance FROM transactions WHERE user_id='" . $client['user_id'] . "'");
            $bal = mysqli_fetch_assoc($b);
            $balance = $bal['balance'];
    ?>
            <tr style="background-color:  <?php echo $bg; ?>;  font-size:var(--small-text);">
                <td><?php echo $client['username']; ?></td>
                <td><?php echo $client['client_name']; ?></td>
                <td><?php echo $client['contact_phone']; ?></td>
                <td><?php echo $client['email']; ?></td>
                <td align="right"><?php echo number_format($balance); ?></td>

                <td><?php echo $reseller['business_name']; ?></td>
                <td><?php echo $client['status']; ?></td>
                <td>
                    <div class="row-options">
                        <a href="client_account.php?client_id=<?php echo $client['user_id']; ?>"><i class="fas fa-user-cog fa-lg" style="color:var(--blue-color);"></i></a>
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
        <i class="fas fa-chevron-left fa-s" <?php if ($start_row > 0) { ?> onclick="get_clients(<?php echo $previous_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
        <i class="fas fa-chevron-right fa-s" <?php if ($next_start_row <= $found) { ?> onclick="get_clients(<?php echo $next_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
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
            <select name="per_page" id="per_page" onchange="get_clients(<?php echo ($start_row + 1); ?>,this.value)">
                <option value="10">10 rows</option>
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