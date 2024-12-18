<?php

namespace App\Livewire\UserPage;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Usernotnull\Toast\Concerns\WireToast;

class UserList extends Component
{
    use WithPagination, WireToast;

    public $search = '';
    public $perPage = 10;
    public $short = 'name';
    public $order = 'asc';

    //form user
    public $typePassword = 'password';
    public $modalForm = false;
    public $id = '';
    public $name = '';
    public $email = '';
    public $address = '';
    public $password = '';

    //role
    public $modalRole = false;
    public $role_id = '';
    public $role_name = '';
    public $role_permission = [];

    //permission
    public $modalPermission = false;
    public $permission_id = '';
    public $permission_name = '';
    // public $permission_description = '';


    public function sort($field)
    {
        if ($this->short === $field) {
            $this->order = $this->order === 'asc' ? 'desc' : 'asc';
        } else {
            $this->short = $field;
            $this->order = 'asc';
        }
    }

    public function togglePasswordVisibility()
    {
        $this->typePassword = $this->typePassword === 'password' ? 'text' : 'password';
    }

    public function resetForm()
    {
       $this->reset([
           'id',
           'name',
           'email',
           'address',
           'password',
           'role_id',
           'role_name',
           'role_permission',
           'permission_id',
           'permission_name',
        //    'permission_description',
       ]);
    }

    public function toggleForm()
    {
        $this->modalForm = !$this->modalForm;
        $this->resetForm();
    }

    public function toggleModalRole()
    {
        $this->modalRole = !$this->modalRole;
        $this->resetForm();
    }

    public function toggleModalPermission()
    {
        $this->modalPermission = !$this->modalPermission;
        $this->resetForm();
    }

    public function toggleFormEdit($id)
    {
        $user = User::find($id);
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->address = $user->address;

        $this->modalForm = !$this->modalForm;
    }

    public function render()
    {
        return view('livewire.userpage.user-list', [
            'datas' => User::with('roles')->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->orderBy($this->short, $this->order)
                ->paginate($this->perPage),
            'roles' => Role::with('permissions')->latest()->paginate(5),
            'permissions' => Permission::latest()->paginate(5)
        ]);
    }

    //save
    public function save()
    {
        if($this->id != ''){
            $this->update();
        } else {
            $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'address' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            try {
                DB::beginTransaction();
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'address' => $this->address,
                    'password' => $this->password
                ]);

                DB::commit();

                toast()
                    ->success('Data user berhasil ditambahkan', 'Berhasil')
                    ->push();

                $this->resetForm();
                $this->toggleForm();
            } catch (\Throwable $th) {
                DB::rollBack();
                toast()
                    ->danger('Data user gagal ditambahkan', 'Gagal')
                    ->push();
                $this->resetForm();
                $this->toggleForm();
            }
        }

    }

    //update
    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->id],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($this->id);

        try {
            DB::beginTransaction();
            if($this->password != null){
                $user->password = $this->password;
            }

            $user->name = $this->name;
            $user->email = $this->email;
            $user->address = $this->address;
            $user->save();
            DB::commit();
            toast()
                ->success('Data user berhasil diupdate', 'Berhasil')
                ->push();
            $this->reset();
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()
                ->danger('Data user gagal diupdate', 'Gagal')
                ->push();
            $this->reset();
        }
    }

    //delete
    public function delete($id)
    {
        $user = User::find($id);
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            toast()
                ->success('Data user berhasil dihapus', 'Berhasil')
                ->push();
            $this->resetForm();
            $this->toggleForm();
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()
                ->danger('Data user gagal dihapus', 'Gagal')
                ->push();
            $this->resetForm();
            $this->toggleForm();
        }
    }

    //permission
    public function saveEditPermission()
    {
        $this->validate([
            'permission_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::beginTransaction();
            DB::table('permissions')->insert([
                'name' => $this->permission_name,
                'guard_name' => 'web',
            ]);
            DB::commit();
            toast()
                ->success('Data permission berhasil ditambahkan', 'Berhasil')
                ->push();

            $this->resetForm();
            // $this->resetForm();
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()
                ->danger('Data permission gagal ditambahkan', 'Gagal')
                ->push();
            $this->resetForm();
        }
    }
}
