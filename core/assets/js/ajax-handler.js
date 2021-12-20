/*------------------------ 
Backend related javascript
PHP passed data  => innocs.{{name}}
------------------------*/
function redis_action_request(action){
    var data = {
        'action': action ,
    };

    jQuery.ajax({
        type: "post",url: ajaxurl ,data: data,
        beforeSend: function() {
            jQuery("#redis_actions_result").html( innocs.please_wait_msg );
        }, 
        success: function(resp){
            jQuery("#redis_actions_result").html( resp.data.msg );
        }
    });
}