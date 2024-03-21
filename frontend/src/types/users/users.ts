export type User = {
    id: string | number;
    name: string;
    email: string;
    email_verified_at: string;
    created_at: string;
    updated_at: string;
};

export type UserResponse = {
    data: User | User[];
};
