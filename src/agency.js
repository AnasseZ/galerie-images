(function($) {
  "use strict"; // Start of use strict

  $("div[id^='myModal']").each(function() {
    var currentModal = $(this);

    //click next
    currentModal.find(".btn-next").click(function() {
      currentModal.modal("hide");
      currentModal
        .closest("div[id^='myModal']")
        .nextAll("div[id^='myModal']")
        .first()
        .modal("show");
    });

    //click prev
    currentModal.find(".btn-prev").click(function() {
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
