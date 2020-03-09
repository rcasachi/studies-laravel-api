<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Services\JSONApiService;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $authors = QueryBuilder::for(Author::class)
        //                 ->allowedSorts(['name', 'created_at', 'updated_at'])
        //                 ->jsonPaginate();

        // return new JSONAPICollection($authors);

        return $this->service->fetchResources(Author::class, 'authors');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateAuthorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
        // $author = Author::create([
        //     'name' => $request->input('data.attributes.name'),
        // ]);

        // return (new JSONAPIResource($author))
        //     ->response()
        //     ->header('Location', route('authors.show', ['author' => $author]));

        return $this->service->createResource(Author::class, $request->input('data.attributes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        // return new JSONAPIResource($author);
        return $this->service->fetchResource($author, $author, 'authors');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAuthorRequest  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Author $author)
    {
        // $author->update($request->input('data.attributes'));
        // return new JSONAPIResource($author);

        return $this->service->updateResource($author, $request->input('data.attributes'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        // $author->delete();
        // return response(null, 204);

        return $this->service->deleteResource($author);
    }
}
