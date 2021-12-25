import { Table, Column, Model } from 'sequelize-typescript';

@Table
export default class User extends Model {
    @Column
    uuid: string

    @Column
    username: string

    @Column
    password: string

    @Column
    displayName: string

    @Column
    createdAt: Date

    @Column
    updatedAt: Date
}
