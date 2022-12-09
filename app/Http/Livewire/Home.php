<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Proyecto;
use App\Models\ProyectoUser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Home extends Component
{
  use WithFileUploads;
  public $nombre, $descripcion, $proyecto_id, $codigo;
  public $users;

  public $modalCrear = false;
  public $modalEdit = false;
  public $errormodal = false;
  public $modalDestroy = false;
  public $modalUnirse = false;
  public $modalCompartir = false;
  public $modalUsers = false;
  public $registrado = false;
  public $opcion = true;
  public $contador = 0;
  public $longitud = 0;
  public $filename;
  public $mensaje = '';

  //constructor -> mount
  public function mount()
  {
  }


  public function render()
  {
    $user = Auth()->user();

    $proyectos = ($this->opcion) ?
      ($user->proyecto) : //proyectos creados 
      ($user->proyectos); //proyectos donde es invitado

    $c = 1;
    unset($arrays);
    unset($array);
    $array = array();
    $arrays = array();
    foreach ($proyectos as $proyecto) {
      if ($c <= 3) {
        array_push($array, $proyecto);
        $c++;
      } else {
        array_push($arrays, $array);
        $array = array();
        array_push($array, $proyecto);
        $c = 1;
      }
    }
    array_push($arrays, $array);
    $this->longitud = count($arrays);
    return view('livewire.home', compact('arrays'));
  }

  public function contar()
  {
    if ($this->contador < $this->longitud - 1) {
      $this->contador = $this->contador + 1;
    }
  }

  public function restar()
  {
    if ($this->contador > 0) {
      $this->contador = $this->contador - 1;
    }
  }

  public function verProyecto($proyecto_id)
  {
    $this->proyecto_id = $proyecto_id;
    $this->users = DB::table('users')
      ->join('proyecto_user', 'proyecto_user.user_id', '=', 'users.id')
      ->where('proyecto_user.proyecto_id', $proyecto_id)
      ->select('users.id', 'users.name', 'users.email', 'proyecto_user.proyecto_id')
      ->get();
    $this->modalUsers = true;
  }
  
  public function actualizarVerUsers($proyecto_id)
  {
    $this->users = DB::table('users')
      ->join('proyecto_user', 'proyecto_user.user_id', '=', 'users.id')
      ->where('proyecto_user.proyecto_id', $proyecto_id)
      ->select('users.id', 'users.name', 'users.email', 'proyecto_user.proyecto_id')
      ->get();
  }

  public function compartirProyecto($codigo)
  {
    $this->modalCompartir = true;
    $this->codigo = $codigo;
  }


  public function storeProyecto()
  {
    if ($this->nombre == "") {
      $this->errormodal = true;
    } else {
      $this->errormodal = false;
      do {
        $token = Str::uuid();
      } while (Proyecto::where("codigo", $token)->first() instanceof Proyecto);

      Proyecto::create([
        'nombre' => $this->nombre,
        'descripcion' => $this->descripcion,
        'user_id' => Auth()->user()->id,
        'codigo' => Str::uuid(),
        'content' => '<mxGraphModel dx="667" dy="662" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="826" pageHeight="1169" background="#ffffff"><root><mxCell id="0"/><mxCell id="1" parent="0"/></root></mxGraphModel>'
      ]);

      $this->limpiar();
    }
  }
  private function validateCodigo(){
    $r = true;
    if ($this->codigo == "") {
      $this->errormodal = true;
      $this->mensaje = 'Campo Requerido!.';
      $r = false;
    }
    return $r;
  }

  public function unirseProyecto()
  {
    if ($this->validateCodigo()) {
      $proyecto = Proyecto::where("codigo", $this->codigo)->get()->first();
      if (!$proyecto) {
        $this->errormodal = true;
        $this->mensaje = 'El proyecto. No existe! ';
      } else {
        $proyectoUser = ProyectoUser::where("user_id", auth()->user()->id)
          ->where("proyecto_id", $proyecto->id)
          ->get()->first();
  
        if ($proyectoUser) {
          $this->errormodal = true;
          $this->mensaje = 'Ya formas parte de este proyecto.';
        } else {
          $proyecto->users()->attach(auth()->user()->id);
          $this->limpiar();
        }
      }
    }    
  }

  public function updateProyecto()
  {
    if ($this->nombre == "") {
      $this->errormodal = true;
    } else {
      $proyecto = Proyecto::find($this->proyecto_id);
      $proyecto->nombre = $this->nombre;
      $proyecto->descripcion = $this->descripcion;
      if (!empty($this->filename)) {
        // dd($this->filename);
        $nameFile = $this->filename->hashName();
        $this->filename->store('public');
        $r = '';
        // $archivo = public_path().'/storage/LqpkSiWVBGcVoweWOL7G5tOg8j96q1JTDFmUqMj8.txt';
        $archivo = public_path().'/storage/'.$nameFile;
        // $file = $this->pathToUploadedFile($archivo);        

        foreach(file($archivo) as $line) {
          $r .= $line;
        }
        if (File::exists($archivo)) {
          File::delete($archivo);
        }
        if (!empty($r)) {
          $proyecto->content = $r;
        }
      }
      
      $proyecto->save();
      // $proyecto->update([
      //   'nombre' => $this->nombre,
      //   'descripcion' => $this->descripcion,
      // ]);
      $this->limpiar();
    }
  }

  public function pathToUploadedFile($path, $test = true)
  {
    $filesystem = new Filesystem;

    $name = $filesystem->name($path);
    $extension = $filesystem->extension($path);
    $originalName = $name . '.' . $extension;
    $mimeType = $filesystem->mimeType($path);
    $error = null;
    return new UploadedFile($path, $originalName, $mimeType, $error, $test);
  }


  public function modalEdit($id)
  {
    $this->modalEdit = true;
    $this->proyecto_id = $id;
    $proyecto = Proyecto::find($this->proyecto_id);
    $this->nombre = $proyecto->nombre;
    $this->descripcion = $proyecto->descripcion;
  }

  public function limpiar()
  {
    $this->nombre = null;
    $this->descripcion = null;
    $this->modalEdit = false;
    $this->modalCrear = false;
    $this->modalDestroy = false;
    $this->modalUnirse = false;
    $this->codigo = null;
    $this->modalCompartir = false;
    $this->errormodal = false;
    $this->modalUsers = false;
  }

  public function cancelar()
  {
    $this->limpiar();
  }

  public function modalDestroy($id)
  {
    $this->modalDestroy = true;
    $this->proyecto_id = $id;
  }

  public function destroyProyecto()
  {
    $tipocuenta = Proyecto::find($this->proyecto_id);
    $tipocuenta->delete();
    $this->modalDestroy = false;
    $this->limpiar();
  }
  public function destroyProyectouser($user, $proyecto)
  {
    $proyectouser = ProyectoUser::where("user_id", $user)
      ->where("proyecto_id", $proyecto)
      ->get()->first();
    $proyectouser->delete();
    $this->actualizarverusers($proyecto);
  }
}
