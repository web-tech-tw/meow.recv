import {sha256} from "js-sha256";
import statusCode from 'http-status-codes';
import {Request, Response, Router, urlencoded} from 'express';

import {issue} from "../kernel/access";
import User from "../models/user"

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
    }
    const username = request.body.username;
    const password = sha256(request.body.password);
    const uuid = await issue(username, password);
    if (uuid !== undefined) {
        response.status(statusCode.OK).json({uuid});
    } else {
        response.status(statusCode.UNAUTHORIZED).send();
    }
});
router.patch('/', async function (request: Request, response: Response) {
    if (!response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
    }
    const user: User = response.locals.user;
    user.display_name = request.body.display_name;
    await user.save()
    response.status(statusCode.NO_CONTENT).send();
});
router.delete('/', async function (request: Request, response: Response) {
    if (!response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
    }
    const user: User = response.locals.user;
    await user.destroy()
    response.status(statusCode.NO_CONTENT).send();
});

export default router;
