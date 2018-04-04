document.addEventListener("DOMContentLoaded", () => {
    
    const flagButton = document.getElementsByClassName('qa-form-light-button-flag')[0];
    flagButton.type = "button";

    flagButton.addEventListener("click", () => {

        const postId = flagButton.dataset.postid;
        const postType = flagButton.dataset.posttype;
        const parentId = flagButton.dataset.parentid;
        
        $("#flagbox-popup").show();
        
        const closer = document.getElementsByClassName('closer')[0];
        closer.addEventListener("click", () => {
            $("#flagbox-popup").hide();
        });

        const sendButton = document.getElementsByClassName('qa-go-flag-send-button')[0];
        sendButton.addEventListener("click", () => {

            const flagReason = document.querySelector("input[name=qa-spam-reason-radio]:checked").value;
            const flagNotice = document.getElementsByClassName("qa-spam-reason-text")[0].value;

            const dataArray = {questionid: flagQuestionid, postid: postId, posttype: postType, parentid: parentId, reasonid: flagReason, notice: flagNotice};
            const sendData = JSON.stringify(dataArray);
            const errorText = '';

            $.ajax({
                 type: "POST",
                 url: flagAjaxURL,
                 data: { ajaxdata: sendData },
                 dataType:"json",
                 cache: false,
                 success: function(data)
                 {
                    if(data.error)
                    {
            
                        errorText = 'Blad serwera. Prosze sprobowac za jakis czas';
                        console.log(data);
                        alert('Blad serwera. Prosze sprobowac za jakis czas');
                    }
                    else if(data.success)
                    {
                        // if success, reload page
                        location.reload();
                    }
                    else
                    {
                        errorText = 'Blad serwera. Prosze sprobowac za jakis czas';
                    }
                 },
                 error: function(data)
                 {
                    errorText = 'Blad serwera. Prosze sprobowac za jakis czas';
                    alert('Wystapil blad! Przepraszamy za niedogodnosci');
                    console.log(data);
                 }
            });
    
          if(errorText != '')
          {
              const errorHtml = document.createElement('p');
              errorHtml.html = errorText;
              const erorrPopupHtml = document.getElementsByClassName('html-error')[0];
              errorPopupHtml.appendChild(errorHtml);
          }
        });

    });
    
});
