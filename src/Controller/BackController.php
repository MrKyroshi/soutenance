<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Product;
use App\Form\AvisType;
use App\Form\EditPrestationType;
use App\Form\EditProductType;
use App\Form\ProductType;
use App\Repository\AvisRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
   #[Route('/ajoutprestation', name: 'ajoutprestation')]
   public function ajoutProduit(Request $request, EntityManagerInterface $manager): Response
   {
       $prestation = new Product();

       $form=$this->createForm(ProductType::class,$prestation);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()){
           $picture=$form->get('photo')->getData();
           if($picture){
               $picture_bdd = date('YmDHis').uniqid().$picture->getClientOriginalName();
               $picture->move($this->getParameter('upload_directory'),$picture_bdd);
               $prestation->setUser($this->getUser());
               $prestation->setPhoto($picture_bdd);
               $manager->persist($prestation);
               $manager->flush();
               $this->addFlash('success','Congration !!! Vous avez ajouté une prestation !!!');
               return $this->redirectToRoute('gestionprestation');
           }

       }

       return $this->render('back/ajoutprestation.html.twig', [
           'form' => $form->createView()


           ]);
       }


    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/gestionprestation', name: 'gestionprestation')]
    public function gestionprestation(ProductRepository $productRepository): Response
    {
        $prestations = $productRepository->findBy([
         'user'=>$this->getUser()
        ]);


        return $this->render('back/gestionprestation.html.twig', [
            'prestations' => $prestations
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/editprestation/{id}', name: 'editprestation')]
    public function editPrestation(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(EditPrestationType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPhoto')->getData()) {
                $picture = $form->get('editPhoto')->getData();
                $picture_bdd = date('YmdHis') . uniqid() . $picture->getClientOriginalName();

                $picture->move($this->getParameter('upload_directory'), $picture_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $product->getPhoto());
                $product->setPhoto($picture_bdd);


            }

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Prestation modifié');
            return $this->redirectToRoute('gestionprestation');


        }


        return $this->render('back/editprestation.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/deletePrestation/{id}', name: 'deletePrestation')]
    public function deletePrestation(Product $product, EntityManagerInterface $manager): Response
    {
        $manager->remove($product);
        $manager->flush();

        $this->addFlash('success', 'Prestation a bien supprimé !!!');

        return $this->redirectToRoute('gestionprestation');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/ajoutAvis/{id}', name: 'ajoutAvis')]
    public function ajoutAvis(AvisRepository $avisRepository ,ProductRepository $productRepository ,Request $request,EntityManagerInterface $manager,$id=null): Response
    {

        $avis= $avisRepository->findBy([
            'user'=>$this->getUser(),
            'product'=>$productRepository->find($id)
        ]);

   if (!$avis){

       $avis = new Avis();

       $form =$this->createForm(AvisType::class,$avis);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $avis->setDate(new \DateTime());
           $avis->setUser($this->getUser());
           $avis->setProduct($productRepository->find($id));
           $manager->persist($avis);
           $manager->flush();
           $this->addFlash('success', 'Merci votre conmentaire');
           return $this->redirectToRoute('gestionAvis');
       }

   }else{
           $this->addFlash('success','avid déja deposé');
           return $this->redirectToRoute('home');
   }


        return $this->render('back/ajoutAvis.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/gestionAvis', name: 'gestionAvis')]
    public function gestionAvis(AvisRepository $avisRepository,UserRepository $userRepository): Response
    {
        $aviss=$avisRepository->findBy(['user'=>$this->getUser()]);
        return $this->render('back/gestionAvis.html.twig', [
            'aviss'=>$aviss

        ]);
    }
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/editAvis/{id}', name: 'editAvis')]
    public function editAvis(AvisRepository $avisRepository , EntityManagerInterface $manager ,Request $request ,$id): Response
    {
        $avis=$avisRepository->find($id);
        $form=$this->createForm(AvisType::class,$avis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($avis);
            $manager->flush();
            $this->addFlash('success','Modifiée avis !!!');
            return $this->redirectToRoute('gestionAvis');
        }

        return $this->render('back/editAvis.html.twig', [
            'form'=>$form->createView()

        ]);
    }
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/deleteAvis/{id}', name: 'deleteAvis')]
    public function deleteAvis(AvisRepository $avisRepository,EntityManagerInterface $manager,$id): Response
    {
        $avis=$avisRepository->find($id);

        $manager->remove($avis);
        $manager->flush();
        $this->addFlash('success','Delete réussi Avis');
        return $this->redirectToRoute('gestionAvis');

    }

         #[Route('/affichageAvis/{id}', name: 'affichageAvis')]
             public function affichageAvis(AvisRepository $avisRepository,ProductRepository $productRepository , $id=null): Response
             {
                 $prestation=$productRepository->find($id);
                 $aviss=$avisRepository->findBy([
                     'product'=>$prestation

                 ]);

                 return $this->render('back/affichageAvis.html.twig', [
                     'aviss'=>$aviss

                 ]);
             }

                #[Route('/affiDescription/{id}', name: 'affiDescription')]
                    public function affiDescription(Product $product): Response
                    {

                        return $this->render('back/affiDescription.html.twig', [
                          'product'=>$product
                        ]);
                    }






















}
