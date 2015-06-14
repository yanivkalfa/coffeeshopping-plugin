<ul class="searchresultsul nolistbull">
    <?php
    // deals with searchALL() object.
    foreach ($searchResults->items as $API => $items) {
    ?>
    <li>
        <?php echo $API;?> Search Results:
        <ul>
            <?php
            foreach ($items as $item) {
                $productPageLink = $productPageLink . "?view-product=" . $item->ID . "&store=" . $API;
                $scope = array(
                    "item" => $item,
                    "productPageLink" => $productPageLink
                );
                Utils::getTemplate('searchResultEntry', $scope);
            }
            ?>
        </ul>
    </li>
    <?php
    }
    ?>
</ul>