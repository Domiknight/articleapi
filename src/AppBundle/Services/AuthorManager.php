<?php
/**
 * Created by PhpStorm.
 * User: davidquaglieri
 * Date: 14/5/17
 * Time: 1:39 PM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Registry;


class AuthorManager
{
    private $doctrine;

    /**
     * AuthorManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Get an Author by the given id
     *
     * @param $id
     * @return null|object|\AppBundle\Entity\Author
     */
    public function findOne($id)
    {
        return $this->doctrine->getRepository('AppBundle:Author')->find($id);
    }

    /**
     * @param Author $author
     * @return Author
     */
    public function save(Author $author)
    {
        $em = $this->doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $author;
    }
}