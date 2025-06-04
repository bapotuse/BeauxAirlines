<?php

namespace App\Controller;

use App\Entity\Hangar;
use App\Entity\Avion;
use App\Repository\HangarRepository;
use App\Repository\AvionRepository;
use App\Form\HangarType;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class HangarController extends AbstractController
{
    #[Route('/hangar', name: 'app_hangar')]
    public function hangar(EntityManagerInterface $entityManager, AvionRepository $avionRepo, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $hangars = $entityManager->getRepository(Hangar::class)->findAll();
        $hangarCapacite = [];

        foreach ($hangars as $hangar) {
            $nbAvions = $avionRepo->count(['hangar' => $hangar]);
            $hangarCapacite[$hangar->getId()] = $nbAvions;
        }

        return $this->render('hangar/index.html.twig', [
            'hangars' => $hangars,
            'hangarCapacite' => $hangarCapacite,
        ]);
    }


    #[Route('/hangar/add', name: 'app_hangar_add')]
    public function addHangar(Request $request, EntityManagerInterface $em, SessionInterface $session, LoggerInterface $logger): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $hangar = new Hangar();
        $form = $this->createForm(HangarType::class, $hangar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($hangar);
            $em->flush();
            
            $logger->info('Hangar ajouté avec succès.', ['id' => $hangar->getId()]);
            return $this->redirectToRoute('app_hangar');
        }


        return $this->render('hangar/ajout.html.twig', [
            'hangar'=> $hangar,
            'form' => $form,
        ]);
    }

    #[Route('/hangar/{id}/edit', name: 'app_hangar_edit')]
    public function editHangar(Request $request, LoggerInterface $logger, HangarRepository $repo, EntityManagerInterface $em, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $hangar = $repo->find($id);

        if (!$hangar) {
            throw $this->createNotFoundException('Hangar non trouvé');
        }

        if ($request->isMethod('POST')) {
            $hangar->setNom($request->request->get('nom'));
            $hangar->setCapacite($request->request->get('capacite'));

            $em->flush();
            $logger->info('Hangar modifié avec succès.', ['id' => $id]);
            return $this->redirectToRoute('app_hangar');
        }

        return $this->render('hangar/edit.html.twig', [
            'hangar' => $hangar,
        ]);
    }

    #[Route('/hangar/{id}/delete', name: 'app_hangar_delete')]
    public function deleteHangar(EntityManagerInterface $em, int $id, SessionInterface $session, LoggerInterface $logger): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $hangar = $em->getRepository(Hangar::class)->find($id);
    
        if (!$hangar) {
            throw $this->createNotFoundException('Pilote non trouvé');
        }

        $avions = $em->getRepository(Avion::class)->findBy(['hangar' => $hangar]);
        if (count($avions) > 0) {
            $this->addFlash('error', 'Impossible de supprimer le hangar car il contient des avions.');
            return $this->redirectToRoute('app_hangar');
        }

        $em->remove($hangar);
        $em->flush();
        $logger->info('Hangar supprimé avec succès.', ['id' => $id]);
        $this->addFlash('success', 'Hangar supprimé avec succès.');
        return $this->redirectToRoute('app_hangar');
    }

    #[Route('/hangar/{id}/avions', name: 'app_hangar_avions')]
    public function avionsParHangar(int $id, HangarRepository $hangarRepo, AvionRepository $avionRepo, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $hangar = $hangarRepo->find($id);

        if (!$hangar) {
            throw $this->createNotFoundException('Hangar non trouvé');
        }

        $avions = $avionRepo->findBy(['hangar' => $hangar]);

        return $this->render('hangar/avions.html.twig', [
            'hangar' => $hangar,
            'avions' => $avions,
        ]);
    }

}