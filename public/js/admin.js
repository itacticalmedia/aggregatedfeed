function validRoleSave(frm)
{
    if (frm.rname.value.trim() == '')
    {
        _customAlert("Please enter Role Name.");
        return false;
    }

    return true;
}

function deleteRole(url)
{
    if (!arguments[1])
    {
        return _customConfirm("Are you delete this Role?", function() {
            deleteRole(url, true);
        });
    }

    document.location.href = url;
}
function validRoleSave(frm)
{
    if (frm.rname.value.trim() == '')
    {
        _customAlert("Please enter Role Name.");
        return false;
    }

    return true;
}

function deleteRole(url)
{
    if (!arguments[1])
    {
        return _customConfirm("Are you delete this Role?", function() {
            deleteRole(url, true);
        });
    }

    document.location.href = url;
}

function validUserSave(frm)
{
    var errors = [];
    if (frm.fname.value.trim() == '')
    {
        errors.push('Please enter First Name');
    }
    if (frm.lname.value.trim() == '')
    {
        errors.push('Please enter Last Name');
    }
    if (frm.email.value.trim() == '')
    {
        errors.push('Please enter email');
    }
    if (frm.password && frm.password.value.trim() == '')
    {
        errors.push('Please enter password');
    }
    if (frm.roleId.value.trim() == '')
    {
        errors.push('Please select role');
    }

    if (errors.length > 0)
        _customAlert(errors.join('<br/>'));
    return (errors.length == 0);
}

function deleteUser(url)
{
    if (!arguments[1])
    {
        return _customConfirm("Are you sure you want to delete this user?", function() {
            deleteRole(url, true);
        });
    }

    document.location.href = url;
}