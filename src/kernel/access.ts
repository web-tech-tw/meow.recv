import {NextFunction, Request, Response} from "express";
import jwt from "jsonwebtoken";

import User from "../models/user"
import statusCode from "http-status-codes";

async function issue(username: string, password: string): Promise<string | undefined> {
    const user = await User.findOne({where: {username, password}})
    return jwt.sign(<object>user, "test_cat");
}

async function verify(request: Request, response: Response, next: NextFunction): Promise<void> {
    const token = request.header("Authorization");
    if (token !== undefined) {
        response.locals.user = jwt.verify(token, "test_cat");
    } else {
        response.locals.user = null;
    }
    next();
}

async function mustVerify(request: Request, response: Response): Promise<void> {
    await verify(request, response, () => response.status(statusCode.UNAUTHORIZED).send("Unauthorized"));
}

export {issue, verify, mustVerify};
