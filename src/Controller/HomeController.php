<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ClassPhp\Cart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Cart $cart): Response
    {
        return $this->render('home/index.html.twig');
    }
}
