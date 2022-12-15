// NAVIGATION

let navId = "#" + $(location).attr('href').split("?")[1].split("&")[0].split("=")[1];
if ($(navId))
  $(navId).addClass("active");
