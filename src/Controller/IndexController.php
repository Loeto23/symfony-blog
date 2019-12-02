<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * Commentaire pour git
     * avec la branch demo
     *
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig');
    }
}
