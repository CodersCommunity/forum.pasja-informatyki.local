const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = {
  FLAG: '/ajaxflagger',
  UN_FLAG: window.location.href
};
const CONTENT_TYPE = {
  FLAG: 'application/json',
  UN_FLAG: 'application/x-www-form-urlencoded'
};

const AJAX_PURPOSE = Object.freeze({
  FLAG: 'FLAG',
  UN_FLAG: 'UN_FLAG'
});

function prepareBody(data, purpose) {
  return purpose === 'FLAG' ? JSON.stringify(data) : data;
}

const sendAjax = (data, purpose) => {
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

    // TODO: ensure the `return` is meaningless
    return fetch(URL[purpose], {
      method: 'POST',
      headers: {
        // 'Content-Type': CONTENT_TYPE[purpose] // 'application/json' // 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      // redirect: 'manual',
      body: prepareBody(data, purpose) // JSON.stringify(data) //`flagData=${ encodeURIComponent(JSON.stringify(data)) }`,
    }).then(async (value) => {
      clearTimeout(timeoutId);

      let text = null;
      if (purpose === AJAX_PURPOSE.UN_FLAG) {
        try {
          text = await value.text();
        } catch ( e ) {
          console.error( 'value.text not worked... /e: ', e );
        }
      }

      console.warn('fetch response: ', value, ' /?:', text);

      const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';
      resolve(resolveValue);
    });
  });
};

export { sendAjax, AJAX_PURPOSE };
