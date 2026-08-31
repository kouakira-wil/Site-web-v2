<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('site.team', [
            'members' => TeamMember::query()->active()->ordered()->get(),
        ]);
    }

    public function show(TeamMember $member): View
    {
        abort_unless($member->is_active, 404);

        return view('site.team-show', [
            'member' => $member,
            'others' => TeamMember::query()
                ->active()
                ->whereKeyNot($member->getKey())
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }
}
