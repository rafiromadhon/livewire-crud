<?php

namespace App\Livewire;

use App\Models\Employee as ModelsEmployee;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $nama;
    public $email;
    public $alamat;
    public $updateData = false;
    public $employee_id;
    public $katakunci;
    public $employee_selected_id = [];
    public $sortColumn = 'nama';
    public $sortDirection = 'asc';

    public function store()
    {
        $rules = [
            "nama" => "required",
            "email" => "required|email",
            "alamat" => "required",
        ];

        $pesan = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsEmployee::create($validated);
        session()->flash('message', 'Data berhasil dimasukkan');
        $this->clear();
    }

    public function edit($id)
    {
        $data = ModelsEmployee::find($id);
        $this->nama = $data->nama;
        $this->email = $data->email;
        $this->alamat = $data->alamat;

        $this->updateData = true;
        $this->employee_id = $id;
    }

    public function update()
    {
        $rules = [
            "nama" => "required",
            "email" => "required|email",
            "alamat" => "required",
        ];

        $pesan = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        $data = ModelsEmployee::find($this->employee_id);
        $data->update($validated);
        session()->flash('message', 'Data berhasil di-update');
        $this->clear();
    }

    public function clear()
    {
        $this->nama = '';
        $this->email = '';
        $this->alamat = '';

        $this->updateData = false;
        $this->employee_id = '';
        $this->employee_selected_id = [];
    }

    public function delete()
    {
        if  ($this->employee_id != '') {
            ModelsEmployee::find($this->employee_id)->delete();            
        } else{
            for ($x=0; $x < count($this->employee_selected_id); $x++) { 
                ModelsEmployee::find($this->employee_selected_id[$x])->delete();
            }
        }
        session()->flash('message', 'Data berhasil dihapus');
        $this->clear();
    }

    public function deleteConfirmation($id)
    {
        if ($id != '') {
            $this->employee_id = $id;
        }
    }

    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        if ($this->katakunci != null) {
            $data = ModelsEmployee::where('nama', 'like', '%' . $this->katakunci . '%')
                ->orWhere('email', 'like', '%' . $this->katakunci . '%')
                ->orWhere('alamat', 'like', '%' . $this->katakunci . '%')
                ->orderBy($this->sortColumn, $this->sortDirection)->paginate(2);
        } else {
            $data = ModelsEmployee::orderBy($this->sortColumn, $this->sortDirection)->paginate(2);
        }
        return view('livewire.employee', ['dataEmployees' => $data]);
    }
}
