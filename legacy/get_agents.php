<?php
include "db/dblink.php";

$start_row = $_GET['start_row'] - 1;
$per_page = $_GET['per_page'];

$previous_start_row = $start_row - $per_page;
$table_rows = 0;

$keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

$q = mysqli_query($conn, "SELECT * FROM agents ORDER BY date_created DESC");

$found = mysqli_num_rows($q);
?>
<table>
    <tr class="table-header">
        <td>Reseller Name</td>
        <td>Email</td>
        <td style="text-align:right;">Balance</td>
        <td>Status</td>
        <td></td>
    </tr>
    <?php
    $q = mysqli_query($conn, "SELECT * FROM agents ORDER BY date_created ASC LIMIT " . $start_row . "," . $per_page);
    if ($found) {
        while ($agent = mysqli_fetch_assoc($q)) {
            $table_rows += 1;
            
            $b = mysqli_query($conn, "SELECT (SUM(allocated)-SUM(consumed)) AS balance FROM transactions WHERE user_id='" . $agent['user_id'] . "'");
            $bal = mysqli_fetch_assoc($b);
            $balance = $bal['balance'];
    ?>
            <tr style="background-color:  <?php echo $bg; ?>;  font-size:var(--small-text);">
                <td><?php echo $agent['agent_name']; ?></td>
                <td><?php echo $agent['user_id']; ?></td>
                <td align="right"><?php echo number_format($balance); ?></td>
                <td><?php echo $agent['status']; ?></td>
                <td>
                    <div class="row-options">
                        <a href="agent_account.php?agent_id=<?php echo $agent['user_id']; ?>"><i class="fas fa-user-cog fa-lg" style="color:var(--blue-color);"></i></a>
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
        <i class="fas fa-chevron-left fa-s" <?php if ($start_row > 0) { ?> onclick="get_resellers(<?php echo $previous_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
        <i class="fas fa-chevron-right fa-s" <?php if ($next_start_row <= $found) { ?> onclick="get_resellers(<?php echo $next_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
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
            <select name="per_page" id="per_page" onchange="get_resellers(<?php echo ($start_row + 1); ?>,this.value)">
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