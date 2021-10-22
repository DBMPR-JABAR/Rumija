//Disable modal close on body click
const modalsNotCloseOnClickData = [
    "#addModal",
    "#delModal",
    "#editModal",
    "#editModal1",
    "#addModal1",
    "#importExcel",
];

modalsNotCloseOnClickData.forEach((modal) => {
    if ($(modal))
        $(modal).modal({ backdrop: "static", keyboard: false, show: false });
});

function setDataSelect(id, url, id_select, text, valueOption, textOption) {
    $.ajax({
        url: url,
        method: "get",
        dataType: "JSON",
        data: {
            id: id,
        },
        complete: function(result) {
            console.log(result.responseJSON);
            $(id_select).empty(); // remove old options
            $(id_select).append($("<option disable></option>").text(text));

            result.responseJSON.forEach(function(item) {
                $(id_select).append(
                    $("<option></option>")
                    .attr("value", item[valueOption])
                    .text(item[textOption])
                );
            });
        },
    });
}
document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementsByClassName("dt-responsive")[0];
    table && (table.style.cursor = "grab");

    let pos = { top: 0, left: 0, x: 0, y: 0 };

    const mouseDownHandler = function(e) {
        table.style.cursor = "grabbing";
        table.style.userSelect = "none";

        pos = {
            left: table.scrollLeft,
            top: table.scrollTop,
            // Get the current mouse position
            x: e.clientX,
            y: e.clientY,
        };

        document.addEventListener("mousemove", mouseMoveHandler);
        document.addEventListener("mouseup", mouseUpHandler);
    };

    const mouseMoveHandler = function(e) {
        // How far the mouse has been moved
        const dx = e.clientX - pos.x;
        // const xa = document.getElementsByClassName("dt-responsive")[0];
        // xa.scrollLeft = pos.left - dx;
        table.scrollLeft = pos.left - dx;
    };

    const mouseUpHandler = function() {
        table.style.cursor = "grab";
        table.style.removeProperty("user-select");

        document.removeEventListener("mousemove", mouseMoveHandler);
        document.removeEventListener("mouseup", mouseUpHandler);
    };

    // Attach the handler
    table && table.addEventListener("mousedown", mouseDownHandler);
});