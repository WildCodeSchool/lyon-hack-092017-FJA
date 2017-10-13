

$(document).ready(function () {
    // Init tabs
    $('ul.tabs').tabs();
    // Init modals and set close when clickOut
    $('.modal').modal();
    // Init collapse
    $('.collapsible').collapsible();
    // Init Select
    $('select').material_select();
    // Init geopattern plugins
    var pattern = GeoPattern.generate();
    $('#geopattern').css('background-image', pattern.toDataUrl());
});

