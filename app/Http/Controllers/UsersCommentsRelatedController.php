<?php

namespace App\Http\Controllers;

use App\Services\JSONApiService;
use App\User;
use Illuminate\Http\Request;

class UsersCommentsRelatedController extends Controller
{
    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
    }

    public function index(User $user) {
        return $this->service->fetchRelated($user, 'comments');
    }
}
