<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Submission;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $submissions = $this->getDoctrine()->getRepository(Submission::class)->findBy(['checked' => false]);

        return $this->render('defaultPages/home.html.twig', array('homePageEnabledPages' => $homePageEnabled, 'submissions' => $submissions));
    }

    /**
     * @Route("/leerduiken", name="leerDuiken")
     */
    public function leerDuiken(Request $request)
    {
        $this->getGlobalVars();

        $submission = new Submission();
        $form = $this->getNewUserForm($submission, "submit");
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $submission = $form->getData();
            $submission->setSubmitDate(new \DateTime());
            $submission->setChecked(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($submission);
            $entityManager->flush();

            return $this->render('defaultPages/leerDuiken.html.twig', array(
                'submitted' => true,
                'header' => 'Schrijf je nu in',
                'form' => $form->createView()
            ));
        }

        return $this->render('defaultPages/leerDuiken.html.twig', array(
            'submitted' => false,
            'header' => 'Schrijf je nu in',
            'form' => $form->createView()
        ));
    }

    private function getNewUserForm($submission, $buttonName){
        return $this->createFormBuilder($submission)
            ->add('username', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Volledige naam'))
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'email'))
            ->add('phoneNumber', TelType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'telefoonnummer'))
            ->add('shoeSize', NumberType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Schoenmaat'))
            ->add('experience', TextareaType::class, array(
                'required' => true,
                'label' => 'Ervaring',
                'attr' => array('class' => 'form-control', 'rows' => '10')))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();
    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
    private function getHomePageEnabledPages(){
        return publishedPageFilter::filterHomePageEnabled($this->getDoctrine()->getRepository(Page::class)->findAll());
    }
}