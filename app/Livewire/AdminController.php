<?php

namespace App\Livewire;

use App\Enums\PreRegistrationStatus;
use App\Models\Candidate;
use App\Models\PreRegistration;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;


class AdminController extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';


public int $preRegPage = 1;
public int $userPage = 1;

protected $scrollToTop = false;

    public $candidates = [];
    public $searchReg = '';
    public $userSearch = '';
    public $countPending = 0;
    public $countApproved = 0;
    public $countVote = 0;
    public $candidateVotes=[];

    public function updatedSearchReg()
    {
        $this->resetPage('preRegPage');
    }



    public function updatedUserSearch()
    {
        $this->resetPage('userPage');
    }


    public function loadCounts()
    {
        $this->countPending = PreRegistration::where('status', 'pending')->count();
        $this->countApproved = PreRegistration::where('status', 'approved')->count();
        $this->countVote=User::where('has_voted',true)->count();
    }


    public function mount()
    {
        $this->candidates = Candidate::all();
        $this->loadCounts();
    }

    protected function userSearchQuery()
    {
        return User::query()
            ->where('role', '!=', 'admin')
            ->when($this->userSearch, function ($q) {
                $q->where(function ($query) {
                    $query->where('mat_no', 'like', '%' . $this->userSearch . '%')
                        ->orWhere('username', 'like', '%' . $this->userSearch . '%');
                });
            });
    }

    public function deleteUser($id)
    {
        $candidate = Candidate::find($id);
        $candidate->delete();
    }

    public function verifyUsers($id)
    {
        $user = User::with('preRegistration')
            ->where('id', $id)
            ->whereHas('preRegistration', fn($q) =>
            $q->where('status', PreRegistrationStatus::REGISTERED))
            ->firstOrFail();

        $user->preRegistration->update([
            'status' => PreRegistrationStatus::APPROVED
        ]);
    }

    protected function getPreRegistrationQuery()
    {
        return PreRegistration::query()
            ->when($this->searchReg, function ($q) {
                $q->where(function ($query) {
                    $query->where('mat_no', 'like', '%' . $this->searchReg . '%')
                        ->orWhere('full_name', 'like', '%' . $this->searchReg . '%');
                });
            });
    }

    public function render()
    {
        $preUsers = $this->getPreRegistrationQuery()->paginate(5, ['*'], 'preRegPage');
        $users = $this->userSearchQuery()->paginate(5, ['*'], 'userPage');

        return view('livewire.admin-controller', [
            'preUsers' => $preUsers,
            'users' => $users,
        ]);
    }
}
