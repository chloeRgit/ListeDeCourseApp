<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(ItemRepository $repo,Request $request): Response
    {
        $items=$repo->findAll();
        $item= new Item();
        $item->setStatus(false);
        $formItem=$this->createForm(ItemType::class, $item);
        $formItem->handleRequest($request);
        if($formItem->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            return $this->render('main/index.html.twig', [
                'items'=>$items,
                'formItem' => $formItem->createView(),
            //     'success_ajout'=>'success'
            ]);
             }

        return $this->render('main/index.html.twig', [
            'formItem' => $formItem->createView(),
            'items'=>$items,
        ]);

    }
    /**
     * @Route("/itemdelete/{id}", name="itemdelete")
     */
    public function itemdelete(Item $item,EntityManagerInterface $em): Response
    {
        // $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        return $this->redirectToRoute('main');
    }
    /**
     * @Route("/itemachat/{id}", name="itemachat")
     */
    public function itemachat(Item $item,EntityManagerInterface $em): Response
    {

        $item->setStatus(true);
        $em->flush();
        return $this->redirectToRoute('main');
    }
   /* public function itemAjout(Request $request): Response
    {
        $item=new Item();
        dd($request);

        //$now = date_create()->format('Y-m-d H:i:s');
        //$wish->setIsPublished(true);
        //$wish->setDateCreated(new \DateTime());
       // $formWish=$this->createForm(WishType::class, $wish);
       // $formWish->handleRequest($request);
        //if($formWish->isSubmitted()){
           // $em=$this->getDoctrine()->getManager();
            //$em->persist($wish);
           // $em->flush();
           // return $this->render('main/wishes.html.twig', [
           //     'wish' => $wish,
           //     'success_ajout'=>'success']);
       // }
        return $this->render('main/index.html.twig', [
           // 'formWish' => $formWish->createView(),
        ]);

    }*/
}
