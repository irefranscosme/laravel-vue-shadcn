export type AxiosErrorResponse = {
    status: number;
    original: Object;
    validation: { [key: string]: string };
    message: string | null;
};

export type ResponseError = {
    message: string;
    errors: {};
};
