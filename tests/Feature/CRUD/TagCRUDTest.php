<?php

namespace EPink\Blog\Tests\Feature\CRUD;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use EPink\Blog\Models\Tag;

class TagCRUDTest extends TestCase
{

    use RefreshDatabase;
    
    protected $endpoint = '/api/v1/blog/tags';

    protected $model_structure = [
        'tag' => [
            'id',
            'name',
            'description',
            'created_at',
            'updated_at'
        ]
    ];

    /**
     * Index tag API endpoint test
     * 
     * @group blog-tag-crud
     * @return void
     */
    public function testIndexTag()
    {
        $response = $this->withHeaders($this->apiHeaders)->get($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'tags'
            ]);
    }

    /**
     * Create tag API endpoint test
     *
     * @group blog-tag-crud
     * @return void
     */
    public function testCreateTag()
    {
        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            'name' => 'My test tag',
            'description' => 'My tag description'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Read tag API endpoint test
     * 
     * @group blog-tag-crud
     * @return void
     */
    public function testReadTag()
    {
        $tag = factory(Tag::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->get("$this->endpoint/$tag->id");

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Update tag API endpoint test
     * 
     * @group blog-tag-crud
     * @return void
     */
    public function testUpdateTag()
    {

        $tag = factory(Tag::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$tag->id", [
            'name' => 'My updated tag name'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Delete tag API endpoint test
     * 
     * @group blog-tag-crud
     * @return void
     */
    public function testDeleteTag()
    {
        $tag = factory(Tag::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->delete("$this->endpoint/$tag->id");

        $response->assertStatus(200)
            ->assertJsonStructure(['tag_posts']);
    }

    /**
     * Can't create tag with duplicated name
     * 
     * @group blog-tag-crud
     * @return void
     */
    public function testCreateTagDuplicatedName()
    {
        $tag = factory(Tag::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            'name' => $tag->name
        ]);

        $response->assertStatus(422);
    }

    /**
     * Can't update tag to duplicated name
     */
    public function testUpdateTagDuplicatedName()
    {
        $tag1 = factory(Tag::class)->create([
            'name' => 'Tag 1'
        ]);
        $tag2 = factory(Tag::class)->create([
            'name' => 'Tag 2'
        ]);

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$tag2->id", [
            'name' => $tag1->name
        ]);

        $response->assertStatus(422);
    }
}
