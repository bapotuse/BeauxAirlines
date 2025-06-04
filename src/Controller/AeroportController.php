<?php

namespace App\Controller;

use App\Entity\Aeroport;
use App\Form\AeroportType;
use App\Repository\AeroportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AeroportController extends AbstractController
{

    #[Route('/aeroport', name: 'app_aeroport')]
    public function aeroport(AeroportRepository $repo, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $recherche = $request->query->get('recherche');
        $pays = $request->query->get('pays');
        $ville = $request->query->get('ville');
        $sortBy = $request->query->get('sortBy', 'id');
        $order = $request->query->get('order', 'ASC');

        $requete = $em->createQueryBuilder()
            ->select('a')
            ->from(Aeroport::class, 'a');

        if ($recherche) {
            $requete->andWhere('a.nom LIKE :recherche OR a.ville LIKE :recherche or a.pays LIKE :recherche')
            ->setParameter('recherche', '%' . $recherche . '%');
        }

        if ($pays) {
            $requete->andWhere('a.pays = :pays')
            ->setParameter('pays', $pays);
        }

        if ($ville) {
            $requete->andWhere('a.ville = :ville')
            ->setParameter('ville', $ville);
        }

        $requete->orderBy('a.' . $sortBy, $order);

        $aeroports = $requete->getQuery()->getResult();

        return $this->render('aeroport/index.html.twig', [
            'aeroports' => $aeroports,
        ]);
    }


    #[Route('/aeroport/add', name: 'app_aeroport_add')]
    public function addAeroport(Request $request, LoggerInterface $logger, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $aeroport = new Aeroport();
        $form = $this->createForm(AeroportType::class, $aeroport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($aeroport);
            $em->flush();

            $logger->info('Aéroport ajouté avec succès.', ['id' => $aeroport->getId()]);
            return $this->redirectToRoute('app_aeroport');
        }

        return $this->render('aeroport/ajout.html.twig', [
            'aeroport' => $aeroport,
            'form' => $form,
        ]);
    }

   

    #[Route('/aeroport/{id}/edit', name: 'app_aeroport_edit')]
    public function editAeroport(Request $request, AeroportRepository $repo, EntityManagerInterface $em, int $id, SessionInterface $session, LoggerInterface $logger): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $aeroport = $repo->find($id);

        if (!$aeroport) {
            throw $this->createNotFoundException('Aéroport non trouvé');
        }

        if ($request->isMethod('POST')) {
            $aeroport->setNom($request->request->get('nom'));
            $aeroport->setVille($request->request->get('ville'));
            $aeroport->setPays($request->request->get('pays'));

            $em->flush();
            $logger->info('Aéroport modifié avec succès.', ['id' => $id]);
            return $this->redirectToRoute('app_aeroport');
        }

        return $this->render('aeroport/edit.html.twig', [
            'aeroport' => $aeroport,
        ]);
    }


    #[Route('/aeroport/{id}/delete', name: 'app_aeroport_delete')]
    public function deleteAeroport(EntityManagerInterface $em, LoggerInterface $logger, int $id, SessionInterface $session): Response
    {

        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $aeroport = $em->getRepository(Aeroport::class)->find($id);

        if (!$aeroport) {
            throw $this->createNotFoundException('Aéroport non trouvé');
        }
    
        $volsDepartNombre = count($aeroport->getVolsDepart());
        $volsArriveeArrivee = count($aeroport->getVolsArrivee());
    
        if ($volsDepartNombre > 0 || $volsArriveeArrivee > 0) {
            $this->addFlash('error', 'Impossible de supprimer cet aéroport : des vols sont encore rattachés à cet aéroport.');
            return $this->redirectToRoute('app_aeroport');
        }

        if (count($aeroport->getHangars()) > 0) {
            $this->addFlash('error', 'Impossible de supprimer cet aéroport : des hangars sont encore rattachés à cet aéroport.');
            return $this->redirectToRoute('app_aeroport');
        }
    
        $em->remove($aeroport);
        $em->flush();
        $logger->info('Aéroport supprimé avec succès.', ['id' => $id]);
        $this->addFlash('success', 'Aéroport supprimé avec succès.');
        return $this->redirectToRoute('app_aeroport');
    }

}