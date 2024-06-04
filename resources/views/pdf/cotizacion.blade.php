<h1>Cotización #{{ $id }}</h1>
<p>Pedido # {{ $pedido->id }}</p>
<p>Nombre del cliente: {{ $cliente->nombre }}</p>
<p>Email: {{ $cliente->email }}</p>
<p>Vendedor: {{ $vendedor->name }}</p>
@foreach ($referencias as $referencia)
    <p>Referencia: {{ $referencia->referencia }}</p>
    {{-- @foreach ($pedidoReferenciaProveedor as $pedidoReferenciaProveedor)
            {{-- <p>Proveedor: {{ $pedidoReferenciaProveedor->tercero }}</p> --}}
            {{-- <p>Precio: {{ $pedidoReferenciaProveedor }}</p>
    @endforeach --}}
    <p>PreRefProv: {{$pedRefProv}}</p>
@endforeach
