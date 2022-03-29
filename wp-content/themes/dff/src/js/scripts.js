console.log('I am testing!!!');
(function ($) {
    $(".archive-courses .courses-categories #tabs-nav .courses-cat a").on("click", function (e) {
        e.preventDefault();
        $(".archive-courses .courses-categories #tabs-nav .courses-cat a").parent().removeClass("active");
        let active = $(this).parent();
        active.addClass("active");

        const taxId = $(this).attr("tax_id");
        const locationHref = location.href;
        const data = {
            action: "courses_tax_ajax",
            tax_id: taxId,
        };
        // console.log(taxId);
        $.ajax({
            type: "POST",
            url: courses_ajax.url,
            data: data,
            success: function (response) {
                $(".archive-courses .archive-courses-list").html(response);
            },
            error: function (response) {
                console.log(response);
            },
        });
        return true;
    });
})(jQuery);
