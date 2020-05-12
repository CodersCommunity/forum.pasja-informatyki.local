const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const sendAjax = (url, data) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    return fetch(url, {
      method: 'POST',
      body: data,
    }).then((value) => {
      clearTimeout(timeoutId);
      resolve(value);
    });
  });
};

export default sendAjax;
