/**
 * Created by lil-works on 01/11/16.
 */
sessionManager = function(action,target){
    var ajax_session_removeParam = Routing.generate('ajax_session_removeParam');

    if (action == "remove"){
        var request = $.ajax({
            url: ajax_session_removeParam,
            method: "POST",
            data: { target:target },
            dataType: "html",
            async: false
        });


        request.done(function (msg) {
            window.location.reload();
        });
    }

}