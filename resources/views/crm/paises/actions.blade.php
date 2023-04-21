<div class="hstack gap-3 fs-20">
    {{-- @can('ver-paises')
        <a href="" class="link-success">
            <i class="ri-eye-line"></i>
        </a>
    @endcan --}}
    @can('editar-paises')
        <a href="{{ route('paises.edit', $id) }}" class="link-secondary">
            <i class="ri-pencil-line"></i>
        </a>
    @endcan
    @can('borrar-paises')
        <a href="javascript:void(0);" class="link-danger" data-id="{{ $id }}" id="eliminar_pais">
            <i class="ri-delete-bin-5-line"></i>
        </a>
    @endcan
</div>


<script>
    // Al dar click en el boton de eliminar_pais se abrira un sweetalert2 para confirmar si se desea eliminar el usuario
    $('body').on('click', '#eliminar_pais', function() {
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
                let route = "{{ route('paises.destroy', ':id') }}";
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
                                'El país ha sido eliminado.',
                                'success'
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    // Actualizamos el datatable de usuarios
                                    $('#tabla_paises').DataTable().ajax.reload();
                                }
                            });
                        }
                    }
                });
            }
        })
    });
</script>
