<?php


namespace App\Controller;




use App\Entity\Page;
use App\Entity\User;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BestuurController extends AbstractController
{
    /**
     * @Route("/bestuur", name="getBestuur")
     */
    public function getBestuur(Request $request, PaginatorInterface $paginator){
        $this->getGlobalVars();
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(["isBestuursLid" => true]);

        $results = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('defaultPages/bestuur.html.twig', array('bestuur' => $results));
    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
}