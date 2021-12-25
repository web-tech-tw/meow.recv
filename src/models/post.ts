import { Table, Column, Model } from 'sequelize-typescript';

@Table
export default class Post extends Model {
    @Column
    uuid: string

    @Column
    author: string

    @Column
    content: string

    @Column
    createdAt: Date

    @Column
    updatedAt: Date

    @Column
    parent: string

    @Column
    link: string

    @Column
    children: string
}
