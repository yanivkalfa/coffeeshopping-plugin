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

        <div id="paginationdivcont" align="center">
        <?php
        // Form our current page link:
        $searchPageLink = get_permalink()."?search-product=".urlencode($searchVal);
        // Build our pagination line:
        $display    =   10;
        $current    =   $searchResults->paginationOutput[$API]["pageNumber"];
        $last       =   $searchResults->paginationOutput[$API]["totalPages"];
        $limit      =   $current + round($display / 2, 1);
        if ($limit > $last){$limit = $last;}
        $init       =   $current - round($display / 2, 1);
        // Output back.
        if ($current>1){echo "<div class=\"pagelink pageback\">&nbsp;</div>";}
        // Output 10 pages.
        for ($d = $init; $d < $limit; $d++) {
            if ($d <= 0) {$limit++;continue;}       // Start from page 1.
            if ($d > $limit || $d >$last) {break;}  // Don't pass the limit.
            if ($d == $current){
                echo " <div class=\"pagelink currpage\">" . $current . "</div> ";
            }else{
                $formedLink = $searchPageLink."&pg=".$d."&ppg=".$searchResults->paginationOutput[$API]["entriesPerPage"];
                echo " <div class=\"pagelink\"><a href=\"".esc_url($formedLink)."\">" . $d . "</a></div> ";
            }
        }
        // Output next.
        if ($current<$last){echo "<div class=\"pagelink pagenext\">&nbsp;</div>";}
        ?>
            <div class="perpagedisplaytext">&nbsp;per page:&nbsp;</div>
            <div id="perpageselect">
                <?php
                $formedLink = $searchPageLink."&pg=".$searchResults->paginationOutput[$API]["pageNumber"];
                ?>
                <div class="perpageopt"><?php echo $searchResults->paginationOutput[$API]["entriesPerPage"];?></div>
                <ul class="perpageopts">
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==10) ? "" : "<li class=\"perpageopt\"><a href=\"".esc_url($formedLink."&ppg=10")."\">10</a>" ;?></li>
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==25) ? "" : "<li class=\"perpageopt\"><a href=\"".esc_url($formedLink."&ppg=25")."\">25</a>" ;?></li>
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==50) ? "" : "<li class=\"perpageopt\"><a href=\"".esc_url($formedLink."&ppg=50")."\">50</a>" ;?></li>
                </ul>
            </div>

        </div>
    </li>
    <?php
    }

    ?>
</ul>

