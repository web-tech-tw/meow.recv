const express = require('express');
const initialize = require('./src/kernel/initialize');

const api = require('./src/index');

// Constants
const app = express();
const port = 3000;

// Static
app.use(express.static('public'));

// Dynamic
app.use('/api', api);

// Listen
initialize().then(() => app.listen(port, () => {
    console.log(`Essential is listening at http://localhost:${port}`)
}))
