import {sha256} from "js-sha256";
import statusCode from 'http-status-codes';
import {Request, Response, Router, urlencoded} from 'express';

import {issue} from "../kernel/access";

const router: Router = Router();
router.use(urlencoded({extended: true}));
router.get('/', function (_: Request, response: Response) {
    if (response.locals.user) {
        response.status(statusCode.OK).json(response.locals.user);
    } else {
        response.status(statusCode.UNAUTHORIZED).send();
    }
})
router.post('/', async function (request: Request, response: Response) {
    if (response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
        return;
    }
    if (!request.body.username || !request.body.password) {
        response.status(statusCode.BAD_REQUEST).send();
        return;
    }
    const username = request.body.username;
    const password = sha256(request.body.password);
    const token = await issue(username, password);
    if (token) {
        response.status(statusCode.CREATED).send(token);
    } else {
        response.status(statusCode.UNAUTHORIZED).send();
    }
});

export default router;
