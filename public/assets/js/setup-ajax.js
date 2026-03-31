"use strict";

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// SweetAlert
const SwalMixin = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

function ajaxGet(url, data = null) {
    return $.ajax({
        type: "GET",
        url: url,
        data: data,
        dataType: "json",
    }).fail(function (res) {
        Swal.fire({
            title: "Gagal",
            text: res.responseJSON.message,
            type: "error",
        });
    });
}

function ajaxPost(url, data) {
    return $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
    }).fail(function (res) {
        Swal.fire({
            title: "Gagal",
            text: res.responseJSON.message,
            icon: "error",
        });
    });
}

function ajaxPostValidate(url, data, form, btn) {
    return $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function () {
            $(btn).prepend('<i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>');
            $(btn).attr("disabled", true);
        },
        complete: function () {
            $(btn).find('i').remove();
            $(btn).attr("disabled", false);
        },
    }).fail(function (res) {
        if (res.status === 422) {
            $.each(res.responseJSON.errors, function (i, v) {
                let getName = getConvertedName(i);

                // Input
                $(form + ' input[name="' + getName + '"]').addClass("is-invalid");
                $(form + ' input[name="' + getName + '"] ~ .invalid-feedback').html(v);

                // Select
                if ($(form + ' select[name="' + getName + '"]').hasClass("select2")) {
                    $(form + ' select[name="' + getName + '"]')
                        .addClass("is-invalid")
                        .addClass("error");
                    $(form + ' select[name="' + getName + '"]')
                        .parent()
                        .next()
                        .html(v);
                }
                else {
                    $(form + ' select[name="' + getName + '"]').addClass("is-invalid");
                    $(form + ' select[name="' + getName + '"] ~ .invalid-feedback').html(v);
                }

                // Textarea
                $(form + ' textarea[name="' + getName + '"]').addClass("is-invalid");
                $(form + ' textarea[name="' + getName + '"] ~ .invalid-feedback').html(v);

                // Input
                $("input[name='" + getName + "']")
                    .keypress(function () {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    })
                    .keyup(function () {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    })
                    .change(function () {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    });

                // Select
                $("select[name='" + getName + "']").change(function () {
                    if ($(this).hasClass("select2")) {
                        $(this)
                            .parent()
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid").removeClass("error");
                    }
                    else {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    }
                });

                // Textarea
                $("textarea[name='" + getName + "']")
                    .keypress(function () {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    })
                    .keyup(function () {
                        $(this)
                            .next(".invalid-feedback")
                            .text("Mohon lengkapi isian diatas");
                        $(this).removeClass("is-invalid");
                    });
            });
        }
        else {
            Swal.fire({
                title: "Gagal",
                text: res.responseJSON.message,
                icon: "error",
            });
        }
    });
}

function ajaxPostDataObject(url, data) {
    return $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
    }).fail(function (res) {
        Swal.fire({
            title: "Gagal",
            text: res.responseJSON.message,
            icon: "error",
        });
    });
}

function getConvertedName(key) {
    let text = "";
    let arrayName = key.split(".");

    if (arrayName.length > 1) {
        arrayName.forEach(function (name, i) {
            if (i === 0) {
                text += name;
            }
            else {
                text += "[" + name + "]";
            }
        });
    }
    else {
        text = arrayName[0];
    }
    return text;
}


function sweetAlertDelete(
    url,
    tableId
) {
    SwalMixin.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger me-2'
        },
        buttonsStyling: false,
    }).fire({
        title: 'Apakah kamu yakin?',
        text: 'Kamu akan menghapus data ini!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'me-2',
        confirmButtonText: 'Ya, hapus data!',
        cancelButtonText: 'Tidak, batalkan!',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            ajaxPostDataObject(
                url,
                {_method: 'DELETE'}
            ).done((res) => {
                SwalMixin.fire({
                    icon: 'success',
                    title: res.message
                }).then(() => {
                    reloadTable(tableId);
                });
            });
        }
    });
}
