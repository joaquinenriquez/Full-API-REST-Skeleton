<?php

require_once './vendor/autoload.php';

require_once './src/app/enum/Acciones.php';
require_once './src/app/enum/EstadosCabeceraPedidos.php';
require_once './src/app/enum/EstadosError.php';
require_once './src/app/enum/EstadosItemPedidos.php';
require_once './src/app/enum/EstadosMesas.php';
require_once './src/app/enum/EstadosUsuarios.php';
require_once './src/app/enum/Roles.php';

require_once './src/app/middleware/ArticuloMiddleware.php';
require_once './src/app/middleware/AuthMiddleware.php';
require_once './src/app/middleware/MesaMiddleware.php';
require_once './src/app/middleware/ItemPedidoMiddleware.php';
require_once './src/app/middleware/LoginMiddleware.php';
require_once './src/app/middleware/LogMiddleware.php';
require_once './src/app/middleware/SectorMiddleware.php';
require_once './src/app/middleware/UsuarioMiddleware.php';

require_once './src/app/model/Articulo.php';
require_once './src/app/model/CabeceraPedido.php';
require_once './src/app/model/Encuesta.php';
require_once './src/app/model/ItemPedido.php';
require_once './src/app/model/ItemPedidoRelacionado.php';
require_once './src/app/model/Log.php';
require_once './src/app/model/Mesa.php';
require_once './src/app/model/Resultado.php';
require_once './src/app/model/Sector.php';
require_once './src/app/model/Usuario.php';
require_once './src/app/model/Validacion.php';

require_once './src/app/modelAPI/ArticuloApi.php';
require_once './src/app/modelAPI/AutentificadorJWT.php';
require_once './src/app/modelAPI/CabeceraPedidoApi.php';
require_once './src/app/modelAPI/IApiControler.php';
require_once './src/app/modelAPI/ItemPedidoApi.php';
require_once './src/app/modelAPI/LogApi.php';
require_once './src/app/modelAPI/LoginApi.php';
require_once './src/app/modelAPI/MesaApi.php';
require_once './src/app/modelAPI/SectorApi.php';
require_once './src/app/modelAPI/TokenSeguridad.php';
require_once './src/app/modelAPI/UsuarioApi.php';

require_once './src/app/ModelDAO/ArticuloDAO.php';
require_once './src/app/ModelDAO/CabeceraPedidoDAO.php';
require_once './src/app/ModelDAO/ItemPedidoDAO.php';
require_once './src/app/ModelDAO/LogDAO.php';
require_once './src/app/ModelDAO/MesaDAO.php';
require_once './src/app/ModelDAO/SectorDAO.php';
require_once './src/app/ModelDAO/UsuarioDAO.php';

require_once './src/app/modelPDO/AccesoDatos.php';

require_once './src/app/Querys/QuerysSQL_CabecerasPedidos.php';
require_once './src/app/Querys/QuerysSQL_Logs.php';
require_once './src/app/Querys/QuerysSQL_Mesas.php';
require_once './src/app/Querys/QuerysSQL_Pedidos.php';
require_once './src/app/Querys/QuerysSQL_Usuarios.php';

require_once './src/app/reports/fpdf/fpdf.php';

require_once './src/app/api/Funciones.php';

?>