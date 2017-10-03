function dropdownMenu()
{
  // Gestion du dropdown
  var timerIn = 2000;
  var timerOut = 2000;
  $('ul li.dropdown').hover(function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeIn(timerIn);
    $(this).addClass('open');
  }, function() {
    $(this).find('> .dropdown-menu').stop(true, true).fadeOut(timerOut);
    $(this).removeClass('open');
  });
}