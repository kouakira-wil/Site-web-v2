<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Messages;

use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Messages')]
class MessageIndex extends Component
{
    use WithPagination;

    #[Url(as: 'statut', except: '')]
    public string $status = '';

    public ?int $openId = null;

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Opens a message inline and marks it read on first view.
     */
    public function open(int $messageId): void
    {
        if ($this->openId === $messageId) {
            $this->openId = null;

            return;
        }

        $this->openId = $messageId;

        ContactMessage::query()->findOrFail($messageId)->markAsRead();
    }

    public function setStatus(int $messageId, string $status): void
    {
        if (! in_array($status, [
            ContactMessage::STATUS_NEW,
            ContactMessage::STATUS_READ,
            ContactMessage::STATUS_REPLIED,
            ContactMessage::STATUS_ARCHIVED,
        ], true)) {
            return;
        }

        ContactMessage::query()->findOrFail($messageId)->update(['status' => $status]);
    }

    public function delete(int $messageId): void
    {
        ContactMessage::query()->findOrFail($messageId)->delete();

        if ($this->openId === $messageId) {
            $this->openId = null;
        }
    }

    public function render(): View
    {
        $messages = ContactMessage::query()
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.messages.index', [
            'messages' => $messages,
        ]);
    }
}
