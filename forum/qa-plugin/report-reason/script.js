document.addEventListener("DOMContentLoaded", () => {
    
    const flagboxPopup = document.querySelector("#flagbox-popup");
    const wrap = document.querySelector(".qa-flag-reasons-wrap");
    const body = document.body;
    const closer = document.querySelector(".close-preview-btn");
    const sendButton = document.querySelector(".qa-go-flag-send-button");
    const errorMessage = "Błąd serwera. Proszę spróbować za jakiś czas";
    const errorPopup = document.querySelector("#qa-spam-reason-error");
    const tooManyReportError = "Zbyt dużo zgłoszeń z tego adresu IP. Spróbuj za godzinę";
    const reportReasonEmptyError = "Wybierz powód zgłoszenia!";

    function showPopup() {
        flagboxPopup.classList.remove("hide");
        flagboxPopup.classList.add("show");
    }
    function hidePopup() {
        flagboxPopup.classList.remove("show");
        flagboxPopup.classList.add("hide");
    }
    function removeAllChildFromErrorPopup() {
        while (errorPopup.firstChild) {
             errorPopup.removeChild(errorPopup.firstChild);
        }
    }
    function showError(errorText) {
        const paragraph = document.createElement("p");
        paragraph.textContent = errorText;
                    
        removeAllChildFromErrorPopup();
        errorPopup.appendChild(paragraph);
        errorPopup.hidden = false
    }

    wrap.addEventListener("click", (e) => {
        e.stopPropagation();
    });
    body.addEventListener("click", (event) => {
        if (event.target.matches(".qa-form-light-button-unflag")) {
            location.reload();
        }
        if (event.target.matches(".qa-form-light-button-clearflags")) {
            location.reload();
        }
        if (event.target.matches("input.qa-form-light-button-flag")) {
            const flagButton = document.querySelector("input.qa-form-light-button-flag");
            flagButton.type = "button";
            
            event.preventDefault();
            showPopup();    

            flagboxPopup.addEventListener("click", () => {
                hidePopup();
            });

            closer.addEventListener("click", () => {
                hidePopup();
                return false;
            });

            sendButton.addEventListener("click", () => {

                const flagReason = document.querySelector("input.qa-spam-reason-radio:checked");
                const flagNotice = document.querySelector(".qa-spam-reason-text").value;
                
                if (!flagReason) {
                    showError(reportReasonEmptyError);
                    return false;
                }
                
                const { postid: postId, posttype: postType, parentid: parentId } = flagButton.dataset;
                
                const dataArray = {questionid: flagQuestionid, postid: postId, posttype: postType, reasonid: flagReason.value, notice: flagNotice};
                const sendData = JSON.stringify(dataArray);
                let isError = false;

                if(flagReason) {
                    sendButton.value = "Wysyłanie...";
                    sendButton.disabled = true;
                    
                    $.ajax({
                        type: "POST",
                        url: flagAjaxURL,
                        data: { ajaxdata: sendData },
                        dataType:"json",
                        cache: false,
                        success: function(data) {
                            if(data.error) {
                                //alert(data.error);
                                if ("Zbyt wiele zgłoszeń. Spróbuj ponownie za godzinę" === data.error) {
                                    showError(tooManyReportError);
                                    return false;
                                } else {
                                    //alert(data.error);
                                    isError = true;
                                }
                            } else if(data.success) {
                                location.reload();
                            } else {
                                //alert(data.error);
                                isError = true;
                            }
                        },
                        error: function(data) {
                            //alert(data.error);
                            isError = true;
                        }
                    });    
                }
                if (isError) {
                    showError(errorText);
                }
            
            });
        }
    });    
});  
