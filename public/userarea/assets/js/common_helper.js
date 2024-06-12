function getLowerAndCombile(str) {
    return str.toLowerCase().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '_');
}

function getWithoutSpaceStr(str) {
    return str.split(/[ ,]+/).join('');
}

function showWarningAlert(str) {
    alertify.logPosition("top right").error("<i class='ion-alert-circled'><i> " + str + "");
}
