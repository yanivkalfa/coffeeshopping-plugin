<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 */

abstract class productViewTemplates {

    static public function getProductView($product){
        ob_start();
        ?>

        <?php
        return ob_get_clean();
    }
}

?>