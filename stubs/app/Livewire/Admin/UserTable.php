<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class UserTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(as: 'per_page', except: 10, history: true)]
    public int $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($this->perPage, $allowedPerPage)) {
            $this->perPage = 10;
        }

        $query = User::with('roles');

        if (!empty($this->search)) {
            $searchEscaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $this->search);

            $query->where(function ($q) use ($searchEscaped) {
                $q->where('username', 'like', "%{$searchEscaped}%")
                  ->orWhere('email', 'like', "%{$searchEscaped}%");
            });
        }

        $users = $query->paginate($this->perPage);

        return view('livewire.admin.user-table', [
            'users' => $users
        ]);
    }
}
