<?php
    namespace Routes;

    use Controllers\AuthController;
    use Controllers\CategoryController;
    use Controllers\ErrorController;
    use Controllers\ProductController;
    use Controllers\CartController;
    use Controllers\OrderController;
    use Lib\Router;

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
            }); 

            Router::add('POST', 'logout', function() {
                (new AuthController()) -> logout();
            });

            /* PRODUCTOS */
            Router::add('GET', 'listProducts', function (){
                (new ProductController())->list();
            });

            Router::add('POST', 'listProducts', function (){
                (new ProductController())->list();
            });

            Router::add('GET', 'CreateProducts', function (){
                (new ProductController())->create();
            });

            Router::add('POST', 'CreateProducts', function (){
                (new ProductController())->create();
            });

            Router::add('GET', 'EditProducts', function ($id) {
                (new ProductController())->edit((int)$id);
            });
            
            Router::add('POST', 'EditProducts', function ($id) {
                (new ProductController())->edit((int)$id);
            });
            
            Router::add('GET', 'DeleteProducts', function ($id) {
                (new ProductController())->delete((int)$id);
            });

            Router::add('POST', 'DeleteProducts', function ($id) {
                (new ProductController())->delete((int)$id);
            });

            /* CATEGORIAS */
            Router::add('GET', 'listCategories', function (){
                (new CategoryController())->list();
            });

            Router::add('POST', 'listCategories', function (){
                (new CategoryController())->list();
            });

            Router::add('GET', 'CreateCategories', function (){
                (new CategoryController())->create();
            });

            Router::add('POST', 'CreateCategories', function (){
                (new CategoryController())->create();
            });

            Router::add('GET', 'EditCategory', function ($id) {
                (new CategoryController())->edit((int)$id);
            });
            
            Router::add('POST', 'EditCategory', function ($id) {
                (new CategoryController())->edit((int)$id);
            });
            
            Router::add('GET', 'DeleteCategory', function ($id) {
                (new CategoryController())->delete((int)$id);
            });

            Router::add('POST', 'DeleteCategory', function ($id) {
                (new CategoryController())->delete((int)$id);
            });

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
            });

            Router::add('POST', 'addToCart', function () {
                (new CartController())->addToCart();
            });

            Router::add('POST', 'removeFromCart', function () {
                (new CartController())->removeFromCart();
            });

            Router::add('GET', 'checkout', function () {
                (new CartController())->checkout();
            });


            /* PEDIDOS */
            Router::add('GET', 'checkoutForm', function () {
                (new OrderController())->checkoutForm();
            });
            
            Router::add('POST', 'placeOrder', function () {
                (new OrderController())->placeOrder();
            });
            
            Router::add('GET', 'orderSuccess', function () {
                (new OrderController())->orderSuccess();
            });

            Router::add('GET', 'myOrders', function () {
                (new OrderController())->listUserOrders();
            });

            Router::add('GET', 'adminOrders', function () {
                (new OrderController())->listAllOrders();
            });
            
            Router::add('POST', 'changeOrderStatus', function () {
                (new OrderController())->changeOrderStatus();
            });


            Router::dispatch();
        }
    }
