<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Entity\Article;
use Snc\RedisBundle\Client\Predis;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;


class AbstractController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('userName', TextType::class)
            ->add('password', PasswordType::class)
            ->add('Giris', SubmitType::class, array('label' => 'Giriş'))
            ->getForm();

        $form->handleRequest($request);
        return $this->render('login.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/", name="homepage")
     *
     *
     */
    public function indexAction(Request $request)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->set("name", "mehmet");
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();
        return $this->render("views/index.twig", array(
            "session" => $request->getSession()->get("name"),
            "articles" => $articles,
        ));
    }


    /**
     * @Route("/number")
     * @Security("has_role('ROLE_USER')")
     */
    public function numberAction()
    {


        $number = mt_rand(0, 100);


        return $this->render("views/calculate.twig", array("number" => $number));
    }


    /**
     *
     * @Route("/admin", name="adminAction")
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function adminAction()
    {
        return $this->render("admin/admin.index.twig", array(
        ));

    }

    /**
     * @Route("/about", name="aboutAction")
     */
    public function aboutAction()
    {
        return $this->render("views/about.twig", array(
        ));

    }

}
