<?php
    namespace Routes;

    use Controllers\AuthController;
    use Controllers\CategoryController;
    use Controllers\ErrorController;
    use Controllers\ProductController;
    use Controllers\CartController;
    use Controllers\OrderController;
    use Lib\Router;
    use Lib\Middleware;

    class Routes{

        public static function index(){

            Router::add('GET', '/', function() {
                (new AuthController()) -> register();
            });

            Router::add('POST', '/', function() {
                (new AuthController()) -> register();
            });

            /* AUTH */
            Router::add('GET', 'register', function() {
                (new AuthController()) -> register();
            });

            Router::add('POST', 'register', function() {
                (new AuthController()) -> register();
            });

            Router::add('GET', 'Auth/login', function() {
                (new AuthController()) -> login();
            });

            Router::add('POST', 'Auth/login', function() {
                (new AuthController()) -> login();
            });

            Router::add('GET', 'logout', function() {
                (new AuthController()) -> logout();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'logout', function() {
                (new AuthController()) -> logout();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('POST', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('GET', 'Auth/resetPassword', function() {
                (new AuthController())->resetPassword();
            });

            Router::add('POST', 'Auth/resetPassword', function() {
                (new AuthController())->resetPassword();
            });

            /* PRODUCTOS */
            Router::add('GET', 'listProducts', function (){
                (new ProductController())->list();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'listProducts', function (){
                (new ProductController())->list();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'CreateProducts', function (){
                (new ProductController())->create();
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'CreateProducts', function (){
                (new ProductController())->create();
            },[Middleware::class, 'isAdmin']);

            Router::add('GET', 'EditProducts', function ($id) {
                (new ProductController())->edit((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'EditProducts', function ($id) {
                (new ProductController())->edit((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('GET', 'DeleteProducts', function ($id) {
                (new ProductController())->delete((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'DeleteProducts', function ($id) {
                (new ProductController())->delete((int)$id);
            },[Middleware::class, 'isAdmin']);

            /* CATEGORIAS */
             Router::add('GET', 'listCategories', function (){
                (new CategoryController())->list();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'listCategories', function (){
                (new CategoryController())->list();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'CreateCategories', function (){
                (new CategoryController())->create();
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'CreateCategories', function (){
                (new CategoryController())->create();
            },[Middleware::class, 'isAdmin']);

            Router::add('GET', 'EditCategory', function ($id) {
                (new CategoryController())->edit((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'EditCategory', function ($id) {
                (new CategoryController())->edit((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('GET', 'DeleteCategory', function ($id) {
                (new CategoryController())->delete((int)$id);
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'DeleteCategory', function ($id) {
                (new CategoryController())->delete((int)$id);
            },[Middleware::class, 'isAdmin']);

            /* ERRORES */
            Router::add('GET', '/not-found', function (){
                ErrorController::error404();
            });

            Router::add('POST', '/not-found', function (){
                ErrorController::error404();
            });

            /* CARRITO */
            Router::add('GET', 'cart', function () {
                (new CartController())->showCart();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'addToCart', function () {
                (new CartController())->addToCart();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'removeFromCart', function () {
                (new CartController())->removeFromCart();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'checkout', function () {
                (new CartController())->checkout();
            }, [Middleware::class, 'isLoggedIn']); 


            /* PEDIDOS */
            Router::add('GET', 'checkoutForm', function () {
                (new OrderController())->checkoutForm();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('POST', 'placeOrder', function () {
                (new OrderController())->placeOrder();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'orderSuccess', function () {
                (new OrderController())->orderSuccess();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'myOrders', function () {
                (new OrderController())->listUserOrders();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'adminOrders', function () {
                (new OrderController())->listAllOrders();
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'changeOrderStatus', function () {
                (new OrderController())->changeOrderStatus();
            },[Middleware::class, 'isAdmin']);

            /*Router::add('GET', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('POST', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('GET', 'Auth/resetPassword', function() {
                $token = $_GET['token'] ?? null;
                (new AuthController())->resetPassword($token);
            });

            Router::add('POST', 'Auth/resetPassword', function() {
                $token = $_POST['token'] ?? null;
                (new AuthController())->resetPassword($token);
            });*/

            Router::add('GET', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('POST', 'Auth/forgotPassword', function() {
                (new AuthController())->forgotPassword();
            });

            Router::add('GET', 'Auth/validateResetToken', function() {
                (new AuthController())->validateResetToken();
            });

            Router::add('POST', 'Auth/validateResetToken', function() {
                (new AuthController())->validateResetToken();
            });

            Router::add('GET', 'Auth/resetPassword', function() {
                (new AuthController())->resetPassword();
            });

            Router::add('POST', 'Auth/resetPassword', function() {
                (new AuthController())->resetPassword();
            });

            /*Router::add('GET', 'Auth/verifyEmail', function() {
                $token = $_GET['token'] ?? null;
                (new AuthController())->verifyEmail($token);
            });*/

            Router::add('GET', 'Auth/verifyEmail', function() {
                (new AuthController())->verifyEmail();
            });

            Router::add('POST', 'Auth/verifyEmail', function() {
                (new AuthController())->verifyEmail();
            });

            Router::add('GET', 'Auth/validateVerificationToken', function() {
                (new AuthController())->validateVerificationToken();
            });

            Router::add('POST', 'Auth/validateVerificationToken', function() {
                (new AuthController())->validateVerificationToken();
            });




            Router::add('POST', 'placeOrder', function () {
                (new OrderController())->placeOrder();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'orderSuccess', function () {
                (new OrderController())->orderSuccess();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'userOrders', function () {
                (new OrderController())->listUserOrders();
            }, [Middleware::class, 'isLoggedIn']); 

            Router::add('GET', 'adminOrders', function () {
                (new OrderController())->listAllOrders();
            },[Middleware::class, 'isAdmin']);

            Router::add('POST', 'changeOrderStatus', function () {
                (new OrderController())->changeOrderStatus();
            },[Middleware::class, 'isAdmin']);

            Router::dispatch();
        }
    }
?>