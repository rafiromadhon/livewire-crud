<?php

namespace App\Livewire;

use App\Models\Employee as ModelsEmployee;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Employee extends Component
{
    public $nama;
    public $email;
    public $alamat;

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
    }

    public function render()
    {
        return view('livewire.employee');
    }
}
