const httpTimeoutReason = 'AJAX_TIMEOUT';

const sendAjax = (url, data, timeout) => {
  return new Promise((resolve, reject) => {
    fetch(url, {
      method: 'POST',
      body: data,
    }).then(resolve, reject);

    setTimeout(() => {
      reject(httpTimeoutReason);
    }, timeout);
  });
};

export default sendAjax;
