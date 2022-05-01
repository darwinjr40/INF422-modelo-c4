<div>
  <head>
    <link rel="stylesheet" href="{{asset('css/modal.css')}}">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <script src="{{ asset('js/home.js') }}"></script>
  </head>
    <div class="container">
      <div align="center" class="m-4">
          <h1><b>Proyectos</b></h1>
      </div>  
      <div class="card m-4">
        <div class="card-body">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <a wire:click="$set('opcion', true)" class="nav-link @if($opcion) active @endif">Tus proyectos</a>
            </li>
            <li class="nav-item">
              <a wire:click="$set('opcion', false)" class="nav-link @if(!$opcion) active @endif">Invitado</a>
            </li>
          </ul>
          @if ($opcion)
            <div align="left" class="my-5">
              <button class="boton-crear" wire:click="$set('modalCrear', true)">crear proyecto</button>
            </div>
          @else
            <div align="left" class="my-5">
              <button class="boton-crear" wire:click="$set('modalUnirse', true)">Unirse a un proyecto</button>
            </div>
          @endif
         
          <div class="row justify-content-center">
            <div class="col-md-8">
              
                @if ($proyectos)
                  @foreach ($proyectos as $proyecto)
                      <div class="card my-5">
                        <div class="card-header">
                          <section class="layout">
                            <div><b>{{$proyecto->nombre}}</b>
                              @if (!$opcion)
                                  - creado por: {{$proyecto->user->name}}
                              @endif
                            </div>
                            @if ($opcion)
                            <div class="marginLeft">
                              <button wire:click="modalEdit('{{$proyecto->id}}')" class="boton">
                                  <img class="img" src="{{asset('img/lapiz.png')}}">
                              </button>
                            </div>
                            <div>
                                <button wire:click="modalDestroy('{{$proyecto->id}}')" class="boton">
                                    <img class="img" src="{{asset('img/eliminar.png')}}">
                                </button> 
                            </div>
                            @endif
                          </section>
                        </div>
                        <div class="card-body">
                            {{$proyecto->descripcion}}
                            <div align="center" class="mt-4">
                                <a wire:click="compartirProyecto('{{$proyecto->codigo}}')" class="myButtonShare">Compartir</a>

                                <a href="http://localhost:8080/model-c4?room={{$proyecto->codigo}}&username={{auth()->user()->name}}" class="myButton">Ingresar</a>
                            </div>
                        </div>
                      </div>
                  @endforeach
                @else
                <div class="card">
                  <div class="card-body">
                      {{ __('No tienes proyectos') }}
                  </div>
                </div>
                @endif 
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--  crear proyecto   --}}
    @if ($modalCrear)
    <div class="modald">
      <div class="modald-contenido">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Crear proyecto</h5>
          </div>
          <div class="modal-body">
            <h4>Nombre:</h4>
            <input type="text" wire:model="nombre" class="form-control">
            @if($errormodal)
                    <small class="text-danger">Campo Requerido</small>
            @endif
            <h4>Descripcion:</h4>
            <input type="text" wire:model="descripcion" class="form-control">
            @if($errormodal)
                    <small class="text-danger">Campo Requerido</small>
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="cancelar()">Cancelar</button>
            <button type="button" class="btn btn-primary" wire:click="storeProyecto()">Guardar</button>
          </div>
        </div>
      </div>
    </div> 
    </div>  
    @endif
    
    {{--  editar proyecto  --}}
    @if ($modalEdit)
    <div class="modald">
      <div class="modald-contenido">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar proyecto</h5>
          </div>
          <div class="modal-body">
            <h4>Nombre:</h4>
            <input type="text" wire:model="nombre" class="form-control">
            @if($errormodal)
                    <small class="text-danger">Campo Requerido</small>
            @endif
            <h4>Descripcion:</h4>
            <input type="text" wire:model="descripcion" class="form-control">
            @if($errormodal)
                    <small class="text-danger">Campo Requerido</small>
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="cancelar()">Cancelar</button>
            <button type="button" class="btn btn-primary" wire:click="updateProyecto()">Actualizar</button>
          </div>
        </div>
      </div>
    </div> 
    </div>  
    @endif
    {{--  eliminar proyecto  --}}
    @if ($modalDestroy)
    <div class="modald">
      <div class="modald-contenido">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="card-header">
              <div class="d-flex align-items-center text-center justify-content-center">
                <h5>¿Estás seguro?</h5>
              </div>
          </div>

          <div class="modal-body">
              <div align="center">
                  <button type="button" class="btn btn-secondary btn-sm my-2 mx-2" wire:click="cancelar()">Cancelar</button>
                  <button wire:click="destroyProyecto()" class="btn btn-danger btn-sm my-2 mx-2">Eliminar</button>
              </div>
          </div>
          
        </div>
      </div>
      </div>  
    </div>
    @endif
    {{--  UNIRSE A UN PROYECTO  --}}
    @if ($modalUnirse)
    <div class="modald">
      <div class="modald-contenido">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Unirse a un proyecto</h5>
          </div>
          <div class="modal-body">
            <h4>Clave del proyecto:</h4>
            <input type="text" wire:model="codigo" placeholder="Ingrese la clave" class="form-control">
            @if($errormodal)
                    <small class="text-danger">Campo Requerido</small>
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="cancelar()">Cancelar</button>
            <button type="button" class="btn btn-primary" wire:click="unirseProyecto()">Guardar</button>
          </div>
        </div>
      </div>
    </div> 
    </div>  
    @endif

    @if ($modalCompartir)
    <div class="modald">
      <div class="modald-contenido">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Clave del proyecto:</h5>
            <button wire:click="$set('modalCompartir',false)" class="close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="m-0 row justify-content-center">
              <div class="col-8 text-center">
                <input id="codigo" readonly='TRUE' type="text" wire:model="codigo" class="form-control">
              </div>
              <div class="col-auto text-center">
                <button onclick="ejecutar('codigo')" type="button" class="btn btn-primary">Copiar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
    </div>  
    @endif
</div>
