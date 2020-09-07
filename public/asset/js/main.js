$(document).ready(function () {
    $("#clients_table").tablesorter();
});

$('.table-filters input').on('input', function () {
    filterTable($(this).parents('table'));
});

function filterTable($table) {
    var $filters = $table.find('.table-filters td');
    var $rows = $table.find('.table-data');
    $rows.each(function (rowIndex) {
        var valid = true;
        $(this).find('td').each(function (colIndex) {
            if ($filters.eq(colIndex).find('input').val()) {
                switch ($filters.eq(colIndex).find('input').attr("type")) {
                    case "text":
                    case "number":
                    case "date":
                        if ($(this).html().toLowerCase().indexOf(
                            $filters.eq(colIndex).find('input').val().toLowerCase()) == -1) {
                            valid = valid && false;
                        }
                        break;
                }
            }

        });
        if (valid === true) {
            $(this).css('display', '');
        } else {
            $(this).css('display', 'none');
        }
    });
}

$(".show-phones").click(function (e) {
    e.preventDefault();
    let modal = $(".modal");
    let phones = $(this).data("phones");
    $(modal).addClass("visible");
    $(modal).find(".modal-body").html(phones.join("<br>"));

});


$('.modal button[data-dismiss="modal"]').click(function (e) {
    e.preventDefault();
    $(".modal").removeClass("visible");
});