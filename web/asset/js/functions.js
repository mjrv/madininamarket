function dropdownMenu()
{
  // Gestion du dropdown
  var timerIn = 200;
  var timerOut = 200;
  $('ul li.dropdown').hover(function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeIn(timerIn);
    $(this).addClass('open');
  }, function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeOut(timerOut);
    $(this).removeClass('open');
  });

  $('ul li.dropdown-submenu').hover(function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeIn(timerIn);
    $(this).addClass('open');
  }, function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeOut(timerOut);
    $(this).removeClass('open');
  });

}