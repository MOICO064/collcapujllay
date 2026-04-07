<div class="flex items-center gap-2">
    <a href="{{ route('ventas.edit', $sale) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 transition">
        <i class="fas fa-edit"></i>
        Editar
    </a>
    <button
        class="delete-btn bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 transition"
        data-id="{{ $sale->id }}">
        <i class="fas fa-trash"></i>
        Eliminar
    </button>
    <a href="{{ route('ventas.factura.pdf', $sale) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 transition">
        <i class="fas fa-print"></i>
        Factura
    </a>
</div>
