<?php

namespace App\Controller;

use App\Entity\CalenderItem;
use App\Entity\Page;
use App\Services\CalenderTypes;
use App\Services\publishedPageFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $calenderItems = $this->getDoctrine()->getRepository(CalenderItem::class)->findAll();
        $query = array_reverse($calenderItems);

        $results = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        $pages = $this->getCustomPages();
        return $this->render('defaultPages/kalender.html.twig', array('calenderItems' => $results, 'pages' => $pages));
    }
    /**
     * @Route("/kalender/add", name="addCalItem")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addCalItem(Request $request)
    {
        $item = new CalenderItem();

        $form = $this->getCalenderItemForm($item, 'create');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $item = $form->getData();
            $item->setAuthor($user->getUsername());
            $item->setStartDate(new \DateTime());
            $item->setEndDate(new \DateTime());
            $item->setSubmitDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('kalender');
        }

        $pages = $this->getCustomPages();
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Nieuw kalender item',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/kalender/remove/{id}", name="removeCalItem")
     * @IsGranted("ROLE_ADMIN")
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
    public function calItem($id)
    {
        $kalenderItem = $this->getDoctrine()->getRepository(CalenderItem::class)->find($id);
        $pages = $this->getCustomPages();
        return $this->render('defaultPages/kalenderItem.html.twig', array('kalenderItem' => $kalenderItem, 'pages' => $pages));
    }

    private function getCalenderItemForm($calenderItem, $buttonName){
        return $this->createFormBuilder($calenderItem)
            ->add('title', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Titel'))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control', 'rows' => '4'), 'label' => 'Korte uitleg'))
            ->add('details', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control', 'rows' => '10')))
 //           ->add('startDate', DateTimeType::class) //TODO: this throws an error
  //          ->add('endDate', DateTimeType::class)
            ->add('CalenderType', ChoiceType::class, [
                'attr' => array('class' => 'form-control'),
                'choices'  => [
                    'Duiken' => 0,
                    'Lessen/trainingen' => 1,
                    'Activiteiten' => 2,
                ],
            ])
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