<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;

class AuthorController extends AbstractController
{
    
    private $AuthorRepo;
    private $entityManager;

    public function __construct(AuthorRepository $authorRepositoryParam, EntityManagerInterface $entityManagerParam)
    {
        $this->AuthorRepo = $authorRepositoryParam;
        $this->entityManager = $entityManagerParam;
    }

    #[Route('/author', name: 'app_author', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'app_show', defaults: ['name' => 'victor hugo'], methods: ['GET'])]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/listAuthor', name: 'app_show', methods:['GET'])]
    public function listAuthor(): Response
    {
        $author = $this->AuthorRepo->findAuthors();
        return $this->render('author/showAuthors.html.twig', [
            'authors'=> $author
        ]);
    }
    #[Route('/authorDetail/{id}', name: 'author_details', methods:['GET'])]
    public function authorDetail($id): Response
    {   
        $index = $id - 1;

        if (isset($this->authors[$index])) {
            $author = $this->authors[$index];
    
            return $this->render('author/detail.html.twig', [
                'author' => $this->authors[$index]
            ]);
        } else {
            return new Response("Author not found", 404);
        }

    }
    #[Route('/add_Author', name: 'author_add', methods: ['GET'])]

    public function addAuthor(): Response
    {
        $author = new Author();
        $author->setUsername('ines');
        $author->setEmail('ines@gmail.com');
        $author->setPicture('ines.png');
        $author->setNbBooks(450);  // setNbBooks() instead of setNb_Books()

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_show');
    }


    #[Route('/deleteAuthor/{id}', name: 'author_delete', methods: ['GET','DELETE'])]
public function deleteAuthor($id): Response
{
    $author = $this->AuthorRepo->find($id); // Utilisation de la bonne propriété
    
    if ($author) {
        $this->entityManager->remove($author);
        $this->entityManager->flush();
    }

    return $this->redirectToRoute('app_show');
}
#[Route('/bookAuthor/{id}', name: 'author_book', methods: ['GET','DELETE'])]
public function showAuthorBooks(int $id): Response
{
    $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

    if (!$author) {
        throw $this->createNotFoundException('The author does not exist');
    }

    $livres = $this->getDoctrine()->getRepository(Livre::class)->findBy(['author' => $author]);

    return $this->render('author/showBooks.html.twig', [
        'author' => $author,
        'livres' => $livres,
    ]);
}

}