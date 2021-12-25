import * as express from 'express';

const port = 5000;

const app: express.Express = express();

app.get('/', (request: express.Request, response: express.Response) => {
    response.type('text/plain');
    response.send('Homepage');
})

app.get('/articles', (request: express.Request, response: express.Response) => {
    response.type('text/plain');
    response.send('All articles are here!');
})

app.get('/about-me', (request: express.Request, response: express.Response) => {
    response.type('text/plain');
    response.send('My name is Jimmy.');
})

app.use((request: express.Request, response: express.Response) => {
    response.type('text/plain');
    response.status(404)
    response.send('Page is not found.');
})

app.listen(port, () => {
    console.log(`server is running on http://localhost:5000`)}
);
