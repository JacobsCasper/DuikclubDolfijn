<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\WebForm;
use App\Entity\WebFormElement;
use App\Entity\WebFormStringType;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{

    /**
     * @Route("/forms", name="forms")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getForms(Request $request, PaginatorInterface $paginator){
        //get forms
        $forms = $this->getDoctrine()->getRepository(WebForm::class)->findAll();
        $results = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            10
        );
        $pages = $this->getCustomPages();

        //add form
        $webForm = new WebForm();
        $form = $this->getNewForm($webForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $webForm = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($webForm);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
               , array('formElements' => $formElements, 'currentForm' => $webForm, 'pages' => $pages));
        }

        return $this->render('AdminSpecificPages/forms.html.twig'
            , array('forms' => $results, 'pages' => $pages, 'form' => $form->createView()));
    }

    /**
     * @Route("/form/edit/{id}", name="editForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editForm($id){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();
        $formElements = $this->getElements($webForm);

        return $this->render('AdminSpecificPages/EditWebForm.html.twig'
            , array('formElements' => $formElements, 'currentForm' => $webForm, 'pages' => $pages));
    }

    /**
     * @Route("/form/addString/{id}", name="addStringElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addStringElement($id, Request $request){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();

        $stringElement = new WebFormStringType();
        $form = $this->getNewStringEllement($stringElement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stringElement = $form->getData();

            if($form->get('multiline') == false){
                $stringElement->setMultiline(0);
            }
            else
            {
                $stringElement->setMultiline(1);
            }
            $stringElement->setParentId($webForm);

            $webForm->addFormElement($stringElement);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stringElement); //TODO: hier is nog een exception volgende keer hier verder gaan.
            $entityManager->persist($webForm);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array('formElements' => $formElements, 'currentForm' => $webForm, 'pages' => $pages));
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/addform", name="addForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addForm(Request $request){
        $pages = $this->getCustomPages();

        //add form
        $webForm = new WebForm();
        $form = $this->getNewForm($webForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $webForm = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($webForm);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array('formElements' => $formElements, 'currentForm' => $webForm, 'pages' => $pages));
        }

        return $this->render('forms/defaultForms.html.twig', array('pages' => $pages, 'form' => $form->createView()));
    }


    private function getNewForm($webForm, $buttonType = "primary"){
        return $this->createFormBuilder($webForm)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Form titel'))
            ->add('open', CheckboxType::class, array(
                'label' => 'Open',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Form aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();

    }

    private function getNewStringEllement($stringEllement, $buttonType = "primary"){
        return $this->createFormBuilder($stringEllement)
            ->add('label', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Naam'))
            ->add('frontLabel', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Text vooraan'))
            ->add('endLabel', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Text achteraan'))
            ->add('multiline', CheckboxType::class, array(
                'mapped' => false,
                'label' => 'Lange text toestaan',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('required', CheckboxType::class, array(
                'label' => 'Verplicht',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Element aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getElements($webForm){
        $criteria = array('parent_id' => $webForm->getId());
        $elements = $this->getDoctrine()->getRepository(WebFormElement::class)->findBy($criteria);
        return $elements;
    }

    private function getCustomPages(){
        return publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll());
    }
}
