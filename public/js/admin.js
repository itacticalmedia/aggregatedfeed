/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var validateStylistProfile = function (form) {

    var errors = [];


    if (form.fName && form.fName.value.trim() == "") {
        errors.push('Please enter First Name');
    }
    if (form.lName && form.lName.value.trim() == "") {
        errors.push('Please enter Last Name');
    }
    if (form.email && form.email.value.trim() == "") {
        errors.push('Please enter Email');
    }
    if (form.address.value.trim() == "") {
        errors.push('Please enter street Address');
    }
    if (form.city.value.trim() == "") {
        errors.push('Please enter City');
    }
    if (form.state.value.trim() == "") {
        errors.push('Please enter State');
    }
    if (form.zip.value.trim() == "") {
        errors.push('Please enter Zip');
    }

    if (form.mobile.value.trim() == "") {
        errors.push('Please enter your mobile Number.');
    }

    if (form.max_miles.value.trim() == "") {
        errors.push('Please select Miles - Distance Willing to Drive.');
    }
    if (form.areas_expertise.value.trim() == "") {
        errors.push('Please enter Areas of Expertisee.');
    }

    if (form.status.value.trim() == "") {
        errors.push('Please select status.');
    }

    if (errors.length > 0)
        sitePopup.alert(errors.join('<br/>'));
    return (errors.length == 0);
}

var validateUserProfile = function (form)
{
    var errors = [];

    if (form.fName && form.fName.value.trim() == "") {
        errors.push('Please enter First Name');
    }

    if (form.lName && form.lName.value.trim() == "") {
        errors.push('Please enter Last Name');
    }

    if (form.email && form.email.value.trim() == "") {
        errors.push('Please enter Email');
    }

    if (form.email && form.home_phone.value.trim() == "") {
        errors.push('Please enter Home Phone');
    }

    if (form.email && form.cell_phone.value.trim() == "") {
        errors.push('Please enter Cell Phone');
    }

    if (form.status.value.trim() == "") {
        errors.push('Please select status.');
    }

    if (errors.length > 0)
        sitePopup.alert(errors.join('<br/>'));
    return (errors.length == 0);
}


var validateService = function (form)
{
    var errors = [];

    if (form.category && form.category.value.trim() == "") {
        errors.push('Please select category');
    }

    if (form.name && form.name.value.trim() == "") {
        errors.push('Please enter service Name');
    }

    if (form.duration && form.duration.value.trim() == "") {
        errors.push('Please enter service duration');
    }

    if (form.price && form.price.value.trim() == "") {
        errors.push('Please enter service price');
    }

    if (form.stylist_price && form.stylist_price.value.trim() == "") {
        errors.push('Please enter stylist price');
    }

    if (errors.length > 0)
        sitePopup.alert(errors.join('<br/>'));
    return (errors.length == 0);
}

function _doAjaxWithLoader(action, postData, successFunc, postType, dataType, async)
{
    sitePopup.showLoader(true);
    
    var args = arguments;
    $.ajax({
        url: action,
        cache: false,
        type: (postType) ? postType : "POST",
        dataType: (dataType) ? dataType : "html",
        data: postData,
        async: (async != 'undefined') ? async : true,
        crossDomain: (dataType == 'jsonp') ? true : false,
        dataFilter: function (jsonString, dataType) 
        {
            return jsonString;
        },
        error: function (jqXHR, exception) {          
             sitePopup.showLoader(false);
        },
        success: function (data) {
            sitePopup.showLoader(false);
            
            if (data != null)
            {
                var callback = $.Callbacks();
                callback.add(successFunc);
                callback.fire(data, args);

            }
        }
    });
}