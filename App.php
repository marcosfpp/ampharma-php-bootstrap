<?php
/**
 * app/Core/App.php
 * -----------------------------------------------------------
 * Router simples baseado em URL amigável:
 *   /controller/metodo/param1/param2
 *
 * Exemplos:
 *   URLROOT/produtos               -> ProdutosController::index()
 *   URLROOT/produtos/show/12       -> ProdutosController::show(12)
 *   URLROOT/                       -> HomeController::index()  (padrão)
 * -----------------------------------------------------------
 */

class App
{
    protected string $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1) Controller (se existir na URL e o arquivo existir)
        if (isset($url[0]) && file_exists(APPROOT . '/app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once APPROOT . '/app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller();

        // 2) Método (se existir na URL e for um método público do controller)
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // 3) Parâmetros restantes
        $this->params = $url ? array_values($url) : [];

        // 4) Executa o Controller->Método(params)
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Lê a query string "url" (definida no .htaccess) e transforma
     * em array: ['produtos', 'show', '12']
     */
    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
