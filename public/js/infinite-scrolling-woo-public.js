(function($) {
    'use strict';
    jQuery(document).ready(function($) {
        var w = $(window);
        var infsp;
        infsp = {
            init: function() {
                console.log('Initializing Infinite Scroll');
                var type = ispfw_option_data.general_option.infinite_sp_pagination_type;
                var status = ispfw_option_data.general_option.infinite_sp_pagination_on_off;
                var scrolltop = ispfw_option_data.advanced_option.infinite_scroll_to_top_enable;
                var infinite_sp_animation = ispfw_option_data.advanced_option.infinite_sp_animation;
                var infinite_scroll_totop = ispfw_option_data.advanced_option.infinite_scroll_totop;
                var infinite_sp_woo_buffer_pixels = ispfw_option_data.advanced_option.infinite_sp_woo_buffer_pixels;
                var prev_selector = ispfw_option_data.general_option.infinite_sp_woo_prev_selector;
                var loader_image = ispfw_option_data.general_option.infinite_loader_image;
                var infinite_sp_woo_item_selector = ispfw_option_data.general_option.infinite_sp_woo_item_selector;
                var infinite_sp_woo_next_selector = ispfw_option_data.general_option.infinite_sp_woo_next_selector;
                var content_selector = ispfw_option_data.general_option.infinite_sp_content_selector;
                var infinite_loading_btn_text = ispfw_option_data.general_option.infinite_loading_btn_text;

                if (status == 'on') {
                    console.log('Infinite Scroll is enabled');
                    if (type == 'infinite_ajax_select') {
                        console.log('Infinite Scroll Type: Ajax Select');
                        $('body').on('click', prev_selector + ' a', function(e) {
                            e.preventDefault();
                            var href = $.trim($(this).attr('href'));
                            if (href != '') {
                                if (!infsp.msieversion()) {
                                    history.pushState(null, null, href);
                                }
                                if (loader_image != '') {
                                    $(prev_selector).before('<div id="isp-infinite-scroll-loader" class="isp-infinite-scroll-loader"><img src="' + loader_image + '" alt=" " /><span>' + infinite_loading_btn_text + '</span></div>');
                                }
                                $.get(href, function(response) {
                                    if (!infsp.msieversion()) {
                                        document.title = $(response).filter('title').html();
                                    }

                                    var infinite_sp_content_selectors = content_selector + ',' + prev_selector;
                                    var delimiter = ",";
                                    var explodedArray = infinite_sp_content_selectors.split(delimiter);

                                    explodedArray.forEach(function(element) {
                                        if (!element) {
                                            return;
                                        }

                                        var html = $(response).find(element).html();
                                        $(element).html(html);
                                    });
                                    $('.isp-infinite-scroll-loader').remove();

                                    if (scrolltop == 'on') {
                                        var scrollto = 0;
                                        if (infinite_scroll_totop != '') {
                                            if ($(infinite_scroll_totop).length) {
                                                scrollto = $(infinite_scroll_totop).offset().top;
                                            }
                                        }
                                        $('html, body').animate({
                                            scrollTop: scrollto
                                        }, 500); // Changed scrolltop to scrollTop
                                    }

                                    $(content_selector + ' ' + infinite_sp_woo_item_selector).addClass("animated  " + infinite_sp_animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                                        $(this).removeClass('animated ' + infinite_sp_animation);
                                    });
                                });
                            }
                            return false;
                        });
                    }

                    if (type == 'infinite_load_more_btn' || type == 'infinite_scrolling') {
                        $(document).ready(function() {
                            if ($(prev_selector).length) {
                                $(prev_selector).before('<div id="isp-infinite-scroll-load-more" class="isp-infinite-scroll-load-more"><a isp-processing="0">' + infinite_loading_btn_text + '</a><br class="ispw-clear" /></div>');
                                if (type == 'infinite_scrolling') {
                                    $('#isp-infinite-scroll-load-more').addClass('isp-hide');
                                }
                            }
                            $(prev_selector).addClass('isp-hide');
                            $(content_selector + ' ' + infinite_sp_woo_item_selector).addClass('isp-added');
                        });
                        $('body').on('click', '#isp-infinite-scroll-load-more a', function(e) {
                            e.preventDefault();
                            if ($(infinite_sp_woo_next_selector).length) {
                                $('#isp-infinite-scroll-load-more a').attr('isp-processing', 1);
                                var href = $(infinite_sp_woo_next_selector).attr('href');
                                if (loader_image != '') {
                                    $(prev_selector).before('<div id="isp-infinite-scroll-loader" class="isp-infinite-scroll-loader"><img src="' + loader_image + '" alt=" " /><span>' + infinite_loading_btn_text + '</span></div>');
                                }

                                $.get(href, function(response) {
                                    $(prev_selector).html($(response).find(prev_selector).html());

                                    $(response).find(content_selector + ' ' + infinite_sp_woo_item_selector).each(function() {
                                        $(content_selector + ' ' + infinite_sp_woo_item_selector + ':last').after($(this));
                                    });

                                    $('#isp-infinite-scroll-loader').remove();
                                    $('#isp-infinite-scroll-load-more').show();
                                    $('#isp-infinite-scroll-load-more a').attr('isp-processing', 0);

                                    $(content_selector + ' ' + infinite_sp_woo_item_selector).not('.isp-added').addClass('animated ' + infinite_sp_animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                                        $(this).removeClass('animated ' + infinite_sp_animation).addClass('isp-added');
                                    });

                                    if ($(infinite_sp_woo_next_selector).length == 0) {
                                        $('#isp-infinite-scroll-load-more').addClass('finished').removeClass('isp-hide');
                                        $('#isp-infinite-scroll-load-more a').show().html('No More Product Available').css('cursor', 'default');
                                    }

                                });
                            } else {
                                $('#isp-infinite-scroll-load-more').addClass('finished').removeClass('isp-hide');
                                $('#isp-infinite-scroll-load-more a').show().html('No More Product Available').css('cursor', 'default');
                            }
                        });

                    }

                    if (type == 'infinite_scrolling') {
                        var sp_woo_buffer_pixels = Math.abs(infinite_sp_woo_buffer_pixels);
                        w.scroll(function() {
                            if ($(ispfw_option_data.general_option.infinite_sp_content_selector).length) {
                                var a = $(ispfw_option_data.general_option.infinite_sp_content_selector).offset().top + $(ispfw_option_data.general_option.infinite_sp_content_selector).outerHeight();
                                var b = a - w.scrollTop();
                                if ((b - sp_woo_buffer_pixels) < w.height()) {
                                    if ($('#isp-infinite-scroll-load-more a').attr('isp-processing') == 0) {
                                        $('#isp-infinite-scroll-load-more a').trigger('click');
                                    }
                                }
                            }
                        });

                    }
                }
            },

            msieversion: function() {
                var ua = window.navigator.userAgent;
                var inf_scroll_index = ua.indexOf("MSIE ");

                if (inf_scroll_index > 0)
                    return parseInt(ua.substring(inf_scroll_index + 5, ua.indexOf(".", inf_scroll_index)));

                return false;
            },

        };

        infsp.init();

    });
})(jQuery);