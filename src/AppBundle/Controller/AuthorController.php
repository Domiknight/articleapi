<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class AuthorController
 *
 * @RouteResource("authors")
 *
 * @package AppBundle\Controller
 */
class AuthorController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Get("/api/authors/{authorId}")
     *
     * @ApiDoc(
     *  description="Get an Existing Author",
     * )
     *
     * @param $authorId
     * @return Response
     */
    public function showAction($authorId)
    {
        $author = $this->getDoctrine()->getRepository('AppBundle:Author')->find($authorId);
        if (!$author)
        {
            throw new NotFoundHttpException();
        }

        $view = $this->view($author);
        return $this->handleView($view);
    }

    /**
     * @Post("/api/authors")
     *
     * @ApiDoc(
     *  description="Create a new Author",
     * )
     *
     * @param Request $request
     * @return Response|Form
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(AuthorType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $author = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();

        $view = $this->view($author);
        $view
            ->setStatusCode(Response::HTTP_CREATED)
            ->setLocation($this->generateUrl('show_authors', ['authorId' => $author->getId()]))
        ;
        return $this->handleView($view);
    }

}
