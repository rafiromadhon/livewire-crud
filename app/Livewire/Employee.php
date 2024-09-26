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
    public $employee_id;
    public $updateData = false;

    public function store(){
        $rules = [
            "nama"=> "required",
            "email"=> "required|email",
            "alamat"=> "required",
        ];

        $pesan =[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        ModelsEmployee::create($validated);
        session()->flash('message','Data berhasil dimasukkan');
        $this->clear();
    }

    public function edit($id){
        $data = ModelsEmployee::find($id);
        $this->nama = $data->nama;
        $this->email = $data->email;
        $this->alamat = $data->alamat;

        $this->updateData = true;
        $this->employee_id = $id;
    }

    public function update(){
        $rules = [
            "nama"=> "required",
            "email"=> "required|email",
            "alamat"=> "required",
        ];

        $pesan =[
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules, $pesan);
        $data = ModelsEmployee::find($this->employee_id);
        $data->update($validated);
        session()->flash('message','Data berhasil di-update');
        $this->clear();
    }

    public function clear(){
        $this->nama = '';
        $this->email = '';
        $this->alamat = '';

        $this->updateData = false;
        $this->employee_id = '';
    }

    public function delete(){
        ModelsEmployee::find($this->employee_id)->delete();
        session()->flash('message','Data berhasil dihapus');
        $this->clear();
    }

    public function deleteConfirmation($id){
        $this->employee_id = $id;
    }

    public function render()
    {   
        $data = ModelsEmployee::orderBy('nama', 'asc')->paginate(2);
        return view('livewire.employee', ['dataEmployees'=> $data]);
    }
}
