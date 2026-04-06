<div class="flex items-center gap-2">

    <a href="{{ route('usuarios.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 transition">
        <i class="fas fa-edit"></i>
        Editar
    </a>

    <button
        class="delete-btn bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg flex items-center gap-1 transition"
        data-id="{{ $user->id }}">
        <i class="fas fa-trash"></i>
        Eliminar
    </button>

</div>