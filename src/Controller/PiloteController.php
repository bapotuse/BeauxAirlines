<?php

namespace App\Controller;

use App\Entity\Pilote;
use App\Form\PiloteType;
use App\Repository\PiloteRepository;
use App\Entity\Vol;
use App\Entity\Realiser;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PiloteController extends AbstractController
{
    #[Route('/pilote', name: 'app_pilote')]
    public function pilote(PiloteRepository $piloteRepository, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $recherche = $request->query->get('piloteSearch');
        $matricule = $request->query->get('matricule');
        $nom = $request->query->get('nom');
        $prenom = $request->query->get('prenom');
        $sortBy = $request->query->get('sortBy', 'matricule');
        $order = $request->query->get('order', 'ASC');

        $requete = $em->createQueryBuilder()
            ->select('p')
            ->from(Pilote::class, 'p');

        if ($recherche) {
            $requete->andWhere('p.nom LIKE :recherche OR p.prenom LIKE :recherche OR p.matricule LIKE :recherche')
                ->setParameter('recherche', '%' . $recherche . '%');
        }

        if ($matricule) {
            $requete->andWhere('p.matricule = :matricule')
                ->setParameter('matricule', $matricule);
        }

        if ($nom) {
            $requete->andWhere('p.nom = :nom')
                ->setParameter('nom', $nom);
        }

        if ($prenom) {
            $requete->andWhere('p.prenom = :prenom')
                ->setParameter('prenom', $prenom);
        }

        $requete->orderBy('p.' . $sortBy, $order);

        $pilotes = $requete->getQuery()->getResult();


        return $this->render('pilote/index.html.twig', [
            'pilotes' => $pilotes,
        ]);
    }


    #[Route('/pilote/add', name: 'app_pilote_add', methods: ['GET', 'POST'])]
    public function addPilote(PiloteRepository $repo, LoggerInterface $logger, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {

        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $pilote = new Pilote();
        $form = $this->createForm(PiloteType::class, $pilote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $piloteExistant = $repo->find($pilote->getMatricule());
            if ($piloteExistant) {
                $this->addFlash('error', 'Un pilote avec ce matricule existe déjà.');
                return $this->redirectToRoute('app_pilote_add');
            }

            $em->persist($pilote);
            $em->flush();

            $logger->info('Pilote ajouté avec succès.', ['id' => $pilote->getMatricule()]);
            return $this->redirectToRoute('app_pilote');
        }


        return $this->render('pilote/ajout.html.twig', [
            'pilote' => $pilote,
            'form' => $form,
        ]);
    }

    #[Route('/pilote/{id}/edit', name: 'app_pilote_edit')]
    public function editPilote(Request $request, LoggerInterface $logger, PiloteRepository $repo, EntityManagerInterface $em, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $pilote = $repo->find($id);

        if (!$pilote) {
            throw $this->createNotFoundException('Pilote non trouvé');
        }

        if ($request->isMethod('POST')) {
            $pilote->setMatricule($request->request->get('matricule'));
            $pilote->setNom($request->request->get('nom'));
            $pilote->setPrenom($request->request->get('prenom'));

            $em->flush();
            $logger->info('Pilote modifié avec succès.', ['matricule' => $id]);
            return $this->redirectToRoute('app_pilote');
        }

        return $this->render('pilote/edit.html.twig', [
            'pilote' => $pilote,
        ]);
    }

    #[Route('/pilote/{id}/delete', name: 'app_pilote_delete')]
    public function deletePilote(EntityManagerInterface $em, LoggerInterface $logger, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $pilote = $em->getRepository(Pilote::class)->find($id);
    
        if (!$pilote) {
            throw $this->createNotFoundException('Pilote non trouvé');
        }

        $volsAssocies = $em->getRepository(Vol::class)->findBy(['pilote' => $pilote]);
        if (count($volsAssocies) > 0) {
            $this->addFlash('error', 'Impossible de supprimer ce pilote : des vols lui sont encore associés.');
            return $this->redirectToRoute('app_pilote');
        }
    
        $em->remove($pilote);
        $em->flush();
        $logger->info('Pilote supprimé avec succès.', ['matricule' => $id]);
        $this->addFlash('success', 'Pilote supprimé avec succès.');
        return $this->redirectToRoute('app_pilote');
    }
}