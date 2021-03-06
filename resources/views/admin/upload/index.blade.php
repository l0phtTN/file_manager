@extends('admin.layout')

@section('content')
  <div class="container-fluid">

    {{-- Top Bar --}}
    <div class="row page-title-row">
      <div class="col-md-6">
        <h3 class="pull-left">Explorateur  </h3>
        <div class="pull-left">
          <ul class="breadcrumb">
            @foreach ($breadcrumbs as $path => $disp)
              <li><a href="/admin/upload?folder={{ $path }}">{{ $disp }}</a></li>
            @endforeach
            <li class="active">{{ $folderName }}</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6 text-right">
        <button type="button" class="btn btn-success btn-md"
                data-toggle="modal" data-target="#modal-folder-create">
          <i class="fa fa-plus-circle"></i> Nouveau dossier
        </button>
        <button type="button" class="btn btn-primary btn-md"
                data-toggle="modal" data-target="#modal-file-upload">
          <i class="fa fa-upload"></i> Upload
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">

        @include('admin.partials.errors')
        @include('admin.partials.success')

        <table id="uploads-table" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Type</th>
              <th>Date</th>
              <th>Taille</th>
              <th data-sortable="false">Actions</th>
            </tr>
          </thead>
          <tbody>

{{-- The Subfolders --}}
@foreach ($subfolders as $path => $name)
  <tr>
    <td>
      <a href="/admin/upload?folder={{ $path }}">
        <i class="fa fa-folder fa-lg fa-fw"></i>
        {{ $name }}
      </a>
    </td>
    <td>Dossier</td>
    <td>-</td>
    <td>-</td>
    <td>
      <button type="button" class="btn btn-xs btn-danger"
              onclick="delete_folder('{{ $name }}')">
        <i class="fa fa-times-circle fa-lg"></i>
        Efaccer
      </button>
    </td>
  </tr>
@endforeach

{{-- The Files --}}
@foreach ($files as $file)
  <tr>
    <td>
      <a href="{{ $file['webPath'] }}">
        @if (is_image($file['mimeType']))
          <i><img src='{{$file['webPath']}}' width='50px' height='50px'></img></i>
        @else
          <i class="fa fa-file-o fa-lg fa-fw"></i>
        @endif
        {{ $file['name'] }}
      </a>
    </td>
<td>{{setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra') , strftime(date("l"))}}</td>
    <td>{{ $file['mimeType'] or 'Unknown' }}</td>
    <td>{{ strftime($file['modified']) }}</td>
    <td>{{ human_filesize($file['size']) }}</td>
    <td>
      <button type="button" class="btn btn-xs btn-danger"
              onclick="delete_file('{{ $file['name'] }}')">
        <i class="fa fa-times-circle fa-lg"></i>
        Efaccer
      </button>
      @if (strrpos('sss'.$file['mimeType'],'image/') == true )
        <button type="button" class="btn btn-xs btn-success"
                onclick="preview_image('{{ $file['webPath'] }}')">
          <i class="fa fa-eye fa-lg"></i>
          Afficher
        </button>
      @endif

<i class="btn btn-xs btn-success">
<i class="glyphicon glyphicon-download-alt"></i>
<a href="" download="{{ $file['webPath'] }}">Télecharger</a>
</i>

    </td>
  </tr>
@endforeach

          </tbody>
        </table>

      </div>
    </div>
  </div>

  @include('admin.upload._modals')

@stop

@section('scripts')
  <script>

    // Confirm file delete
    function delete_file(name) {
      $("#delete-file-name1").html(name);
      $("#delete-file-name2").val(name);
      $("#modal-file-delete").modal("show");
    }

    // Confirm folder delete
    function delete_folder(name) {
      $("#delete-folder-name1").html(name);
      $("#delete-folder-name2").val(name);
      $("#modal-folder-delete").modal("show");
    }

    // Preview image
    function preview_image(path) {
      $("#preview-image").attr("src", path);
      $("#modal-image-view").modal("show");
    }

    // Startup code
    $(function() {
      $("#uploads-table").DataTable();
    });
  </script>
@stop
