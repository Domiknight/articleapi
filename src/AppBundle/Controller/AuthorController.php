<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;

class AuthorController extends FOSRestController
{
    /**
     * @Post("/api/authors", name="author_create")
     *
     * @ApiDoc(
     *  description="Create a new Author",
     * )
     */
    public function createAction()
    {
        $view = $this->view(['thing']);
        $view->setStatusCode(Response::HTTP_CREATED);
        return $this->handleView($view);
    }

}
