<?php
namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
class BookController extends BaseController {

    private $bookService;
   
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/books', name: 'book_add', methods: ['POST'])]
    public function addBook(Request $request): Response {
        $data = $this->getInputArray($request);

        $book = $this->bookService->addBook($data);

        $rs = [
            "code"    => "200",
            "message" => "1 Book created",
            "data"    => $this->bookService->getBookDto($book)
            // "data"    => $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_update', requirements: ['bookId' => '\d+'], methods: ['PUT'])]
    public function updateBook(int $bookId, Request $request): Response { 
        $rs = [];
        $data = $this->getInputArray($request);
        $book = $this->bookService->updateBook($bookId, $data);

        if(!$book){
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        } 

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId} updated",
            "data"    => $this->bookService->getBookDto($book)
            // "data"    => $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_delete', requirements: ['bookId' => '\d+'], methods: ['DELETE'])]
    public function deleteBook(int $bookId): Response { 
        $rs = [];  
        $status = $this->bookService->deleteBook($bookId);
        
        if(!$status) {
            // throw $this->createNotFoundException('No book found for id '.$bookId);
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId} deleted",
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_show', requirements: ['bookId' => '\d+'], methods: ['GET'])]
    public function showBook(int $bookId): Response {  
        $rs = [];
        $book = $this->bookService->getBook($bookId);
        
        if(!$book) {
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $reviews = $this->bookService->bookReviews($bookId);

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId}",
            "data"    => $this->bookService->getBookDtoDeep($book, $reviews)
            // "data"    =>  $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/search', name: 'book_search', methods: ['GET'])]
    public function searchBook(Request $request): Response {  
        $searchkey = $request->query->get('title');
        
        $books = $this->bookService->searchBookByTitle($searchkey);

        $rs = [
            "code"    => "200",
            "message" => "all Books",
            "data"    => $this->bookService->getBookDtoArray($books)
            // "data"    =>  $books
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function allBooks(): Response {
    	$books = $this->bookService->allBooks();

        $rs = [
            "code"    => "200",
            "message" => "all Books",
            "data"    => $this->bookService->getBookDtoArray($books)
            // "data"    =>  $books
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}

