const express = require('express');
const initialize = require('./src/kernel/initialize');

require('dotenv').config()
const api = require('./src/index');

// Constants
const app = express();
const port = 3000;

// Dynamic
app.use('/', api);

// Listen
initialize().then(() => app.listen(port, () => {
    console.log('meow.recv\n====')
    console.log(`Application is listening at http://localhost:${port}`)
}))
