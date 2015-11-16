<?php

namespace Prh\BlogBundle\Service;

use Prh\BlogBundle\Entity\PostRepository;
use Prh\BlogBundle\Entity\Post;
use Symfony\Component\Form\FormFactory;
use Prh\BlogBundle\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\Form;

/**
 * Class PostService.
 */
class PostService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor.
     *
     * @param FormFactory $formFactory
     * @param Router $router
     * @param PostRepository $postRepository
     */
    public function __construct(FormFactory $formFactory, Router $router, PostRepository $postRepository)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->postRepository = $postRepository;
    }

    /**
     * Get a post.
     *
     * @param $postId
     *
     * @return null|Post
     */
    public function getPost($postId)
    {
        return $this->postRepository->find($postId);
    }

    /**
     * Get all the posts.
     *
     * @return array|Post[]
     */
    public function getPosts()
    {
        return $this->postRepository->findAll();
    }

    /**
     * Save a post.
     *
     * @param Post $post
     */
    public function save(Post $post)
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     */
    public function delete(Post $post)
    {
        $this->postRepository->delete($post);
    }

    /**
     * Get an array of category ids.
     *
     * @param Post $post
     * @return array
     */
    public function getCategoryIds(Post $post)
    {
        $categoryIds = [];

        foreach ($post->getCategories() as $category) {
            $categoryIds[] = $category->getId();
        }

        return $categoryIds;
    }

    /**
     * Get the published posts related to an array of category ids.
     *
     * @param array $categoryIds
     * @param bool $in False specifies posts unrelated to the array of category ids.
     *
     * @return array|Post[]
     */
    public function getPublishedPostsByCategories(array $categoryIds, $in = true)
    {
        return $this->postRepository->findPublishedPostsByCategories($categoryIds, $in);
    }

    /**
     * Get the published featured posts.
     *
     * @param int $max
     *
     * @return array|Post[]
     */
    public function getPublishedFeaturedPosts($max = 0)
    {
        $posts = $this->postRepository->findBy(
            [
                'state' => Post::STATE_PUBLISHED,
                'featured' => 1
            ],
            [
                'date' => 'desc'
            ]
        );

        if ($max > 0 && count($posts) > $max) {
            $posts = array_slice($posts, 0, $max);
        }

        return $posts;
    }

    /**
     * Get the published latest posts.
     *
     * @param int $max
     *
     * @return array|Post[]
     */
    public function getPublishedLatestPosts($max = 0)
    {
        $posts = $this->postRepository->findBy(
            [
                'state' => Post::STATE_PUBLISHED,
            ],
            [
                'date' => 'desc'
            ]
        );

        if ($max > 0 && count($posts) > $max) {
            $posts = array_slice($posts, 0, $max);
        }

        return $posts;
    }

    /**
     * Create a post form.
     *
     * @param Post $post
     *
     * @return Form
     */
    public function createForm(Post $post)
    {
        $route = $this->router->generate('prh_blog_post_create');

        if ($post->getId()) {
            $route = $this->router->generate('prh_blog_post_update', ['id' => $post->getId()]);
        }

        return $this->formFactory->create(
            new PostType(),
            $post,
            [
                'action' => $route,
                'method' => 'POST'
            ]
        );
    }

    /**
     * Process a submitted form.
     *
     * @param Request $request
     * @param Post $post
     *
     * @return Post|null
     */
    public function processForm(Request $request, Post $post = null)
    {
        if (null === $post) {
            $post = new Post();
        }

        $form = $this->createForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $this->save($post);

            return $post;
        }
    }
}
