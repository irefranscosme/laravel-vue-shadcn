import axios from 'axios';
import { validation } from '@/utils/errors/validation';

axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.timeout = 60000;
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

axios.interceptors.response.use(null, (err) => {
    const error = {
        status: err.response!.status,
        original: err,
        validation: {},
        message: '',
    };

    console.log(err.response.data.message);

    switch (err.response!.status) {
        case 422:
            error.validation = validation(err);
            break;
        case 401:
            error.message = err.response.data.message;
            break;
        default:
            error.message = 'Something went wrong. Please try again later.';
    }

    return Promise.reject(error);
});
