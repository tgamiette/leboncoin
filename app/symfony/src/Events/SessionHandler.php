<?php


namespace App\Events;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\StrictSessionHandler;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\EventListener\SessionListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;

class SessionHandler {
//    public function __construct(private SessionStorageInterface $sessionStorage, private TokenStorageInterface $tokenStorage, private RouterInterface $router,private $max = 0) {
//    }
    public function __construct(private string $maxIdleTime,
        private SessionTokenStorage $session,
        private TokenStorageInterface $tokenStorage,
        private RouterInterface $router,
        private AuthorizationCheckerInterface $checker) {
    }

    public function onKernelRequest(RequestEvent $event): void {

        if (HttpKernelInterface::MAIN_REQUEST != $event->getRequestType()) {
            return;
        }

//        dd($this->session);

        if ($this->maxIdleTime > 0) {
//            $this->session->
//            $this->session->start();
//            $lapse = time() - $this->sessionStorage->getMetadataBag()->getLastUsed();

//            dd($lapse);
//            if ($lapse > $this->max) {
//                $this->tokenStorage->setToken(null);
//                $this->sessionStorage->getFlashBag()->set('info', 'You have been logged out due to inactivity.');
//
//                //Change the route if you are not using FOSUserBundle.
//                $event->setResponse(new RedirectResponse($this->router->generate('app_login')));
//        }
        }
    }
}