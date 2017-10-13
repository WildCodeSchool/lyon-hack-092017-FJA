<?php
// Check if page are in URI
if (isset($_GET['page'])) {
    $display = $_GET['page'];
} else {
    $display = "home";
}
// Check case match with the case in URI
switch ($display) {
    case 'home':
        $link = "home.php";
        $title = "Home";
        break;
    case 'update':
        $link = "update.php";
        $title = "Admin";
        break;
    default:
        $link = "home.php";
        break;
}

?>