<?php
/**
 * Created by PhpStorm.
 * User: kaptan
 * Date: 23.08.2017
 * Time: 09:56
 */

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class NewActionService

{

    private $request;
    private $targetDir;


    /**
     * @var EntityManager
     */
    private $entityManager;
    /** @var Form $form */
    private $form;

    private $entity;

    private $formFactory;

    private $container;

    public function __construct(EntityManager $entityManager, RequestStack $request, FormFactory $formFactory,ContainerInterface  $container)
    {
        $this->request = $request->getCurrentRequest();
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function createForm($formType, $entity)
    {
        $this->form = $this->formFactory->create($formType, $entity);
        $this->form->handleRequest($this->getRequest());
        $this->entity = $entity;

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->container->setParameter("file_id", $entity->getFileId());
            $em = $this->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return true;
        }

        return false;
    }


    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }




}