<?php


namespace App\Controller;

use App\Entity\NieuwsItem;
use App\Entity\Page;
use App\Entity\User;
use App\Services\FileService;
use App\Services\publishedPageFilter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
    /**
     * @Route("/page/all", name="getAllPages")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAllPages(Request $request, PaginatorInterface $paginator){
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        $query = array_reverse($pages);

        $results = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $navbarPages = $this->getCustomPages();
        return $this->render("AdminSpecificPages/customPagesOverview.html.twig", array('pages' => $navbarPages, 'allPages' => $results));
    }

    /**
     * @Route("/page/add", name="addPage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addPage(Request $request, SluggerInterface $slugger){
        $fileService = new FileService();
        $page = new Page();

        $form = $this->getPageForm($page, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $page = $form->getData();

            //configure and upload image
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $newFilename = $fileService->uploadFile($picture, $slugger, $this->getParameter('uploads_directory'));
                $page->setPicturePath($newFilename);
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

        $pages = $this->getCustomPages();

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Nieuwe pagina',
            'form' => $form->createView(),
            'pages' => $pages
        ));

    }

    /**
     * @Route("/page/remove/{id}", name="removePage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removePage(Request $request, $id){
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);

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
        $fileService = new FileService();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);
        $form = $this->getPageForm($page, 'edit');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $picture = $form->get('picture')->getData();

            if ($picture) {
                try {
                    if($page->getPicturePath() != null || $page->getPicturePath() != ""){
                        $path = $this->getParameter('uploads_directory') . '/' . $page->getPicturePath();
                        unlink($path);
                    }

                    $newFilename = $fileService->uploadFile($picture, $slugger, $this->getParameter('uploads_directory'));

                } catch (FileException $e) {

                }

                $page->setPicturePath($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("getAllPages");
        }

        $pages = $this->getCustomPages();

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Nieuwe pagina',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/page/{id}", name="getPage")
     */
    public function getPage($id){
        $pages = $this->getCustomPages();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);
        if($page != null && $page->getPublished()){
            return $this->render("defaultPages/customPageTemplate.html.twig", array('pages' => $pages, 'customPageInfo' => $page));
        }
        return $this->render("defaultPages/home.html.twig", array('pages' => $pages));

    }

    private function getPageForm($page, $buttonName, $buttonType='primary'){
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
                'label' => "Upload foto",
                'attr' => array('class' => 'form-control'),
                'required' => false))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getCustomPages(){
        return publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll());
    }

}