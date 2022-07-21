<?php
    session_start();
    require "database.php";
    //Ve si hay un usuario en sesión actualmente y carga los usuarios
    if (isset($_SESSION['user_id'])) {
        $records = $connn->prepare('SELECT * FROM users WHERE id = :id');
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $user = null;

        if (count($results) > 0) {
            $user = $results;
        }
    }
    include "conexion.php";
?>
<!doctype html>
<html lang="es">
    <head>
        <!--HEAD-->
        <?php require 'partials/head.php' ?>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <link href='./jquery-bar-rating-master/dist/themes/fontawesome-stars.css' rel='stylesheet' type='text/css'>
            
        <link href="./css/css-carta.css" rel="stylesheet">
    </head>
    <body class="ft-menu" id="body-pag" onload="alIniciar()">
        <!--HEADER-->
        <header>
            <!--NAV-->
            <?php require 'partials/header_menu.php' ?>
            <!-- Carta -->
            <div class="f-menu" id="Carta-pag">
                <div class="caja-trans">
                    <div class="container-fluid d-flex align-items-center justify-content-center vh-100">
                        <div class="row d-flex justify-content-center text-center anim-inic">
                            <div class="col-md-10 featurette text-white">
                                <!-- Heading -->
                                <h2 class="display-4 mb-2 featurette-heading">MENU.</h2>
                                
                                <hr class="hr-light">
                                <!-- Descripcion -->
                                <h4 class="my-4 lead">La exelencia de la cocina en Ratatouille</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="d-flex justify-content-center h-100 caja-trans">
            <!--BODEGA-->
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h5 class="modal-title text-primary" id="exampleModalLabel">Agregar Plato</h5>
                        </div>
                        <div class="modal-body">
                            <div> 
                                <form action="admin/enviar_p.php" class="form" role="form" autocomplete="off" method="get">
                                    <label>ID del Plato</label>
                                    <center><input name="id" type="text" class="text-center" size="14" required></center>
                                    <label>Nombre del Plato</label>
                                    <center><input name="n_plato" class="text-center"  type="text" size="14" required pattern=".*\S+.*"></center>
                                    <label>Precio del Plato</label>
                                    <center><input name="p_plato" class="text-center" type="number" size="14" required pattern=".*\S+.*"></center>
                                    <<label>Imagen</label>
                                    <center><input name="imagen" class="text-center" type="text" size="14" required pattern=".*\S+.*"   ></center>
                                    <input type="submit" class="btn btn-primary mt-4" value="Crear Producto"></button>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="featurette">
                <hr class="featurette-divider">
                <h2 class="display-4 mb-3 featurette-heading">CARTA</h2>
                <table>
                    <thead>
                        <?php if(!empty($user) && $user['roles'] === 'admin'): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar Plato </button>
                        <?php endif ?>
                    </thead>
                    <tbody>
                    <?php include "./mostrar_estrella_carta.php"; ?>
                    </tbody>
                </table>
                <hr class="featurette-divider">
                
                <!--FOOTER-->
                <?php require 'partials/footer_menu.php' ?> 

            </div>
            
        </main>
        <!-- Bootstrap JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" 
                crossorigin="anonymous">
        </script>
        <!-- JavaScript -->
        <script src="app.js"></script>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./jquery-bar-rating-master/dist/jquery.barrating.min.js"></script>
    <!-- Invocar puntaje de Estrellas -->
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#star_rating_<?php echo $id_producto; ?>').barrating('set',<?php echo $rating; ?>);
        });
        //Función para ver el puntaje que ha votado el cliente a traves de ajax
        $(function () {
            $('.rating').barrating({
                theme: 'fontawesome-stars',
                onSelect: function (value, text, event) {
                    
                    var el = this;
                    var el_id = el.$elem.data('id');
                    
                    if (typeof (event) !== 'undefined') {
                        var split_id = el_id.split("_");
                        var id_producto = split_id[1];
                        $.ajax({
                            url: './ajax_star_rating_carta.php',
                            type: 'POST',
                            data: {
                                id_producto: id_producto,
                                rating: value
                            },
                            dataType: 'json',
                            success: function (data) {
                                var average = data['numRating'];
                                $('#numeric_rating_' + id_producto).text(average);
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                alert(textStatus, errorThrown);
                                console.log(jqXHR);
                                console.log(errorThrown);
                            }
                        });
                    }
                }
            });
        });
    </script>
</html>
