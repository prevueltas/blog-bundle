<?php

namespace Prh\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Prh\BlogBundle\Entity\Post;

/**
 * Class PostController.
 *
 * @Route("/admin/post")
 */
class PostController extends Controller
{
    /**
     * List all the posts.
     *
     * @return Response
     *
     * @Route("/list", name="prh_blog_post_list")
     */
    public function listAction()
    {
        $postService = $this->get('prh.blog.service.post');

        return $this->render(
            'PrhBlogBundle:default:posts.html.twig',
            [
                'posts' => $postService->getPosts()
            ]
        );
    }

    /**
     * Create a new post.
     *
     * @return Response
     *
     * @Route("/new", name="prh_blog_post_new")
     */
    public function newAction()
    {
        $postService = $this->get('prh.blog.service.post');

        return $this->render(
            'PrhBlogBundle:default:post.html.twig',
            [
                'form' => $postService->createForm(new Post())->createView()
            ]
        );
    }

    /**
     * Update a post.
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="prh_blog_post_edit")
     * @ParamConverter("post", class="PrhBlogBundle:Post")
     */
    public function editAction(Post $post)
    {
        $postService = $this->get('prh.blog.service.post');

        $preview = false;
        if (null !== $this->get('router')->getRouteCollection()->get('app_post')) {
            $preview = true;
        }

        return $this->render(
            'PrhBlogBundle:default:post.html.twig',
            [
                'post' => $post,
                'form' => $postService->createForm($post)->createView(),
                'preview' => $preview
            ]
        );
    }

    /**
     * Create a post.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/create", name="prh_blog_post_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $postService = $this->get('prh.blog.service.post');

        if ($post = $postService->processForm($request)) {
            $this->get('session')->getFlashBag()->add('success', 'The post has been created.');

            return $this->redirectToRoute('prh_blog_post_edit', ['id' => $post->getId()]);
        }

        $this->get('session')->getFlashBag()->add('error', 'Oops! Please try again.');

        return $this->redirectToRoute('prh_blog_post_new');
    }

    /**
     * Update a post.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update/{id}", name="prh_blog_post_update")
     * @Method("POST")
     * @ParamConverter("post", class="PrhBlogBundle:Post")
     */
    public function updateAction(Request $request, Post $post)
    {
        $postService = $this->get('prh.blog.service.post');

        if ($postService->processForm($request, $post)) {
            $this->get('session')->getFlashBag()->add('success', 'The post has been updated.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Oops! Please try again.');
        }

        return $this->redirectToRoute('prh_blog_post_edit', ['id' => $post->getId()]);
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{id}", name="prh_blog_post_delete")
     * @ParamConverter("post", class="PrhBlogBundle:Post")
     */
    public function deleteAction(Post $post)
    {
        $postService = $this->get('prh.blog.service.post');
        $postService->delete($post);

        $this->addFlash('success', 'The post has been removed');

        return $this->redirectToRoute('prh_blog_post_list');
    }
}
