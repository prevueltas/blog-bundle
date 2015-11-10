<?php

namespace Prh\BlogBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Prh\BlogBundle\Entity\Post;
use Prh\BlogBundle\Service\PostService;
use Prophecy\Prophet;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Class PostServiceTest.
 */
class PostServiceTest extends KernelTestCase
{
    /**
     * @var ObjectProphecy
     */
    private $routerDouble;

    /**
     * @var ObjectProphecy
     */
    private $formFactoryDouble;

    /**
     * @var ObjectProphecy
     */
    private $postRepositoryDouble;

    /**
     * @inheritdoc
     */
    public function setup()
    {
        $prohet = new Prophet();
        $this->routerDouble = $prohet->prophesize('Symfony\Component\Routing\Router');

        $prohet = new Prophet();
        $this->formFactoryDouble = $prohet->prophesize('Symfony\Component\Form\FormFactory');

        $prohet = new Prophet();
        $this->postRepositoryDouble = $prohet->prophesize('Prh\BlogBundle\Entity\PostRepository');
    }

    /**
     * @return PostService
     */
    private function createPostService()
    {
        return new PostService(
            $this->formFactoryDouble->reveal(),
            $this->routerDouble->reveal(),
            $this->postRepositoryDouble->reveal()
        );
    }

    /**
     * @param $id
     * @return ObjectProphecy
     */
    private function createCategoryDouble($id)
    {
        $prophet = new Prophet();
        $categoryDouble = $prophet->prophesize('Prh\BlogBundle\Entity\Category');
        $categoryDouble->getId()->willReturn($id);

        return $categoryDouble;
    }

    /**
     * Test getCategoryIds.
     */
    public function testGetCategoryIds()
    {
        $postService = $this->createPostService();
        $post = new Post();

        for ($i = 1; $i < 3; $i++) {
            $categoryDouble = $this->createCategoryDouble($i);
            $categoryDouble->addPost($post)->willReturn(null);
            $post->addCategory($categoryDouble->reveal());
        }

        $categoryIds = $postService->getCategoryIds($post);

        $this->assertTrue(2 == count($categoryIds));
        $this->assertTrue(1 == $categoryIds[0]);
    }

    /**
     * Test getPublishedFeaturedPosts.
     */
    public function testGetPublishedFeaturedPosts()
    {
        $postService = $this->createPostService();
        $this->postRepositoryDouble->findBy(
                [
                    'state' => Post::STATE_PUBLISHED,
                    'featured' => 1
                ],
                [
                    'date' => 'desc'
                ]
            )
            ->willReturn([new Post(), new Post()]);

        $posts = $postService->getPublishedFeaturedPosts();

        $this->assertTrue(2 == count($posts));

        $posts = $postService->getPublishedFeaturedPosts(1);
        $this->assertTrue(1 == count($posts));

        $posts = $postService->getPublishedFeaturedPosts(2);
        $this->assertTrue(2 == count($posts));

        $posts = $postService->getPublishedFeaturedPosts(3);
        $this->assertTrue(2 == count($posts));

        $this->assertInstanceOf('Prh\BlogBundle\Entity\Post', $posts[0]);
    }

    /**
     * Test getPublishedFeaturedPosts.
     */
    public function testGetPublishedLatestPosts()
    {
        $postService = $this->createPostService();
        $this->postRepositoryDouble->findBy(
                [
                    'state' => Post::STATE_PUBLISHED,
                ],
                [
                    'date' => 'desc'
                ]
            )
            ->willReturn([new Post(), new Post()]);

        $posts = $postService->getPublishedLatestPosts();

        $this->assertTrue(2 == count($posts));

        $posts = $postService->getPublishedLatestPosts(1);
        $this->assertTrue(1 == count($posts));

        $posts = $postService->getPublishedLatestPosts(2);
        $this->assertTrue(2 == count($posts));

        $posts = $postService->getPublishedLatestPosts(3);
        $this->assertTrue(2 == count($posts));

        $this->assertInstanceOf('Prh\BlogBundle\Entity\Post', $posts[0]);
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        parent::tearDown();
    }
}