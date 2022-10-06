<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController {
    #[Route('/test', name: 'app_test')]
    public function index(ParameterBagInterface $bag): Response {

        dd(getenv('GOOGLE_KEY'));
        dd($bag->get(GOOGLE_KEY));
        dd($bag->get('kernel.debug'));
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
