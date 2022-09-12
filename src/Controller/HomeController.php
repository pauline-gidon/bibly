<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    public function __construct(private readonly LibraryRepository $libraryRepository)
    {

    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        if($this->getUser()){
           return $this->redirectToRoute('index');
        }

        return $this->render('layout/front/layout.html.twig', [
            'librarys' => $this->libraryRepository->findAll(),
        ]);
    }

    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $librarys = $this->libraryRepository->findBy(['user'=> $this->getUser()]);

        return $this->render('layout/back/list_library.html.twig', [
            'librarys' => $librarys,
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {

        return $this->render('user/show.html.twig', [
            'user'=> $this->getUser(),
        ]);
    }
}
