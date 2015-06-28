<div align="center" class="cartstatusicons">
    <i class="fa fa-check-square-o fa-<?php echo $size;?>x<?php echo (  array_search($status, $statusArr) >= array_search("saved",      $statusArr)) ? ' done':'';?>"></i> &nbsp;
    <i class="fa fa-ils fa-<?php echo $size;?>x<?php echo (             array_search($status, $statusArr) >= array_search("paid",       $statusArr)) ? ' done':'';?>"></i> &nbsp;
    <i class="fa fa-truck fa-<?php echo $size;?>x<?php echo (           array_search($status, $statusArr) >= array_search("storage",    $statusArr)) ? ' done':'';?>"></i> &nbsp;
    <i class="fa fa-cubes fa-<?php echo $size;?>x<?php echo (           array_search($status, $statusArr) >= array_search("at_store",   $statusArr)) ? ' done':'';?>"></i> &nbsp;
    <i class="fa fa-thumbs-up fa-<?php echo $size;?>x<?php echo (       array_search($status, $statusArr) >= array_search("delivered",  $statusArr)) ? ' done':'';?>"></i> &nbsp;
</div>