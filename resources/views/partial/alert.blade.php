@if(Session::has('message'))
	<div class="alert alert-mint">
		<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
		{!!Session::get('message')!!}
	</div>
@endif
@if(Session::has('error'))
	<div class="alert alert-warning">
		<button class="close" data-dismiss="alert"><i class="pci-cross pci-circle"></i></button>
		{!!Session::get('error')!!}
	</div>
@endif