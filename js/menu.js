var meny = Meny.create({
    menuElement: document.querySelector( '.meny' ),
    contentsElement: document.querySelector( '.content' ),
    position: 'left',
    width: 260,
    threshold: 0
  });
$('#menu-icon').click(function(){meny.open(); event.preventDefault();});