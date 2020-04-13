<?php

namespace EPink\Blog\Tests\Feature\CRUD;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use EPink\Blog\Models\Category;

class CategoryCRUDTest extends TestCase
{

    use RefreshDatabase;
    
    protected $endpoint = '/api/v1/blog/categories';

    protected $model_structure = [
        'category' => [
            'id',
            'name',
            'description',
            'created_at',
            'updated_at'
        ]
    ];

    /**
     * Index category API endpoint test
     * 
     * @group blog-category-crud
     * @return void
     */
    public function testIndexCategory()
    {
        $response = $this->withHeaders($this->apiHeaders)->get($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories'
            ]);
    }

    /**
     * Create category API endpoint test
     *
     * @group blog-category-crud
     * @return void
     */
    public function testCreateCategory()
    {
        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            'name' => 'My test category',
            'description' => 'My category description'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Read category API endpoint test
     * 
     * @group blog-category-crud
     * @return void
     */
    public function testReadCategory()
    {
        $category = factory(Category::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->get("$this->endpoint/$category->id");

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Update category API endpoint test
     * 
     * @group blog-category-crud
     * @return void
     */
    public function testUpdateCategory()
    {

        $category = factory(Category::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$category->id", [
            'name' => 'My updated category name'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure($this->model_structure);
    }

    /**
     * Delete category API endpoint test
     * 
     * @group blog-category-crud
     * @return void
     */
    public function testDeleteCategory()
    {
        $category = factory(Category::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->delete("$this->endpoint/$category->id");

        $response->assertStatus(200)
            ->assertJsonStructure(['category_posts']);
    }

    /**
     * Can't create category with duplicated name
     * 
     * @group blog-category-crud
     * @return void
     */
    public function testCreateCategoryDuplicatedName()
    {
        $category = factory(Category::class)->create();

        $response = $this->withHeaders($this->apiHeaders)->postJson($this->endpoint, [
            'name' => $category->name
        ]);

        $response->assertStatus(422);
    }

    /**
     * Can't update category to duplicated name
     */
    public function testUpdateCategoryDuplicatedName()
    {
        $category1 = factory(Category::class)->create([
            'name' => 'Category 1'
        ]);
        $category2 = factory(Category::class)->create([
            'name' => 'Category 2'
        ]);

        $response = $this->withHeaders($this->apiHeaders)->putJson("$this->endpoint/$category2->id", [
            'name' => $category1->name
        ]);

        $response->assertStatus(422);
    }
}
