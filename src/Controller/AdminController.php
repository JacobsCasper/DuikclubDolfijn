<?php


namespace App\Controller;



use App\Entity\User;
use ContainerSROja2H\getHomeControllerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/user/add", name="addUser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addUser(Request $request)
    {
        $user = new User();
        $form = $this->getUserForm($user, 'create');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $password = $user->getPassword();
            $user->setPassword(
                $this->encoder->encodePassword($user, $password)
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render("defaultPages/home.html.twig");
        }

        return $this->render('forms/defaultForms.html.twig', array(
            'header' => 'Gebruiker aanmmaken',
            'form' => $form->createView()
        ));
    }

    private function getUserForm($user, $buttonName){
        return $this->createFormBuilder($user)
            ->add('username', TextType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'Username'))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
//                'invalid_message' => 'You entered an invalid value, it should include %num% letters', TODO: this throws an error
//                'invalid_message_parameters' => ['%num%' => 6],
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('email', EmailType::class,
                array('attr' => array('class' => 'form-control'), 'label' => 'email'))
            ->add('save', SubmitType::class, array(
                'label' => $buttonName,
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

    }
}