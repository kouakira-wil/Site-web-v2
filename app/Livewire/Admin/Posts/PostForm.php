<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PostForm extends Component
{
    use WithFileUploads;

    public ?Post $post = null;

    public ?int $category_id = null;

    public string $title = '';

    public string $slug = '';

    public string $excerpt = '';

    public string $body = '';

    public string $status = Post::STATUS_DRAFT;

    public ?string $published_at = null;

    /** @var array<int, int> */
    public array $selectedTags = [];

    public ?TemporaryUploadedFile $cover = null;

    /**
     * Images ajoutées sous l'image principale.
     *
     * @var array<int, TemporaryUploadedFile>
     */
    public array $gallery = [];

    public function mount(?Post $post = null): void
    {
        if ($post?->exists !== true) {
            $this->published_at = now()->format('Y-m-d\TH:i');

            return;
        }

        $this->post = $post;
        $this->fill($post->only(['category_id', 'title', 'slug', 'excerpt', 'body', 'status']));
        $this->published_at = $post->published_at?->format('Y-m-d\TH:i');
        $this->selectedTags = $post->tags->pluck('id')->all();
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    protected function rules(): array
    {
        $ignore = $this->post?->getKey();

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', 'unique:posts,slug'.($ignore ? ','.$ignore : '')],
            'category_id' => ['nullable', 'exists:categories,id'],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'body' => ['nullable', 'string', 'max:60000'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'selectedTags' => ['array'],
            'selectedTags.*' => ['exists:tags,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'title' => 'titre',
            'slug' => 'identifiant d’URL',
            'category_id' => 'catégorie',
            'excerpt' => 'accroche',
            'body' => 'contenu',
            'cover' => 'image de couverture',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $post = $this->post ?? new Post(['user_id' => auth()->id()]);

        $post->fill([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'excerpt' => $data['excerpt'],
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['published_at'],
        ]);

        if (filled($data['slug'])) {
            $post->slug = $data['slug'];
        }

        if ($this->cover !== null) {
            if (filled($post->cover_image)) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $post->cover_image = $this->cover->store('articles/couvertures', 'public');
        }

        $post->save();
        $post->tags()->sync($data['selectedTags']);

        $this->storeGalleryUploads($post);

        $this->post = $post->refresh();
        $this->slug = $post->slug;
        $this->reset('cover', 'gallery');

        session()->flash('status', 'Article enregistré.');

        $this->redirectRoute('admin.posts.edit', $post, navigate: true);
    }

    public function removeImage(int $imageId): void
    {
        $image = $this->post?->images()->whereKey($imageId)->first();

        if ($image === null) {
            return;
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $this->post->refresh();
    }

    private function storeGalleryUploads(Post $post): void
    {
        if ($this->gallery === []) {
            return;
        }

        $nextPosition = (int) $post->images()->max('position') + 1;

        foreach ($this->gallery as $file) {
            PostImage::query()->create([
                'post_id' => $post->id,
                'path' => $file->store('actualites/galerie', 'public'),
                'caption' => $post->title,
                'position' => $nextPosition++,
            ]);
        }
    }

    public function removeCover(): void
    {
        if ($this->post?->cover_image === null) {
            return;
        }

        Storage::disk('public')->delete($this->post->cover_image);
        $this->post->update(['cover_image' => null]);
        $this->post->refresh();
    }

    public function render(): View
    {
        return view('livewire.admin.posts.form', [
            'categories' => Category::query()->ofType(Category::TYPE_POST)->ordered()->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
            'pageTitle' => $this->post?->exists ? 'Modifier l’article' : 'Nouvel article',
        ]);
    }
}
