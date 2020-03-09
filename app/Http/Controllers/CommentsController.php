<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\JSONAPIRequest;
use App\Services\JSONApiService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * @var JSONAPIService
     */
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
        return $this->service->fetchResources(Comment::class, 'comments');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
        return $this->service->createResource(Comment::class, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($comment)
    {
        return $this->service->fetchResource(Comment::class, $comment, 'comments');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Comment $comment)
    {
        return $this->service->updateResource($comment, $request->input('data.attributes'), $request->input('data.relationships'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        return $this->service->deleteResource($comment);
    }
}
