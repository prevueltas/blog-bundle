<?php

namespace Prh\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Prh\BlogBundle\Entity\Category;

/**
 * Class CategoryController.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
    /**
     * List all the categories.
     *
     * @return Response
     *
     * @Route("/list", name="prh_blog_category_list")
     */
    public function listAction()
    {
        $categoryService = $this->get('prh.blog.service.category');

        return $this->render(
            'PrhBlogBundle:default:categories.html.twig',
            [
                'categories' => $categoryService->getCategories()
            ]
        );
    }

    /**
     * Show a form to create a new category.
     *
     * @return Response
     *
     * @Route("/new", name="prh_blog_category_new")
     */
    public function newAction()
    {
        $categoryService = $this->get('prh.blog.service.category');

        return $this->render(
            'PrhBlogBundle:default:category.html.twig',
            [
                'form' => $categoryService->createForm(new Category())->createView()
            ]
        );
    }

    /**
     * Show a form to edit a category.
     *
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="prh_blog_category_edit")
     * @ParamConverter("category", class="PrhBlogBundle:Category")
     */
    public function editAction(Category $category)
    {
        $categoryService = $this->get('prh.blog.service.category');

        return $this->render(
            'PrhBlogBundle:default:category.html.twig',
            [
                'category' => $category,
                'form' => $categoryService->createForm($category)->createView()
            ]
        );
    }

    /**
     * Create a category.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/create", name="prh_blog_category_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $categoryService = $this->get('prh.blog.service.category');

        if ($category = $categoryService->processForm($request)) {
            $this->addFlash('success', 'The category has been created.');

            return $this->redirectToRoute('prh_blog_category_edit', ['id' => $category->getId()]);
        }

        $this->addFlash('error', 'Oops! Please try again.');

        return $this->redirectToRoute('prh_blog_category_new');
    }

    /**
     * Update a category.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update/{id}", name="prh_blog_category_update")
     * @Method("POST")
     * @ParamConverter("category", class="PrhBlogBundle:Category")
     */
    public function updateAction(Request $request, Category $category)
    {
        $categoryService = $this->get('prh.blog.service.category');

        if ($categoryService->processForm($request, $category)) {
            $this->addFlash('success', 'The category has been updated.');
        } else {
            $this->addFlash('error', 'Oops! Please try again.');
        }

        return $this->redirectToRoute('prh_blog_category_edit', ['id' => $category->getId()]);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{id}", name="prh_blog_category_delete")
     * @ParamConverter("category", class="PrhBlogBundle:Category")
     */
    public function deleteAction(Category $category)
    {
        $categoryService = $this->get('prh.blog.service.category');
        $categoryService->delete($category);

        $this->addFlash('success', 'The category has been removed');

        return $this->redirectToRoute('prh_blog_category_list');
    }
}
