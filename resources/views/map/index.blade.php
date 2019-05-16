@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 ml-auto mr-auto">
      <div class="card card-calendar">
        <div class="card-body ">
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
          <div class="row">
            <div class="col-md-auto">
              <h4 class="card-title ">List Denah</h4>
              <p class="card-category"></p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="toolbar">
            <!--        Here you can write extra buttons/actions for the toolbar              -->
          </div>

          <div class="row">
            @foreach($maps as $m)
              <div class="col-md-3">
                <a href="{!! route('map.edit', $m->id) !!}">
                  <div class="card" style="height: 90%;">
                    <div class="card-header card-header-primary">
                      <div class="row">
                        <div class="col-md-auto">
                          <h4 class="card-title ">{!! $m->nama !!} {!! $m->processed_path == null || $m->processed_path == "" ? "(Map is not processed yet!)" : "(Ready)" !!}</h4>
                          <p class="card-category">{!! $m->deskripsi !!}</p>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <img src='{!! Storage::url("$m->original_path") !!}' style="max-width: 100%; max-height: 100%;">
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
            <div class="col-md-3">
              <a href="{!! route('map.create') !!}">
                <div class="card" style="height: 90%;">
                  <div class="card-header card-header-primary">
                    <div class="row">
                      <div class="col-md-auto">
                        <h4 class="card-title ">Add Denah Baru</h4>
                        <p class="card-category"></p>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-auto mr-auto ml-auto">
                        <img src="{!! asset('assets/img/add-icon.png') !!}" style="width: 100%;">
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
        <!-- end content-->
      </div>
      <!--  end card  -->
    </div>
    <!-- end col-md-12 -->
  </div>
  <!-- end row -->
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
    $('#sidenav_denah').addClass('active');
	});
</script>

<script>
</script>
@endsection