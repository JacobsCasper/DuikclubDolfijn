<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Page;
use App\Entity\WebForm;
use App\Entity\WebFormElement;
use App\Entity\WebFormEmailType;
use App\Entity\WebFormIntType;
use App\Entity\WebFormRadioType;
use App\Entity\WebFormStringType;
use App\Services\AddGlobalsService;
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
        $this->getGlobalVars();
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

        return $this->render('AdminSpecificPages/forms.html.twig'
            , array('forms' => $results, 'form' => $form->createView()));
    }

    /**
     * @Route("/form/remove/{id}", name="removeForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeForm($id){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($formElements = $this->getElements($webForm) as $element) {
            $entityManager->remove($element);
        }
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(['parent_id'=> $id]);
        foreach ($answers as $answer) {
            $entityManager->remove($answer);
        }
        $entityManager->flush();
        $entityManager->remove($webForm);
        $entityManager->flush();
        return $this->redirectToRoute('forms');
    }

    /**
     * @Route("/form/edit/{id}", name="editForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editForm($id){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $formElements = $this->getElements($webForm);

        $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder())->getForm();

        return $this->render('AdminSpecificPages/EditWebForm.html.twig'
            , array(
                'formElements' => $formElements,
                'currentForm' => $webForm,
                'form' => $formTemplate->createView(),
                'formTitle' => $webForm->getTitle()
            ));
    }
    /**
     * @Route("/form/open/{id}", name="openForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function openForm($id, Request $request, PaginatorInterface $paginator){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $webForm->setOpen(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($webForm);
        $entityManager->flush();
        return $this->getForms($request, $paginator);
    }
    /**
     * @Route("/form/close/{id}", name="closeForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function closeForm($id, Request $request, PaginatorInterface $paginator){
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        $webForm->setOpen(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($webForm);
        $entityManager->flush();
        return $this->getForms($request, $paginator);
    }

    /**
     * @Route("/form/addString/{id}", name="addStringElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addStringElement($id, Request $request){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);

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

            return $this->redirectToRoute('editForm', ['id' => $id]);
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form/addEmail/{id}", name="addEmailElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addEmailElement($id, Request $request){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);

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

            return $this->redirectToRoute('editForm', ['id' => $id]);
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form/addInt/{id}", name="addIntElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addIntElement($id, Request $request){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);

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

            return $this->redirectToRoute('editForm', ['id' => $id]);
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form/addRadio/{id}", name="addRadioElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addRadioElement($id, Request $request){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);

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

            return $this->redirectToRoute('editForm', ['id' => $id]);
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuwe rij',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/form/removeEl/{id}/{elId}/{type}", name="removeElement")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeElement($id, $elId, $type){
        $this->getGlobalVars();
        $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($id);
        switch ($type){
            case "string":
                $element = $this->getDoctrine()->getRepository(WebFormStringType::class)->find($elId);
                break;
            case "int":
                $element = $this->getDoctrine()->getRepository(WebFormIntType::class)->find($elId);
                break;
            case "email":
                $element = $this->getDoctrine()->getRepository(WebFormEmailType::class)->find($elId);
                break;
            case "radio":
                $element = $this->getDoctrine()->getRepository(WebFormRadioType::class)->find($elId);
                break;
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($element);
        $entityManager->flush();

        return $this->redirectToRoute('editForm', ['id' => $id]);
    }

    /**
     * @Route("/addform", name="addForm")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addForm(Request $request){
        $this->getGlobalVars();

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

        return $this->render('forms/defaultForms.html.twig', array('form' => $form->createView()));
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

        $tempArray = [];
        foreach ($elements as $item){
            array_push($tempArray, $item);
        }
        usort($tempArray, fn($a, $b) => strcmp($a->getPosition(), $b->getPosition()));
        $elements = $tempArray;

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

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
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
