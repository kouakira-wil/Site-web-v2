<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class TeamController extends Controller
{
    public function __invoke(): View
    {
        return view('site.team', [
            'members' => TeamMember::query()->active()->ordered()->get(),
        ]);
    }
}
