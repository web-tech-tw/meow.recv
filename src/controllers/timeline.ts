import {Request, Response, Router, urlencoded} from 'express';

const router: Router = Router();
router.use(urlencoded({extended: true}));
router.get('/', function (request: Request, response: Response) {
})
router.post('/', function (request: Request, response: Response) {
});
router.patch('/', function (request: Request, response: Response) {
});
router.delete('/', function (request: Request, response: Response) {
});

export default router;
