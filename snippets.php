<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Snippets Github API's - <?php echo $title; ?></title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <!-- Personal stylesheet -->
    <link rel="stylesheet" href="public/assets/css/main.css">
</head>
<body>

<main class="containerHome"  id="">

<?php

require 'src/Request.php';

use FJA\Request;

if (isset($_GET['extends'])) {
    $varExtend = TRUE;
}else {
    $varExtend = FALSE;
}

$user = new Request($_GET['user'], $_GET['var']);

echo $user->snippetsLite($varExtend);
if (isset($_GET['extends'])) {
    echo $user->snippetsFat();
}



?>
</main>


<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<!-- Import main.js -->
<script src="public/assets/js/main.js"></script>
</body>
</html>
