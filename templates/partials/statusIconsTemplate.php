<div align="center" class="cartstatusicons">

    <?php
    $keys = array_keys($statusArr);
    foreach($keys as $statusKey => $keyVal){
    ?>
    <i title="<?php echo $statusArr[$keyVal]["nameAs"]; ?>" class="fa <?php echo $statusArr[$keyVal]["fa-icon"]; ?> fa-<?php echo $size;?>x<?php echo (  array_search($status, $keys) >= array_search($keyVal,      $keys)) ? ' done':'';?>"></i> &nbsp;
    <?php } ?>

</div>