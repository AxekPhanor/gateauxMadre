<?php

namespace App\Controller;

use App\Entity\Address;
use App\ClassPhp\Cart;
use App\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class AccountAddressController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresse', name: 'app_account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-adresse', name: 'app_account_address_add')]
    public function add(Cart $cart, Request $request)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $address = $form->getData();
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if($cart->get())
            {
                return $this->redirectToRoute('app_order');
            }
            else
            {
                return $this->redirectToRoute('app_account_address');
            }
            
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView(),
            'address' => $address,
        ]);
    }

    #[Route('/compte/supprimer-adresse/{id}', name: 'app_account_address_delete')]
    public function delete(Request $request, $id)
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        if($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_account_address');
    }

    #[Route('/compte/modifier-adresse/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id)
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        
        if(!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/addressForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
