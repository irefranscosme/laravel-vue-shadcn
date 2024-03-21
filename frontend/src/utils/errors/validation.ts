import { AxiosError } from 'axios';
import { ResponseError } from '@/types';

export const validation = (err: AxiosError): Object => {
    if (!err.response) return {};
    if (!err.response.data) return {};

    const response = err.response.data as ResponseError;
    const errorArray: [string, any][] = [];

    Object.entries(response.errors).forEach(([key, value]) => {
        if (!Array.isArray(value)) return;
        value = value.join(', ');
        errorArray.push([key, value]);
    });

    const errors = Object.fromEntries(errorArray);

    return errors;
};
