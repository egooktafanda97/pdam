
$(document)
    .on("keypress", ".inputnumber", function (e) {
        let c = e.keyCode || e.charCode;
        switch (c) {
            case 8:
            case 9:
            case 27:
            case 13:
                return;
            case 65:
                if (e.ctrlKey === true) return;
        }
        if (c < 48 || c > 57) e.preventDefault();
    })
    .on("keyup", ".inputnumber", function () {
        let inp = parseInt($(this).val().replace(/\./g, ''));
        if(isNaN(inp)) inp = 0;
        $(this).val(convertToThousand(inp));
    });

$(document)
    .on("keypress", ".inputnumber-coma", function (e) {
        let c = e.keyCode || e.charCode;
        switch (c) {
            case 8:
            case 9:
            case 27:
            case 13:
                return;
            case 65:
                if (e.ctrlKey === true) return;
        }
        if (c < 48 || (c > 57 && c < 188) || c > 188) e.preventDefault();
    })
    .on("keyup", ".inputnumber-coma", function () {
        let number = parseFloat($(this).val().replace(/\./g, '').replace(/,/g, '.'));
        if(isNaN(number)) number = 0;

        const numberFormatter = new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        $(this).val(numberFormatter.format(Number(number.toFixed(2))));
    });

$(document)
    .on("keydown", ".numberonly", function () {
        return ['Backspace','Delete','ArrowLeft','ArrowRight'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'
    });

/* Reload table */
function reloadTable(id, reset = true) {
    let table = $(id).DataTable();
    table.clear().draw();
    table.ajax.reload(null, reset);
}

/* Fungsi format Rupiah */
function convertToThousand(angka) {
    let rupiah = "";
    let angkarev = angka.toString().split("").reverse().join("");
    for (let i = 0; i < angkarev.length; i++)
        if (i % 3 === 0) rupiah += angkarev.substr(i, 3) + ".";
    return rupiah
        .split("", rupiah.length - 1)
        .reverse()
        .join("");
}

function convertToNumber(value) {
    let number = parseInt(value.replace(/,.*|[^0-9]/g, ""), 10);
    return isNaN(number) ? 0 : number;
}

$(document).on('show.bs.modal', function() {
    $('.is-invalid').trigger('change');
});
