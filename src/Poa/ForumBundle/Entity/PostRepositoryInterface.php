<?php

namespace Poa\ForumBundle\Entity;

interface PostRepositoryInterface extends RepositoryInterface
{
    /**
     * Finds one post by its id
     *
     * @param integer $id
     * @return \Poa\ForumBundle\Entity\Post or NULL whether the specified id does not match any post
     */
    function findOneById($id);

    /**
     * Finds all posts matching the specified Topic ordered by their
     * last created date
     *
     * @param \Poa\ForumBundle\Entity\Topic $topic
     * @param boolean $asPaginator Will return a Paginator instance if true
     * @return array|Paginator
     */
    function findAllByTopic($topic, $asPaginator);

    /**
     * Finds more recent posts matching the specified Topic
     *
     * @param \Poa\ForumBundle\Entity\Topic $topic
     * @param int $number max number of posts to fetch
     * @return array of Post
     */
    function findRecentByTopic($topic, $number);

    /**
     * Finds all posts matching the specified query ordered by their
     * last created date
     *
     * @param string $query
     * @param boolean $asPaginator Will return a Paginator instance if true
     * @return array|Paginator
     */
    function search($query, $asPaginator);

    /**
     * Gets the post that preceds this one
     *
     * @return \Poa\ForumBundle\Entity\Post or null
     **/
    public function getPostBefore($post);

    /**
     * Creates a new post instance
     *
     * @return \Poa\ForumBundle\Entity\Post
     */
    function createNewPost();
}
