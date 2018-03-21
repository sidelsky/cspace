jQuery(function($) {
    var $filterList = $("#filter-list");
    $("#filter").change(function() {
        var value = this.value;

        $.ajax({
            url:
                window.location.origin +
                "/wp-json/cs-api/filter?categoryid=" +
                value,

            success: function(data) {
                $filterList.html(data);
            }
        });
        return false;
    });
});
