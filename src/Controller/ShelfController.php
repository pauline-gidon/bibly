<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\Shelf;
use App\Form\ShelfType;
use App\Repository\ShelfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shelf', name: 'shelf_')]
class ShelfController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ShelfRepository $shelfRepository): Response
    {
        return $this->render('shelf/index.html.twig', [
            'shelves' => $shelfRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShelfRepository $shelfRepository): Response
    {

        $shelf = new Shelf();
        $form = $this->createForm(ShelfType::class, $shelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelfRepository->add($shelf, true);

            return $this->redirectToRoute('library_show', ['id' => $shelf->getLibrary()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shelf/new.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Shelf $shelf): Response
    {
        return $this->render('shelf/show.html.twig', [
            'shelf' => $shelf,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shelf $shelf, ShelfRepository $shelfRepository): Response
    {
        $form = $this->createForm(ShelfType::class, $shelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shelfRepository->add($shelf, true);

            return $this->redirectToRoute('shelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shelf/edit.html.twig', [
            'shelf' => $shelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Shelf $shelf, ShelfRepository $shelfRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shelf->getId(), $request->request->get('_token'))) {
            $shelfRepository->remove($shelf, true);
        }

        return $this->redirectToRoute('shelf_index', [], Response::HTTP_SEE_OTHER);
    }
}
