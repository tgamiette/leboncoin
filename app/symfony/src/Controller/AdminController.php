<?php

namespace App\Controller;

use App\Security\GoogleAuthenticator;
use Google\Client;
use Google\Service\Drive\Drive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin',)]
//    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
//        $client = new Client(GoogleAuthenticator::GOOGLE_CINFIG);
//$client->fetchAccessTokenWithAuthCode();
        $driveService = new Drive();

        $driveService->getName();
        dd($driveService->getName());
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
