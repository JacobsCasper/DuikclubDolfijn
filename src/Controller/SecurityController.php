<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\User;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, Security $security)
    {
        $this->security = $security;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils)
    {
        $this->getGlobalVars();

        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
    /**
     * @Route("/changePassword", name="changePassword")
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $form = $this->getPasswordForm('save');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $u = $this->security->getUser();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $u->getUsername()]);

            $password = $form->get('password')->getData();
            $user->setPassword(
                $this->encoder->encodePassword($user, $password)
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('home', ['request'=>$request, 'paginator'=>$paginator]);
        }
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Wachtwoord aanpassen',
            'form' => $form->createView()
        ));
    }

    private function getPasswordForm($buttonName, $buttonType = "primary"){
        return $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Beide paswoorden moeten hetzelfde zijn.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
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
}
