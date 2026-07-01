<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RoleTable extends Component
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
        if (! in_array($this->perPage, $allowedPerPage)) {
            $this->perPage = 10;
        }

        $query = Role::withCount('users', 'permissions');

        if (! empty($this->search)) {
            $searchEscaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $this->search);

            $query->where('name', 'like', "%{$searchEscaped}%");
        }

        $roles = $query->paginate($this->perPage);

        return view('livewire.admin.role-table', [
            'roles' => $roles,
        ]);
    }
}
