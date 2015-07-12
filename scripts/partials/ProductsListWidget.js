/**
 * Created by SK on 6/30/2015.
 */

jQuery(document).ready( function(){
    var masonry = jQuery('.grid');
    masonry.imagesLoaded( function() {
        // init Masonry after all images have loaded
        masonry.masonry({
            // options:
            itemSelector: '.grid-item',
            columnWidt: 225
        });
    });
});