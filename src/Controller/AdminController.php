<?php


namespace App\Controller;



use App\Entity\Page;
use App\Entity\User;
use App\Services\AddGlobalsService;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/users", name="getUsers")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getUsers(Request $request, PaginatorInterface $paginator){
        $this->getGlobalVars();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $results = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('AdminSpecificPages/users.html.twig', array('users' => $results));
    }

    /**
     * @Route("/user/add", name="addUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addUser(Request $request, PaginatorInterface $paginator, SluggerInterface $slugger)
    {
        $this->getGlobalVars();
        $user = new User();
        $form = $this->getUserForm($user, 'create');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $admin = $form->get('UserRole')->getData();
            $user = $form->getData();
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $user->setPicture($slugger, $picture, $this->getParameter('profilePictures_directory'));
            } else{
                $user->setPicturePath("");
            }

            $password = $user->getPassword();
            $user->setPassword(
                $this->encoder->encodePassword($user, $password)
            );
            if($admin == 2){
                $user->makeAdmin();
            } else if($admin == 1) {
                $user->makeInstructor();
            } else {
                $user->makeUser();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('getUsers', ['request'=>$request, 'paginator'=>$paginator]);
        }
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Gebruiker aanmmaken',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/edit/{id}", name="editUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser($id, Request $request, PaginatorInterface $paginator, SluggerInterface $slugger)
    {
        $this->getGlobalVars();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->getEditUserForm($user, 'save');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $admin = $form->get('UserRole')->getData();
            $user = $form->getData();

            $picture = $form->get('picture')->getData();
            if ($picture) {
                try {
                    if($user->getProfilePicturePath() != null || $user->getProfilePicturePath() != ""){
                        $user->removePicture($this->getParameter('profilePictures_directory'));
                    }
                    $user->setPicture($slugger, $picture, $this->getParameter('profilePictures_directory'));
                } catch (FileException $e) {

                }

            }

            if($admin == 2){
                $user->makeAdmin();
            } else if($admin == 1) {
                $user->makeInstructor();
            } else {
                $user->makeUser();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('getUsers', ['request'=>$request, 'paginator'=>$paginator]);
        }
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Gebruiker aanpassen',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/editpw/{id}", name="editUserPw")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUserPassword($id, Request $request, PaginatorInterface $paginator)
    {
        $this->getGlobalVars();
        $form = $this->getPasswordForm('save');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);

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

    /**
     * @Route("/user/remove/{id}", name="removeUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("getUsers");
    }


    private function getUserForm($user, $buttonName, $buttonType = "primary"){
        return $this->createFormBuilder($user)
            ->add('username', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Username'))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Beide paswoorden moeten hetzelfde zijn.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'email'))
            ->add('phoneNumber', TelType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'telefoon nummer'))
            ->add('UserRole', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Welke rol krijgt deze user',
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'choices'  => [
                    'User' => 0,
                    'Instructeur' => 1,
                    'Administrator' => 2,
                ],
                'data' => 0
            ])
            ->add('isBestuursLid', CheckboxType::class, array(
                'label' => 'Bestuurslid',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'omschrijving', 'required' => true))
            ->add('picture', FileType::class, array(
                'mapped' => false,
                'label' => "Afbeelding",
                'attr' => array('class' => 'form-control'),
                'required' => false))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();

    }

    private function getEditUserForm($user, $buttonName, $buttonType = "primary"){
        return $this->createFormBuilder($user)
            ->add('username', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Username'))
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'email'))
            ->add('phoneNumber', TelType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'telefoon nummer'))
            ->add('UserRole', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Welke rol krijgt deze user',
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'choices'  => [
                    'User' => 0,
                    'Instructeur' => 1,
                    'Administrator' => 2,
                ],
                'data' => count($user->getRoles()) - 1
            ])
            ->add('isBestuursLid', CheckboxType::class, array(
                'label' => 'Bestuurslid',
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'omschrijving', 'required' => true))
            ->add('picture', FileType::class, array(
                'mapped' => false,
                'label' => "Afbeelding",
                'attr' => array('class' => 'form-control'),
                'required' => false))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-' . $buttonType . ' mt-3')
            ))
            ->getForm();

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