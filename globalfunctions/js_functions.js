function _postbayloc(locmodal_bayloc, dimgroupmodal_bayloc, baymodal_bayloc, waklbaymodal_bayloc, whse) {

    var formData = 'locmodal_bayloc=' + locmodal_bayloc + '&dimgroupmodal_bayloc=' + dimgroupmodal_bayloc + '&baymodal_bayloc=' + baymodal_bayloc + '&waklbaymodal_bayloc=' + waklbaymodal_bayloc + '&whse=' + whse;
    $.ajax({
        url: 'formpost/postbaylocmodify.php',
        type: 'POST',
        data: formData,
        success: function (result) {
            $("#postsuccess").html(result);
            $('#modifybaylocmodal').modal('hide');
            $('#baylocerrortable').DataTable().ajax.reload();
        }
    });
}

function _deletebayloc(location) {

    var formData = 'locid=' + location;
    $.ajax({
        url: 'formpost/postdeletebayloc.php',
        type: 'POST',
        data: formData,
        success: function (result) {
            $("#postsuccess").html(result);
            $('#modifybaylocmodal').modal('hide');
            $('#baylocerrortable').DataTable().ajax.reload();
        }
    });
}