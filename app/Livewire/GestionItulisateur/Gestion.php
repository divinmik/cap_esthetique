<?php

namespace App\Livewire\GestionUtilisateur;

use App\Models\Role;
use App\Models\User;
use App\Models\Tache;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\MailInfoCompte;
use App\Models\Helper_function;

class Gestion extends Component
{
    public $datas,$user_id,$name,$email,$etat,$client_info,$currentUrl,$task,$input_task;


    public function add()
    {
        array_push($this->input_task, [
            'task_id' => '',
            'task' => '',
            'description' => '',
        ]);

    }

    public function remove($i)
    {
        unset($this->input_task[$i]);
    }

    public function mount(){
        $this->currentUrl = url()->current();
    }

    public function render()
    {
        $this->datas = User::where('statut','agent')->get();
        $this->task = Tache::all();

        return view('livewire.gestion-utilisateur.gestion');
    }

    public function store(){
        $donne = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            ]);

            $pwd = "Admincompte##";

            //pwd
            $pwd_crypt = bcrypt($pwd);
            $donne['name'] = ucwords(strtolower($donne['name']));
            $donne['password'] =  $pwd_crypt;

            User::create($donne);

            toast('succès','success');

            $mailData = [
                'title' => 'Création de compte crée avec succès',
                'email'=>$donne['email'],
                'fullname' => $donne['name'],
                'pwd' => $pwd,

            ];

            Helper_function::send_mail(new MailInfoCompte($mailData),$donne['email']);
            return redirect()->to($this->currentUrl);
    }

    public function SearchTask(){
        if(!empty($this->task)){
            $this->input_task = [];
            foreach($this->task as $v){
                array_push($this->input_task, [
                    'task_id'=>$v->id,
                    'task' => $v->modul,
                    'description'=>$v->description
                ]);
            }
        }
    }

    public function SearchUser(){
        if(!empty($this->user_id)){
            $this->client_info = User::where('id',$this->user_id)->first();
        }

        if(!empty($this->client_info)){
            $this->name = $this->client_info->name;
            $this->email = $this->client_info->email;
            $this->etat = $this->client_info->etat;
            $tasks = Role::where('user_id',$this->user_id)->get();
            if(!empty($tasks)){
                $this->input_task = [];
                foreach($tasks as $v){
                    array_push($this->input_task, [
                        'task_id'=>$v->task_id,
                        'task' => $v->task->modul,
                        'description'=>$v->task->description
                    ]);
                }
            }
        }
    }

    public function Valider(){

        $this->validate([
            'user_id'=>'required',
            'input_task.*.description'=>'required',

            ], [
                '*.required'=>'Les champs avec * sont obligatoires'
            ]);

            //save task
            if(!empty($this->user_id)){
                $delete_tach_actuelle = Role::where('user_id',$this->user_id)->delete();
                $info_client = User::where('id',$this->user_id)->first();
                if(!empty($info_client)){
                    foreach($this->input_task as $v){
                        $v['user_id']= $info_client->id;
                        $v['task']=$v['task_id'];
                        Role::create($v);
                    }
                }

                toast('succès update','success');
                return redirect()->to($this->currentUrl);
            }



    }

    public function update(){
        $donne = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->user_id,
            'etat' => 'required',
            'input_task.*.description'=>'required',
            ]);

            $pwd = Str::random(8);

            $donne['name'] = ucwords(strtolower($donne['name']));

            User::where('id',$this->user_id)->update([
                'name'=>$donne['name'],
                'email'=>$donne['email'],
                'etat'=>$donne['etat']
            ]);

            //save task
            if(!empty($this->user_id)){
                $delete_tach_actuelle = Role::where('user_id',$this->user_id)->delete();
                $info_client = User::where('id',$this->user_id)->first();
                if(!empty($info_client)){
                    foreach($this->input_task as $v){
                        $v['user_id']= $info_client->id;
                        $v['task']=$v['task_id'];
                        Role::create($v);
                    }
                }
            }

            toast('succès','success');
            return redirect()->to($this->currentUrl);
    }
}

