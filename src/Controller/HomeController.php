<?php

namespace App\Controller;

use App\Entity\CalenderItem;
use App\Entity\NieuwsItem;
use App\Entity\Page;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
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
        $this->getGlobalVars();
        $homePageEnabled = $this->getHomePageEnabledPages();

        return $this->render('defaultPages/home.html.twig', array('homePageEnabledPages' => $homePageEnabled));
    }

    /**
     * @Route("/leerduiken", name="leerDuiken")
     */
    public function leerDuiken()
    {
        $this->getGlobalVars();
        return $this->render('defaultPages/leerDuiken.html.twig');
    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
    private function getHomePageEnabledPages(){
        return publishedPageFilter::filterHomePageEnabled($this->getDoctrine()->getRepository(Page::class)->findAll());
    }
}