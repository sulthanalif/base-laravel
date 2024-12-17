<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $short = 'name';
    public $order = 'asc';

    public $typePassword = 'password';
    public $modalForm = false;

    //form
    public $id = '';
    public $name = '';
    public $email = '';
    public $address = '';
    public $password = '';


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
        $this->name = '';
        $this->email = '';
        $this->address = '';
        $this->password = '';
    }

    public function toggleForm()
    {
        $this->modalForm = !$this->modalForm;
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
        return view('livewire.user-list', [
            'datas' => User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->orderBy($this->short, $this->order)
                ->paginate($this->perPage),
        ]);
    }

    //save
    public function save()
    {
        if($this->password == null){
            $this->update();
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'password' => $this->password
        ]);


        toast()
            ->success('Data user berhasil ditambahkan', 'Berhasil')
            ->push();

        // session()->flash('message', 'Data saved successfully.');
        $this->redirect(route('userpage'));
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

        if($this->password != null){
            $user->password = $this->password;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->address = $this->address;
        $user->save();

        toast()
            ->success('Data user berhasil diupdate', 'Berhasil')
            ->push();

        $this->redirect(route('userpage'));
    }
}
