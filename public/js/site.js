/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var alert_new_line = "<br>";
function _customJsDialog(msgText, titleText, type, callBack)
{
    //type=0 alert
    //type=1 confirm

    /*switch (type)
     {
     case 0:
     alert(msgText);
     break;
     case 1:
     if(confirm(msgText))
     {
     setTimeout(callBack, 50); 
     }
     break;
     }*/


    if (!titleText)
    {
        titleText = "Aggregated Feed";
    }
    /*var dlg = $("#dialog-custom-confirm").dialog({
        create: function (event, ui) {
            //$(".ui-dialog-titlebar").hide();
        },
        dialogClass: "",
        title: titleText,
        resizable: false,
        maxHeight: 500,
        width: 400,
        text: msgText,
        modal: true
    }).html(msgText);

    if (type == 0)
    {
        
        $("#dialog-custom-confirm").dialog("option", "buttons", [{
                text: "Ok",
                click: function () {
                    $(this).dialog("close");
                    return false;
                }
            }]);
    } else if (type == 1)
    {
        $("#dialog-custom-confirm").dialog("option", "buttons", [{
                text: "Ok",
                click: function () {
                    $(this).dialog("close");

                    //jQuery.globalEval(callBack)
                    setTimeout(callBack, 50);
                }
            },
            {
                text: "Cancel",
                click: function () {
                    $(this).dialog("close");
                    return false;
                }
            }

        ]);

    }*/
    if (type == 0)
    {
        sitePopup.alert(msgText,callBack,titleText);
    }else if (type == 1)
    {
        sitePopup.confirm(msgText,callBack);
    }
    


}
function _customAlert(msgText, titleText,callBack)
{
    _customJsDialog(msgText, titleText, 0,callBack);
}
;
function _customConfirm(msgText, callBack, titleText)
{
    return _customJsDialog(msgText, titleText, 1, callBack);
}
;



/**
 * 
 * @param {type} action
 * @param {type} postData
 * @param {type} successFunc
 * @param {type} postType default POST
 * @param {type} dataType default HTML
 * @param {type} async    default TRUE  
 * @returns {undefined}
 */
function _doAjax(action, postData, successFunc, postType, dataType, async)
{
    var args = arguments;
    $.ajax({
        url: action,
        cache: false,
        type: (postType) ? postType : "POST",
        dataType: (dataType) ? dataType : "html",
        data: postData,
        async: (async != 'undefined') ? async : true,
        crossDomain: (dataType == 'jsonp') ? true : false,
        dataFilter: function (jsonString, dataType) {

            return jsonString;
        },
        error: function (jqXHR, exception) {
            //alert('Response Code: '+jqXHR.status+'\n '+exception+' Occured.');
        },
        success: function (data) {
            if (data != null)
            {
                var callback = $.Callbacks();
                callback.add(successFunc);
                callback.fire(data, args);

            }
        }
    });
}

function _string_valid(element)
{
    var txt = element.value;
    var txtlen = txt.length;
    var ch = txt.charAt(txtlen - 1);
    var no = /[0-9]/;
    var re = /^\w+$/;

    if (!re.test(ch) && ch != " " && ch != "")
    {
        element.value = txt.substring(0, txt.length - 1);
        message = 'special characters does not allowed.' + alert_new_line;

        _customAlert(message);
    }
    else if (no.test(txt)) {
        element.value = txt.substring(0, txt.length - 1);
        message = 'Numbers does not allowed.' + alert_new_line;

        _customAlert(message);
    }
}
function _trim_input(element)
{
    element.value = element.value.trim();
    _caps(element);
}
function _caps(element)
{
    var txt = element.value;
    var spl = txt.split(" ");
    var upstring = "";
    for (var i = 0; i < spl.length; i++)
    {
        try {
            upstring += spl[i].substr(0, 1).toUpperCase();

        } catch (err)
        {

        }
        upstring += spl[i].substring(1, spl[i].length);
        upstring += " ";

    }

    element.value = upstring.substring(0, upstring.length - 1);
}

function _email_validate(element)
{
    var x = element.value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length)
    {
        _customAlert("Not a valid e-mail address");
        return false;
    }
    return true;
}

