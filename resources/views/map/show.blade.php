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
	          <h4 class="card-title">Detail Denah</h4>
	        </div>

            <div class="card-body ">
            	<div class="row">
            		<div class="col-md-9">
		            	<img src="{!! Storage::url($map->original_path) !!}" id="original_image">
		            </div>
		            <div class="col-md-3">
		            	Places List
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
		$('#nav_pembelian').addClass('active');
    md.initFormExtendedDatetimepickers();
    if ($('.slider').length != 0) {
      md.initSliders();
    }
	});
</script>
@endsection