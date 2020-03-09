<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Services\JSONApiService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BooksController extends Controller
{
    protected function resourceMethodsWithoutModels() {
        return ['index', 'store', 'show'];
    }

    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
        $this->authorizeResource(Book::class, 'book');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $books = QueryBuilder::for(Book::class)
        //             ->allowedSorts(['title', 'publication_year', 'created_at', 'updated_at'])
        //             ->jsonPaginate();

        // return new JSONAPICollection($books);

        return $this->service->fetchResources(Book::class, 'books');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
        // $book = Book::create([
        //     'title' => $request->input('data.attributes.title'),
        //     'description' => $request->input('data.attributes.description'),
        //     'publication_year' => $request->input('data.attributes.publication_year'),
        // ]);

        // return (new JSONAPIResource($book))
        //     ->response()
        //     ->header('Location', route('books.show', [ 'book' => $book ]));

        return $this->service->createResource(Book::class, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        // $query = QueryBuilder::for(Book::where('id', $book))
        //             ->allowedIncludes('authors')
        //             ->firstOrFail();

        // return new JSONAPIResource($query);

        return $this->service->fetchResource(Book::class, $book, 'books');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Book $book)
    {
        // $book->update($request->input('data.attributes'));
        // return new JSONAPIResource($book);

        return $this->service->updateResource($book, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        // $book->delete();
        // return response(null, 204);

        return $this->service->deleteResource($book);
    }
}
