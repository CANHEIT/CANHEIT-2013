var meny = Meny.create({
    menuElement: document.querySelector( '.meny' ),
    contentsElement: document.querySelector( '.container' ),
    position: 'left',
    width: 260,
    threshold: 40,
    use3D: false
  });
$('#hamburger-icon').click(function(){meny.open();});