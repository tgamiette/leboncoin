<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Google\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends AbstractAuthenticator {
    use TargetPathTrait;

    const GOOGLE_CINFIG = [
        'application_name' => '',

        'client_id' => '721948890940-cvqq3dsr4er1nagflb7j24d1p1pt2oha.apps.googleusercontent.com',
        'client_secret' => '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',
        'credentials' => null,
        // @see Google\Client::setScopes
        'scopes' => null,
        // Sets X-Goog-User-Project, which specifies a user project to bill
        // for access charges associated with the request
        'quota_project' => null,
        'redirect_uri' => "http://localhost:1234/connect/google/check",
        // Simple API access key, also from the API console. Ensure you get
        // a Server key, and not a Browser key.
        'developer_key' => 'AIzaSyCUizk6dVPuaUzpEU6qLtp7X5s0YpeRfQg'
    ];

    public function __construct(private EntityManagerInterface $em, private $projectDir, private readonly UrlGeneratorInterface $urlGenerator) {
    }

    public function supports(Request $request): ?bool {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport {
        $code = $request->query->get('code');
        $client = new Client(self::GOOGLE_CINFIG);
        $token = $client->fetchAccessTokenWithAuthCode($code);
        $oauth = new \Google\Service\Oauth2($client);
        $userInfo = $oauth->userinfo->get();

        $passport = new SelfValidatingPassport(
            new UserBadge($userInfo->getEmail(),
                function () use ($userInfo) {
                    $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $userInfo->getEmail()]);

                    if (!$existingUser) {
                        $existingUser = new User();
                        $existingUser->setEmail($userInfo->getEmail());
                        $existingUser->setLastname($userInfo->getFamilyName());
                        $existingUser->setFirstname($userInfo->getGivenName());
                        $existingUser->setPseudo($userInfo->getName());
                        $this->em->persist($existingUser);
                    }

                    $existingUser->setGoogleId($userInfo->getId());
                    $existingUser->setAvatar($userInfo->getPicture());
                    $this->em->flush($existingUser);

                    return $existingUser;
                }
            ),
        );

//        $this->session->set('google_token',$token);
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }


        return new RedirectResponse($this->urlGenerator->generate('app_offers_all'));

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
//
//    public function createToken(Passport $passport, string $firewallName): TokenInterface {
//
//        $token = new PostAuthenticationToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles());
//        $token->setAttribute('token_google',$this->session->get('google_token'));
//        return $token;
//    }


//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
