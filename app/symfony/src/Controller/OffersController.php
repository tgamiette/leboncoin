<?php

namespace App\Controller;

use App\Form\QuestionFormType;
use App\Form\AnswerFormType;
use App\Repository\OfferRepository;
use App\Repository\ResponseRepository;
use App\Repository\QuestionRepository;
use DateTime;
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
        $user = $this->getUser();

        $formQuestions = $this->createForm(QuestionFormType::class);
        $formQuestions->handleRequest($request);

<<<<<<< HEAD
        $formAnswer = $this->createForm(AnswerFormType::class);
        $formAnswer->handleRequest($request);

        if ($formQuestions->isSubmitted() && $formQuestions->isValid()) {
            $question = $formQuestions->getData();
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new DateTime());
            $question->setUpdatedAt(new DateTime());
=======
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new \DateTime());
            $question->setUpdatedAt(new \DateTime());
>>>>>>> 67d8121 ([chnage] change error)

            $manager->persist($question);
            $manager->flush();
            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));
        }

<<<<<<< HEAD
        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();
            $answer->setUser($this->getUser());
            $answer->setOffer($offer);
            $answer->setCreatedAt(new \DateTime());
            $answer->setUpdatedAt(new \DateTime());
            $manager->persist($answer);
            $manager->flush();
            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));
=======
            return ($this->redirectToRoute('app_offers',));

>>>>>>> 67d8121 ([chnage] change error)
        }

        return $this->render('offers/single.html.twig', [
            'offer' => $offer,
            'questions' => $questions,
            'responses' => $responses,
<<<<<<< HEAD
            'questionForm' => $formQuestions->createView(),
            'answerForm' => $formAnswer->createView(),
=======
            'questionForm' => $form->createView(),
>>>>>>> 67d8121 ([chnage] change error)
            'userLogin' => $user
        ]);
    }

}
