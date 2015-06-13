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
        $Items = $searchResults->item;
        foreach ($Items as $item){
            $scope = array(
                "item" => $item
            );
            Utils::getTemplate('searchResultEntry', $scope);
        }
    }
    ?>
</ul>