<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Commande;
use App\Repository\ProductRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{



    #[Route('/', name: 'home')]
    public function home(ProductRepository $productRepository): Response
    {
        $prestations=$productRepository->findAll();


        return $this->render('front/home.html.twig', [
            'prestations'=>$prestations
        ]);
    }

    #[Route('/ajoutPanier/{id}', name: 'ajoutPanier')]
    public function ajoutPanier(PanierService $panierService, $id): Response
    {
          $panierService->add($id);

        return $this->redirectToRoute('panier');
    }

    #[Route('/retraitPanier/{id}', name: 'retraitPanier')]
    public function retraitPanier(PanierService $panierService,$id): Response
    {
        $panierService->remove($id);


        return $this->redirectToRoute('panier');

    }


    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(PanierService $panierService , $id): Response
    {
       $panierService->delete($id);

        return $this->redirectToRoute('panier');
    }

    #[Route('/destroy', name: 'destroy')]
    public function destroy(PanierService $panierService): Response
    {
        $panierService->destroy();


        return $this->redirectToRoute('home');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(PanierService $panierService): Response
    {
        $panier=$panierService->getFullPanier();
        $total=$panierService->getTotal();


        return $this->render('front/panier.html.twig', [
            'panier'=>$panier,
            'total'=>$total

        ]);
    }

    #[Route('/commande', name: 'commande')]
    public function commande(ProductRepository $productRepository ,EntityManagerInterface $manager, PanierService $panierService): Response
    {

            $panier = $panierService->getFullPanier();
            $commande = new Commande();
            $commande->setUtilisateur($this->getUser());
            $commande->setDate(new \DateTime());

            foreach ($panier as $item) {
                if ($item['produit']->getParticipant() >=  $item['quantite']) {
                $achat = new Achat();
                $achat->setProduit($item['produit']);
                $achat->setCommande($commande);
                $achat->setQuantite($item['quantite']);
                $item['produit']->setParticipant($item['produit']->getParticipant() - $item['quantite']);


                $manager->persist($achat);
                $manager->persist($item['produit']);}
                else {

                        $this->addFlash('success','Quantité maximun est dépassé !!!');
                        return $this->redirectToRoute('panier');

                    }

            }
            $panierService->destroy();
            $manager->persist($commande);
            $manager->flush();


        $this->addFlash('success', 'Merci pour votre commande');

        return $this->redirectToRoute('home');
    }


}
