@extends('layouts.master')

@section('content')
<div class="container-fluid">
	<div class="row">
  	<div class="col-md-12">
      <div class="card ">
        <div class="card-header card-header-primary card-header-icon">
          <div class="card-icon">
            <i class="material-icons">add</i>
          </div>
          <h4 class="card-title">Tambah Denah</h4>
        </div>
        <div class="card-body ">
          <form class="form-horizontal" method="POST" action="{{ route('map.store') }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="row">
              <label class="col-md-3 col-form-label" for="nama">Nama Denah</label>
              <div class="col-md-7">
                <div class="form-group has-default">
                  <input type="text" autocomplete="off" class="form-control" name="nama" id="nama" required value="">
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-3 col-form-label" for="kodeharga">Deskripsi</label>
              <div class="col-md-7">
                <div class="form-group has-default">
                  <input type="text" autocomplete="off" class="form-control" name="deskripsi_denah" id="deskripsi_denah" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-3 col-form-label" for="kodeharga">Map Height (Decimal separated by '.', in meter)</label>
              <div class="col-md-7">
                <div class="form-group has-default">
                  <input type="number" autocomplete="off" class="form-control" name="height" id="height" step="any" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-3 col-form-label" for="kodeharga">Map Width Scale (Decimal separated by '.')</label>
              <div class="col-md-7">
                <div class="form-group has-default">
                  <input type="text" autocomplete="off" class="form-control" name="scale_width" id="scale_width" step="any" required>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-md-3 col-form-label" for="kodeharga">Map Length Scale (Decimal separated by '.')</label>
              <div class="col-md-7">
                <div class="form-group has-default">
                  <input type="text" autocomplete="off" class="form-control" name="scale_length" id="scale_length" step="any" required>
                </div>
              </div>
            </div>

            <div class="row"> 
              <label class="col-md-3 col-form-label" for="nama">Gambar Denah</label>
              <div class="col-md-4">
                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                  <div class="fileinput-new thumbnail">
                    <img src="../../assets/img/image_placeholder.jpg" alt="...">
                  </div>
                  <div class="fileinput-preview fileinput-exists thumbnail"></div>
                  <div>
                    <span class="btn btn-rose btn-round btn-file">
                      <span class="fileinput-new">Select image</span>
                      <span class="fileinput-exists">Change</span>
                      <input type="file" name="denah" />
                    </span>
                    <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-7 offset-md-3">
                <div class="form-group">
                  <button type="submit" class="btn btn-fill btn-primary col-md-12">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer ">
          <div class="row">
            <div class="col-md-7">
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#sidenav_denah').addClass('active');
	});
</script>
@endsection