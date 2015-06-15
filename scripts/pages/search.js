// shlomer this is all the product details we need.
// right now i added only 2 methods for manipulating cart:
// addProduct and saveCart

//To update parts of the sites we dont have direct access or several thing can be effected by one thing like:
// say you add an item or increase the quantity on an item, or remove an item (the cart total will change) and this should be
// reflected both in the top cart place and say view cart page.

// we are using pub/sub system where certain part of the site subscribes to a channel or event. and another part of the site publish to that channel.
// in out case the cart header subscribes to cart update event. and addProduct/saveCart publish to that channel.

$(document).ready(function(){


});
