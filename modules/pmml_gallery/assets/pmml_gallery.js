var $ = jQuery.noConflict();

$('.gallery-close-icon').click( function(e){
    e.preventDefault();
    $('.lightbox-gallery-row .image-block').removeClass('lightbox-gallery-active');
    $('#lightBoxGallery').hide();

});


$('.lightbox-gallery-row .image-block').click( function(e){
    e.preventDefault();
    $(this).addClass('lightbox-gallery-active');
    // get data and replace in modal
    //let image_box = $(this).closest('.lightbox-gallery-active .image-block');
    initializeLightBoxGallery();
});


$('#lightBoxGallery .right-control a').click( function(e){
    e.preventDefault();
    
    let nav_item = $('.lightbox-gallery-active').next();
    if( nav_item.length == 0 ) {
        nav_item = $('.image-block').first();    
    }
    $('.image-block').removeClass('lightbox-gallery-active');
    nav_item.addClass('lightbox-gallery-active');

    initializeLightBoxGallery();
});

$('#lightBoxGallery .left-control a').click( function(e){
    e.preventDefault();

    let nav_item = $('.lightbox-gallery-active').prev();
    if( nav_item.length == 0 ) {
        nav_item = $('.image-block').last();    
    }
    $('.image-block').removeClass('lightbox-gallery-active');
    nav_item.addClass('lightbox-gallery-active');

    initializeLightBoxGallery();
});

function initializeLightBoxGallery(){
    $('#lightBoxGallery').hide();
    let image_tag = $('.lightbox-gallery-active .image-block-data .views-field-field-exhibit-image .field-content').html();
    let body_tag = $('.lightbox-gallery-active .image-block-data .views-field-field-teaser-caption .field-content').html();
    let button_tag = $('.lightbox-gallery-active .image-block-data .views-field-view-node .field-content').html();

    $('#lightBoxGallery .lightbox-gallery-left').html( image_tag );
    $('#lightBoxGallery .lightbox-gallery-right .lightbox-gallery-title').html( body_tag );
    $('#lightBoxGallery .lightbox-gallery-right .lightbox-gallery-button').html( button_tag );
    

    let total_images = $('.lightbox-gallery .image-block').length;
    let current_index = $(".lightbox-gallery .image-block:not(:has(.lightbox-gallery-active))").toArray().findIndex(    function(item) {
            return $(item).hasClass('lightbox-gallery-active');
        });

    current_index += 1;
    $('#lightBoxGallery .lightbox-gallery-modal-footer').html( '<p>  '+ current_index +' of '+total_images+'</p>');
    $('#lightBoxGallery').show();
}