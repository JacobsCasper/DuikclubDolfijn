<?php


namespace App\Controller;


use App\Entity\Page;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileController extends AbstractController
{
    /**
     * @Route("/page/files/{id}", name="getPageFiles")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPageFiles($id, Request $request, PaginatorInterface $paginator, SluggerInterface $slugger){
        $this->getGlobalVars();
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);

        $form = $this->getFileForm('Save');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $name = $form->get('name')->getData();
            $file = $form->get('file')->getData();
            $page->addFile($slugger, $this->getParameter('uploads_directory'), $name, $file);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        $results = $paginator->paginate(
            $page->getFileNames(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("AdminSpecificPages/pageFiles.html.twig",
            array(
                'pageId' => $id,
                'files' => $results,
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/file/remove/{id}/{name}", name="removeFile")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeFile($id, Request $request, $name, PaginatorInterface $paginator, SluggerInterface $slugger){
        $page = $this->getDoctrine()->getRepository(Page::class)->find($id);

        $page->removeFile($name, $this->getParameter('uploads_directory'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('getPageFiles', array(
            'id'=>$id, 'request'=>$request, 'paginator'=>$paginator, 'slugger'=>$slugger
        ));
    }




    private function getFileForm($buttonName, $buttonType='primary'){
        return $this->createFormBuilder()
            ->add('name', TextType::class,
                array('attr' => array('class' => 'form-control'),
                    'mapped' => false,
                    'label' => 'Naam'))
            ->add('file', FileType::class, array(
                'mapped' => false,
                'label' => "Upload file",
                'attr' => array('class' => 'form-control'),
                'required' => true))

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