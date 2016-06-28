

function cancel() {

    $('.sharebutton').removeAttr('style');
    $('#done').removeAttr('style');


}
function done() {
 
    var updateUrl = $("#shareEdit").attr('action');
    
    var ids = $("#shareEdit").serialize();
    ids = decodeURI(ids);

    // if both validate we attempt to send the e-mail
    // first we hide the submit btn so the user doesnt click twice
    // $("#send").replaceWith("<em>Saving...</em>");
    var data = {
        'id': ids,
    }
    $.ajax({
        type: 'POST',
        url: updateUrl,
        data: data,
        success: function(data) {

            $("#taskForm").fadeOut("fast", function() {
                //$(this).before("<p><strong>Success! Your feedback has been sent, thanks :)</strong></p>");
                setTimeout("$.fancybox.close()", 500);
                setTimeout(" location.reload();", 1000);

            });
        }
    });
}
function share() {

    var users = $("#users").val();
    
    
    var level = $("#level").val();
    var userId = $("#user_id").val();
    var addUrl = $("#shareForm").attr('action');
    users = decodeURI(users);
    
    // if both validate we attempt to send the e-mail
    // first we hide the submit btn so the user doesnt click twice
    // $("#send").replaceWith("<em>Saving...</em>");
    var data = {
        'id': '',
        'users': users,
        'level': level,
        'user_id': userId

    }
    $.ajax({
        type: 'POST',
        url: addUrl,
        data: data,
        success: function(data) {

            $("#taskForm").fadeOut("fast", function() {
                //$(this).before("<p><strong>Success! Your feedback has been sent, thanks :)</strong></p>");
                setTimeout("$.fancybox.close()", 500);
                setTimeout(" location.reload();", 1000);

            });
        }
    });


}
function addTask() {


    $("#taskForm").submit(function() {
        return false;
    });


    var category_id = $("#category_id").val();
    var title = $("#title").val();
    var description = $("#description").val();
    var userId = $("#user_id").val();
    var addUrl = $("#taskForm").attr('action');

    // if both validate we attempt to send the e-mail
    // first we hide the submit btn so the user doesnt click twice
    // $("#send").replaceWith("<em>Saving...</em>");
    
    if (category_id == '0') {
        alert('Select a category');
        return false;
    }
    else if (title == '') {
       
        alert('Title should not be empty');
        return false;
    } else {
        var data = {
            'id': '',
            'category_id': category_id,
            'title': title,
            'description': description,
            'user_id': userId

        }
        $.ajax({
            type: 'POST',
            url: addUrl,
            data: data,
            success: function(data) {

                $("#taskForm").fadeOut("fast", function() {
                    //$(this).before("<p><strong>Success! Your feedback has been sent, thanks :)</strong></p>");
                    setTimeout("$.fancybox.close()", 500);
                    setTimeout(" location.reload();", 1000);

                });
            }
        });

    }

}

function  fnClickAddRow(id, type, profile, userid){

    var updateUrl = $('#sharedurl').data('url');
   
    var formData = {task_id: id, type: type,profile:profile, user_id: userid}; //Array 

    $.ajax({
        url: updateUrl,
        type: "POST",
        data: formData,
        success: function(data, textStatus, jqXHR)
        {
            location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {

        }
    });
}
function sharedTable(userLoad, userUpdate, tableName, shared) {

    var table = "#" + tableName;
    var id = '';
    $(".dragevent, " + table + " tr:not(.disabled)").draggable({
        helper: 'clone',
        revert: 'td:first-child',
        start: function(event, ui) {

            id = ui.helper.data('id');
           
            $('#drag_task').val(id);

        },
        stop: function(event, ui) {
            $(this).css('opacity', '1');

        }
    });

    $("#dataTable, " + table + "").droppable({
        drop: function(event, ui) {
            var userid;
            if (shared == 1) {
                userid = $(".sharedrows:last").data('user');
            } else {
                userid = '';
            }
             var obituaryid = $('#sharedurl').data('profile');

            fnClickAddRow($('#drag_task').val(),'obituary',obituaryid, userid);
            //$(this).append($(ui.helper).clone().draggable());

        }
    });

    $(table).dataTable({
        "sScrollY": "200px",
        "bPaginate": false,
        "bScrollCollapse": true,
        "bFilter": false,
        "bInfo": false
    }).makeEditable();

}