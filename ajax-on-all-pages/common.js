jQuery(document).ready(function($) {

    var $ajaxLinks = $("a:not([href^='http'])").add("a[href^='http://mcmpspb.yellowmarker.ru']"),
        $ajaxContainer = $('#ajax-container'),
        $loading = $('<div class="loading"><img src="/assets/images/loading.gif" alt="loadind"></div>');

    $ajaxLinks.unbind('click').bind('click', ajaxAction);

    $(window).bind('popstate', ajaxAction);

    /**
     * AJAX reload of main frame func
     */
    function ajaxAction(e, pathname) {
        if (e !== undefined) {
            var eventType = e.type;
        }
        var $this = $(this);
        if (!pathname) {
            var pathname = window.location.pathname,
                url = eventType == 'popstate' ? pathname : $this.attr('href'); //check browser button click
        } else {
            var url = pathname;
            //console.log(pathname);
        }

        var minDelay = 600; //время минимальной задержки
        var startTime = new Date();

        $.ajax({
                url: url,
                type: 'POST',
                beforeSend: function() {
                    $ajaxContainer.empty();
                    $ajaxContainer.append($loading); // бегунок загрузки
                }
            })
            .done(function(response) {

                if (url != window.location && e.type != 'popstate') {
                    window.history.pushState({
                        path: url
                    }, '', url);
                }

                $('.active').removeClass('active');

                if ($this.parents('.header-links').length > 0) {
                    //$('.header-links a').removeClass('active');
                    $this.addClass('active');
                } else if ($this.parents('.side-menu').length > 0) {
                    //$('.side-menu-block li').removeClass('active');
                    $this.parent().addClass('active');
                }

                var endTime = new Date();
                var time = endTime - startTime;

                if (time < minDelay) {
                    setTimeout(function() {
                        ajaxRender(response, eventType);
                    }, minDelay - time);
                } else {
                    ajaxRender(response, eventType);
                }

            })
            .fail(function() {
                //console.log("error");
            })
            .always(function() {
                //console.log("complete");
            });

        return false;

    }

    /**
     * After AJAX complete
     */
    function ajaxRender(response, eventType) {

        var scrollTop = $(window).scrollTop();
        //var winHeight = $(window).height();

        $ajaxContainer.empty();
        $ajaxContainer.append(response).fadeIn();

        //console.log(scrollTop); //check
        if (scrollTop > 108) {
            //disableScroll();
            $('html, body').animate({
                scrollTop: 108
            }, 400);
        }

        $("a:not([href^='http']), a[href^='http://mcmp']").unbind('click').bind('click', ajaxAction);

    }
});
