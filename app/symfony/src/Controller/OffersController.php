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
    #[Route('/offers/{id}', name: 'app_offers_id', methods: ['GET', 'POST'])]
    public function single(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository, int $id): Response
    {
        $offer = $offerRepository->findOneBy(['id' => $id]);
        $user = $this->getUser();

        $formQuestions = $this->createForm(QuestionFormType::class);
        $formQuestions->handleRequest($request);

        $formAnswer = $this->createForm(AnswerFormType::class);
        $formAnswer->handleRequest($request);

        if ($formQuestions->isSubmitted() && $formQuestions->isValid()) {
            $question = $formQuestions->getData();
<<<<<<< HEAD
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new DateTime());
            $question->setUpdatedAt(new DateTime());
=======
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
=======
>>>>>>> 91a3360 ([fix] commit fix commit)
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new \DateTime());
            $question->setUpdatedAt(new \DateTime());
>>>>>>> 67d8121 ([chnage] change error)

            $manager->persist($question);
            $manager->flush();
            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));
        }

        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();
            $answer->setUser($this->getUser());
            $answer->setCreatedAt(new \DateTime());
            $answer->setUpdatedAt(new \DateTime());
            $manager->persist($answer);
            $manager->flush();
            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));
<<<<<<< HEAD
=======
            return ($this->redirectToRoute('app_offers',));

>>>>>>> 67d8121 ([chnage] change error)
=======
>>>>>>> 91a3360 ([fix] commit fix commit)
        }

        return $this->render('offers/single.html.twig', [
            'offer' => $offer,
            'questionForm' => $formQuestions->createView(),
            'answerForm' => $formAnswer->createView(),
<<<<<<< HEAD
=======
            'questionForm' => $form->createView(),
>>>>>>> 67d8121 ([chnage] change error)
=======
>>>>>>> 91a3360 ([fix] commit fix commit)
            'userLogin' => $user
        ]);
    }

}
