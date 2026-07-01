<?php
/**
 * app/Core/Controller.php
 * -----------------------------------------------------------
 * Controller base. Todos os controllers da aplicação
 * (HomeController, ProdutosController, CarrinhoController...)
 * devem "extends Controller" para herdar model() e view().
 * -----------------------------------------------------------
 */

class Controller
{
    /**
     * Carrega um Model, ex: $this->model('ProdutoModel')
     */
    public function model(string $model)
    {
        require_once APPROOT . '/app/Models/' . $model . '.php';
        return new $model();
    }

    /**
     * Carrega uma View e injeta dados nela.
     * Ex: $this->view('produtos/index', ['produtos' => $lista]);
     */
    public function view(string $view, array $data = [])
    {
        $viewFile = APPROOT . '/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Extrai o array $data em variáveis individuais (ex: $data['produtos'] -> $produtos)
            extract($data);
            require_once $viewFile;
        } else {
            die('View "' . $view . '" não encontrada em ' . $viewFile);
        }
    }
}
