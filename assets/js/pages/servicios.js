document.addEventListener('DOMContentLoaded', () => {
    // Lógica para buscar/registrar cliente por cédula en el modal de pedidos
    const pedidoCedula = document.getElementById('pedido_cedula_cliente');
    const pedidoTipoPersona = document.getElementById('pedido_tipo_persona');
    const mensajePedidoCedula = document.getElementById('mensaje_pedido_cedula');
    const pedidoNombreCliente = document.getElementById('pedido_nombre_cliente');
    const pedidoIdCliente = document.getElementById('pedido_id_cliente');

    if (pedidoCedula && pedidoTipoPersona) {
        pedidoCedula.addEventListener('blur', async () => {
            const cedula = pedidoCedula.value.trim();
            const tipo = pedidoTipoPersona.value;

            if (cedula.length >= 6 && tipo) {
                mensajePedidoCedula.style.color = 'blue';
                mensajePedidoCedula.innerText = 'Buscando cliente...';
                pedidoNombreCliente.value = '';
                pedidoIdCliente.value = '';
                pedidoNombreCliente.setAttribute('readonly', true);

                try {
                    const response = await fetch(`?c=clientes&accion=buscarYRegistrarCedula&tipo_persona=${tipo}&cedula=${cedula}`);
                    const data = await response.json();

                    if (data.success && data.data) {
                        mensajePedidoCedula.style.color = 'green';
                        mensajePedidoCedula.innerText = data.source === 'db' ? 'Cliente encontrado en BD.' : 'Cliente registrado desde API.';
                        pedidoNombreCliente.value = data.data.nombre_completo;
                        pedidoIdCliente.value = data.data.id;
                        pedidoNombreCliente.setAttribute('readonly', true);
                    } else {
                        mensajePedidoCedula.style.color = 'orange';
                        mensajePedidoCedula.innerText = 'Cliente no encontrado. Ingrese el nombre para registrarlo.';
                        pedidoNombreCliente.removeAttribute('readonly');
                        pedidoNombreCliente.focus();
                    }
                } catch (error) {
                    console.error('Error al consultar cédula:', error);
                    mensajePedidoCedula.style.color = 'red';
                    mensajePedidoCedula.innerText = 'Error al conectar. Ingrese el nombre manualmente.';
                    pedidoNombreCliente.removeAttribute('readonly');
                    pedidoNombreCliente.focus();
                }
            } else {
                if (mensajePedidoCedula) mensajePedidoCedula.innerText = '';
                if (pedidoNombreCliente) {
                    pedidoNombreCliente.value = '';
                    pedidoNombreCliente.setAttribute('readonly', true);
                }
                if (pedidoIdCliente) pedidoIdCliente.value = '';
            }
        });
    }
});
