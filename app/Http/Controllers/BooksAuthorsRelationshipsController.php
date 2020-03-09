<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\BooksAuthorsRelationshipsRequest;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Http\Resources\AuthorsIdentifierResource;
use App\Http\Resources\JSONAPIIdentifierResource;
use App\Services\JSONApiService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class BooksAuthorsRelationshipsController extends Controller
{
    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
    }

    public function index(Book $book) {
        // return JSONAPIIdentifierResource::collection($book->authors);

        return $this->service->fetchRelationship($book, 'authors');
    }

    public function update(JSONAPIRelationshipRequest $request, Book $book) {
        // $ids = $request->input('data.*.id');
        // $book->authors()->sync($ids);
        // return response(null, 204);

        if(Gate::denies('admin-only')){
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->service->updateManyToManyRelationships($book, 'authors', $request->input('data.*.id'));
    }
}
