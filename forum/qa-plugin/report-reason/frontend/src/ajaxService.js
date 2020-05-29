const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = '/report-flag';
const CONTENT_TYPE = 'application/json';

const sendAjax = (data) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    fetch(URL, {
      method: 'POST',
      headers: {
        'Content-Type': CONTENT_TYPE,
      },
      body: JSON.stringify(data),
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

      console.warn('fetch response: ', value /*, ' /?:', text*/);

      // const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';
      resolve(value.json());
    });
  });
};

export default sendAjax;
