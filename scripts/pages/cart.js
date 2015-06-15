jQuery(document).ready( function(){

    $('.product-quantity').on('keypress',  function(){
        $(this).parents('.cart-product-part').find('.cart-product-update').removeClass('display-none');
    })
});