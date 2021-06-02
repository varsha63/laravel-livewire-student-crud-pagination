<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Student;

class Students extends Component
{
    public $data, $firstname, $email, $selected_id;
    public $updateMode = false;
    public function render()
    {
        $this->data = Student::all();
        return view('livewire.students');
    }

    private function resetInput()
    {
        $this->firstname = null;
        $this->email = null;
    }
    public function store()
    {
        $this->validate([
            'firstname' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        Student::create([
            'firstname' => $this->firstname,
            'email' => $this->email
        ]);
        $this->resetInput();
    }
    public function edit($id)
    {
        $record = Student::findOrFail($id);
        $this->selected_id = $id;
        $this->firstname = $record->firstname;
        $this->email = $record->email;
        $this->updateMode = true;
    }
    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'firstname' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        if ($this->selected_id) {
            $record = Student::find($this->selected_id);
            $record->update([
                'firstname' => $this->firstname,
                'email' => $this->email
            ]);
            $this->resetInput();
            $this->updateMode = false;
        }
    }
    public function destroy($id)
    {
        if ($id) {
            $record = Student::where('id', $id);
            $record->delete();
        }
    }
}
