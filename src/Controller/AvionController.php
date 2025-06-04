<?php

namespace App\Controller;

use App\Entity\Hangar;
use App\Entity\Avion;
use App\Repository\AvionRepository;
use App\Form\AvionType;
use App\Entity\Vol;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class AvionController extends AbstractController
{
    #[Route('/avion', name: 'app_avion')]
    public function avion(AvionRepository $avionRepository, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $recherche = $request->query->get('avionRecherche');
        $id = $request->query->get('id');
        $modele = $request->query->get('modele');
        $nbPlaces = $request->query->get('nbPlaces');
        $hangar = $request->query->get('hangar');
        $sortBy = $request->query->get('sortBy', 'id');
        $order = $request->query->get('order', 'ASC');

        $requete = $em->createQueryBuilder()
            ->select('a')
            ->from(Avion::class, 'a')
            ->innerJoin('a.hangar', 'h');


        if ($recherche) {
            $requete->andWhere('a.id LIKE :recherche OR a.modele LIKE :recherche OR a.nbPlaces LIKE :recherche OR h.nom LIKE :recherche')
            ->setParameter('recherche', '%' . $recherche . '%');
        }

        if ($id) {
            $requete->andWhere('a.id = :id')
                ->setParameter('id', $id);
        }

        if ($modele) {
            $requete->andWhere('a.modele = :modele')
                ->setParameter('modele', $modele);
        }

        if ($nbPlaces) {
            $requete->andWhere('a.nbPlaces = :nbPlaces')
                ->setParameter('nbPlaces', $nbPlaces);
        }

        if ($hangar) {
            $requete->andWhere('h.id = :hangar')
                ->setParameter('hangar', $hangar);
        }

        $requete->orderBy('a.' . $sortBy, $order);

        $avions = $requete->getQuery()->getResult();

        return $this->render('avion/index.html.twig', [
            'avions' => $avions,
        ]);
    }

    #[Route('/avion/add', name: 'app_avion_add')]
    public function addAvion(Request $request, LoggerInterface $logger, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $avion = new Avion();
        $form = $this->createForm(AvionType::class, $avion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avion);
            $em->flush();

            $logger->info('Avion ajouté avec succès.', ['id' => $avion->getId()]);
            return $this->redirectToRoute('app_avion');
        }


        return $this->render('avion/ajout.html.twig', [
            'avion'=> $avion,
            'form' => $form,
        ]);
    }


    #[Route('/avion/{id}/edit', name: 'app_avion_edit')]
    public function editAvion(Request $request, LoggerInterface $logger, AvionRepository $repo, EntityManagerInterface $em, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $avion = $repo->find($id);

        if (!$avion) {
            throw $this->createNotFoundException('Avion non trouvé');
        }

        if ($request->isMethod('POST')) {
            $avion->setModele($request->request->get('modele'));
            $avion->setNbPlaces($request->request->get('nbPlaces'));
            $hangarId = $request->request->get('hangar');
            $hangar = $em->getRepository(Hangar::class)->find($hangarId);
            $avion->setHangar($hangar);

            $em->flush();
            $logger->info('Avion modifié avec succès.', ['id' => $id]);
            return $this->redirectToRoute('app_avion');
        }

        $hangars = $em->getRepository(Hangar::class)->findAll();

        return $this->render('avion/edit.html.twig', [
            'avion' => $avion,
            'hangars' => $hangars,
        ]);
    }

    #[Route('/avion/{id}/delete', name: 'app_avion_delete')]
    public function deleteAvion(EntityManagerInterface $em, LoggerInterface $logger, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $avion = $em->getRepository(Avion::class)->find($id);

        if (!$avion) {
            throw $this->createNotFoundException('Avion non trouvé');
        }

        $volsAssocies = $em->getRepository(Vol::class)->findBy(['avion' => $avion]);
        
        if (count($volsAssocies) > 0) {
            $this->addFlash('error', 'Impossible de supprimer cet avion : des vols lui sont encore associés.');
            return $this->redirectToRoute('app_avion');
        }

        $em->remove($avion);
        $em->flush();
        $logger->info('Avion supprimé avec succès.', ['id' => $id]);
        $this->addFlash('success', 'Avion supprimé avec succès.');
        return $this->redirectToRoute('app_avion');
    }
}
