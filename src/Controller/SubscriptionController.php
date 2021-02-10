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
    //remove user (admin)

    /**
     * @Route("/sub/{calId}/{userId}", name="subUser")
     */
    public function subUser($calId, $userId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $cal = $this->getDoctrine()->getRepository(CalenderItem::class)->find($calId);
        $cal->addSubscriber($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cal);
        $entityManager->flush();

        return $this->redirectToRoute('kalender');
    }

    /**
     * @Route("/remove/{calId}/{userId}", name="removeSub")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeSub($calId, $userId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $cal = $this->getDoctrine()->getRepository(CalenderItem::class)->find($calId);
        $cal->removeSubscriber($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cal);
        $entityManager->flush();

        return $this->redirectToRoute('kalender');
    }

}