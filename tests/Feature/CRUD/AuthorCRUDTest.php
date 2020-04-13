<?php

namespace EPink\Blog\Tests\Feature\CRUD;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use EPink\Blog\Models\Author;

class AuthorCRUDTest extends TestCase
{

    use RefreshDatabase;

    protected $endpoint = "/api/v1/blog/authors";

    protected $model_structure = [
        "author" => [
            "id",
            "name",
            "email"
        ]
    ];

    /**
     * Index author API endpoint test
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testIndexAuthor()
    {
        $response = $this->withHeaders($this->apiHeaders)->get($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "authors"
            ]);
    }

    /**
     * Create author API endpoint test
     *
     * @group blog-author-crud
     * @return void
     */
    public function testCreateAuthor()
    {
        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            "name" => "My test author",
            "email" => "author@mail.com",
            "password" => "my-password"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Read author API endpoint test
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testReadAuthor()
    {
        $author = factory(Author::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->get("$this->endpoint/$author->id");

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Update author API endpoint test
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testUpdateAuthor()
    {

        $author = factory(Author::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$author->id", [
            "name" => "My updated name",
            "email" => "updated@mailcom"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Delete author API endpoint test
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testDeleteAuthor()
    {
        $author = factory(Author::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->delete("$this->endpoint/$author->id");

        $response->assertStatus(204);
    }

    /**
     * Can't create an author with duplicated email
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testCreateAuthorDuplicatedEmail()
    {
        $author = factory(Author::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, $author->toArray());
        
        $response->assertStatus(422);
    }

    /**
     * Can't update an author to a duplicated email
     * 
     * @group blog-author-crud
     * @return void
     */
    public function testUpdateAuthorDuplicatedEmail()
    {
        $author1 = factory(Author::class)->create([
            'email' => 'first@mail.com'
        ]);
        $author2 = factory(Author::class)->create([
            'email' => 'second@mail.com'
        ]);

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$author2->id", [
            "name" => $author2->name,
            "email" => $author1->email
        ]);

        $response->assertStatus(422);
    }

}
