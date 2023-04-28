<div class="hstack gap-3 fs-20">
    @can('ver-ventas')
        <a href="{{ route('ver-usuario', $id) }}" class="link-success">
            <i class="ri-eye-line"></i>
        </a>
    @endcan
    {{-- @can('editar-usuario')
        <a href="{{ route('editar-usuario', $id) }}" class="link-secondary">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan --}}
</div>
