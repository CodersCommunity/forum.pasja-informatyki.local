const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = '/report-flag';
const CONTENT_TYPE = {
  FLAG: 'application/json',
  UN_FLAG: 'application/x-www-form-urlencoded'
};

const AJAX_PURPOSE = Object.freeze({
  FLAG: 'FLAG',
  UN_FLAG: 'UN_FLAG'
});

function prepareBody(data, purpose) {
  return purpose === AJAX_PURPOSE.FLAG ? JSON.stringify(data) : data;
}

const sendAjax = (data, purpose) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    fetch(URL, {
      method: 'POST',
      headers: {
        // 'Content-Type': CONTENT_TYPE[purpose] // 'application/json' // 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: JSON.stringify(data) // prepareBody(data, purpose)
    }).then(async (value) => {
      clearTimeout(timeoutId);

      // let text = null;
      // if (purpose === AJAX_PURPOSE.UN_FLAG) {
      //   try {
      //     text = await value.text();
      //   } catch ( e ) {
      //     console.error( 'value.text not worked... /e: ', e );
      //   }
      // }

      console.warn('fetch response: ', value/*, ' /?:', text*/);

      // const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';
      resolve(value.json());
    });
  });
};

export { sendAjax, AJAX_PURPOSE };
