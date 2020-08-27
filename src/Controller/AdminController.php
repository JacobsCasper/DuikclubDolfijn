<?php


namespace App\Controller;



use App\Entity\Page;
use App\Entity\User;
use App\Services\publishedPageFilter;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $results = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            20
        );
        $pages = $this->getCustomPages();
        return $this->render('AdminSpecificPages/users.html.twig', array('users' => $results, 'pages' => $pages));
    }

    /**
     * @Route("/user/add", name="addUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $form = $this->getUserForm($user, 'create');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $admin = $form->get('Administrator')->getData();

            $user = $form->getData();
            $password = $user->getPassword();
            $user->setPassword(
                $this->encoder->encodePassword($user, $password)
            );
            if($admin){
                $user->makeAdmin();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render("defaultPages/home.html.twig");
        }
        $pages = $this->getCustomPages();
        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Gebruiker aanmmaken',
            'form' => $form->createView(),
            'pages' => $pages
        ));
    }

    /**
     * @Route("/user/remove/{id}", name="removeUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeUser(Request $request, $id)
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
                'invalid_message' => 'Bijde paswoorden moeten hetzelfde zijn.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'email'))
            ->add('Administrator', CheckboxType::class, array(
                'mapped' => false,
                'label' => 'Maak van deze user een admin',
                'required' => false,
                'attr' => array('class' => 'form-control')))
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