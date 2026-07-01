<?php

namespace App\Services\Admin\GlobalSearch\Providers;

use App\Models\User;
use App\Services\Admin\GlobalSearch\SearchProviderInterface;

class UserSearchProvider implements SearchProviderInterface
{
    public function search(string $query, int $limit): array
    {
        $users = User::where('username', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->select('id_users', 'username as title')
            ->limit($limit)
            ->get();

        return $users->map(function ($item) {
            return [
                'type' => 'User',
                'title' => $item->title,
                'url' => route('admin.users.edit', $item->id_users),
                'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />',
            ];
        })->toArray();
    }
}
