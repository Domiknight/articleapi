<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * Article controller.
 *
 * @Route("api/articles")
 */
class ArticleController extends FOSRestController
{
    /**
     * @Get("/", name="api_articles_index")
     *
     * @ApiDoc(
     *   description = "Lists all article entities."
     * )
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        $view = $this->view($articles);
        return $this->handleView($view);
    }

    /**
     * @Post("/", name="api_articles_new")
     *
     * @ApiDoc(
     *  description = Creates a new article entity.
     * )
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ArticleType::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $article = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        $view = $this->view($article);
        $view
            ->setStatusCode(Response::HTTP_CREATED)
            ->setLocation($this->generateUrl('api_articles_show', ['articleId' => $article->getId()]))
        ;
        return $this->handleView($view);
    }

    /**
     * @Get"/{id}", name="api_articles_show")
     *
     * @ApiDoc(
     *     description = Gets the details for a specific article
     * )
     */
    public function showAction($articleId)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($articleId);
        if (!$article)
        {
            throw new NotFoundHttpException();
        }

        $view = $this->view($article);
        return $this->handleView($view);
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Put("/{id}", name="api_articles_edit")
     */
    public function editAction(Request $request, Article $article)
    {
        throw new ServiceUnavailableHttpException();

        $deleteForm = $this->createDeleteForm($article);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $form;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('api_articles_edit', array('id' => $article->getId()));
        }

        return $this->handleView($this->view(), Response::HTTP_NO_CONTENT);
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}", name="api_articles_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        throw new ServiceUnavailableHttpException();

        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('api_articles_index');
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('api_articles_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
