<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController {

//    #[Route('/connect/google', name: 'connect_google')]
//    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse {
//        //Redirect to google
//
////        new ClientRegistry()
//
//        return $clientRegistry
//            ->getClient('google')
//            ->redirect([
//                'profile', 'email'
//            ]);
//        return $clientRegistry->getClient('google')->redirect([], []);
//    }
}
