<?php

namespace Prh\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController.
 *
 * @Route("/admin")
 */
class DefaultController extends Controller
{
    /**
     * Redirect to post list.
     *
     * @return Response
     *
     * @Route("/", name="prh_blog_index")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('prh_blog_post_list');
    }
}
