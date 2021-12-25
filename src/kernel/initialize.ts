import database from './database';

module.exports = async function (): Promise<void> {
    database.addModels([]);
    await database.sync()
}
