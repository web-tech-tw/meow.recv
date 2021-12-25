import { Table, Column, Model } from 'sequelize-typescript';

@Table
export default class Article extends Model {
    @Column
    uuid: string

    @Column
    username: string

    @Column
    password: string

    @Column
    display_name: string

    @Column
    device: string

    @Column
    ip_address: string

    @Column
    created_at: number

    @Column
    updated_at: number
}
