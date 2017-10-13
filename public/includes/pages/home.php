<div class="centerApp center">
<!-- display home page -->
<?php
  
$preview =  file_get_contents("cache.html");

$preview = str_replace('&#60;','<',$preview);
$preview = str_replace('&#62;','>',$preview);

$preview = str_replace('380px','85%',$preview);
$preview = str_replace('500px','1000px',$preview);

echo $preview;

?>
</div>
<div class="fixed-action-btn">
    <a class="btn-floating btn-large red waves-effect waves-light" href="?page=update" title="updateDisplay">
        <i class="large material-icons">mode_edit</i>
    </a>
</div>
