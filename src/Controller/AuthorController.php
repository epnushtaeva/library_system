<?php

namespace App\Controller;

use App\DataTables\AuthorsDataTable;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route('/authors', name: 'app_author', methods: ['GET'])]
    public function index(AuthorsDataTable $table, Request $request): Response
    {
        $errMessage = $request->query->get('err');
        $table->handleRequest($request);

        if ($table->isRequestHandled()) {
            return $table->getResponse();
        }

        return $this->render('author/index.html.twig', [
            'table' => $table->getDataTable(),
            'errMessage' => $errMessage
        ]);
    }

    #[Route('/add_author', name: 'app_add_author', methods: ['GET', 'POST'])]
    public function addAuthor(Request $request): Response
    {
        $author = new Author();
        return $this->renderAddOrEditForm($author, $request, 'author/add_or_edit/add.html.twig');
    }

    #[Route('/edit_author/{id}', name: 'app_edit_author', methods: ['GET', 'POST'])]
    public function editAuthor(Author $author, Request $request){
        return $this->renderAddOrEditForm($author, $request, 'author/add_or_edit/edit.html.twig');
    }

    #[Route('/remove_author/{id}', name: 'app_remove_author', methods: ['POST'])]
    public function removeAuthor(Author $author){
       $removeRes = $this->authorService->removeAuthor($author);

       if($removeRes){
           return $this->redirectToRoute('app_author', ['err' => $removeRes], Response::HTTP_SEE_OTHER);
       }

        return $this->redirectToRoute('app_author', [], Response::HTTP_SEE_OTHER);
    }

    private function renderAddOrEditForm(Author $author, Request $request, string $viewName):Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->authorService->addOrEditAuthor($author);
            return $this->redirectToRoute('app_author', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($viewName, [
            'author' => $author,
            'entityForm' => $form
        ]);
    }
}
