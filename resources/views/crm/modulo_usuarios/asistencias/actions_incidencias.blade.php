<div class="hstack gap-3 fs-20">
    @can('editar-usuarios')
        <a href="#" class="link-secondary modal_show_asistencia" data-id="{{ $id }}" title="Editar Asistencia">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan
</div>
