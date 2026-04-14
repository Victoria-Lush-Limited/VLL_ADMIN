<?php
include "db/dblink.php";

$start_row = $_GET['start_row'] - 1;
$per_page = $_GET['per_page'];

$previous_start_row = $start_row - $per_page;
$table_rows = 0;

$keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

$q = mysqli_query($conn, "SELECT * FROM senders WHERE sender_id LIKE '%" . $keyword . "%' || message LIKE '%" . $keyword . "%'");

$found = mysqli_num_rows($q);

?>

<table>
    <tr class="table-header">
        <td>Sender</td>
        <td>Client</td>
        <td>Sample Message</td>
        <td>Type</td>
        <td>Requested</td>
        <td>Status</td>
        <td></td>
    </tr>
    <?php
    $q = mysqli_query($conn, "SELECT * FROM senders WHERE sender_id LIKE '%" . $keyword . "%' || message LIKE '%" . $keyword . "%' ORDER BY date_requested DESC LIMIT " . $start_row . "," . $per_page);
    if ($found) {

        while ($sender = mysqli_fetch_assoc($q)) {
            $table_rows += 1;
            $c=mysqli_query($conn,"SELECT * FROM users WHERE user_id='".$sender['user_id']."'");
            $client=mysqli_fetch_assoc($c);
    ?>
            <tr>
                <td><?php echo $sender['sender_id']; ?></td>
                <td><?php echo $client['client_name']; ?></td>
                <td><?php echo $sender['message']; ?></td>
                <td><?php echo $sender['id_type']; ?></td>
                <td><?php echo date("d-m-Y", $sender['date_requested']); ?></td>
                <td><?php echo $sender['id_status']; ?></td>
                <td>
                    <div class="row-options">
                        <i class="fas fa-edit fa-lg" onclick="edit_sender(<?php echo $sender['id']; ?>)"></i>
                        <i class="fas fa-trash fa-lg" onclick="delete_senders(<?php echo $sender['id']; ?>)"></i>
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
        <i class="fas fa-chevron-left fa-s" <?php if ($start_row > 0) { ?> onclick="get_senders(<?php echo $previous_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
        <i class="fas fa-chevron-right fa-s" <?php if ($next_start_row <= $found) { ?> onclick="get_senders(<?php echo $next_start_row; ?>,<?php echo $per_page; ?>)" <?php } ?>></i>
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
            <select name="per_page" id="per_page" onchange="get_senders(<?php echo ($start_row + 1); ?>,this.value)">
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