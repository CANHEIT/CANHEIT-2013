var meny = Meny.create({
    menuElement: document.querySelector( '.meny' ),
    contentsElement: document.querySelector( '.content' ),
    position: 'left',
    width: 260,
    threshold: 0,
    use3D: false
  });
$('#menu-icon').click(function(){meny.open(); event.preventDefault();});

meny.addEventListener( 'open', function() {
    /*
        HACK: set position of meny to absolute for mobile devices
        ie. window height is < 620px (arbitrary cutoff at approx menu height)

        why? meny sets position of menu to "fixed", with no option to set otherwise
        the workaround it when the "open" event for the menu is triggered, set the
        menu position to absolute if the viewport can't fit the entire menu

        TODO: make the height of the menu be calculated on the fly
    */
    if ($(window).height() < 620) {
        $('.meny').css('position', 'absolute');
    }
} );
