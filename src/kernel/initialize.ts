import database from './database';

import Post from "../models/post";
import User from '../models/user';

module.exports = async function (): Promise<void> {
    database.addModels([Post, User]);
    await database.sync()
}
