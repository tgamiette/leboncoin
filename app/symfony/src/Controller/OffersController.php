<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use App\Form\QuestionFormType;
use App\Repository\OfferRepository;
use App\Form\AnswerFormType;
use App\Repository\ResponseRepository;
use App\Repository\QuestionRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class OffersController extends AbstractController {
    #[Route('/offers', name: 'app_offers')]
    public function index(): Response {
        return $this->render('offers/index.html.twig', [
            'controller_name' => 'OffersController',
        ]);
    }

    #[Route('/offers/{id}', name: 'app_offers_id', methods: ['GET', 'POST'])]
    public function single(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository, int $id): Response {
class OffersController extends AbstractController
{
    #[Route('/offers/{id}', name: 'app_offers_id')]
    public function single(QuestionRepository $questionRepository, ResponseRepository $responseRepository, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository, int $id): Response
    {
        $offer = $offerRepository->findOneBy(['id' => $id]);
        $questions = $questionRepository->joinUser($id);
        $responses = $responseRepository->joinQuestions();
        $user = $this->getUser();

        $formQuestions = $this->createForm(QuestionFormType::class);
        $formQuestions->handleRequest($request);

        $formAnswer = $this->createForm(AnswerFormType::class);
        $formAnswer->handleRequest($request);

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

        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();
            $answer->setUser($this->getUser());
            $answer->setOffer($offer);
            $answer->setCreatedAt(new \DateTime());
            $answer->setUpdatedAt(new \DateTime());
            $manager->persist($answer);
            $manager->flush();

            return ($this->redirectToRoute('app_offers_id', ['id' => $id]));
        }

        return $this->render('offers/single.html.twig', [
            'offer' => $offer,
            'questions' => $questions,
            'responses' => $responses,
            'questionForm' => $formQuestions->createView(),
            'answerForm' => $formAnswer->createView(),
            'userLogin' => $user
        ]);
    }

    #[Route('/create', name: 'app_offer_create')]
    public function create(Request $request, Security $security, EntityManagerInterface $entityManager,  FileUploader $fileUploader): Response
    {
        $offer = new Offer();
        $user = $security->getUser();

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $offer->setUser($user);
            $offer->setStatus(false);
            /** @var UploadedFile $imageFile */
            $imageFiles = $form->get('files')->getData();

            if($imageFiles) {

                foreach ($imageFiles as $imageFile) {
                    $file = new File();
                    $imageFileName = $fileUploader->uploadFile($imageFile);
                    $file->setFileName($imageFileName);
                    $file->setPath('images/'.$imageFileName);
                    $file->setOffer($offer);
                    $entityManager->persist($file);
                }
            }

            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('offer/createOffer.html.twig', [
            'offerForm' => $form->createView(),
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



    #[Route('/delete/{id}', name:'app_offer_delete_id')]
    public function delete(EntityManagerInterface $entityManager, int $id, OfferRepository $offerRepository): Response
    {

        $offer = $offerRepository->findOneBy(['id' => $id]);

        $entityManager->remove($offer);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
