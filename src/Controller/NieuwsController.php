<?php

namespace App\Controller;

use App\Entity\CalenderItem;
use App\Entity\NieuwsItem;
use App\Entity\Page;
use App\Services\CalenderTypes;
use App\Services\publishedPageFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class NieuwsController extends AbstractController {

    /**
     * @Route("/nieuws", name="nieuws")
     */
    public function nieuws()
    {
        $nieuwsItems = $this->getDoctrine()->getRepository(NieuwsItem::class)->findAll();
        $pages = $this->getCustomPages();

        return $this->render('defaultPages/nieuws.html.twig', array('nieuwsItems' => array_reverse($nieuwsItems), 'pages' => $pages));
    }

    /**
     * @Route("/nieuws/add", name="addNieuws")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addNieuws(Request $request)
    {
        $item = new NieuwsItem();

        $form = $this->getNieuwsItemForm($item, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $item = $form->getData();
            $item->setAuthor($user->getUsername());
            $item->setSubmitDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('nieuws');
        }
        $pages = $this->getCustomPages();

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuw Nieuws item',
            'form' => $form->createView(),
            'pages' => $pages
        ));

    }

    /**
     * @Route("/nieuws/{id}", name="nieuwsItem")
     */
    public function nieuwsItem($id)
    {
        $nieuwsItem = $this->getDoctrine()->getRepository(NieuwsItem::class)->find($id);
        $pages = $this->getCustomPages();

        return $this->render('defaultPages/nieuwsItem.html.twig', array('nieuwsItem' => $nieuwsItem, 'pages' => $pages));
    }

    /**
     * @Route("/nieuws/delete/{id}", name="deleteNieuwsItem")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteNieuwsItem($id)
    {
        $nieuwsItem = $this->getDoctrine()->getRepository(NieuwsItem::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($nieuwsItem);
        $entityManager->flush();

        return $this->redirectToRoute('nieuws');
    }

    /**
     * @Route("/nieuws/edit/{id}", name="editNieuwsItem")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editNieuwsItem(Request $request, $id)
    {
        $item = $this->getDoctrine()->getRepository(NieuwsItem::class)->find($id);
        $form = $this->getNieuwsItemForm($item, 'save');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('nieuws');
        }
        $pages = $this->getCustomPages();

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Edit',
            'form' => $form->createView(),
            'pages' => $pages
        ));

    }

    private function getNieuwsItemForm($nieuwsItem, $buttonName){
        return $this->createFormBuilder($nieuwsItem)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'label' => 'Titel'))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'label' => 'Inhoud',
                'attr' => array('class' => 'form-control', 'rows' => '10')))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();
    }

    private function getCustomPages(){
        return publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll());
    }
}