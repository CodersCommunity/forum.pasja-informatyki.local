document.addEventListener("DOMContentLoaded", () => {
    
    const flagboxPopup = document.querySelector("#flagbox-popup");
    const erorrPopupHtml = document.querySelector('.html-error');
    const flagButton = document.querySelector('.qa-form-light-button-flag');
    flagButton.type = "button";


    flagButton.addEventListener("click", () => {

            const { postid: postId, posttype: postType, parentid: parentId } = flagButton.dataset;
	    flagboxPopup.classList.remove("hide");
            flagboxPopup.classList.add("show");
            const closer = document.querySelector('.closer');
            closer.addEventListener("click", () => {
	        flagboxPopup.classList.remove("show");
                flagboxPopup.classList.add("hide");
            });

            const sendButton = document.querySelector('.qa-go-flag-send-button');
            sendButton.addEventListener("click", () => {

            const flagReason = document.querySelector("input[name=qa-spam-reason-radio]:checked").value;
            const flagNotice = document.querySelector(".qa-spam-reason-text").value;

            const dataArray = {questionid: flagQuestionid, postid: postId, posttype: postType, reasonid: flagReason, notice: flagNotice};
            const sendData = JSON.stringify(dataArray);
            const errorMessage = 'Blad serwera. Prosze sprobowac za jakis czas';
            let errorText = '';

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
                        errorText = errorMessage;
                    }
                    else if(data.success)
                    {
                        location.reload();
                    }
                    else
                    {
			            errorText = errorMessage;
                    }
                 },
                 error: function(data)
                 {
		             errorText = errorMessage;
                 }
            });
	
        if(errorText)
	    {
		    const errorHtml = document.createElement('p');
		    errorHtml.innerHTML = errorText;
		    errorPopupHtml.appendChild(errorHtml);
	    }
        });

    });
    
});  
