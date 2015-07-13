<ul class="searchresultsul nolistbull">
    <?php
    // deals with searchALL() object.
    foreach ($searchResults->items as $API => $items) {
    ?>
    <li class="searchresultsli">
        <h2><?php echo $API;?> <?php _e("Search Results", 'coffee-shopping' ); ?> (<?php echo $searchResults->paginationOutput[$API]["totalEntries"];?>): <?php echo $searchVal;?></h2>
        <ul class="searchresultsstore nolistbull">
            <?php
            foreach ($items as $item) {
                $productPageLink = $productPage . "?view-product=" . $item["ID"] . "&store=" . $API;
                $scope = array(
                    "item" => $item,
                    "productPageLink" => $productPageLink,
                    "API" => $API,
                );
                Utils::getTemplate('searchResultEntry', $scope);
            }
            ?>
        </ul>

        <div id="paginationdivcont" align="center">
        <?php
        // Form our current page link:
        $searchPageLink = get_permalink()."?".http_build_query($_GET);
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
                echo " <a href=\"".esc_url($formedLink)."\"><div class=\"pagelink\">" . $d . "</div></a> ";
            }
        }
        // Output next.
        if ($current<$last){echo "<div class=\"pagelink pagenext\">&nbsp;</div>";}
        ?>
            <div class="perpagedisplaytext">&nbsp;<?php _e("per page:", 'coffee-shopping' ); ?>&nbsp;</div>
            <div id="perpageselect">
                <?php
                $formedLink = $searchPageLink."&pg=".$searchResults->paginationOutput[$API]["pageNumber"];
                ?>
                <div class="perpageopt"><?php echo $searchResults->paginationOutput[$API]["entriesPerPage"];?></div>
                <ul class="perpageopts">
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==10) ? "" : "<a href=\"".esc_url($formedLink."&ppg=10")."\"><li class=\"perpageopt\">10" ;?></li></a>
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==25) ? "" : "<a href=\"".esc_url($formedLink."&ppg=25")."\"><li class=\"perpageopt\">25" ;?></li></a>
                    <?php echo ($searchResults->paginationOutput[$API]["entriesPerPage"]==50) ? "" : "<a href=\"".esc_url($formedLink."&ppg=50")."\"><li class=\"perpageopt\">50" ;?></li></a>
                </ul>
            </div>

        </div>
    </li>
    <?php
    }

    ?>
</ul>

