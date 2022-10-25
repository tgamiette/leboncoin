<?php

namespace App\Controller;

use App\Form\QuestionFormType;
use App\Form\AnswerFormType;
use App\Repository\OfferRepository;
use App\Repository\ResponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffersController extends AbstractController {
    #[Route('/offers', name: 'app_offers')]
    public function index(): Response {
        return $this->render('offers/index.html.twig', [
            'controller_name' => 'OffersController',
        ]);
    }

    #[Route('/offers/{id}', name: 'app_offers_id', methods: ['GET', 'POST'])]
    public function single(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository, int $id): Response {
        $offer = $offerRepository->findOneBy(['id' => $id]);
        $questions = $questionRepository->joinUser($id);
        $responses = $responseRepository->joinQuestions();
        $user = $this->getUser();

        $formQuestions = $this->createForm(QuestionFormType::class);
        $formQuestions->handleRequest($request);

        if ($formQuestions->isSubmitted() && $formQuestions->isValid()) {
            $question = $formQuestions->getData();
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
            'questionForm' => $formQuestions->createView(),
            'userLogin' => $user
        ]);
    }
    #[Route('/offers/{offer}/answer/{id}', name: 'app_answer_id', methods: ['GET', 'POST'])]
    public function answerQuestion(QuestionRepository $questionRepository, Request $request, EntityManagerInterface $manager, int $id, int $offer){

        $question = $questionRepository->findOneBy(['id' => $id]);

        $formAnswer = $this->createForm(AnswerFormType::class);
        $formAnswer->handleRequest($request);

        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();
            $answer->setQuestion($question);
            $answer->setUser($this->getUser());
            $answer->setCreatedAt(new \DateTime());
            $answer->setUpdatedAt(new \DateTime());
            $manager->persist($answer);
            $manager->flush();
            return ($this->redirectToRoute('app_offers_id', ['id' => $offer]));
        }

        return $this->render('responses/form.html.twig', [
            'question' => $question,
            'answerForm' => $formAnswer->createView(),
        ]);
    }

    #[Route('/offers/search', name: 'app_offers_id', methods: ['GET', 'POST'])]
    public function searchOffer(Request $request, OfferRepository $offerRepository, PaginatorInterface $paginator): Response {

        $search = $request->query->get('search');
        $title = $request->query->get('search');
        $offersQuery = $offerRepository->searchQueryBuilder($title);
        $pagination = $paginator->paginate($offersQuery, $request->query->getInt('page', 1), 10);

        return $this->render('offers/index.html.twig', [
            'offers' => $pagination,
        ]);
    }


}
