<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SessionInterface $session): Response 
    {
        if (!$session->get('utilisateur_id')) { 
            return $this->redirectToRoute('app_login'); 
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request, EntityManagerInterface $em, SessionInterface $session, LoggerInterface $logger): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $logger->info('Tentative de connexion avec ce mail', ['email' => $email]);

            $user = $em->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            if ($user && password_verify($password, $user->getMotDePasse())) {
                $session->set('utilisateur_id', $user->getId());
                $session->set('utilisateur_nom', $user->getNom());

                $logger->info('Connexion réussie : ', ['id' => $user->getId(), 'email' => $email]);

                return $this->redirectToRoute('app_home');
            } else {
                $logger->warning('Échec de connexion : ', ['email' => $email]);

                $this->addFlash('error', 'Email ou mot de passe incorrect.');
                return $this->redirectToRoute('app_login');
            }
        }

        $logger->debug('Affichage de la page de login.');
        return $this->render('security/login.html.twig');
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session, LoggerInterface $logger): Response
    {
        $session->clear();
        $logger->debug('Affichage de la page de login.');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/compte', name: 'app_compte')]
    public function compte(SessionInterface $session, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $utilisateurId = $session->get('utilisateur_id');
        
        if (!$utilisateurId) {
            return $this->redirectToRoute('app_login');
        }

        $utilisateur = $em->getRepository(Utilisateur::class)->find($utilisateurId);

        $logger->debug('Affichage de la page de compte.', ['id' => $utilisateurId]);
        return $this->render('security/compte.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

}
