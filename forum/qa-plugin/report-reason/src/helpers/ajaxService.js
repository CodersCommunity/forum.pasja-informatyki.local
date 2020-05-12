const URL = '/ajaxflagger';
const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const sendAjax = (data) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    return fetch(URL, {
      method: 'POST',
      body: data,
    }).then((value) => {
      clearTimeout(timeoutId);
      resolve(value);
    });
  });
};

export default sendAjax;
