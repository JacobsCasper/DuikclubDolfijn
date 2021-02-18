<?php


namespace App\Controller;

use App\Entity\Answer;
use App\Entity\NieuwsItem;
use App\Entity\Page;
use App\Entity\User;
use App\Entity\WebForm;
use App\Entity\WebFormEmailType;
use App\Entity\WebFormIntType;
use App\Entity\WebFormRadioType;
use App\Entity\WebFormStringType;
use App\Services\AddGlobalsService;
use App\Services\FileService;
use App\Services\FormTemplateGenerator;
use App\Services\publishedPageFilter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class CustomPageController extends AbstractController
{

    private $formTemplateGenerator;

    public function __construct(FormTemplateGenerator $formTemplateGenerator){
        $this->formTemplateGenerator = $formTemplateGenerator;
    }

    /**
     * @Route("/page/all", name="getAllPages")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAllPages(Request $request, PaginatorInterface $paginator){
        $this->getGlobalVars();
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        $query = array_reverse($pages);

        $results = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("AdminSpecificPages/customPagesOverview.html.twig", array('allPages' => $results));
    }

    /**
     * @Route("/page/add", name="addPage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addPage(Request $request, SluggerInterface $slugger){
        $this->getGlobalVars();
        $page = new Page();

        $form = $this->getPageForm($page, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $page = $form->getData();

            //configure and upload image
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $page->setPicture($slugger, $picture, $this->getParameter('uploads_directory'));
            } else{
                $page->setPicturePath("");
            }

            $user = $this->getUser();
            $page->setAuthor($user->getUsername());
            $page->setSubmitDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('getAllPages');
        }

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Nieuwe pagina',
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/page/remove/{id}", name="removePage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removePage(Request $request, $id){
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);

        $page->destruct($this->getParameter('uploads_directory'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($page);
        $entityManager->flush();

        return $this->redirectToRoute("getAllPages");
    }

    /**
     * @Route("/page/edit/{id}", name="editPage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editPage(Request $request, SluggerInterface $slugger, $id){
        $this->getGlobalVars();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);
        $form = $this->getPageForm($page, 'edit');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $picture = $form->get('picture')->getData();
            if ($picture) {
                try {
                    if($page->getPicturePath() != null || $page->getPicturePath() != ""){
                        $page->removePicture($this->getParameter('uploads_directory'));
                    }
                    $page->setPicture($slugger, $picture, $this->getParameter('uploads_directory'));
                } catch (FileException $e) {

                }

            }
            $page->setSubmitDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("getAllPages");
        }

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Nieuwe pagina',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/page/{id}", name="getPage")
     */
    public function getPage($id, Request $request){
        $this->getGlobalVars();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);
        $webForm = null;
        if($page->getFormId() != -1 || $page->getFormId() != null){
            $webForm = $this->getDoctrine()->getRepository(WebForm::class)->find($page->getFormId());
        }
        if($page != null && $page->getPublished()){
            if($webForm == null){
                return $this->render("defaultPages/customPageTemplate.html.twig"
                    , array(
                        'customPageInfo' => $page,
                    ));
            }
            if($webForm->getOpen()){
                $formElements = $this->getElements($webForm);
                $formTemplate = $this->formTemplateGenerator->getForm($formElements, $this->createFormBuilder(), "Submit")->getForm();
                $formTemplate->handleRequest($request);

                if($formTemplate->isSubmitted() && $formTemplate->isValid()){
                    $data = $formTemplate->getData();

                    $answer = new Answer();
                    $answer->setAnswers($data);
                    $answer->setParentId($webForm);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($answer);
                    $entityManager->flush();

                    return $this->render("defaultPages/customPageTemplate.html.twig"
                        , array(
                            'customPageInfo' => $page,
                            'form' => $formTemplate->createView(),
                            'formTitle' => $webForm->getTitle()
                        ));
                }

                return $this->render("defaultPages/customPageTemplate.html.twig"
                    , array(
                        'customPageInfo' => $page,
                        'form' => $formTemplate->createView(),
                        'formTitle' => $webForm->getTitle()
                    ));
            }
            return $this->render("defaultPages/customPageTemplate.html.twig", array('customPageInfo' => $page));
        }
        return $this->render("defaultPages/home.html.twig");

    }

    private function getPageForm($page, $buttonName, $buttonType='primary'){
        $webForms = $this->getDoctrine()->getRepository(WebForm::class)->findAll();
        $choices = $this->mapChoices($webForms);
        return $this->createFormBuilder($page)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'label' => 'Titel'))
            ->add('navigationText', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'label' => 'Navigatiebalk text',
                    'required' => false))
            ->add('body', CKEditorType::class, array(
                'config' =>[
                    'uiColor' => '#e2e2e2',
                    'toolbar' => 'basic',
                    'required' => true
                ],
                'required' => true,
                'label' => 'Inhoud',
                'attr' => array('class' => 'form-control', 'rows' => '10')))
            ->add('published', CheckboxType::class, array(
                'label' => 'Publiceren',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('homepageEnabled', CheckboxType::class, array(
                'label' => 'Tonen op homepagina (als nieuws item)',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('navigationEnabled', CheckboxType::class, array(
                'label' => 'Tonen in de navigatiebalk',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('picture', FileType::class, array(
                'mapped' => false,
                'label' => "Afbeelding",
                'attr' => array('class' => 'form-control'),
                'required' => false))
            ->add('formId', ChoiceType::class, [
                'attr' => array('class' => 'form-control'),
                'choices'  => $choices,
                'label' => "Link webform"
            ])
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }
    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }

    private function mapChoices($webForms){
        $arr1 = [];
        $arr2 = [];

        array_push($arr1, -1);
        array_push($arr2, null);
        foreach ($webForms as $form){
            array_push($arr1, $form->getId());
            array_push($arr2, $form->getTitle());
        }


        return array_combine($arr2, $arr1);
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

    private function addWebFormElementsToArray($elements, $arrayToAdd){
        foreach ($arrayToAdd as $item){
            array_push($elements, $item);
        }
        return $elements;
    }
}