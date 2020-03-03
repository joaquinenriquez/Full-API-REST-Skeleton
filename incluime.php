<?php

include_once './src/app/enum/Acciones.php';
include_once './src/app/enum/EstadosCabeceraPedidos.php';
include_once './src/app/enum/EstadosError.php';
include_once './src/app/enum/EstadosItemPedidos.php';
include_once './src/app/enum/EstadosMesas.php';
include_once './src/app/enum/EstadosUsuarios.php';
include_once './src/app/enum/Roles.php';
include_once './src/app/enum/Sectores.php';

include_once './src/app/middleware/ArticuloMiddleware.php';
include_once './src/app/middleware/AuthMiddleware.php';
include_once './src/app/middleware/MesaMiddleware.php';
include_once './src/app/middleware/ItemPedidoMiddleware.php';
include_once './src/app/middleware/LoginMiddleware.php';
include_once './src/app/middleware/LogMiddleware.php';
include_once './src/app/middleware/SectorMiddleware.php';
include_once './src/app/middleware/UsuarioMiddleware.php';

include_once './src/app/model/Articulo.php';
include_once './src/app/model/CabeceraPedido.php';
include_once './src/app/model/ItemPedido.php';
include_once './src/app/model/ItemPedidoRelacionado.php';
include_once './src/app/model/Log.php';
include_once './src/app/model/Mesa.php';
include_once './src/app/model/Resultado.php';
include_once './src/app/model/Sector.php';
include_once './src/app/model/Usuario.php';
include_once './src/app/model/Validacion.php';

include_once './src/app/modelAPI/ArticuloApi.php';
include_once './src/app/modelAPI/AutentificadorJWT.php';
include_once './src/app/modelAPI/CabeceraPedidoApi.php';
include_once './src/app/modelAPI/IApiControler.php';
include_once './src/app/modelAPI/ItemPedidoApi.php';
include_once './src/app/modelAPI/LogApi.php';
include_once './src/app/modelAPI/LoginApi.php';
include_once './src/app/modelAPI/MesaApi.php';
include_once './src/app/modelAPI/SectorApi.php'; //
include_once './src/app/modelAPI/TokenSeguridad.php';
include_once './src/app/modelAPI/UsuarioApi.php';

include_once './src/app/ModelDAO/ArticuloDAO.php';
include_once './src/app/ModelDAO/CabeceraPedidoDAO.php';
include_once './src/app/ModelDAO/ItemPedidoDAO.php';
include_once './src/app/ModelDAO/LogDAO.php';
include_once './src/app/ModelDAO/MesaDAO.php';
include_once './src/app/ModelDAO/SectorDAO.php';
include_once './src/app/ModelDAO/UsuarioDAO.php';

include_once './src/app/modelPDO/AccesoDatos.php';

include_once './src/app/Querys/QuerysSQL_CabecerasPedidos.php';
include_once './src/app/Querys/QuerysSQL_Logs.php';
include_once './src/app/Querys/QuerysSQL_Mesas.php';
include_once './src/app/Querys/QuerysSQL_Pedidos.php';
include_once './src/app/Querys/QuerysSQL_Usuarios.php';

include_once './src/app/reports/fpdf/fpdf.php';

include_once './src/app/api/Funciones.php';

require_once './vendor/autoload.php';

?>