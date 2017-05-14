<?php
/**
 * Created by PhpStorm.
 * User: davidquaglieri
 * Date: 14/5/17
 * Time: 1:39 PM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\Collection;

class ArticleManager
{
    private $doctrine;

    /**
     * ArticleManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get all Articles
     *
     * @return null|array
     */
    public function findAll()
    {
        return $this->doctrine->getRepository('AppBundle:Article')->findAll();
    }

    /**
     * Get an Article by the given id
     *
     * @param $id
     * @return null|object|\AppBundle\Entity\Article
     */
    public function findOne($id)
    {
        return $this->doctrine->getRepository('AppBundle:Article')->find($id);
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function save(Article $article)
    {
        $em = $this->doctrine->getManager();
        $em->persist($article);
        $em->flush();

        return $article;
    }

    /**
     * @param Article $article
     */
    public function delete(Article $article)
    {
        $em = $this->doctrine->getManager();
        $em->remove($article);
        $em->flush();
    }
}