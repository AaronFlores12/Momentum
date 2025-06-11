<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear un usuario administrador para pruebas
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Crear 10 usuarios
        $users = User::factory(10)->create();

        // Para cada usuario
        $users->each(function ($user) {
            // Cada usuario tendrá entre 1 y 3 posts
            $posts = Post::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);

            // Por cada post, crear entre 2 y 5 comentarios
            $posts->each(function ($post) {
                // Los comentarios pueden venir de cualquier usuario
                $commenters = User::inRandomOrder()->take(rand(2, 5))->get();

                foreach ($commenters as $commenter) {
                    Comment::factory()->create([
                        'post_id' => $post->id,
                        'user_id' => $commenter->id,
                    ]);
                }
            });
        });

        // Asegurarse de que el usuario admin también tenga posts
        $adminUser = User::where('email', 'admin@example.com')->first();
        
        // Crear 2 posts para el admin
        $adminPosts = Post::factory(2)->create([
            'user_id' => $adminUser->id,
        ]);

        // Agregar comentarios a los posts del admin
        $adminPosts->each(function ($post) {
            $commenters = User::where('email', '!=', 'admin@example.com')
                ->inRandomOrder()
                ->take(rand(3, 6))
                ->get();

            foreach ($commenters as $commenter) {
                Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $commenter->id,
                ]);
            }
        });
    }
}