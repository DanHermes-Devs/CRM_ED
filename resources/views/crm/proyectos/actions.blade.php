<div class="hstack gap-3 fs-20">
    {{-- @can('ver-proyectos')
        <a href="" class="link-success">
            <i class="ri-eye-line"></i>
        </a>
    @endcan --}}
    @can('editar-proyectos')
        <a href="{{ route('proyectos.edit', $id) }}" class="link-secondary">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan
    @can('borrar-proyectos')
        <a href="javascript:void(0);" class="link-danger" data-id="{{ $id }}" id="eliminar_proyecto">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endcan
</div>


<script>
    // Al dar click en el boton de eliminar_proyecto se abrira un sweetalert2 para confirmar si se desea eliminar el usuario
    $('body').on('click', '#eliminar_proyecto', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, bórralo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma la eliminacion del usuario, se redirecciona a la ruta de eliminar usuario
                let route = "{{ route('proyectos.destroy', ':id') }}";
                route = route.replace(':id', id);

                $.ajax({
                    url: route,
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.code == 200) {
                            Swal.fire(
                                '¡Eliminado!',
                                'El proyecto ha sido eliminado.',
                                'success'
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    // Actualizamos el datatable de usuarios
                                    $('#tabla_proyectos').DataTable().ajax.reload();
                                }
                            });
                        }
                    }
                });
            }
        })
    });
</script>
