jQuery('.carousel').carousel({
	interval: interval,
	pause: pause,
	wrap:wrap
})
/*jQuery(document).ready(function() {
  jQuery("#myCarousel").on("slide.bs.carousel", function(e) {
    var $e = jQuery(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 4;
    var totalItems = jQuery(".carousel-item").length;

    if (idx >= totalItems - (itemsPerSlide - 1)) {
      var it = itemsPerSlide - (totalItems - idx);
      for (var i = 0; i < it; i++) {
        // append slides to end
        if (e.direction == "left") {
          jQuery(".carousel-item")
            .eq(i)
            .appendTo(".carousel-inner");
        } else {
          jQuery(".carousel-item")
            .eq(0)
            .appendTo(jQuery(this).find(".carousel-inner"));
        }
      }
    }
  });
});*/