<?php

namespace App\Controller;

use App\Repository\AvionRepository;
use App\Form\VolType;
use App\Repository\PiloteRepository;
use App\Repository\VolRepository;
use App\Entity\Vol;
use App\Repository\AeroportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class VolController extends AbstractController
{
   #[Route('/vol', name: 'app_vol')]
    public function vols(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $recherche = $request->query->get('volSearch');
        $sortBy = $request->query->get('sortBy', 'v.id');
        $order = $request->query->get('order', 'ASC');

        $requete = $em->createQueryBuilder()
            ->select('v')
            ->from(Vol::class, 'v')
            ->innerJoin('v.avion', 'a')
            ->innerJoin('v.pilote', 'p')
            ->innerJoin('v.aeroportDepart', 'ad')
            ->innerJoin('v.aeroportArrivee', 'aa');

            
        if ($recherche) {
            $requete->andWhere('v.id LIKE :recherche OR ad.nom LIKE :recherche OR aa.nom LIKE :recherche OR p.matricule LIKE :recherche')
                ->setParameter('recherche', '%' . $recherche . '%');
        }

        $requete->orderBy($sortBy, $order);
        $vols = $requete->getQuery()->getResult();

        return $this->render('vol/index.html.twig', [
            'vols' => $vols,
        ]);
    }

    #[Route('/vol/add', name: 'app_vol_add')]
    public function addVol(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $vol = new Vol();
        $form = $this->createForm(VolType::class, $vol);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($vol->getDateHeureDepart() >= $vol->getDateHeureArrivee()) {
                $this->addFlash('error', 'La date de départ doit être antérieure à la date d\'arrivée.');
                return $this->redirectToRoute('app_vol_add');
            }
            if ($vol->getAeroportDepart() == $vol->getAeroportArrivee()) {
                $this->addFlash('error', 'L\'aéroport de départ et d\'arrivée ne peuvent pas être identiques.');
                return $this->redirectToRoute('app_vol_add');
            }

            $entityManager->persist($vol);
            $entityManager->flush();
            $logger->info('Vol ajouté avec succès.', ['id' => $vol->getId()]);
            return $this->redirectToRoute('app_vol');
        }


        return $this->render('vol/ajout.html.twig', [
            'vol' => $vol,
            'form' => $form,
        ]);
    }

    #[Route('/vol/{id}/edit', name: 'app_vol_edit')]
    public function editVol(Request $request, LoggerInterface $logger, VolRepository $volRepo, AvionRepository $avionRepo, AeroportRepository $aeroportRepo, PiloteRepository $piloteRepo, EntityManagerInterface $em, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $vol = $volRepo->find($id);

        if (!$vol) {
            throw $this->createNotFoundException('Vol non trouvé');
        }

        if ($request->isMethod('POST')) {
            $aeroportDepart = $aeroportRepo->find($request->request->get('aeroportDepart'));
            $aeroportArrivee = $aeroportRepo->find($request->request->get('aeroportArrivee'));
            $avion = $avionRepo->find($request->request->get('avion'));
            $pilote = $piloteRepo->find($request->request->get('pilote'));

            if ($aeroportDepart == $aeroportArrivee) {
                $this->addFlash('error', 'L\'aéroport de départ et d\'arrivée ne peuvent pas être identiques.');
                return $this->redirectToRoute('app_vol_add');
            }

            $vol->setAeroportDepart($aeroportDepart);
            $vol->setAeroportArrivee($aeroportArrivee);
            $vol->setDateHeureDepart(new \DateTime($request->request->get('dateHeureDepart')));
            $vol->setDateHeureArrivee(new \DateTime($request->request->get('dateHeureArrivee')));
            $vol->setAvion($avion);
            $vol->setPilote($pilote);
            $vol->setDateHeureDepartEffective(new \DateTime($request->request->get('dateHeureDepartEffective')));
            $vol->setDateHeureArriveeEffective(new \DateTime($request->request->get('dateHeureDepartArrivee')));

            $em->flush();
            $logger->info('Vol modifié avec succès.', ['id' => $id]);
            return $this->redirectToRoute('app_vol');
        }

        return $this->render('vol/edit.html.twig', [
            'vol' => $vol,
            'avions' => $avionRepo->findAll(),
            'aeroports' => $aeroportRepo->findAll(),
            'pilotes' => $piloteRepo->findAll(),
        ]);
    }


    #[Route('/vol/{id}/delete', name: 'app_vol_delete')]
    public function deleteVol(EntityManagerInterface $em, LoggerInterface $logger, int $id, SessionInterface $session): Response
    {
        if (!$session->has('utilisateur_id')) {
            return $this->redirectToRoute('app_login');
        }

        $vol = $em->getRepository(Vol::class)->find($id);
    
        if (!$vol) {
            throw $this->createNotFoundException('Vol non trouvé');
        }
    
        $em->remove($vol);
        $em->flush();
        $logger->info('Vol supprimé avec succès.', ['id' => $id]);
        $this->addFlash('success', 'Vol supprimé avec succès.');
        return $this->redirectToRoute('app_vol');
    }

    #[Route('/athAller/{id}', name: 'app_ath')]
    public function ath(int $id, Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $sortBy = 'v.dateHeureDepart';
        $order = 'DESC';

        $requete = $em->createQueryBuilder()
            ->select('v')
            ->from(Vol::class, 'v')
            ->innerJoin('v.avion', 'a')
            ->innerJoin('v.pilote', 'p')
            ->innerJoin('v.aeroportDepart', 'ad')
            ->innerJoin('v.aeroportArrivee', 'aa')
            ->where('ad.id = :idAeroport')
            ->setParameter('idAeroport', $id)
            ->orderBy($sortBy, $order);

        $vols = $requete->getQuery()->getResult();

        return $this->render('vol/athAller.html.twig', [
            'vols' => $vols,
        ]);
    }
    
    #[Route('/athRetour/{id}', name: 'app_ath_retour')]
    public function athRetour(int $id, EntityManagerInterface $em): Response
    {
        $vols = $em->createQueryBuilder()
            ->select('v')
            ->from(Vol::class, 'v')
            ->innerJoin('v.avion', 'a')
            ->innerJoin('v.pilote', 'p')
            ->innerJoin('v.aeroportDepart', 'ad')
            ->innerJoin('v.aeroportArrivee', 'aa')
            ->where('aa.id = :idAeroport') 
            ->setParameter('idAeroport', $id)
            ->orderBy('v.dateHeureArrivee', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('vol/athRetour.html.twig', [
            'vols' => $vols,
            'idAeroport' => $id
        ]);
    }

}




