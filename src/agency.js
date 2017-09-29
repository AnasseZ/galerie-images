$(window).scroll(function() {
  sessionStorage.scrollTop = $(this).scrollTop();
});

$(document).ready(function() {
  if (sessionStorage.scrollTop != "undefined") {
    $(window).scrollTop(sessionStorage.scrollTop);
  }
});
(function($) {
  "use strict"; // Start of use strict

  $("div[id^='myModal']").each(function() {
    var currentModal = $(this);

    //click next
    currentModal.find(".btn-next").click(function() {
      console.log(currentModal.attr("data-next"));
      loadBigPic(currentModal.attr("data-next"));
      currentModal.modal("hide");
      currentModal
        .closest("div[id^='myModal']")
        .nextAll("div[id^='myModal']")
        .first()
        .modal("show");
    });

    //click prev
    currentModal.find(".btn-prev").click(function() {
      console.log(currentModal.attr("data-prev"));
      loadBigPic(currentModal.attr("data-prev"));
      currentModal.modal("hide");
      currentModal
        .closest("div[id^='myModal']")
        .prevAll("div[id^='myModal']")
        .first()
        .modal("show");
    });
  });
  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      if (target.length) {
        $("html, body").animate(
          {
            scrollTop: target.offset().top - 54
          },
          1000,
          "easeInOutExpo"
        );
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $(".js-scroll-trigger").click(function() {
    $(".navbar-collapse").collapse("hide");
  });

  $(".galery-hover").click(function() {
    var img = $(this).next();
    var id = img.attr('data-id');
    loadBigPic(id);
  });

  function loadBigPic(id){
    var attr = $(id).attr("src");
    if(typeof attr !== undefined && attr !== false){
      $(id).attr('src', $(id).attr('data-src'));
    }
  }
  // Activate scrollspy to add active class to navbar items on scroll
  $("body").scrollspy({
    target: "#mainNav",
    offset: 54
  });

  // Collapse the navbar when page is scrolled
  $(window).scroll(function() {
    if ($("#mainNav").offset().top > 100) {
      $("#mainNav").addClass("navbar-shrink");
    } else {
      $("#mainNav").removeClass("navbar-shrink");
    }
  });
})(jQuery); // End of use strict
