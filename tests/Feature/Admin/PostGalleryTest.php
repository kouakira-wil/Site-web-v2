<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Posts\PostForm;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PostGalleryTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsStaff(): User
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($user);

        return $user;
    }

    public function test_gallery_images_are_stored_on_the_public_disk(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(PostForm::class)
            ->set('title', 'Actualite illustree')
            ->set('gallery', [
                UploadedFile::fake()->image('une.jpg'),
                UploadedFile::fake()->image('deux.jpg'),
            ])
            ->call('save')
            ->assertHasNoErrors();

        $post = Post::query()->where('title', 'Actualite illustree')->firstOrFail();

        $this->assertCount(2, $post->images);

        foreach ($post->images as $image) {
            Storage::disk('public')->assertExists($image->path);
        }
    }

    public function test_gallery_images_keep_their_order(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        $post = Post::factory()->create();
        PostImage::query()->create(['post_id' => $post->id, 'path' => 'a.webp', 'position' => 5]);

        Livewire::test(PostForm::class, ['post' => $post])
            ->set('gallery', [UploadedFile::fake()->image('suivante.jpg')])
            ->call('save')
            ->assertHasNoErrors();

        // La nouvelle image se place après la dernière, sans réordonner l'existant.
        $this->assertSame([5, 6], $post->refresh()->images->pluck('position')->all());
    }

    public function test_a_gallery_image_can_be_removed(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        $post = Post::factory()->create();
        Storage::disk('public')->put('actualites/galerie/a.webp', 'contenu');
        $image = PostImage::query()->create([
            'post_id' => $post->id,
            'path' => 'actualites/galerie/a.webp',
        ]);

        Livewire::test(PostForm::class, ['post' => $post])
            ->call('removeImage', $image->id);

        $this->assertDatabaseMissing('post_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing('actualites/galerie/a.webp');
    }

    public function test_a_non_image_upload_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(PostForm::class)
            ->set('title', 'Actualite douteuse')
            ->set('gallery', [UploadedFile::fake()->create('script.php', 20)])
            ->call('save')
            ->assertHasErrors('gallery.0');
    }

    public function test_deleting_a_post_removes_its_gallery_rows(): void
    {
        $post = Post::factory()->create();
        $image = PostImage::query()->create(['post_id' => $post->id, 'path' => 'a.webp']);

        $post->forceDelete();

        $this->assertDatabaseMissing('post_images', ['id' => $image->id]);
    }
}
