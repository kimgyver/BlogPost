<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $jinyoung = App\User::find(1);
        $else = factory(App\User::class, 20)->create();
        $users = $else->concat([$jinyoung]);

        if (App\BlogPost::all()->count() == 0)
        {
        $posts = factory(App\BlogPost::class, 50)->make()->each(function($post) use($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        $comments = factory(App\Comment::class, 150)->make()->each(function($comment) use($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });

        $this->call([
            TagsTableSeeder::class,
            BlogPostTagTableSeeder::class,
        ]);
        

        $posts = App\BlogPost::all();
        $comments = App\Comment::all()->each(function($comment) use($posts, $users) {
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
        }

        $this->call([
            CommentsPolymorhsSeeder::class,
        ]);
    }
}
