@extends('layouts.master')

@section('content')
<div class="container-fluid">
	<div class="row">
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