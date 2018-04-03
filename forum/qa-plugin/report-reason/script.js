$(document).ready(function()
{
    // prevent submit
    $(".qa-form-light-button-flag").attr("type", "button");
    
    $(".qa-form-light-button-flag").click( function()
    {
        var postId = $(this).data("postid");
        var postType = $(this).data("posttype");
        var parentId = $(this).data("parentid");
        
        // remove button so no double inserts
        // $(this).remove();
        
        $("#flagbox-popup").show();
        
        $(".qa-flag-reasons-wrap .closer").click( function()
        {
            $("#flagbox-popup").hide();
        });
        
        // focus on first element, then Enter and Escape key work
        $('.qa-flag-reasons-wrap input').first().focus();
        
        $(".qa-go-flag-send-button").click( function()
        {
            const flagReason = $("input[name=qa-spam-reason-radio]:checked").val();
            const flagNotice = $(".qa-spam-reason-text").val();
            
            let dataArray = {};
                dataArray.questionid= flagQuestionid;
                dataArray.postid= postId;
                dataArray.posttype= postType;
                dataArray.parentid= parentId;
                dataArray.reasonid= flagReason;
                dataArray.notice= flagNotice;
            
            
            const sendData = JSON.stringify(dataArray);
            console.log("sending: "+sendData);
                        console.log(flagAjaxURL);

            // send ajax
            $.ajax({
                 type: "POST",
                 url: flagAjaxURL,
                 data: { ajaxdata: sendData },
                 dataType:"json",
                 cache: false,
                 success: function(data)
                 {
                    console.log("got server data:");
                    console.log(data);
                    
                    if(typeof data.error !== "undefined")
                    {
                        alert(data.error);
                    }
                    else if(typeof data.success !== "undefined")
                    {
                        // if success, reload page
                        location.reload();
                    }
                    else
                    {
                        alert(data);
                    }
                 },
                 error: function(data)
                 {
                    console.log("Ajax error:");
                    console.log(data);
                 }
            });        });
        
    }); // END click
    
    // submit by enter key, cancel by escape key
    $('.qa-flag-reasons-wrap').on('keyup', function(e)
    {
        console.log(e.keyCode);
        if(e.keyCode == 13)
        {
            $(this).find('.qa-go-flag-send-button').click();
        }
        else if(e.keyCode == 27)
        {
            $(this).find('.closer').click();
        }
    });
    
    // mouse click on flagbox closes div
    $('#flagbox-popup').click(function(e)
    {
        if(e.target == this)
        { 
            $(this).find('.closer').click();
        }
    });
    
});
