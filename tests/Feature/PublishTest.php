<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use EPink\Blog\Models\Author;
use EPink\Blog\Models\Category;
use EPink\Blog\Models\Post;
use EPink\Blog\Models\PostCategory;
use EPink\Blog\Models\PostTag;
use EPink\Blog\Models\Tag;

class PublishTest extends TestCase
{
    use RefreshDatabase;

    protected $endpoint = '/api/v1/blog/post';

    /**
     * BlogPublishController@publishPost test
     * 
     * @group blog-publish
     * @return void
     */
    public function testPublishPost()
    {
        $author = factory(Author::class)->create();
        $post = [
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
        ];

        $categories = [
            factory(Category::class)->create([
                'name' => 'Category 1'
            ])->id,
            factory(Category::class)->create([
                'name' => 'Category 2'
            ])->id
        ];

        $tags = [
            factory(Tag::class)->create([
                'name' => 'Tag 1'
            ])->id,
            factory(Tag::class)->create([
                'name' => 'Tag 2'
            ])->id,
            factory(Tag::class)->create([
                'name' => 'Tag 3'
            ])->id
        ];

        $response = $this->withHeaders($this->apiHeaders)->postJson("$this->endpoint/publish", [
            'post' => $post,
            'categories' => json_encode($categories),
            'tags' => json_encode($tags)
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
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
            ]);
    }

    /**
     * BlogPublishController@editPostCategories test
     * Test removing all categories and adding a new one
     * 
     * @group blog-publish
     * @return void
     */
    public function testEditPostCategories()
    {
        $author = factory(Author::class)->create();
        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $categories = [
            factory(Category::class)->create([
                'name' => 'Category 1'
            ])->id,
            factory(Category::class)->create([
                'name' => 'Category 2'
            ])->id
        ];

        foreach ($categories as $category) {
            PostCategory::create([
                'post_id' => $post->id,
                'category_id' => $category
            ]);
        }

        $new_category = factory(Category::class)->create([
            'name' => 'Category 3'
        ]);

        $response = $this->withHeaders($this->apiHeaders)->postJson("$this->endpoint/categories", [
            'post_id' => $post->id,
            'categories' => [$new_category->id]
        ]);

        $response->assertStatus(204);
    }
    
    /**
     * BlogPublishController@editPostTags test
     * Test removing all tags and adding a new one
     * 
     * @group blog-publish
     * @return void
     */
    public function testEditPostTags()
    {

        $author = factory(Author::class)->create();
        $post = factory(Post::class)->create([
            'author_id' => $author->id
        ]);

        $tags = [
            factory(Tag::class)->create([
                'name' => 'Tag 1'
            ])->id,
            factory(Tag::class)->create([
                'name' => 'Tag 2'
            ])->id
        ];

        foreach ($tags as $tag) {
            PostTag::create([
                'post_id' => $post->id,
                'tag_id' => $tag
            ]);
        }

        $new_tag = factory(Tag::class)->create([
            'name' => 'Tag 3'
        ]);

        $response = $this->withHeaders($this->apiHeaders)->postJson("$this->endpoint/tags", [
            'post_id' => $post->id,
            'tags' => [$new_tag->id]
        ]);

        $response->assertStatus(204);
    }
}
