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


#[Route('/offers')]
class OffersController extends AbstractController {

    #[Route('/search', name: 'app_offers_search', methods: ['GET'])]
    public function searchOffer(Request $request, OfferRepository $offerRepository, PaginatorInterface $paginator): Response {

        $title = $request->query->get('search');
        $offersQuery = $offerRepository->searchQueryBuilder($title);
        $pagination = $paginator->paginate($offersQuery, $request->query->getInt('page', 1), 12);

        return $this->render('offers/index.html.twig', [
            'offers' => $pagination,
        ]);
    }

    #[Route('/', name: 'app_offers_all')]
    public function show(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $offers = $offerRepository->findAllPaginated();
        $pagination = $paginator->paginate(
            $offers,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('offers/index.html.twig', [
            'offers' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_id', requirements: ['id' => '\d+'])]
    public function single(int $id, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->findOneBy(['id' => $id]);
        $user = $this->getUser();

        $formQuestions = $this->createForm(QuestionFormType::class);
        $formQuestions->handleRequest($request);

        if(!$offer){
            throw $this->createNotFoundException('No offer found for id ' . $id);
        }

        if ($formQuestions->isSubmitted() && $formQuestions->isValid()) {
            $question = $formQuestions->getData();
            $question->setUser($this->getUser());
            $question->setOffer($offer);
            $question->setCreatedAt(new \DateTime());
            $question->setUpdatedAt(new \DateTime());

            $manager->persist($question);
            $manager->flush();
            return ($this->redirectToRoute('app_offer_id', ['id' => $id]));
        }

        return $this->render('offers/single.html.twig', [
            'offer' => $offer,
            'questionForm' => $formQuestions->createView(),
            'userLogin' => $user
        ]);
    }

    #[Route('/{offer}/answer/{id}', name:'app_answer_id')]
    public function answer(QuestionRepository $questionRepository, Request $request, EntityManagerInterface $manager, int $id, int $offer): Response
    {
        $question = $questionRepository->findOneBy(['id' => $id]);

        $formAnswer = $this->createForm(AnswerFormType::class);
        $formAnswer->handleRequest($request);

        if ($formAnswer->isSubmitted() && $formAnswer->isValid()) {
            $answer = $formAnswer->getData();
            $answer->setUser($this->getUser());
            $answer->setQuestion($question);
            $answer->setCreatedAt(new \DateTime());
            $answer->setUpdatedAt(new \DateTime());

            $manager->persist($answer);
            $manager->flush();

            return ($this->redirectToRoute('app_offer_id', ['id' => $offer]));
        }

        return $this->render('responses/form.html.twig', [
            'question' => $question,
            'answerForm' => $formAnswer->createView(),
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

    #[Route('/delete/{id}', name:'app_offer_delete_id')]
    public function delete(EntityManagerInterface $entityManager, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->findOneBy(['id' => $id]);

        $entityManager->remove($offer);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('update/{id}', name:'app_offer_update_id')]
    public function update(Offer $offer, EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader, Security $security): Response
    {
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

            $entityManager->flush();

            return $this->redirectToRoute('app_user_id', ['id' => $user->getId()]);
        }

        return $this->render('offer/createOffer.html.twig', [
            'offerForm' => $form->createView(),
        ]);
    }
}
