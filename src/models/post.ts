import { Table, Column, Model } from 'sequelize-typescript';

@Table
export default class Article extends Model {
    @Column
    uuid: string

    @Column
    author: string

    @Column
    content: string

    @Column
    created_at: number

    @Column
    updated_at: number

    @Column
    parent: string

    @Column
    link: string

    @Column
    children: this[]
}
