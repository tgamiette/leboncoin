<?php

namespace App\Controller;

use App\Security\GoogleAuthenticator;
use Google\Client;
use Google\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController {

    #[Route('/connect/google', name: 'connect_google')]
    public function connectAction($projectDir, Request $request): RedirectResponse {
        //Redirect to google
        $code = $request->query->get('code');

        $client = new Client();
        $client->setAuthConfig($projectDir . '/ressource/code_secret_client_721948890940-cvqq3dsr4er1nagflb7j24d1p1pt2oha.apps.googleusercontent.com.json');
        $client->setRedirectUri('YOUR_REDIRECT_URI');
        $client->addScope(Service\Oauth2::USERINFO_EMAIL);
        $client->addScope(Service\Oauth2::USERINFO_PROFILE);
        $client->addScope(Service\Drive::DRIVE_FILE);
        $client->addScope(Service\Drive::DRIVE_PHOTOS_READONLY);

        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/connect/google/check';

        $client->setRedirectUri($redirect_uri);
        $authUrl = $client->createAuthUrl();


        return $this->redirect($authUrl);

    }


    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request) {

    }
}
