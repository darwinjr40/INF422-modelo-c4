<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Proyecto;
use Illuminate\Support\Str;

class Home extends Component
{
    public $nombre,$descripcion,$proyecto_id,$codigo;
    

    public $modalCrear=false;
    public $modalEdit=false;
    public $errormodal=false;
    public $modalDestroy=false;
    public $modalUnirse=false;
    public $modalCompartir=false;
    public $opcion=true;


    public function render()
    {
        $user=User::find(Auth()->user()->id);
        if ($this->opcion){
            $proyectos=$user->proyecto;
        }else{
            $proyectos=$user->proyectos;
        }
        return view('livewire.home',compact('proyectos'));
    }
    public function compartirProyecto($codigo){
        $this->modalCompartir=true;
        $this->codigo=$codigo;
    }
    
    
    public function storeProyecto()
    {
        if ($this->nombre!=""){
            $this->errormodal=false;
            do {
                $token = Str::uuid();
            } while (Proyecto::where("codigo", $token)->first() instanceof Proyecto);

            Proyecto::create([
                'nombre'=>$this->nombre,
                'descripcion'=>$this->descripcion,
                'user_id'=>Auth()->user()->id,
                'codigo'=>Str::uuid(),
            ]);
            $this->limpiar();
        }else{
            $this->errormodal=true;
        }   
       
    }
    public function unirseProyecto(){
        if ($this->codigo==""){
            $this->errormodal=true;
        }else{
            $proyecto=Proyecto::where("codigo",$this->codigo)->get()->first();
            if ($proyecto){
                $proyecto->users()->attach(auth()->user()->id);
            }
            $this->limpiar();
        }
    }
    public function updateProyecto(){
        if ($this->nombre==""){
            $this->errormodal=true;
        }else{
            $proyecto=Proyecto::find($this->proyecto_id);
            $proyecto->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]);
            $this->limpiar();
        }
    }

    public function modalEdit($id){
        $this->modalEdit=true;
        $this->proyecto_id=$id;
        $proyecto=Proyecto::find($this->proyecto_id);
        $this->nombre=$proyecto->nombre;
        $this->descripcion=$proyecto->descripcion;
    }
    
    public function limpiar(){
        $this->nombre=null;
        $this->descripcion=null;
        $this->modalEdit=false;
        $this->modalCrear=false;
        $this->modalDestroy=false;
        $this->modalUnirse=false;
    }
    public function cancelar(){
        $this->limpiar();
    }

    public function modalDestroy($id){
        $this->modalDestroy=true;
        $this->proyecto_id=$id;
    }

    public function destroyProyecto()
    {
        $tipocuenta=Proyecto::find($this->proyecto_id);
        $tipocuenta->delete();
        $this->modalDestroy=false;
        $this->limpiar();
    }
}
