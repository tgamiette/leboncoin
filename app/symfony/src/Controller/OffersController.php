<?php

namespace App\Controller;

use App\Form\QuestionFormType;
use App\Repository\OfferRepository;
use App\Repository\ResponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController
{
    #[Route('/offers', name: 'app_offers')]
    public function index(): Response
    {
        return $this->render('offers/index.html.twig', [
            'controller_name' => 'OffersController',
        ]);
    }

    #[Route('/offers/{id}', name: 'app_offers_id', methods: ['GET', 'POST'])]
    public function single(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository, int $id): Response
    {
        $offer = $offerRepository->findOneBy(['id' => $id]);
        $questions = $questionRepository->joinUser($id);
        $responses = $responseRepository->joinQuestions();

        $form = $this->createForm(QuestionFormType::class);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new \DateTime());
            $question->setUpdatedAt(new \DateTime());

            $manager->persist($question);
            $manager->flush();

            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));

        }

        return $this->render('offers/single.html.twig', [
            'offer' => $offer,
            'questions' => $questions,
            'responses' => $responses,
            'questionForm' => $form->createView(),
            'userLogin' => $user
        ]);
    }

}
