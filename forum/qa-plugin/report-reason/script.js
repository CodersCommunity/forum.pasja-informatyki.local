document.addEventListener("DOMContentLoaded", () => {
    
    const flagboxPopup = document.querySelector("#flagbox-popup");
    const flagButton = document.querySelector('.qa-form-light-button-flag');
    flagButton.type = "button";
    const wrap = document.querySelector('.qa-flag-reasons-wrap');

    function showPopup() {
        flagboxPopup.classList.remove("hide");
        flagboxPopup.classList.add("show");
    }
    function hidePopup() {
        flagboxPopup.classList.remove("show");
        flagboxPopup.classList.add("hide");
    }

    wrap.addEventListener("click", (e) => {

    e.stopPropagation();

    });

    flagButton.addEventListener("click", () => {
        showPopup();    

        flagboxPopup.addEventListener("click", () => {
            hidePopup();
        });

	const { postid: postId, posttype: postType, parentid: parentId } = flagButton.dataset;
	    
        const closer = document.querySelector('.closer');
        closer.addEventListener("click", () => {
	    hidePopup();
        });

        const sendButton = document.querySelector('.qa-go-flag-send-button');
        sendButton.addEventListener("click", () => {

        const flagReason = document.querySelector("input[name=qa-spam-reason-radio]:checked");
        const flagNotice = document.querySelector(".qa-spam-reason-text").value;

        const dataArray = {questionid: flagQuestionid, postid: postId, posttype: postType, reasonid: flagReason.value, notice: flagNotice};
        const sendData = JSON.stringify(dataArray);
        const errorMessage = 'Blad serwera. Prosze sprobowac za jakis czas';
        let errorText = '';

        if(flagReason) {

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
        } else {
            errorText = "Proszę wybrać powód zgłoszenia!";    
        }
        if(errorText)
        {
            const errorPopupHtml = document.querySelector('.html-error');
	    const errorHtml = document.createElement('p');
            errorHtml.innerHTML = errorText;
	    errorPopupHtml.appendChild(errorHtml);
	}
            
        });

    });
    
});  
