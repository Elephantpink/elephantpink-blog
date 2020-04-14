<?php

use Illuminate\Database\Seeder;
use EPink\Blog\Models\Author;
use EPink\Blog\Models\Category;
use EPink\Blog\Models\Post;
use EPink\Blog\Models\PostCategory;
use EPink\Blog\Models\PostTag;
use EPink\Blog\Models\Tag;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_author = Author::create([
            'email' => 'demo@elephantpink.es',
            'name' => 'Demo user',
            'additional_information' => 'Demo user account created on database seed',
            'password' => bcrypt('password'),
            'is_admin' => 1,
            'is_disabled' => 0
        ]);

        $tech_category = Category::create([
            'name' => 'Technology',
            'description' => 'Technology category posts'
        ]);

        $news_category = Category::create([
            'name' => 'News',
            'description' => 'News category posts'
        ]);

        $mobile_tag = Tag::create([
            'name' => 'Mobile',
            'description' => 'Technology mobile tag',
        ]);

        $event_tag = Tag::create([
            'name' => 'Event',
            'description' => 'Event tag'
        ]);

        $europe_tag = Tag::create([
            'name' => 'Europe'
        ]);

        $post1 = Post::create([
            'title' => 'Lorem ipsum',
            'slug' => 'lorem-ipsum',
            'subtitle' => 'Dolor sit amet',
            'excerpt' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'thumbnail_url' => '/storage/blog_demo_images/post1_thumb.png',
            'header_image_url' => '/storage/blog_demo_images/post1_cover.jpg',
            'author_id' => $default_author->id,
            'body' => '<h3>The standard Lorem Ipsum passage, used since the 1500s</h3>
            <p class="ql-align-justify">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed 
            do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis 
            aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla 
            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia 
            deserunt mollit anim id est laborum."</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><h3>Section 1.10.32 of "de Finibus Bonorum et 
            Malorum", written by Cicero in 45 BC</h3><p class="ql-align-justify">"Sed ut perspiciatis 
            unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem 
            aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae 
            dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit 
            aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. 
            Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci 
            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam 
            quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis 
            suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure 
            reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum 
            qui dolorem eum fugiat quo voluptas nulla pariatur?"</p><p class="ql-align-justify">
            <br></p><p class="ql-align-justify"><br></p><p class="ql-align-justify"><br>
            <p class="ql-align-justify"><br></p><h3>1914 translation by H. Rackham</h3>
            <p class="ql-align-justify">"But I must explain to you how all this mistaken idea of 
            denouncing pleasure and praising pain was born and I will give you a complete account 
            of the system, and expound the actual teachings of the great explorer of the truth, 
            the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure 
            itself, because it is pleasure, but because those who do not know how to pursue pleasure 
            rationally encounter consequences that are extremely painful. Nor again is there anyone 
            who loves or pursues or desires to obtain pain of itself, because it is pain, but 
            because occasionally circumstances occur in which toil and pain can procure him some 
            great pleasure. To take a trivial example, which of us ever undertakes laborious 
            physical exercise, except to obtain some advantage from it? But who has any right to 
            find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, 
            or one who avoids a pain that produces no resultant pleasure?"</p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>
            <h3>Section 1.10.33 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</h3>
            <p class="ql-align-justify">"At vero eos et accusamus et iusto odio dignissimos ducimus 
            qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias 
            excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt 
            mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita 
            distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo 
            minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor 
            repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus 
            saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum 
            rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias 
            consequatur aut perferendis doloribus asperiores repellat."</p>'
        ]);

        PostCategory::create([
            'post_id' => $post1->id,
            'category_id' => $tech_category->id
        ]);

        PostTag::create([
            'post_id' => $post1->id,
            'tag_id' => $mobile_tag->id
        ]);

        PostTag::create([
            'post_id' => $post1->id,
            'tag_id' => $europe_tag->id
        ]);

        $post2 = Post::create([
            'title' => 'Pellentesque vitae faucibus elit',
            'slug' => 'pellentesque-vitae-faucibus-elit',
            'subtitle' => '',
            'excerpt' => 'Pellentesque vitae faucibus elit. Ut mollis vel turpis eu dictum. Mauris ultrices feugiat ante, quis vestibulum lorem porttitor',
            'thumbnail_url' => '/storage/blog_demo_images/post2_thumb.png',
            'header_image_url' => '/storage/blog_demo_images/post2_cover.jpg',
            'author_id' => $default_author->id,
            'body' => '<p class="ql-align-justify">Pellentesque vitae faucibus elit. Ut mollis vel 
            turpis eu dictum. Mauris ultrices feugiat ante, quis vestibulum lorem porttitor ultricies. 
            Ut iaculis eu quam ac ullamcorper. Suspendisse gravida dui dolor, mattis dignissim augue 
            commodo eget. Aliquam dictum lacus et nibh efficitur, at tincidunt nunc placerat. 
            Donec eget est et ex consequat sagittis. Sed tincidunt gravida sem at tincidunt. Morbi 
            tincidunt orci in nisi dignissim, quis consectetur massa feugiat. Vivamus sit amet auctor 
            neque, eget cursus magna.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify">
            <br></p><p class="ql-align-justify">Sed eget lacus vitae dui condimentum dapibus sed quis neque. 
            Mauris tempor lacinia placerat. Nam augue elit, aliquam eu purus sit amet, lobortis pellentesque 
            est. Vivamus vitae tempor nunc, quis mattis elit. Phasellus dignissim, turpis eget sagittis 
            pharetra, est urna maximus justo, eu tincidunt nisl eros non purus. Vivamus hendrerit sollicitudin 
            accumsan. Integer eu massa quis elit ullamcorper accumsan ut at diam. Mauris diam ante, 
            scelerisque eget tellus vitae, tempus porta urna. Fusce et est felis. Suspendisse vel auctor est. 
            Donec posuere mauris viverra, facilisis quam in, porttitor eros. Suspendisse in imperdiet nisl. 
            Donec nulla nibh, tempus eu magna at, scelerisque sodales massa. Proin commodo ante quam, sit 
            amet mattis tellus venenatis non.</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify">In nec ligula ut elit consequat 
            fermentum. Nulla non facilisis enim. Integer sagittis tincidunt nisl. Cras ultricies odio purus, 
            nec faucibus orci rutrum non. Etiam vel quam ultricies, fringilla nisi at, sodales massa. Morbi 
            malesuada eget eros sed scelerisque. Fusce dapibus lacus semper sem convallis, eget volutpat dui 
            elementum. Phasellus consectetur turpis eu consectetur vehicula. Donec cursus erat feugiat 
            porttitor consectetur.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify">In tincidunt egestas leo, et ultrices elit dictum sed. Nunc eget 
            volutpat turpis. Proin venenatis, nibh nec semper euismod, libero felis finibus libero, ac 
            interdum metus arcu in nulla. In tempus arcu quis sem gravida, at vulputate magna blandit. Sed 
            vitae aliquam metus. Duis placerat eget nisl a vulputate. Pellentesque habitant morbi tristique 
            senectus et netus et malesuada fames ac turpis egestas. Nunc aliquam mattis orci, in lacinia est 
            fringilla sed. Vestibulum velit felis, feugiat at ultricies et, egestas vel ipsum.</p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify">Maecenas faucibus quam commodo, ultricies tellus quis, dignissim 
            lorem. Nunc semper, nulla a sollicitudin finibus, lacus enim ornare ipsum, viverra pulvinar dui 
            nisi sed libero. Nam vel pellentesque purus. Quisque dignissim metus vel ex eleifend fringilla. 
            Cras quis congue risus. In vitae rhoncus odio. Cras accumsan arcu vel nunc viverra porttitor. 
            Curabitur mattis augue ligula, eu ultricies elit congue eu. Aliquam sed arcu laoreet, lacinia 
            ex vitae, venenatis nisl. Suspendisse laoreet laoreet mi sed porttitor. Maecenas vel nisl 
            posuere, egestas augue vitae, pulvinar mauris.</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify">Nam eu laoreet risus, non 
            posuere nulla. Maecenas posuere efficitur tellus. Pellentesque quis malesuada ipsum, at 
            condimentum sem. Fusce fermentum vitae justo vitae elementum. Maecenas eu odio dui. Nunc 
            et mi sem. Sed a vestibulum urna. Phasellus vitae nisl finibus, euismod massa a, malesuada 
            libero.</p>'
        ]);

        PostCategory::create([
            'post_id' => $post2->id,
            'category_id' => $news_category->id
        ]);

        PostTag::create([
            'post_id' => $post2->id,
            'tag_id' => $europe_tag->id
        ]);

        $post3 = Post::create([
            'title' => 'Curabitur a velit vel justo euismod commodo.',
            'slug' => 'curabitur-a-velit-vel-justo-euismod-commodo',
            'subtitle' => '',
            'excerpt' => 'Curabitur a velit vel justo euismod commodo. Aliquam gravida rhoncus metus, quis vestibulum nibh porta et. Vestibulum ante ipsum',
            'thumbnail_url' => '/storage/blog_demo_images/post3_thumb.png',
            'header_image_url' => '/storage/blog_demo_images/post3_cover.jpg',
            'author_id' => $default_author->id,
            'body' => '<p class="ql-align-justify">Curabitur a velit vel justo euismod commodo. 
            Aliquam gravida rhoncus metus, quis vestibulum nibh porta et. Vestibulum ante ipsum 
            primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis imperdiet velit 
            quam, at pretium nibh mollis ut. Fusce quis nulla in risus commodo semper. Nulla efficitur 
            nibh eget ex gravida, ac condimentum augue elementum. Maecenas varius purus sit amet 
            rutrum venenatis. Curabitur id erat blandit, faucibus augue eget, suscipit tellus. Aenean 
            enim augue, maximus at leo vitae, hendrerit convallis justo.</p><p class="ql-align-justify">
            <br></p><p class="ql-align-justify"><br></p><p class="ql-align-justify">Nulla sit amet 
            tristique metus, in fringilla libero. Cras eros tortor, posuere sed mauris sit amet, 
            vulputate consequat nibh. Donec tellus libero, vestibulum ut viverra quis, tempor vel magna. 
            Vestibulum accumsan suscipit turpis, eu efficitur nunc facilisis nec. Cras vel rhoncus 
            ipsum. Etiam ligula nibh, accumsan sed est sodales, interdum finibus eros. Vivamus ut odio 
            euismod nunc bibendum laoreet tristique at quam. Nullam mollis vulputate nisi. In euismod 
            lacus at fringilla viverra. Fusce at accumsan nunc. Fusce placerat quam non quam varius 
            rhoncus. Sed commodo, arcu eu tincidunt lobortis, purus velit varius massa, nec pretium 
            dolor nisl et mauris. In non ligula volutpat, interdum metus vel, porttitor odio. Maecenas 
            placerat, eros nec dignissim sodales, tellus nulla sodales dui, in volutpat tortor mauris 
            eu velit. Vestibulum rhoncus nulla et lacinia sodales. Nunc vehicula urna ut urna molestie, 
            vitae ornare ipsum dignissim.</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify">In ut arcu aliquet, varius 
            leo eu, euismod ipsum. Nullam rutrum, tortor et sollicitudin bibendum, enim metus dictum 
            turpis, at venenatis erat massa et arcu. Cras dignissim ut ligula sit amet dignissim. 
            Vivamus ut est mattis, dignissim nisi efficitur, tristique urna. Sed sit amet sollicitudin 
            dolor, ac egestas neque. Pellentesque habitant morbi tristique senectus et netus et 
            malesuada fames ac turpis egestas. Mauris vehicula libero a turpis accumsan consectetur. 
            Etiam luctus nulla sed risus blandit rutrum eget sed diam. Fusce viverra semper dolor 
            mollis commodo. Sed consequat orci a nibh congue, at aliquam enim consectetur.</p>'
        ]);

        PostCategory::create([
            'post_id' => $post3->id,
            'category_id' => $tech_category->id
        ]);

        $post4 = Post::create([
            'title' => 'Suspendisse imperdiet nibh vel sapien venenatis molestie.',
            'slug' => 'suspendisse-imperdiet-nibh-vel-sapien-venenatis-molestie',
            'subtitle' => 'Quisque nunc nisi',
            'excerpt' => 'Suspendisse imperdiet nibh vel sapien venenatis molestie. Quisque nunc nisi, accumsan eu tempor non, consequat sed nisl. Vestibu',
            'thumbnail_url' => '/storage/blog_demo_images/post4_thumb.png',
            'header_image_url' => '/storage/blog_demo_images/post4_cover.jpg',
            'author_id' => $default_author->id,
            'body' => '<p class="ql-align-justify">Suspendisse imperdiet nibh vel sapien venenatis 
            molestie. Quisque nunc nisi, accumsan eu tempor non, consequat sed nisl. Vestibulum eu 
            odio vel elit aliquam auctor. Donec in dictum massa, quis convallis lorem. Praesent at 
            felis eget enim posuere mollis et sed erat. Ut vitae felis eu tellus elementum pharetra 
            nec vitae turpis. Donec nunc nulla, tincidunt eget urna in, laoreet pellentesque risus. 
            Etiam tincidunt magna eu nunc sollicitudin egestas. Vivamus ac placerat ipsum, ut dictum 
            nisl. Sed quis lectus a lorem semper finibus in a magna. Duis iaculis, velit imperdiet 
            tempus tempus, elit est suscipit justo, iaculis egestas dolor mi et est. Curabitur justo 
            lorem, feugiat vel ipsum eget, convallis accumsan tortor. Mauris at metus et libero 
            commodo consequat. Aenean laoreet tellus vitae rutrum laoreet. Suspendisse ullamcorper 
            leo ut tortor euismod, in finibus arcu auctor.</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify">Quisque rhoncus, neque 
            ac eleifend condimentum, lectus leo finibus velit, sed suscipit felis purus et mauris. 
            In nec augue sed lacus posuere imperdiet quis et sem. Praesent vitae purus fringilla est 
            luctus laoreet nec non massa. Cras quis nulla sit amet purus dapibus lobortis quis in 
            enim. Aenean efficitur ut leo non tincidunt. Nam odio sapien, iaculis ut scelerisque 
            vitae, pharetra non diam. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify">Etiam eu ante aliquam, rutrum ex id, feugiat nunc. 
            Cras dapibus justo id libero rutrum, nec congue nisl viverra. Cras et tellus aliquam, 
            vehicula diam hendrerit, pulvinar ante. Pellentesque et lorem lectus. Nunc et commodo 
            nunc. Phasellus accumsan varius nulla convallis volutpat. In efficitur maximus arcu nec 
            eleifend. Sed aliquam accumsan mi non elementum. Pellentesque suscipit pulvinar est, 
            non maximus libero tincidunt eu. Aliquam sed suscipit elit. Nullam imperdiet iaculis 
            dui.</p><p class="ql-align-justify"><br></p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify">Nullam pellentesque egestas libero sit amet commodo. 
            Quisque congue enim eget est pulvinar, sit amet condimentum ex laoreet. Suspendisse 
            eleifend lobortis ante at pharetra. Etiam semper sapien et lorem volutpat consectetur 
            eget eu neque. Nulla egestas eget velit id auctor. Proin in quam tellus. Quisque 
            efficitur, velit et semper posuere, dui metus viverra lorem, sed placerat est magna 
            tempus risus. Pellentesque habitant morbi tristique senectus et netus et malesuada 
            fames ac turpis egestas. Aliquam libero enim, rhoncus id hendrerit id, luctus in 
            turpis. Ut vitae erat in orci dignissim luctus. Pellentesque nec dui imperdiet, 
            ultricies mauris ac, efficitur sem. Integer condimentum tristique dapibus. 
            Suspendisse vehicula dui sed felis porta, ac mollis nisi congue. Maecenas luctus 
            augue est, quis aliquet dui vehicula eu.</p><p class="ql-align-justify"><br></p>
            <p class="ql-align-justify"><br></p><p class="ql-align-justify">Vivamus non vestibulum 
            justo, ut rutrum nisl. Vivamus varius nulla lectus, vel sagittis turpis ullamcorper et. 
            Sed scelerisque mi in metus pharetra, quis consequat velit aliquet. Mauris id arcu 
            tincidunt, convallis quam ac, tincidunt velit. Praesent tincidunt elementum ligula, 
            eu egestas enim varius quis. Nunc quam quam, convallis elementum ante a, semper porta 
            est. Donec eleifend in enim vel pulvinar. Donec ac nisi id dolor venenatis luctus et 
            ac erat. Cras nec lacus blandit, vulputate eros sit amet, tincidunt augue. Aliquam 
            erat volutpat.</p>'
        ]);

        PostTag::create([
            'post_id' => $post4->id,
            'tag_id' => $europe_tag->id
        ]);
    }
}
