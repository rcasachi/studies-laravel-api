<?php

namespace App\Http\Controllers;

use App\Author;
use App\Services\JSONApiService;
use Illuminate\Http\Request;

class AuthorsBooksRelatedController extends Controller
{
    private $service;

    public function __construct(JSONApiService $service) {
        $this->service = $service;
    }

    public function index(Author $author) {
        return $this->service->fetchRelated($author, 'books');
    }
}
