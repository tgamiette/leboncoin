<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController {
    #[Route('/user/{id}', name: 'app_user_id', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, int $id, Security $security): Response {

        $user = $userRepository->findOneBy(['id' => $id]);
        $formRate = $this->createForm(UserFormType::class);
        $formRate->handleRequest($request);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        if ($formRate->isSubmitted() && $formRate->isValid()) {
            $rate = $formRate['rate']->getData();

            if ($rate === true) {
                $user->upRate();
            }
            elseif ($rate === false) {
                $user->downRate();
            }

            $manager->flush();
            return ($this->redirectToRoute('app_user_id', ['id' => $id]));
        }

        return $this->render('user/single.html.twig', [
            'user' => $user,
            'rateForm' => $formRate->createView(),
        ]);
    }
}
