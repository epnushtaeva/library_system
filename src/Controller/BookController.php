<?php

namespace App\Controller;

use App\Constants\BookFormVialotionMessages;
use App\DataTables\BooksDataTable;
use App\Entity\Book;
use App\Entity\BookToCell;
use App\Entity\User;
use App\Form\BookType;
use App\Message\UpdateBooksMessage;
use App\Service\BookCellService;
use App\Service\BookService;
use App\Task\SetBookUnbookingTask;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class BookController extends AbstractController
{
    public function __construct(
        private BookService $bookService,
        private BookCellService $bookCellService,
        private MessageBusInterface $messageBus
    )
    {
    }

    #[Route('/books', name: 'app_book')]
    public function index(BooksDataTable $table, Request $request): Response
    {
        $successMess = $request->query->get('success');
        $errMess = $request->query->get('err');
        $table->handleRequest($request);

        if ($table->isRequestHandled()) {
            return $table->getResponse();
        }

        return $this->render('book/index.html.twig', [
            'table' => $table->getDataTable(),
            'successMessage' => $successMess,
            'errMessage' => $errMess
        ]);
    }

    #[Route('/add_book', name: 'app_add_book', methods: ['GET', 'POST'])]
    public function add(Request $request){
        $book = new Book();
        return $this->renderAddOrEditForm($book, $request, 'book/add_or_edit/add.html.twig');
    }

    #[Route('/edit_book/{id}', name: 'app_edit_book', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book): Response
    {
        return $this->renderAddOrEditForm($book, $request, 'book/add_or_edit/edit.html.twig');
    }

    #[Route('/remove_book/{id}', name: 'app_edit_book', methods: ['POST'])]
    public function removeBook(Book $book): Response
    {
        $this->bookService->removeBook($book);
        return $this->redirectToRoute('app_book', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/brone_book/{id}', name: 'app_brone_book', methods: ['POST'])]
    public function broneBook(Book $book, #[CurrentUser] ?User $user): Response
    {
        $errMess = $this->bookService->broneBook($book, $user);

        if($errMess){
            return $this->redirectToRoute('app_book', ['err' => $errMess], Response::HTTP_SEE_OTHER);
        }

        $this->messageBus->dispatch(new UpdateBooksMessage($book->getId()));
        return $this->redirectToRoute('app_book', ['success' => BookFormVialotionMessages::BRONE_SUCCESS], Response::HTTP_SEE_OTHER);
    }

    #[Route('/unbook/{id}', name: 'app_unbook', methods: ['POST'])]
    public function unbook(BookToCell $bookToCell):Response
    {
        $this->bookCellService->unBook($bookToCell);
        return $this->redirectToRoute('app_book_cells', ['id' => $bookToCell->getBook()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/book_cells/{id}', name: 'app_book_cells', methods: ['GET'])]
    public function getBookCells(Book $book):Response
    {
        $bookCells = $this->bookCellService->getBookCells($book->getId());
        return $this->render('book/cells/cells.html.twig', [
            'cells' => $bookCells
        ]);
    }

    #[Route('/set_on_hand/{id}', name: 'app_set_on_hand', methods: ['POST'])]
    public function setBookCellOnHandStatus(BookToCell $bookToCell):Response
    {
        $this->bookCellService->setBookCellOnHandStatus($bookToCell);
        return $this->redirectToRoute('app_book_cells', ['id' => $bookToCell->getBook()->getId()], Response::HTTP_SEE_OTHER);
    }

    public function renderAddOrEditForm(Book $book, Request $request, string $viewName): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->addOrUpdateBook($book);
            return $this->redirectToRoute('app_book', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render($viewName, [
            'book' => $book,
            'entityForm' => $form,
        ]);
    }
}
