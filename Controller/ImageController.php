<?php

namespace Prh\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImageController.
 *
 * @Route("/admin/image")
 */
class ImageController extends Controller
{
    /**
     * Save an image and resize it.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/save", name="prh_image_save")
     * @Method("POST")
     */
    public function saveAction(Request $request)
    {
        $imageService = $this->get('prh.blog.service.image');
        $path = $this->getParameter('kernel.root_dir') . '/../web/uploads/images';

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('file');

        $imageService->createResizedImages($path, $file);

        return new Response(sprintf(
                '%s://%s/uploads/images/%s',
                $request->getScheme(),
                $request->getHttpHost(),
                $imageService->appendSizeExt($file->getClientOriginalName(), 'b')
            )
        );
    }
}
