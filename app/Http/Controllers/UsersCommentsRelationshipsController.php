<?php

namespace App\Http\Controllers;

use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Services\JSONApiService;
use App\User;
use Illuminate\Http\Request;

class UsersCommentsRelationshipsController extends Controller
{
    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
    }

    public function index(User $user) {
        return $this->service->fetchRelationship($user, 'comments');
    }

    public function update(JSONAPIRelationshipRequest $request, User $user) {
        return $this->service->updateToManyRelationships($user, 'comments', $request->input('data.*.id'));
    }
}
