export interface Pagination<T> {
    current_page: number;
    data: T;
    first_page_url: string;
    from: number;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    last_page: 1;
    last_page_url: string;
    links: [{ [key: string]: any }];
    total: number;
}
