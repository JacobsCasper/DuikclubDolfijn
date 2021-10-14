<?php


namespace App\Controller;



use App\Entity\CalenderItem;
use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private CalenderController $calenderController;
    //remove user (admin)
    /**
     * SubscriptionController constructor.
     */
    public function __construct(CalenderController $calenderController)
    {
        $this->calenderController = $calenderController;
    }


    /**
     * @Route("/sub/{calId}/{userId}", name="subUser")
     */
    public function subUser($calId, $userId, Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $cal = $this->getDoctrine()->getRepository(CalenderItem::class)->find($calId);
        $subscription = $cal->addSubscriber($user);

        if($subscription) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subscription);
            $entityManager->flush();
        }

        return $this->calenderController->calItem($calId, $request, $paginator);
    }

    /**
     * @Route("/remove/{calId}/{userId}", name="removeSub")
     * @IsGranted("ROLE_INST")
     */
    public function removeSub($calId, $userId, Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $cal = $this->getDoctrine()->getRepository(CalenderItem::class)->find($calId);
        $subscription = $cal->getUserSubscriptionByUser($user);

        if($subscription) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subscription);
            $entityManager->flush();
        }

        return $this->calenderController->calItem($calId, $request, $paginator);
    }

}