function _format_tp_number(inp)
{
    var number = "";
    for (var i = 0; i < inp.value.length; i++)
        if (jQuery.isNumeric(inp.value.substring(i, i + 1)))
            number += '' + inp.value.substring(i, i + 1);
    inp.value = "";
    for (var i = 0; i < number.length; i++) {
        if (i == 0)
            inp.value += '(';
        if (i == 3)
            inp.value += ')';
        if (i == 6)
            inp.value += '-';

        inp.value += '' + number.substring(i, i + 1);
    }
}

/**
 * 
 * @param {type} url
 * @param {type} w
 * @param {type} h
 * @param {type} title
 * @returns {undefined}
 */
function openPop(url, w, h,title)
{
    /*var adjW = 0;
    var adjH = 0;
    if (w > 0)
    {
        adjW = w / 2;
    }
    if (h > 0)
    {
        adjH = h / 2;
    }
    var left = ($(window).width() / 2) - adjW;
    var top = ($(window).height() / 2) - adjH;
    window.open(url, '', "width=" + w + ", height=" + h + ", scrollbars=yes, top=" + top + ", left=" + left + "");*/
    
    sitePopup.link(url,h,w,title);
}

function closePop()
{
     window.parent.$('#glamModalLinkIframe').off('load');
     window.parent.$('#glamModalLinkIframe').unbind('load');
     window.parent.$('#glamModalLinkIframe').attr('src', "");   
     window.parent.$('#glamModalLink').modal('hide');
   
    
}
function doPrint()
{
    window.print();
}
function directPrint(url)
{

    var printWindow = window.open(url, '', "width=" + 10 + ", height=" + 10 + ", scrollbars=yes");
    var printAndClose = function () {
        if (printWindow.document.readyState === 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
}


function _showMapDirection(originLatitude, originLongitude, destinationLatitude, destinationLongitude, mapCanvasDivId, mapDirectionInsDivId, mapDurationDivId)
{
    //https://developers.google.com/maps/documentation/javascript/examples/directions-complex
    var map;
    var origin = new google.maps.LatLng(originLatitude, originLongitude);
    var destination = new google.maps.LatLng(destinationLatitude, destinationLongitude);
    var direction = function ()
    {
        var mapOptions = {
            zoom: 10,
            center: origin
        };
        map = new google.maps.Map(document.getElementById(mapCanvasDivId),
                mapOptions);

        var rendererOptions = {
            map: map
        }
        var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

        var directionsService = new google.maps.DirectionsService();

        var request = {
            origin: origin,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };

        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                //var warnings = document.getElementById('warnings_panel');
                //warnings.innerHTML = '<b>' + response.routes[0].warnings + '</b>';
                directionsDisplay.setDirections(response);

                if (mapDirectionInsDivId)
                {

                    var myRoute = response.routes[0].legs[0];

                    for (var i = 0; i < myRoute.steps.length; i++) {

                        $("#" + mapDirectionInsDivId).append("<br>-> " + myRoute.steps[i].instructions);
                    }
                }


            }
        })
    }

    if (mapDurationDivId)
    {
        var calculateDistances = function () {
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                    {
                        origins: [origin],
                        destinations: [destination],
                        travelMode: google.maps.TravelMode.DRIVING,
                        unitSystem: google.maps.UnitSystem.IMPERIAL
                    }, callback);
        }

        var callback = function (response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                console.log('Error was: ' + status);
            } else {
                var results = response.rows[0].elements;
                if(results.length>0 && results[0].distance && results[0].duration)
                {
                    $("#"+mapDurationDivId).append("Distence: " + results[0].distance.text);
                    $("#"+mapDurationDivId).append(" Duration: " + results[0].duration.text);
                }else
                {
                    console.log('Invalid duration');
                }

            }
        }
        google.maps.event.addDomListener(window, 'load', calculateDistances);
    }

    
    google.maps.event.addDomListener(window, 'load', direction);
}


function _validateUrl(url)
{
    if (/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url)) {
        return true;
    } else {
        return false;
    }
}