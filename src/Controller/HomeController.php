<?php

namespace App\Controller;

use App\Entity\CalenderItem;
use App\Entity\NieuwsItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class HomeController extends AbstractController {
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('defaultPages/home.html.twig');
    }

    /**
     * @Route("/leerduiken", name="leerDuiken")
     */
    public function leerDuiken()
    {
        return $this->render('defaultPages/leerDuiken.html.twig');
    }


}