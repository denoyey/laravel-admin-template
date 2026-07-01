<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTable extends Component
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

        $query = Activity::with('causer');

        if (! empty($this->search)) {
            $searchEscaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $this->search);

            $query->where(function ($q) use ($searchEscaped) {
                $q->where('description', 'like', "%{$searchEscaped}%")
                    ->orWhere('event', 'like', "%{$searchEscaped}%")
                    ->orWhere('subject_type', 'like', "%{$searchEscaped}%")
                    ->orWhereHas('causer', function ($qCauser) use ($searchEscaped) {
                        $qCauser->where('username', 'like', "%{$searchEscaped}%")
                            ->orWhere('email', 'like', "%{$searchEscaped}%");
                    });
            });
        }

        $activities = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.activity-log-table', [
            'activities' => $activities,
        ]);
    }
}
