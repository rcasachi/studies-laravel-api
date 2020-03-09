<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use App\Comment;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CommentsRelationshipsTest extends TestCase {
    use DatabaseMigrations;

    /**
     * @test
     */
    public function when_creating_a_comment_it_can_also_add_relationships_right_away() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $book = factory(Book::class)->create();

        $this->postJson('/api/v1/comments', [
            'data' => [
                'type' => 'comments',
                'attributes' => [
                    'message' => 'Hello world',
                ],
                'relationships' => [
                    'users' => [
                        'data' => [
                            'id' => $user->id,
                            'type' => 'users',
                        ]
                    ],
                    'books' => [
                        'data' => [
                            'id' => (string)$book->id,
                            'type' => 'books',
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(201)
        ->assertJson([
            "data" => [
                "id" => '1',
                "type" => 'comments',
                "attributes" => [
                    'message' => 'Hello world',
                    'created_at' => now()->setMilliseconds(0)->toJSON(),
                    'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                ],
                'relationships' => [
                    'books' => [
                        'links' => [
                            'self' => route('comments.relationships.books', ['id' => 1]),
                            'related' => route('comments.books', ['id' => 1]),
                        ],
                        'data' => [
                            'id' => $book->id,
                            'type' => 'books',
                        ]
                    ],
                    'users' => [
                        'links' => [
                            'self' => route('comments.relationships.users', ['id' => 1]),
                            'related' => route('comments.users', ['id' => 1]),
                        ],
                        'data' => [
                            'id' => $user->id,
                            'type' => 'users',
                        ]
                    ]
                ]
            ]
        ])->assertHeader('Location', url('/api/v1/comments/1'));

        $this->assertDatabaseHas('comments', [
            'id' => 1,
            'message' => 'Hello world',
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * @test
     */
    public function it_validates_relationships_given_when_creating_comment() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $book = factory(Book::class)->create();

        $this->postJson('/api/v1/comments', [
            'data' => [
                'type' => 'comments',
                'attributes' => [
                    'message' => 'Hello world',
                ],
                'relationships' => [
                    'users' => [],
                    'books' => [
                        'data' => [
                            'id' => 1,
                            'type' => 'random',
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.users.data field is required.',
                    'source' => [
                        'pointer' => '/data/relationships/users/data',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.books.data.id must be a string.',
                    'source' => [
                        'pointer' => '/data/relationships/books/data/id',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.relationships.books.data.type is invalid.',
                    'source' => [
                        'pointer' => '/data/relationships/books/data/type',
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function when_updating_a_comment_it_can_also_update_relationships() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $comment = factory(Comment::class)->make();
        $user->comments()->save($comment);
        $book = factory(Book::class)->create();
        $book->comments()->save($comment);
        $anotherUser = factory(User::class)->create();
        $anotherBook = factory(Book::class)->create();

        $this->patchJson('/api/v1/comments/1', [
            'data' => [
                'id' => (string)$comment->id,
                'type' => 'comments',
                'attributes' => [
                    'message' => 'Hello world',
                ],
                'relationships' => [
                    'users' => [
                        'data' => [
                            'id' => $anotherUser->id,
                            'type' => 'users',
                        ]
                    ],
                    'books' => [
                        'data' => [
                            'id' => (string)$anotherBook->id,
                            'type' => 'books',
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => '1',
                "type" => 'comments',
                "attributes" => [
                    'message' => 'Hello world',
                    'created_at' => now()->setMilliseconds(0)->toJSON(),
                    'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                ],
                'relationships' => [
                    'books' => [
                        'links' => [
                            'self' => route('comments.relationships.books', ['id' => 1]),
                            'related' => route('comments.books', ['id' => 1]),
                        ],
                        'data' => [
                            'id' => $anotherBook->id,
                            'type' => 'books',
                        ]
                    ],
                    'users' => [
                        'links' => [
                            'self' => route('comments.relationships.users', ['id' => 1]),
                            'related' => route('comments.users', ['id' => 1]),
                        ],
                        'data' => [
                            'id' => $anotherUser->id,
                            'type' => 'users',
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('comments', [
            'id' => 1,
            'message' => 'Hello world',
            'user_id' => $anotherUser->id,
            'book_id' => $anotherBook->id,
        ]);
    }

    /**
     * @test
     */
    public function it_validates_relationships_given_when_updating_comment() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $comment = factory(Comment::class)->make();
        $user->comments()->save($comment);
        $book = factory(Book::class)->create();
        $book->comments()->save($comment);

        $this->patchJson('/api/v1/comments/1', [
            'data' => [
                'id' => (string)$comment->id,
                'type' => 'comments',
                'attributes' => [
                    'message' => 'Hello world',
                ],
                'relationships' => [
                    'users' => [],
                    'books' => [
                        'data' => [
                            'id' => 1,
                            'type' => 'random',
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.users.data field is required.',
                    'source' => [
                        'pointer' => '/data/relationships/users/data',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.books.data.id must be a string.',
                    'source' => [
                        'pointer' => '/data/relationships/books/data/id',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.relationships.books.data.type is invalid.',
                    'source' => [
                        'pointer' => '/data/relationships/books/data/type',
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function when_creating_a_book_it_can_also_add_relationships_right_away() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $authors = factory(Author::class, 2)->create();
        $this->postJson('/api/v1/books', [
            'data' => [
                'type' => 'books',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                ],
                'relationships' => [
                    'authors' => [
                        'data' => [
                            [
                                'id' => (string)$authors[0]->id,
                                'type' => 'authors',
                            ],
                            [
                                'id' => (string)$authors[1]->id,
                                'type' => 'authors',
                            ],
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(201)
        ->assertJson([
            "data" => [
                "id" => '1',
                "type" => 'books',
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                    'created_at' => now()->setMilliseconds(0)->toJSON(),
                    'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                ],
                'relationships' => [
                    'authors' => [
                        'links' => [
                            'self' => route('books.relationships.authors', ['id' => 1]),
                            'related' => route('books.authors', ['id' => 1]),
                        ],
                        'data' => [
                            [
                                'id' => $authors->get(0)->id,
                                'type' => 'authors'
                            ],
                                ['id' => $authors->get(1)->id,
                                'type' => 'authors'
                            ]
                        ]
                    ]
                ]
            ]
        ])
        ->assertHeader('Location', url('/api/v1/books/1'));

        $this->assertDatabaseHas('books', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
        ])
        ->assertDatabaseHas('author_book', [
            'book_id' => 1,
            'author_id' => $authors[0]->id,
        ]);
    }

    /**
     * @test
     */
    public function it_validates_relationships_given_when_creating_book() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $authors = factory(Author::class, 2)->create();
        $this->postJson('/api/v1/books', [
            'data' => [
                'type' => 'books',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                ],
                'relationships' => [
                    'authors' => [
                        'data' => [
                                [
                                'id' => $authors[1]->id,
                                'type' => 'authors',
                            ],
                            [
                                'id' => (string) $authors[1]->id,
                                'type' => 'random',
                            ],
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.authors.data.0.id must be a string.',
                    'source' => [
                        'pointer' => '/data/relationships/authors/data/0/id',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.relationships.authors.data.1.type is invalid.',
                    'source' => [
                        'pointer' => '/data/relationships/authors/data/1/type',
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function when_updating_a_book_it_can_also_update_relationships() {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 3)->create();
        $book->authors()->sync($authors->pluck('id'));

        $this->patchJson('/api/v1/books/1', [
            'data' => [
                'id' => '1',
                'type' => 'books',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                ],
                'relationships' => [
                    'authors' => [
                        'data' => [
                            [
                                'id' => (string) $authors[2]->id,
                                'type' => 'authors',
                            ],
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => '1',
                "type" => 'books',
                "attributes" => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                    'created_at' => now()->setMilliseconds(0)->toJSON(),
                    'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                ],
                'relationships' => [
                    'authors' => [
                        'links' => [
                            'self' => route('books.relationships.authors', ['id' => 1]),
                            'related' => route('books.authors', ['id' => 1]),
                        ],
                        'data' => [
                            [
                                'id' => $authors->get(2)->id,
                                'type' => 'authors'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('books', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
        ])->assertDatabaseHas('author_book', [
            'book_id' => 1,
            'author_id' => $authors[2]->id,
        ]);
    }

    /**
     * @test
     */
    public function it_validates_relationships_given_when_updating_a_book() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 3)->create();
        $book->authors()->sync($authors->pluck('id'));

        $this->patchJson('/api/v1/books/1', [
            'data' => [
                'id' => '1',
                'type' => 'books',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A book about API development',
                    'publication_year' => '2019',
                ],
                'relationships' => [
                    'authors' => [
                        'data' => [
                            [
                                'id' => $authors[1]->id,
                                'type' => 'authors',
                            ],
                            [
                                'id' => (string) $authors[1]->id,
                                'type' => 'random',
                            ],
                        ]
                    ]
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                [
                    'title' => 'Validation Error',
                    'details' => 'The data.relationships.authors.data.0.id must be a string.',
                    'source' => [
                        'pointer' => '/data/relationships/authors/data/0/id',
                    ]
                ],
                [
                    'title' => 'Validation Error',
                    'details' => 'The selected data.relationships.authors.data.1.type is invalid.',
                    'source' => [
                        'pointer' => '/data/relationships/authors/data/1/type',
                    ]
                ],
            ]
        ]);
    }
}
