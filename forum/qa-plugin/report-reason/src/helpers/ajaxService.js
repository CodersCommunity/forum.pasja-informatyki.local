const URL = '/ajaxflagger';
const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const sendAjax = (data) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    // return new Promise((res, rej) => {
    //   $.ajax({
    //     type: "POST",
    //     url: URL,
    //     data: { flagData: JSON.stringify(data) },
    //     dataType:"json",
    //     cache: false,
    //     success: res,
    //     error: rej
    //   });
    // })

    return fetch(URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: `flagData=${ encodeURIComponent(JSON.stringify(data)) }`,
    }).then((value) => {
      clearTimeout(timeoutId);
      resolve(value.json());
    });
  });
};

export default sendAjax;
