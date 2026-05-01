var navInside = $('header .bottom nav .max-width ul').html();
$('body').append('<nav class="mobilmenu" id="mobilmenu"><ul>' + navInside + '</ul></nav>');
$('#mobilmenu').mmenu({
    offCanvas: {
        position: "right"
    },
    "extensions": [
        "fx-menu-slide",
        "fx-panels-zoom",
        "fx-listitems-slide",
        "pagedim-black",
        "theme-dark",
        "shadow-page",
        "shadow-panels"
    ],
    navbars: {
        title: "Menu",
        position: "top",
        content: ["close"],
        height: 2

    }
});
$(document).ready(function () {
    $('.owl-carousel-slider').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5500,
        smartSpeed: 2500,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1000: {
                items: 1,
            }
        }
    });
})
;




