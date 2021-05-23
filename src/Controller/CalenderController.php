<?php

namespace App\Controller;

use App\Entity\CalenderItem;
use App\Entity\Page;
use App\Entity\User;
use App\Services\AddGlobalsService;
use App\Services\CalenderTypes;
use App\Services\publishedPageFilter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Knp\Component\Pager\PaginatorInterface;


class CalenderController extends AbstractController {
    /**
     * @Route("/kalender", name="kalender")
     */
    public function kalender(Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $calenderItems = $this->getDoctrine()->getRepository(CalenderItem::class)->findAll();
        $query = array_reverse($calenderItems);

        $results = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('defaultPages/kalender.html.twig', array('calenderItems' => $results));
    }

    /**
     * @Route("/kalender/add", name="addCalItem")
     * @IsGranted("ROLE_INST")
     */
    public function addCalItem(Request $request)
    {
        $this->getGlobalVars();
        $item = new CalenderItem();

        $form = $this->getCalenderItemForm($item, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $item = $form->getData();
            $item->setAuthor($user->getUsername());
            $item->setSubmitDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('kalender');
        }

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Nieuw kalender item',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/kalender/edit/{id}", name="editCalItem")
     * @IsGranted("ROLE_INST")
     */
    public function editCalItem($id, Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $item = $this->getDoctrine()->getRepository(CalenderItem::class)->find($id);

        $form = $this->getCalenderItemForm($item, 'save');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $item = $form->getData();
            $item->setAuthor($user->getUsername());
            $item->setSubmitDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->calItem($id, $request, $paginator);
        }

        return $this->render('forms/defaultBigForms.html.twig', array(
            'header' => 'Edit kalender item',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/kalender/remove/{id}", name="removeCalItem")
     * @IsGranted("ROLE_INST")
     */
    public function removeCalItem($id)
    {
        $item = $this->getDoctrine()->getRepository(CalenderItem::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();

        return $this->redirectToRoute('kalender');
    }

    /**
     * @Route("/kalender/{id}", name="calItem")
     */
    public function calItem($id,Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $kalenderItem = $this->getDoctrine()->getRepository(CalenderItem::class)->find($id);
        $users = $paginator->paginate(
            $kalenderItem->getUsers(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('defaultPages/kalenderItem.html.twig', array('kalenderItem' => $kalenderItem, 'users' => $users));
    }

    private function getCalenderItemForm($calenderItem, $buttonName){
        return $this->createFormBuilder($calenderItem)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Titel'))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control', 'rows' => '4'), 'label' => 'Korte uitleg'))

            ->add('details', CKEditorType::class, array(
                'config' =>[
                    'uiColor' => '#e2e2e2',
                    'toolbar' => 'basic',
                    'required' => true
                ],
                'required' => true,
                'label' => 'Details',
                'attr' => array('class' => 'form-control', 'rows' => '10')))
            ->add('startDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => array('class' => 'form-control'),
                'label' =>"Begin datum"
            ))
            ->add('endDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => array('class' => 'form-control'),
                'label' =>"Eind datum"
            ))
            ->add('subscriptionEndDate', DateTimeType::class, array(
                'widget' => 'single_text',
                'attr' => array('class' => 'form-control'),
                'label' =>"Sluiting inschrijvingen"
            ))
            ->add('CalenderType', ChoiceType::class, [
                'attr' => array('class' => 'form-control'),
                'choices'  => [
                    'Duiken' => 0,
                    'Lessen/trainingen' => 1,
                    'Activiteiten' => 2,
                ],
            ])
            ->add('maxSubscriptions', IntegerType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Maximum aantal inschrijvingen'))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

    }

    private function getGlobalVars(){
        AddGlobalsService::addGlobals($this->get('twig'), publishedPageFilter::filter($this->getDoctrine()->getRepository(Page::class)->findAll()));
    }
}