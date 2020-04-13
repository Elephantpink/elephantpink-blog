<?php

namespace EPink\Blog\Tests\Feature\CRUD;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use EPink\Blog\Models\Author;
use EPink\Blog\Models\Post;

class PostCRUDTest extends TestCase
{

    use RefreshDatabase;
    
    protected $endpoint = '/api/v1/blog/posts';

    protected $model_structure = [
        'post' => [
            'id',
            'title',
            'subtitle',
            'thumbnail_url',
            'body',
            'slug',
            'author_id',
            'publish_date',
            'created_at',
            'updated_at'
        ]
    ];

    /**
     * Index post API endpoint test
     * 
     * @group blog-post-crud
     * @return void
     */
    public function testIndexPost()
    {
        $response = $this->withHeaders($this->apiHeaders)->get($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'posts'
            ]);
    }

    /**
     * Create post API endpoint test
     *
     * @group blog-post-crud
     * @return void
     */
    public function testCreatePost()
    {
        $author = factory(Author::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            "title" => "My post title",
            "subtitle" => "My post subtitle",
            "thumbnail_url" => "fakeroute.jpg",
            "body" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "slug" => "my-post-title",
            "author_id" => $author->id,
            "publish_date" => date("Y-m-d H:i:s")
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Read post API endpoint test
     * 
     * @group blog-post-crud
     * @return void
     */
    public function testReadPost()
    {
        $author = factory(Author::class)->create();
        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $response = $this->withHeaders($this->apiHeaders)->get("$this->endpoint/$post->id");

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Update post API endpoint test
     * 
     * @group blog-post-crud
     * @return void
     */
    public function testUpdatePost()
    {
        $author = factory(Author::class)->create();

        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$post->id", [
            "title" => "My post title updated",
            "subtitle" => "My post subtitle updated",
            "thumbnail_url" => "updatedroute.jpg",
            "excerpt" => "Updated lorem ipsum dolor sit amet, consectetur adipiscing elit...",
            "body" => "Updated lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "slug" => "my-post-title-updated",
            "author_id" => $author->id,
            "publish_date" => date("Y-m-d H:i:s")
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Delete post API endpoint test
     * 
     * @group blog-post-crud
     * @return void
     */
    public function testDeletePost()
    {
        $author = factory(Author::class)->create();
        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $response = $this->withHeaders($this->apiHeaders)->delete("$this->endpoint/$post->id");

        $response->assertStatus(204);
    }

    /**
     * Can't create post with duplicated slug
     * 
     * @group blog-post-crud
     * @return void
     */
    public function testCreatePostDuplicatedSlug()
    {
        $author = factory(Author::class)->create();
        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            "title" => "My post title",
            "subtitle" => "My post subtitle",
            "thumbnail_url" => "fakeroute.jpg",
            "excerpt" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit...",
            "body" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
                        sunt in culpa qui officia deserunt mollit anim id est laborum.",
            "slug" => $post->slug,
            "author_id" => $author->id,
            "publish_date" => date("Y-m-d H:i:s")
        ]);

        $response->assertStatus(422);
    }

    /**
     * Can't update category to duplicated name
     */
    public function testUpdateCategoryDuplicatedSlug()
    {
        $author = factory(Author::class)->create();
        $post1 = factory(Post::class)->create([
            'slug' => 'slug-1',
            'author_id' => $author->id
        ]);
        $post2 = factory(Post::class)->create([
            'slug' => 'slug-2',
            'author_id' => $author->id
        ]);

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$post2->id", [
            'slug' => $post1->slug
        ]);

        $response->assertStatus(422);
    }
}
