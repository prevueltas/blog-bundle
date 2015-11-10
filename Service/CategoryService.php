<?php

namespace Prh\BlogBundle\Service;

use Prh\BlogBundle\Entity\CategoryRepository;
use Prh\BlogBundle\Entity\Category;
use Prh\BlogBundle\Form\CategoryType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\Form;

/**
 * Class CategoryService.
 */
class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

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
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(FormFactory $formFactory, Router $router, CategoryRepository $categoryRepository)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get a category.
     *
     * @param int $categoryId
     *
     * @return null|Category
     */
    public function getCategory($categoryId)
    {
        return $this->categoryRepository->find($categoryId);
    }

    /**
     * Get all the categories.
     *
     * @return array|Category[]
     */
    public function getCategories()
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * Save a category.
     *
     * @param Category $category
     */
    public function save(Category $category)
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     */
    public function delete(Category $category)
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Create a category form.
     *
     * @param Category $category
     *
     * @return Form
     *
     */
    public function createForm(Category $category)
    {
        $route = $this->router->generate('prh_blog_category_create');

        if ($category->getId()) {
            $route = $this->router->generate('prh_blog_category_update', ['id' => $category->getId()]);
        }

        return $this->formFactory->create(
            new CategoryType(),
            $category,
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
     * @param Category $category
     *
     * @return null|Category
     */
    public function processForm(Request $request, Category $category = null)
    {
        if (null === $category) {
            $category = new Category();
        }

        $form = $this->createForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->save($category);

            return $category;
        }
    }
}
