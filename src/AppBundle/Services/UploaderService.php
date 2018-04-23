<?php
/**
 * Created by PhpStorm.
 * User: kaptan
 * Date: 22.08.2017
 * Time: 16:00
 */

namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class UploaderService implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getParameter("upload_dir");
        /** @var File $file */
        $file = $container->getParameter("file_id");
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move(
            $container->getParameter('upload_dir'),
            $fileName
        );
    }
}
