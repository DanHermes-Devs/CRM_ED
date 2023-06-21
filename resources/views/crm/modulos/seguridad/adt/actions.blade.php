<div class="hstack gap-3 fs-20">
    @can('ver-educacion')
        <a href="{{ route('seguridad-adt.show', $id) }}" class="link-success">
            <i class="ri-eye-line"></i>
        </a>
    @endcan
    {{-- @can('editar-educacion')
        <a href="{{ route('seguridad-adt.edit', $id) }}" class="link-secondary">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan --}}
</div>
