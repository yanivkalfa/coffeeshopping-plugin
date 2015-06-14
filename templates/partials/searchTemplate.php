<ul class="searchresultsul nolistbull">
    <?php
    // deals with searchALL() object.
    foreach ($searchResults->items as $API => $items) {
    ?>
    <li class="searchresultsli">
        <h2><?php echo $API;?> Search Results (<?php echo $searchResults->paginationOutput[$API]["totalEntries"];?>): <?php echo $searchVal;?></h2>
        <ul class="searchresultsstore nolistbull">
            <?php
            foreach ($items as $item) {
                $productPageLink = $productPage . "?view-product=" . $item["ID"] . "&store=" . $API;
                $scope = array(
                    "item" => $item,
                    "productPageLink" => $productPageLink,
                    "exchangeExtension" => $exchangeExtension,
                    "API" => $API,
                );
                Utils::getTemplate('searchResultEntry', $scope);
            }
            ?>
        </ul>

        <div id="paginationdivcont">
        <?php
        // Build our pagination line:
        $display    =   10;
        $current    =   $searchResults->paginationOutput[$API]["pageNumber"];
        $last       =   $searchResults->paginationOutput[$API]["totalPages"];
        $limit      = $current + round($display / 2, 1);
        if ($limit > $last){$limit = $last;}
        $init       = $current - round($display / 2, 1);
        // Output back.
        if ($current>1){echo "<div class=\"pagelink pageback\"></div>";}
        // Output 10 pages.
        for ($d = $init; $d < $limit; $d++) {
            if ($d <= 0) {$limit++;continue;}       // Start from page 1.
            if ($d > $limit || $d >$last) {break;}  // Don't pass the limit.
            echo ($d == $current) ? " <div class=\"pagelink currpage\">" . $current . "</div> " : " <div class=\"pagelink\">" . $d . "</div> ";
        }
        // Output next.
        if ($current<$last){echo "<div class=\"pagelink pagenext\"></div>";}
        ?>
        </div>
    </li>
    <?php
    }

    ?>
</ul>