<?php
function changeStatus($status) {
    $status = (string) $status; // Ensure it's treated as a string
    switch ($status) {
        case '1':
            $badge = "<span class='badge bg-success'>Sudah dikembalikan</span>";
            break;
        default:
            $badge = "<span class='badge bg-primary'>Baru</span>";
            break;
    }
    return $badge;
}

?>