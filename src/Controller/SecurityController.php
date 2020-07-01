<?php

namespace App\Controller;

use App\Entity\Page;
use App\Services\publishedPageFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        $pages = $this->getCustomPages();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    private function getCustomPages(){
        return publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll());
    }
}
