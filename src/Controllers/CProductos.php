<?php
namespace Lenovo\Dalu\Controllers;

use Lenovo\Dalu\Models\Productos;
use Lenovo\Dalu\Models\Categorias;
use Exception;

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'view';

switch($accion) {
    case "view":
        $productoModel = new Productos();
        $productos = $productoModel->search();
        foreach ($productos as &$p) {
            $p['variantes'] = $productoModel->getVariantesByProducto($p['id']);
            $p['variantes_inactivas'] = $productoModel->getInactiveVariantesByProducto($p['id']);
        }
        unset($p);
        
        $productosInactivos = $productoModel->searchInactive();
        foreach ($productosInactivos as &$pIN) {
            $pIN['variantes'] = $productoModel->getVariantesByProducto($pIN['id']);
            $pIN['variantes_inactivas'] = $productoModel->getInactiveVariantesByProducto($pIN['id']);
        }
        unset($pIN);

        $categorias = (new Categorias())->search();
        $categoriasInactivas = (new Categorias())->searchInactive();
        require_once __DIR__ . "/../Views/V_Productos.php";
        break;
    
case "insert":
    // Validar campos requeridos
    if (empty($_POST['id_categoria']) || empty($_POST['nombre']) || (empty($_POST['precio_venta']) && empty($_POST['precio_compra']))) {
        $_SESSION['error'] = "Todos los campos requeridos (*) deben ser llenados.";
        header("Location: ?c=productos&accion=view");
        exit();
    }
    
    $precio_compra = !empty($_POST['precio_compra']) ? floatval($_POST['precio_compra']) : 0;
    $tempProducto = new Productos();
    $precio_venta = $precio_compra > 0 ? $tempProducto->calcularPrecioVentaDesdeCompra($precio_compra) : $_POST['precio_venta'];
    
    $producto = new Productos(
        null,                           
        $_POST['id_categoria'],      
        $_POST['nombre'],              
        $_POST['descripcion'],          
        $precio_venta,                 
        $precio_compra,                 
        !empty($_POST['marca']) ? $_POST['marca'] : null       
    );
    
   
    $producto->setPrecioOferta(isset($_POST['precio_oferta']) && $_POST['precio_oferta'] !== '' ? $_POST['precio_oferta'] : null)
             ->setStockMinimo(isset($_POST['stock_minimo']) && $_POST['stock_minimo'] !== '' ? $_POST['stock_minimo'] : 3);
    
    // ========== PROCESAR IMAGEN PRINCIPAL CON CATEGORÍA DINÁMICA ==========
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        try {
            // Obtener nombre de la categoría desde BD usando el ID
            $categoriaModel = new Categorias();
            $categoria = $categoriaModel->searchById($_POST['id_categoria']);
            
            if ($categoria) {
                $nombreCategoria = $categoria['nombre'];
            } else {
                $nombreCategoria = 'sin_categoria';
            }
            
            // Subir imagen usando el método del modelo
            $rutaImagen = $producto->subirImagen(
                $_FILES['imagen'],
                $nombreCategoria,
                $_POST['nombre']
            );
            
            if ($rutaImagen) {
                $producto->setImagenPrincipal($rutaImagen);
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al subir la imagen: " . $e->getMessage();
            header("Location: ?c=productos&accion=view");
            exit();
        }
    }
    
    // Procesar variantes si existen
    $variantes = [];
    if (isset($_POST['variantes']) && is_array($_POST['variantes'])) {
        foreach ($_POST['variantes'] as $index => $variante) {
            // Validar que la variante tenga al menos nombre y stock
            if (!empty($variante['nombre_variante']) && isset($variante['stock'])) {
                $atributos = [];
                
                // Construir atributos dinámicos según tipo de producto
                if (!empty($variante['talla'])) $atributos['talla'] = $variante['talla'];
                if (!empty($variante['color'])) $atributos['color'] = $variante['color'];
                if (!empty($variante['volumen_ml'])) $atributos['volumen_ml'] = $variante['volumen_ml'];
                if (!empty($variante['spf'])) $atributos['spf'] = $variante['spf'];
                if (!empty($variante['fragancia'])) $atributos['fragancia'] = $variante['fragancia'];
                if (!empty($variante['tipo_piel'])) $atributos['tipo_piel'] = $variante['tipo_piel'];
                
                $imagen_variante = null;

                if (isset($_FILES['imagen_variante']) && isset($_FILES['imagen_variante']['error'][$index]) && $_FILES['imagen_variante']['error'][$index] === UPLOAD_ERR_OK) {
                    try {
                        $fileArray = [
                            'name' => $_FILES['imagen_variante']['name'][$index],
                            'type' => $_FILES['imagen_variante']['type'][$index],
                            'tmp_name' => $_FILES['imagen_variante']['tmp_name'][$index],
                            'error' => $_FILES['imagen_variante']['error'][$index],
                            'size' => $_FILES['imagen_variante']['size'][$index]
                        ];
                        
                        $categoriaModel = new Categorias();
                        $categoria = $categoriaModel->searchById($_POST['id_categoria']);
                        $nombreCategoria = $categoria ? $categoria['nombre'] : 'sin_categoria';

                        $rutaImagenVariante = $producto->subirImagen(
                            $fileArray,
                            $nombreCategoria,
                            $_POST['nombre'] . '_' . $variante['nombre_variante']
                        );
                        if ($rutaImagenVariante) {
                            $imagen_variante = $rutaImagenVariante;
                        }
                    } catch (Exception $e) {
                        // Ignorar error de subida para no detener el proceso
                    }
                }
                
                $variantes[] = [
                    'nombre_variante' => $variante['nombre_variante'],
                    'atributos' => $atributos,
                    'precio_adicional' => $variante['precio_adicional'] !== '' ? $variante['precio_adicional'] : 0,
                    'stock' => $variante['stock'] !== '' ? $variante['stock'] : 0,
                    'imagen_variante' => $imagen_variante,
                    'activo' => 1
                ];
            }
        }
    }
    
    $producto->setVariantes($variantes);
    
    if ($producto->insert()) {
        $_SESSION['success'] = "Producto registrado exitosamente con " . count($variantes) . " variante(s).";
    } else {
        $_SESSION['error'] = "Error al registrar el producto.";
    }
    header("Location: ?c=productos&accion=view");
    exit();
    break;
    
case "update":
    // Validar campos requeridos
    if (empty($_POST['id']) || empty($_POST['id_categoria']) || empty($_POST['nombre']) || (empty($_POST['precio_venta']) && empty($_POST['precio_compra']))) {
        $_SESSION['error'] = "Todos los campos requeridos (*) deben ser llenados.";
        header("Location: ?c=productos&accion=view");
        exit();
    }
    
    $precio_compra = !empty($_POST['precio_compra']) ? floatval($_POST['precio_compra']) : 0;
    $producto = new Productos();
    $precio_venta = $precio_compra > 0 ? $producto->calcularPrecioVentaDesdeCompra($precio_compra) : $_POST['precio_venta'];

    $producto->setId($_POST['id'])
             ->setIdCategoria($_POST['id_categoria'])
             ->setNombre($_POST['nombre'])
             ->setDescripcion($_POST['descripcion'])
             ->setPrecioCompra($precio_compra)
             ->setPrecioVenta($precio_venta)
             ->setPrecioOferta(isset($_POST['precio_oferta']) && $_POST['precio_oferta'] !== '' ? $_POST['precio_oferta'] : null)
             ->setStockMinimo(isset($_POST['stock_minimo']) && $_POST['stock_minimo'] !== '' ? $_POST['stock_minimo'] : 3)
             ->setMarca(!empty($_POST['marca']) ? $_POST['marca'] : null);
    
    // ========== PROCESAR NUEVA IMAGEN SI SE SUBIÓ ==========
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        try {
            // Obtener nombre de la categoría
            $categoriaModel = new Categorias();
            $categoria = $categoriaModel->searchById($_POST['id_categoria']);
            $nombreCategoria = $categoria ? $categoria['nombre'] : 'sin_categoria';
            
            // Subir nueva imagen
            $rutaImagen = $producto->subirImagen(
                $_FILES['imagen'],
                $nombreCategoria,
                $_POST['nombre']
            );
            
            if ($rutaImagen) {
                // Opcional: Eliminar imagen anterior si existe
                $productoActual = $producto->getById($_POST['id']);
                if ($productoActual && !empty($productoActual['imagen_principal'])) {
                    $rutaAnterior = __DIR__ . '/../../public/' . $productoActual['imagen_principal'];
                    if (file_exists($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                }
                
                $producto->setImagenPrincipal($rutaImagen);
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al subir la imagen: " . $e->getMessage();
            header("Location: ?c=productos&accion=view");
            exit();
        }
    } else {
        // Mantener la imagen actual
        $productoActual = $producto->getById($_POST['id']);
        if ($productoActual) {
            $producto->setImagenPrincipal($productoActual['imagen_principal']);
        }
    }
    
    if ($producto->update()) {
        // ========== PROCESAR VARIANTES ELIMINADAS ==========
        if (isset($_POST['deleted_variants']) && is_array($_POST['deleted_variants'])) {
            foreach ($_POST['deleted_variants'] as $variante_id) {
                if (!empty($variante_id)) {
                    $producto->deleteVariante($variante_id);
                }
            }
        }

        // ========== PROCESAR VARIANTES REACTIVADAS ==========
        if (isset($_POST['reactivate_variants']) && is_array($_POST['reactivate_variants'])) {
            foreach ($_POST['reactivate_variants'] as $variante_id) {
                if (!empty($variante_id)) {
                    $producto->reactivateVariante($variante_id);
                }
            }
        }

        // ========== PROCESAR VARIANTES (ACTUALIZAR O AGREGAR NUEVAS) ==========
        if (isset($_POST['variantes']) && is_array($_POST['variantes'])) {
            foreach ($_POST['variantes'] as $index => $v) {
                if (!empty($v['nombre_variante']) && isset($v['stock'])) {
                    $atributos = [];
                    if (!empty($v['talla'])) $atributos['talla'] = $v['talla'];
                    if (!empty($v['color'])) $atributos['color'] = $v['color'];
                    if (!empty($v['volumen_ml'])) $atributos['volumen_ml'] = $v['volumen_ml'];
                    if (!empty($v['spf'])) $atributos['spf'] = $v['spf'];
                    if (!empty($v['fragancia'])) $atributos['fragancia'] = $v['fragancia'];
                    if (!empty($v['tipo_piel'])) $atributos['tipo_piel'] = $v['tipo_piel'];

                    $imagen_variante = !empty($v['imagen_variante_actual']) ? $v['imagen_variante_actual'] : null;

                    if (isset($_FILES['imagen_variante']) && isset($_FILES['imagen_variante']['error'][$index]) && $_FILES['imagen_variante']['error'][$index] === UPLOAD_ERR_OK) {
                        try {
                            $fileArray = [
                                'name' => $_FILES['imagen_variante']['name'][$index],
                                'type' => $_FILES['imagen_variante']['type'][$index],
                                'tmp_name' => $_FILES['imagen_variante']['tmp_name'][$index],
                                'error' => $_FILES['imagen_variante']['error'][$index],
                                'size' => $_FILES['imagen_variante']['size'][$index]
                            ];
                            
                            $categoriaModel = new Categorias();
                            $categoria = $categoriaModel->searchById($_POST['id_categoria']);
                            $nombreCategoria = $categoria ? $categoria['nombre'] : 'sin_categoria';

                            $rutaImagenVariante = $producto->subirImagen(
                                $fileArray,
                                $nombreCategoria,
                                $_POST['nombre'] . '_' . $v['nombre_variante']
                            );
                            if ($rutaImagenVariante) {
                                $imagen_variante = $rutaImagenVariante;
                            }
                        } catch (Exception $e) {
                            // Ignorar error
                        }
                    }

                    $variante_data = [
                        'nombre_variante' => $v['nombre_variante'],
                        'atributos' => $atributos,
                        'precio_adicional' => isset($v['precio_adicional']) && $v['precio_adicional'] !== '' ? $v['precio_adicional'] : 0,
                        'stock' => isset($v['stock']) && $v['stock'] !== '' ? $v['stock'] : 0,
                        'imagen_variante' => $imagen_variante
                    ];

                    if (!empty($v['id'])) {
                        // Actualizar variante existente
                        $res = $producto->updateVariante($v['id'], $variante_data);
                    } else {
                        // Agregar nueva variante
                        $res = $producto->addVariante($_POST['id'], $variante_data);
                    }
                }
            }
        }

        $_SESSION['success'] = "Producto y sus variantes actualizados exitosamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el producto.";
    }
    header("Location: ?c=productos&accion=view");
    exit();
    break;
    
    case "delete":
        if (empty($_POST['id'])) {
            $_SESSION['error'] = "ID de producto no especificado.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $producto = new Productos();
        $producto->setId($_POST['id']);
        if ($producto->delete()) {
            $_SESSION['success'] = "Producto inhabilitado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al inhabilitar el producto.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    case "activate":
        if (empty($_POST['id'])) {
            $_SESSION['error'] = "ID de producto no especificado.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $producto = new Productos();
        $producto->setId($_POST['id']);
        if ($producto->activate()) {
            $_SESSION['success'] = "Producto activado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al activar el producto.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    // ==================== NUEVAS ACCIONES PARA VARIANTES ====================
    
    case "viewVariantes":
        // Ver variantes de un producto específico (modal o página)
        if (empty($_GET['id'])) {
            $_SESSION['error'] = "ID de producto no especificado.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $producto = (new Productos())->getById($_GET['id']);
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        // Retornar JSON para AJAX o cargar vista
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($producto);
            exit();
        }
        
        require_once __DIR__ . "/../Views/V_ProductosVariantes.php";
        break;
    
    case "addVariante":
        // Agregar variante a producto existente
        if (empty($_POST['id_producto']) || empty($_POST['nombre_variante']) || !isset($_POST['stock'])) {
            if (isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Datos incompletos para agregar variante.']);
                exit();
            }
            $_SESSION['error'] = "Datos incompletos para agregar variante.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $atributos = [];
        if (!empty($_POST['talla'])) $atributos['talla'] = $_POST['talla'];
        if (!empty($_POST['color'])) $atributos['color'] = $_POST['color'];
        if (!empty($_POST['volumen_ml'])) $atributos['volumen_ml'] = $_POST['volumen_ml'];
        if (!empty($_POST['spf'])) $atributos['spf'] = $_POST['spf'];
        if (!empty($_POST['fragancia'])) $atributos['fragancia'] = $_POST['fragancia'];
        if (!empty($_POST['tipo_piel'])) $atributos['tipo_piel'] = $_POST['tipo_piel'];
        
        $variante_data = [
            'nombre_variante' => $_POST['nombre_variante'],
            'atributos' => $atributos,
            'precio_adicional' => !empty($_POST['precio_adicional']) ? $_POST['precio_adicional'] : 0,
            'stock' => !empty($_POST['stock']) ? $_POST['stock'] : 0,
            'imagen_variante' => !empty($_POST['imagen_variante']) ? $_POST['imagen_variante'] : null
        ];
        
        $producto = new Productos();
        $variante_id = $producto->addVariante($_POST['id_producto'], $variante_data);
        
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            if ($variante_id) {
                echo json_encode(['success' => true, 'variante' => array_merge(['id' => $variante_id], $variante_data)]);
            } else {
                echo json_encode(['error' => 'Error al agregar la variante.']);
            }
            exit();
        }
        
        if ($variante_id) {
            $_SESSION['success'] = "Variante agregada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar la variante.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    case "updateVariante":
        // Actualizar variante existente
        if (empty($_POST['variante_id']) || empty($_POST['nombre_variante']) || !isset($_POST['stock'])) {
            $_SESSION['error'] = "Datos incompletos para actualizar variante.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $atributos = [];
        if (!empty($_POST['talla'])) $atributos['talla'] = $_POST['talla'];
        if (!empty($_POST['color'])) $atributos['color'] = $_POST['color'];
        if (!empty($_POST['volumen_ml'])) $atributos['volumen_ml'] = $_POST['volumen_ml'];
        if (!empty($_POST['spf'])) $atributos['spf'] = $_POST['spf'];
        if (!empty($_POST['fragancia'])) $atributos['fragancia'] = $_POST['fragancia'];
        if (!empty($_POST['tipo_piel'])) $atributos['tipo_piel'] = $_POST['tipo_piel'];
        
        $variante_data = [
            'nombre_variante' => $_POST['nombre_variante'],
            'atributos' => $atributos,
            'precio_adicional' => !empty($_POST['precio_adicional']) ? $_POST['precio_adicional'] : 0,
            'stock' => !empty($_POST['stock']) ? $_POST['stock'] : 0,
            'imagen_variante' => !empty($_POST['imagen_variante']) ? $_POST['imagen_variante'] : null
        ];
        
        $producto = new Productos();
        if ($producto->updateVariante($_POST['variante_id'], $variante_data)) {
            $_SESSION['success'] = "Variante actualizada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la variante.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    case "updateVarianteStock":
        // Actualizar solo el stock de una variante (rápido)
        if (empty($_POST['variante_id']) || !isset($_POST['stock'])) {
            $_SESSION['error'] = "Datos incompletos para actualizar stock.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $producto = new Productos();
        if ($producto->updateVarianteStock($_POST['variante_id'], $_POST['stock'])) {
            $_SESSION['success'] = "Stock actualizado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el stock.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    case "deleteVariante":
        // Eliminar variante (borrado lógico)
        if (empty($_POST['variante_id'])) {
            $_SESSION['error'] = "ID de variante no especificado.";
            header("Location: ?c=productos&accion=view");
            exit();
        }
        
        $producto = new Productos();
        if ($producto->deleteVariante($_POST['variante_id'])) {
            $_SESSION['success'] = "Variante eliminada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la variante.";
        }
        header("Location: ?c=productos&accion=view");
        exit();
        break;
    
    // ==================== ACCIONES ADICIONALES ÚTILES ====================
    
    case "getLowStock":
        // Productos con bajo stock (para dashboard)
        $productos = (new Productos())->getLowStock();
        $variantes = (new Productos())->getVariantesLowStock();
        
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'productos' => $productos,
                'variantes' => $variantes
            ]);
            exit();
        }
        
        require_once __DIR__ . "/../Views/V_ProductosLowStock.php";
        break;
    
    case "searchByText":
        // Búsqueda por texto (AJAX)
        if (empty($_GET['q'])) {
            echo json_encode([]);
            exit();
        }
        
        $productos = (new Productos())->searchByText($_GET['q']);
        header('Content-Type: application/json');
        echo json_encode($productos);
        exit();
        break;
    
    case "getProductoJson":
        // Obtener producto en JSON (para APIs)
        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no especificado']);
            exit();
        }
        
        $pModel = new Productos();
        $producto = $pModel->getById($_GET['id']);
        if ($producto) {
            $producto['categoria_nombre'] = $pModel->getNombreCategoria($producto['id']);
        }
        header('Content-Type: application/json');
        echo json_encode($producto);
        exit();
        break;
    
    default:
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        break;
}