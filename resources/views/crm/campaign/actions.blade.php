<div class="hstack gap-3 fs-20">
    {{-- Enlace para editar cronjob --}}
    <a href="{{ route('campaigns.edit', $id) }}" class="link-secondary" title="Editar CronJob">
        <i class="ri-pencil-line"></i>
    </a>

    {{-- Enlace para eliminar cronjob --}}
    <a href="javascript:void(0);" class="link-danger" data-id="{{ $id }}" id="eliminar_cronjob" title="Eliminar CronJob">
        <i class="ri-delete-bin-5-line"></i>
    </a>
</div>

<script>
    // Al dar click en el boton de eliminar_cronjob se abrira un sweetalert2 para confirmar si se desea eliminar el cronjob
    $('body').on('click', '#eliminar_cronjob', function() {
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
                // Si el usuario confirma la eliminacion del cronjob, se redirecciona a la ruta de eliminar cronjob
                let route = "{{ route('campaigns.destroy', ':id') }}";
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
                                'El cronjob ha sido eliminado.',
                                'success'
                            ).then((result) => {
                                if (result.isConfirmed) {
                                    // Actualizamos el datatable de cronjobs
                                    $('#tabla_campana').DataTable().ajax.reload();
                                }
                            });
                        }
                    }
                });
            }
        })
    });
</script>