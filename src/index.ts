import {Request, Response, Router} from 'express';
import statusCode from "http-status-codes";

import { verify, mustVerify } from "./kernel/access";
import user from "./controllers/user";
import token from "./controllers/token";
import post from "./controllers/post";
import timeline from "./controllers/timeline";

const router: Router = Router();

router.get('/', function (_: Request, response: Response) {
    response.status(statusCode.OK).send({
        status: statusCode.OK,
        data: {
            description: "General MicroService Application Template",
            information: "https://github.com/star-inc/essential",
            copyright: "(c)2021 Star Inc."
        }
    });
});

router.use('/user', verify, user);
router.use('/token', verify, token);
router.use('/post', mustVerify, post);
router.use('/timeline', mustVerify, timeline);

module.exports = router;
