if( /Android|webOS|iPhone|iPad|iOS|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('html').addClass('touch');
} else {
    $('html').addClass('non-touch');
}