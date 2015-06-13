<ul class="searchresultsul nolistbull">
    <?php
    if (isset($searchResults->items)){
        // deals with searchALL() object.
        foreach ($searchResults->items as $API => $Items){
            ?>
    <li>
        <?php echo $API;?> Search Results:
        <ul>

            <?php
            foreach ($Items as $item){
                $scope = array(
                    "item" => $item
                );
                Utils::getTemplate('searchResultEntry', $scope);
            }
            ?>
        </ul>
    </li>
            <?php
        }

    }else{
        // deals with searchAPI() array results.


        $searchPageId = get_option("cs_search_p_id");
        if (!$searchPageId){
            Utils::adminPreECHO("productSearch::searchALL(...) failed!", "searchLoader() ERROR:: ");
            $scope = array(
                "errorsText" => $result["output"]
            );
        }
        get_page_link($searchPageId);
        $items = $searchResults->item;
        foreach ($items as $item){
            $scope = array(
                "item" => $item
            );
            Utils::getTemplate('searchResultEntry', $scope);
        }
    }
    ?>
</ul>