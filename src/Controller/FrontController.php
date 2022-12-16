<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\PanierService;
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

}
