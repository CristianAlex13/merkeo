@extends('layouts.main')

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Agregar proveedor</h1>
    
  </div><!-- End Page Title -->
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Agregar Nuevo Proveedor</h5>
            
            <form action="{{ route("proveedores.store") }}" method="POST">
                @csrf
                <label for="nombre">Nombre de proveedor</label>
                <input type="text" class="form-control" required name="nombre" id="nombre">
                <label for="telefono">Telefono</label>
                <input type="text" class="form-control" required name="telefono" id="telefono">
                <label for="notas">Notas</label>
                <textarea name="notas" id="notas" cols="30" rows="10" class="form-control"></textarea>
                <button class="btn btn-primary mt-3">Guardar</button>
                <a href="{{ route("proveedores") }}" class="btn btn-info mt-3">
                    Cancelar
                </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

