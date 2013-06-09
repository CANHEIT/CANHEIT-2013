var meny = Meny.create({
    menuElement: document.querySelector( '.meny' ),
    contentsElement: document.querySelector( '.content' ),
    position: 'left',
    width: 260,
    threshold: 0,
    use3D: false
  });
$('#hamburger-icon').click(function(){meny.open();});