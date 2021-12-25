import statusCode from 'http-status-codes';

import {sha256} from "js-sha256";
import {Request, Response, Router, urlencoded} from 'express';
import {v4 as uuid} from "uuid";

import User from "../models/user"

const router: Router = Router();
router.use(urlencoded({extended: true}));
router.post('/', async function (request: Request, response: Response) {
    if (response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
        return;
    }
    if (
        !request.body.username ||
        !request.body.password ||
        !request.body.display_name
    ) {
        response.status(statusCode.BAD_REQUEST).send();
        return;
    }
    const user = new User();
    user.uuid = uuid();
    user.username = request.body.username;
    user.password = sha256(request.body.password);
    user.displayName = request.body.displayName;
    await user.save();
    response.status(statusCode.CREATED).send();
});
router.patch('/', async function (request: Request, response: Response) {
    if (!response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
        return;
    }
    const user: User = response.locals.user;
    user.displayName = request.body.displayName;
    await user.save()
    response.status(statusCode.NO_CONTENT).send();
});
router.delete('/', async function (request: Request, response: Response) {
    if (!response.locals.user) {
        response.status(statusCode.FORBIDDEN).send();
        return;
    }
    const user: User = response.locals.user;
    await user.destroy()
    response.status(statusCode.NO_CONTENT).send();
});

export default router;
