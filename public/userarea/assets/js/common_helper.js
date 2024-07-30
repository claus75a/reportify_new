function getLowerAndCombile(str) {
    return str.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
}

function getWithoutSpaceStr(str) {
    return str.split(/[ ,]+/).join('');
}

function showWarningAlert(str) {
    alertify.logPosition("top right").error("<i class='ion-alert-circled'><i> " + str + "");
}

function showSuccessAlert(str) {
    alertify.logPosition("top right").success("<i class='ion-alert-circled'><i> " + str + "");
}

function showWarningPopup(str, callback = null, confirm_str = "Confirm") {
    Swal.fire({
        title: 'Warning!',
        text: str,
        icon: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Confirm',
    }).then((result) => {
        if(callback) {
            callback();
        }
    });
}

function showSuccessPopup(str, callback = null) {
    Swal.fire({
        title: 'Success',
        text: str,
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if(callback)
            callback();
    });
}
