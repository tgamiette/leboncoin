<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Core\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{
    #[Route('/questions/{offer}', name: 'app_questions',  methods: ['GET', 'POST'])]
    public function index(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, int $offer): Response
    {
        $questions = $questionRepository->joinUser($offer);
        $responses = $responseRepository->joinQuestions();

        $form = $this->createForm(QuestionFormType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $question = $form->getData();
            $question->setUser($this->getUser());
            $question->setOffer($this->getOffer());
            $question->setCreatedAt(new \DateTime());
            $question->setUpdatedAt(new \DateTime());

            $manager->persist($question);
            $manager->flush();

            return($this->redirectToRoute('app_questions'));
        }


        return $this->render('questions/question.html.twig', [
            'questions' => $questions,
            'responses' => $responses,
            'questionForm' => $form->createView()
        ]);
    }
}
