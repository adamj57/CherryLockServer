<?php

namespace App\Controller;

use App\Entity\RegisterCode;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/auth", name="auth_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/dialog", name="dialog")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function dialog()
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("dashboard_main");
        }

        return $this->render("security/dialog.html.twig");
    }

    /**
     * @Route("/login", name="login")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @param AuthenticationUtils $authenticationUtils
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("dashboard_main");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("dashboard_main");
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $registerCode = $entityManager->getRepository(RegisterCode::class)
                ->findOneByCode($form->get("inviteCode")->getData());

            $registerCode->setUser($user);
            $registerCode->setValid(false);

            $user->setRole("ROLE_USER");

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("dashboard_main");
        }
        return $this->render("security/register.html.twig",
                            ['form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {

    }
}
