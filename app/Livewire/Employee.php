<?php

namespace App\Livewire;

use App\Models\Employee as ModelsEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama;
    public $email;
    public $alamat;
    public $UpdateData = false;
    public $employee_id;
    public $katakunci;

    public function store()
    {
        $rules = [
            'nama'=>'required',
            'email'=>'required|email',
            'alamat'=>'required',
        ];
        $pesan = [
            'nama.required'=>'Nama wajib diisi',
            'email.required'=>'Email wajib diisi',
            'email.email'=>'Email wajib diisi',
            'alamat.required'=>'Alamat wajib diisi',
        ];
        $validated = $this->validate($rules,$pesan);
        ModelsEmployee::create($validated);
        session()->flash('message','Data berhasil di post');

        $this->clear();
    }
    public function edit($id){
        $data = ModelsEmployee::find($id);
        $this->nama = $data->nama;
        $this->email = $data->email;
        $this->alamat = $data->alamat;

        $this->UpdateData = true;
        $this->employee_id = $id;

    }
    public function update()
    {
        $rules = [
            'nama'=>'required',
            'email'=>'required|email',
            'alamat'=>'required',
        ];
        $pesan = [
            'nama.required'=>'Nama wajib diisi',
            'email.required'=>'Email wajib diisi',
            'email.email'=>'Email wajib diisi',
            'alamat.required'=>'Alamat wajib diisi',
        ];
        $validated = $this->validate($rules,$pesan);
        $data = ModelsEmployee::find($this->employee_id);
        $data->update($validated);
        session()->flash('message','Data berhasil di-update');

        $this->clear();
    }
    public function clear(){
        $this->nama = '';
        $this->email = '';
        $this->alamat = '';

        $this->UpdateData = false;
        $this->employee_id = '';
    }

    public function delete()
    {
        $id = $this->employee_id;
        ModelsEmployee::find($id)->delete();
        session()->flash('message','Data berhasil di-delete');
        $this->clear();
    }

    public function delete_confirmation($id)
    {
        $this->employee_id = $id;
    }

    public function render()
    {
        if($this->katakunci !=null) {
            $data = ModelsEmployee::where('nama', 'like', '%'. $this->katakunci . '%')
            ->orWhere('email', 'like', '%'. $this->katakunci . '%')
            ->orWhere('alamat', 'like', '%'. $this->katakunci . '%')
            ->orderBy('nama','asc')->paginate(2);
        }else{
            $data = ModelsEmployee::orderBy('nama','asc')->paginate(2);
        }
        return view('livewire.employee',['dataEmployees' => $data]);
    }
}
