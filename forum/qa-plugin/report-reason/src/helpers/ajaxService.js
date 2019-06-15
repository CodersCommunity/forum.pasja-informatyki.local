const httpMethod = 'POST';
const httpTimeoutReason = 'Ajax timeout';

const ajax = (url, data, onSuccess, onError, timeout) => {
    new Promise((resolve, reject) => {
        fetch(url, {
            method: httpMethod,
            body: data
        }).then(resolve, reject);

        setTimeout(() => {
            reject(httpTimeoutReason);
        }, timeout);
    }).then(onSuccess, onError);
};

export default ajax;