<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class ArticleController
 *
 * @RouteResource("articles")
 *
 * @package AppBundle\Controller
 */
class ArticleController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Get("/api/articles")
     *
     * @ApiDoc(
     *   description = "Lists all article entities."
     * )
     */
    public function indexAction()
    {
        $articles = $this->get('app.article.manager')->findAll();

        $context = new Context();
        $context->setGroups(['list']);

        $view = $this->view($articles);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @Post("/api/articles")
     *
     * @ApiDoc(
     *  description = "Creates a new article entity."
     * )
     *
     * @param Request $request
     * @return \Symfony\Component\Form\Form|Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ArticleType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $article = $this->get('app.article.manager')->save($form->getData());

        $context = new Context();
        $context->setGroups(['detail']);

        $view = $this->view($article);
        $view
            ->setStatusCode(Response::HTTP_CREATED)
            ->setLocation($this->generateUrl('show_articles', ['id' => $article->getId()]))
            ->setContext($context)
        ;
        return $this->handleView($view);
    }

    /**
     * @Get("/api/articles/{id}")
     *
     * @ApiDoc(
     *     description = "Gets the details for a specific article"
     * )
     */
    public function showAction(Article $article)
    {
        $context = new Context();
        $context->setGroups(['detail']);

        $view = $this->view($article);
        $view->setContext($context);
        return $this->handleView($view);
    }

    /**
     * @Put("/api/articles/{id}")
     *
     * @ApiDoc(
     *     description = "Displays a form to edit an existing article entity."
     * )
     */
    public function editAction(Request $request, Article $article)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $this->get('app.article.manager')->save($article);

        return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
    }

    /**
     * @Delete("/api/articles/{articleId}")
     *
     * @ApiDoc(
     *     description = "Deletes a article entity."
     * )
     */
    public function deleteAction($articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        if ($article)
        {
            $this->get('app.article.manager')->delete($article);
        }

        return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
    }
}
