<ul class="searchresultsul nolistbull">
    <?php
    if (isset($searchResults->items)){
        // deals with searchALL() object.
        foreach ($searchResults->items as $API => $Items){
            ?>
            <?php echo $API;?> Search Results:
            <?php
            foreach ($Items as $item){
                $scope = array(
                    "item" => $item
                );
                Utils::getTemplate('searchResultsEntry', $scope);
            }
        }
    }else{
        // deals with searchAPI() array results.
        $Items = $searchResults["output"]->item;
        foreach ($Items as $item){
            $scope = array(
                "item" => $item
            );
            Utils::getTemplate('searchResultsEntry', $scope);
        }
    }
    ?>
</ul>