<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\WebForm;
use App\Entity\WebFormElement;
use App\Entity\WebFormEmailType;
use App\Entity\WebFormIntType;
use App\Entity\WebFormRadioType;
use App\Entity\WebFormStringType;
use App\Services\FormTemplateGenerator;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    private $formTemplateGenerator;

    public function __construct(FormTemplateGenerator $formTemplateGenerator){
        $this->formTemplateGenerator = $formTemplateGenerator;
    }

    /**
     * @Route("/forms", name="forms")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getForms(Request $request, PaginatorInterface $paginator){
        //get forms


        //add form
        $webForm = new WebForm();
        $form = $this->getNewForm($webForm);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && !$this->checkIfFormTitleAlreadyExists($form->get('title')->getData())){
            $webForm = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($webForm);
            $entityManager->flush();

        }
        $forms = $this->getDoctrine()->getRepository(WebForm::class)->findAll();
        $results = $paginator->paginate(
            $forms,
            $request->query->getInt('page', 1),
            10
        );
        $pages = $this->getCustomPages();

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

        $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

        return $this->render('AdminSpecificPages/EditWebForm.html.twig'
            , array(
                'formElements' => $formElements,
                'currentForm' => $webForm,
                'pages' => $pages,
                'form' => $formTemplate->createView(),
                'formTitle' => $webForm->getTitle()
            ));
    }
    /**
     * @Route("/form/open/{id}", name="openForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function openForm($id){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $webForm->setOpen(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($webForm);
        $entityManager->flush();
        return $this->redirectToRoute("editForm", ['id' => $id]);
    }
    /**
     * @Route("/form/close/{id}", name="closeForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function closeForm($id){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $webForm->setOpen(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($webForm);
        $entityManager->flush();
        return $this->redirectToRoute("editForm", ['id' => $id]);
    }

    /**
     * @Route("/form/addString/{id}", name="addStringElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addStringElement($id, Request $request){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();

        $stringElement = new WebFormStringType();
        $form = $this->getNewStringElement($stringElement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $this->checkIfDuplicateNameExists($form->get('label')->getData(), $webForm)){
            $stringElement = $form->getData();


            if($form->get('multiline')->getData() == false){
                $stringElement->setMultiline(0);
            }
            else
            {
                $stringElement->setMultiline(1);
            }
            $stringElement->setParentId($webForm);
            $size = count($this->getElements($webForm));
            $stringElement->setPosition($size);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stringElement);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);
            $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array(
                    'formElements' => $formElements,
                    'currentForm' => $webForm,
                    'pages' => $pages,
                    'form' => $formTemplate->createView(),
                    'formTitle' => $webForm->getTitle()
                ));
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/form/addEmail/{id}", name="addEmailElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addEmailElement($id, Request $request){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();

        $emailElement = new WebFormEmailType();
        $form = $this->getNewEmailElement($emailElement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $this->checkIfDuplicateNameExists($form->get('label')->getData(), $webForm)){
            $emailElement = $form->getData();
            $emailElement->setParentId($webForm);
            $size = count($this->getElements($webForm));
            $emailElement->setPosition($size);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emailElement);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);
            $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array(
                    'formElements' => $formElements,
                    'currentForm' => $webForm,
                    'pages' => $pages,
                    'form' => $formTemplate->createView(),
                    'formTitle' => $webForm->getTitle()
                ));
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/form/addInt/{id}", name="addIntElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addIntElement($id, Request $request){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();

        $intElement = new WebFormIntType();
        $form = $this->getNewIntElement($intElement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $this->checkIfDuplicateNameExists($form->get('label')->getData(), $webForm)){
            $intElement = $form->getData();
            $intElement->setParentId($webForm);
            $size = count($this->getElements($webForm));
            $intElement->setPosition($size);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($intElement);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);
            $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array(
                    'formElements' => $formElements,
                    'currentForm' => $webForm,
                    'pages' => $pages,
                    'form' => $formTemplate->createView(),
                    'formTitle' => $webForm->getTitle()
                ));
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/form/addRadio/{id}", name="addRadioElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addRadioElement($id, Request $request){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $pages = $this->getCustomPages();

        $radioElement = new WebFormRadioType();
        $form = $this->getNewRadioElement($radioElement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $this->checkIfDuplicateNameExists($form->get('label')->getData(), $webForm)){
            $radioElement = $form->getData();

            $radioElement->setParentId($webForm);
            $choicesString = $form->get('choices')->getData();
            $choices = explode('$', $choicesString);
            $radioElement->setChoices($choices);
            $size = count($this->getElements($webForm));
            $radioElement->setPosition($size);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($radioElement);
            $entityManager->flush();

            $formElements = $this->getElements($webForm);
            $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

            return $this->render('AdminSpecificPages/EditWebForm.html.twig'
                , array(
                    'formElements' => $formElements,
                    'currentForm' => $webForm,
                    'pages' => $pages,
                    'form' => $formTemplate->createView(),
                    'formTitle' => $webForm->getTitle()
                ));
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

        if($form->isSubmitted() && $form->isValid() && !$this->checkIfFormTitleAlreadyExists($form->get('title')->getData())){

            $webForm = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($webForm);
            $entityManager->flush();

            return $this->redirectToRoute('editForm', array('id' => $webForm->getId()));
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

    private function getDefaultFormElements($element){
        return $this->createFormBuilder($element)
            ->add('label', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Naam'))
            ->add('price', IntegerType::class,
                array(
                    'required' => false,
                    'attr' => array('class' => 'form-control'), 'label' => 'Prijs'
                ))
            ->add('comment', TextType::class,
                array(
                    'required' => false,
                    'attr' => array('class' => 'form-control'), 'label' => 'Bijvoegsel'
                ))
            ->add('required', CheckboxType::class, array(
                'label' => 'Veld verplichten',
                'required' => false,
                'attr' => array('class' => 'form-control')
            ));
    }

    private function getNewStringElement($stringElement, $buttonType = "primary"){
        return $this->getDefaultFormElements($stringElement)
            ->add('multiline', CheckboxType::class, array(
                'mapped' => false,
                'label' => 'Lange text toestaan',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Element aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();

    }

    private function getNewEmailElement($emailElement, $buttonType = "primary"){
        return $this->getDefaultFormElements($emailElement)
            ->add('save', SubmitType::class, array(
                'label' => 'Element aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getNewRadioElement($radioElement, $buttonType = "primary"){
        return $this->getDefaultFormElements($radioElement)
            ->add('choices', TextType::class,
                array(
                    'mapped' => false,
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Opties gescheiden door een $'
                ))
            ->add('save', SubmitType::class, array(
                'label' => 'Element aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getNewIntElement($intElement, $buttonType = "primary"){
        return $this->getDefaultFormElements($intElement)
            ->add('save', SubmitType::class, array(
                'label' => 'Element aanmaken',
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getElements($webForm){
        $criteria = array('parent_id' => $webForm->getId());
        $elements = [];
        $elements = $this->addWebFormElementsToArray($elements, $this->getDoctrine()->getRepository(WebFormStringType::class)->findBy($criteria));
        $elements = $this->addWebFormElementsToArray($elements, $this->getDoctrine()->getRepository(WebFormRadioType::class)->findBy($criteria));
        $elements = $this->addWebFormElementsToArray($elements, $this->getDoctrine()->getRepository(WebFormEmailType::class)->findBy($criteria));
        $elements = $this->addWebFormElementsToArray($elements, $this->getDoctrine()->getRepository(WebFormIntType::class)->findBy($criteria));
        return $elements;
    }

    private function checkIfDuplicateNameExists($label, $webForm){
        $elements = $this->getElements($webForm);
        foreach ($elements as $element){
            if($element->getLabel() == $label){
                return false;
            }
        }
        return true;
    }

    private function getCustomPages(){
        return publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll());
    }

    private function addWebFormElementsToArray($elements, $arrayToAdd){
        foreach ($arrayToAdd as $item){
            array_push($elements, $item);
        }
        return $elements;
    }

    private function checkIfFormTitleAlreadyExists($title){
        $forms = $this->getDoctrine()->getRepository(WebForm::class)->findAll();
        foreach ($forms as $form){
            if($form->getTitle() == $title){
                return true;
            }
        }
        return false;
    }
}
