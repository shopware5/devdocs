(function($, window) {
    if($('.content--wrapper').children('div').hasClass('custom-detail')) {
        window.StateManager.removePlugin('.product--image-zoom', 'swImageZoom', 'xl');
    }
}(jQuery, window));