$(document).ready(function () {
    'use strict';

    /**
     * Bootstrap Select
     */
    $('select').selectpicker();

    /**
     * Background image
     */
    $('*[data-background-image]').each(function () {
        $(this).css({
            'background-image': 'url(' + $(this).data('background-image') + ')'
        });
    });

    /**
     * Bootstrap Tooltip
     */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    /**
     * Map
     */


    /**
     * Colorbox
     */
    $('.detail-gallery-preview a').colorbox();

    /**
     * Detail gallery
     */
    if ($('.detail-gallery-index').length != 0) {
        $('.detail-gallery-index').owlCarousel({
            items: 5,
            nav: true,
            dots: true,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>']
        });
    }

    $('.detail-gallery-list-item a').on('click', function (e) {
        e.preventDefault();
        var link = $(this).data('target');
        $('.detail-gallery-preview img').attr('src', link);
        $('.detail-gallery-preview a').attr('href', link);
    });


    /**
     * Listing Detail Bookmark & Like
     */
    $(".detail-banner-btn").click(function () {
        $(this).toggleClass("marked");

        var span = $(this).children("span");
        var toggleText = span.data("toggle");
        span.data("toggle", span.text());
        span.text(toggleText);

    });

    /**
     * Textarea resizer
     */
    $("textarea").after('<div class="textarea-resize"></div>');

    /**
     * Rating form
     */
    $(".input-rating label").hover(function () {
        $(this).siblings("label").toggleClass("hovered");
        $(this).toggleClass("filled");
        $(this).prevAll("label").toggleClass("filled");
    });

    $(".input-rating input").change(function () {
        $(this).siblings().removeClass("marked");
        $(this).prevAll("label").addClass("marked");
    });

    /**
     * Chart
     */
    if ($('#superlist-chart').length !== 0) {
        var counter = 0;
        var increase = Math.PI * 2 / 100;

        var fun1 = [];
        for (i = 0; i <= 1; i += 0.015) {
            var x = i;
            var y = Math.sin(counter);
            fun1.push([x, y]);
            counter += increase;
        }

        var counter = 0;
        var increase = Math.PI * 2 / 100;

        var fun2 = [];
        for (i = 0; i <= 1; i += 0.015) {
            var x = i;
            var y = Math.cos(counter);
            fun2.push([x, y]);
            counter += increase;
        }

        var plot = $.plot($('#superlist-chart'), [
                {
                    color: '#ceb65f',
                    data: fun1
                },
                {
                    color: '#009f8b',
                    data: fun2
                }
            ],
            {
                series: {
                    splines: {
                        show: true,
                        tension: 0.24,
                        lineWidth: 3,
                        fill: .40
                    },
                    lines: false,
                    shadowSize: 0
                },
                points: {show: true},
                legend: false,
                grid: {
                    borderColor: '#f1f1f1',
                    borderWidth: 0
                },
                xaxis: {
                    color: '#f1f1f1'
                },
                yaxis: {
                    color: '#f1f1f1',
                    min: -1,
                    max: 1
                }
            });
    }

    /**
     * Input file
     */
    $('#input-file').fileinput({
        initialPreview: [],
        overwriteInitial: true,
        initialCaption: ""
    });
});
