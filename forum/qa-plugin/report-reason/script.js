document.addEventListener("DOMContentLoaded", () => {

    const flagboxPopup = document.querySelector("#flagbox-popup");
    const wrap = document.querySelector(".qa-flag-reasons-wrap");
    const body = document.body;
    const closer = document.querySelector(".close-preview-btn");
    const sendButton = document.querySelector(".qa-go-flag-send-button");
    const commentSubmitButton = document.querySelector('.qa-form-tall-button-comment')

    const errorMessage = "Błąd serwera. Proszę spróbować za jakiś czas";
    const errorPopup = document.querySelector("#qa-spam-reason-error");
    const tooManyReportError = "Zbyt dużo zgłoszeń z tego adresu IP. Spróbuj za godzinę";
    const reportReasonEmptyError = "Wybierz powód zgłoszenia!";

    function showPopup() {
        flagboxPopup.classList.remove("hide");
        flagboxPopup.classList.add("show");
    }
    function hidePopup() {
        flagboxPopup.classList.add("hide");
        flagboxPopup.classList.remove("show");
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
        errorPopup.hidden = false
        errorPopup.appendChild(paragraph);
    }

    wrap.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    commentSubmitButton.addEventListener("click", () => {
       location.reload();
    });

    body.addEventListener("click", (event) => {
        if (event.target.matches(".qa-form-light-button-unflag, .qa-form-light-button-clearflags")) {
            location.reload();
        }

        if (event.target.matches("input.qa-form-light-button-flag")) {
            const flagButton = event.target;
            flagButton.type = "button";

            event.preventDefault();
            showPopup();

            flagboxPopup.addEventListener("click", showPopup());

            closer.addEventListener("click", (event) => {
                hidePopup();
                event.preventDefault();
            });

            sendButton.addEventListener("click", (event) => {

                const flagReason = document.querySelector("input.qa-spam-reason-radio:checked");
                const flagNotice = document.querySelector(".qa-spam-reason-text").value;

                if (!flagReason) {
                    showError(reportReasonEmptyError);
                    event.preventDefault();
                }

                const { postid: postId, posttype: postType } = flagButton.dataset;
                const dataObject = {questionid: flagQuestionid, postid: postId, posttype: postType, reasonid: flagReason.value, notice: flagNotice};
                const sendData = JSON.stringify(dataObject);

                if(flagReason) {
                    blockSendButton(sendButton);

                    $.ajax({
                        type: "POST",
                        url: flagAjaxURL,
                        data: { flagData: sendData },
                        dataType:"json",
                        cache: false,
                        success: function(data) {
                            if(data.error) {
                                showError(tooManyReportError);
                            } else if(data.success.includes("1")) {
                                location.reload();
                            }
                        },
                        error: function(data) {
                            isError = true;
                        }
                    });
                }
            });
        }
    });
});

function blockSendButton(sendButton) {
    sendButton.value = "Wysyłanie...";
    sendButton.disabled = true;
}
