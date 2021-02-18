<?php


namespace App\Controller;


use App\Entity\Album;
use App\Entity\Page;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AlbumController extends AbstractController
{
    /**
     * @Route("/fotos", name="fotos")
     */
    public function index()
    {
        $this->getGlobalVars();

        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();
        $albums = array_reverse($albums);

        return $this->render('defaultPages/albums.html.twig', array('albums' => $albums));
    }

    /**
     * @Route("/album/add", name="addAlbum")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addAlbum(Request $request, SluggerInterface $slugger){
        $this->getGlobalVars();
        $album = new Album();

        $form = $this->getAlbumForm($album, "create");
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $album = $form->getData();

            $picture = $form->get('picture')->getData();
            if ($picture) {
                $album->setPicture($slugger, $picture, $this->getParameter('uploads_directory'));
            } else{
                $album->setPicturePath("");
            }

            $user = $this->getUser();
            $album->setAuthor($user->getUsername());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('fotos');
        }
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuw album',
            'form' => $form->createView()
        ));
    }


    private function getAlbumForm($album, $buttonName, $buttonType='primary'){
        return $this->createFormBuilder($album)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'label' => 'Titel'))
            ->add('link', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'label' => 'Link'))
            ->add('picture', FileType::class, array(
                'mapped' => false,
                'label' => "Afbeelding",
                'required' => false))
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array('class' => 'form-control'),
                'label' =>"Datum"
            ))
            ->add('public', CheckboxType::class, array(
                'label' => 'Album voor iedereen zichtbaar maken (personen zonder account)',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();
    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
}