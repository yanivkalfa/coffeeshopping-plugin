<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/2/2015
 * Time: 1:49 PM
 */
/**
 * How to add a new template page to gantry properly.
 * 1. Duplicate the index.php file from the theme root directory and name it to something else ie. page-artistas.php
 * 2. Add to that file template declaration like with any other WP page template (in the comment area at the top of the file)
 * 3. Find this line :
 * <?php echo $gantry->displayMainbody('mainbody','sidebar','standard','standard','standa rd','standard','standard'); ?>
 * and change the name of the loaded body layout (mainbody in that case) to something else ie.
 * <?php echo $gantry->displayMainbody('artistas','sidebar','standard','standard','standa rd','standard','standard'); ?>
 * 4. Save the file
 * 5. Go to the wp-content/plugins/gantry/html/layouts and copy the file body_mainbody.php to theme-directory/html/layouts/
 * 6. Rename the file to body_artistas.php
 * 7. Edit the body_artistas.php file and change the classname from GantryLayoutBody_MainBody to GantryLayoutBody_Artistas
 * 8. Now you can edit the new body layout to your needs
 *
 * Our files:
 *  \wp-content\themes\rt_gantry_wp\html\layouts\
 *  \wp-content\themes\rt_gantry_wp
 */

if (
            (isset($_GET["view-product"]) && !empty($_GET["view-product"]))
            &&
            (isset($_GET["store"]) && !empty($_GET["store"]) && utils::API_Exists($_GET["store"]))
        ) {
            // Sanitize our product id and store name.
            $productID = $_GET["view-product"];
            $store = $_GET["store"];

            // Our options array.
            $itemOpts = array();
            // Requested details.
            $itemOpts["IncludeSelector"] = explode(",", "Details,Description,ItemSpecifics,Variations,Compatibility");
            $sandbox = false;

            // performs the actual request.
            $result = productView::getProduct($store, $productID, $itemOpts, $sandbox);

            // Output results if we have any proper ones, else display errors.
            if ($result["result"] == "ERROR") {
                echo productView::displayError($result["output"]);
            } else {
                echo productView::constructResults($result["output"]);
            }
}

?>