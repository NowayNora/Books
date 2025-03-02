<?php

namespace Engine;

use Service\Session;
use Engine\Container;

class Router
{
    public $action;
    public $path;
    private $session;
    private $container;  // Thêm container
    private $permissions;

    public function __construct(Session $session, Container $container)
    {
        $this->session = $session;
        $this->container = $container;  // Gán container
        $this->permissions = include "system/config/permissions.php";

        $url_action = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if (isset($_REQUEST['route'])) {
            $path = $_REQUEST['route'];
        } else {
            $path = $this->getPathFromSeoUrl($url_action);
        }

        $this->action = $url_action;
        $this->path = $path;
    }

    public function parsePath(): array
    {
        if (!$this->authorize()) {
            $file = 'src/common/controller/CommonController.php';
            $method = 'forbidden';
            $class = "Common\\CommonController";
            return ["file" => $file, "method" => $method, "class" => $class];
        }

        $url_split = explode('/', $this->path);

        $file = "src/" . $url_split[0] . '/controller/' . ucfirst($url_split[1]) . 'Controller.php';
        $class = ucfirst($url_split[1]) . "\\" . ucfirst($url_split[1]) . 'Controller';
        $method = $url_split[2];

        if (!$this->isValidPath($file, $class, $method)) {
            $file = 'src/common/controller/CommonController.php';
            $method = 'pageNotFound';
            $class = "Common\\CommonController";
        }

        return [
            "file" => $file,
            "method" => $method,
            "class" => $class
        ];
    }

    private function authorize(): bool
    {
        $uri = $this->action;
        if (in_array($uri, $this->permissions['public'])) {
            return true;
        }
        if (!$this->session->isValid() || !$this->session->get('user_logged_in')) {
            header('Location: /login');
            exit;
        }
        $userRole = $this->session->get('user.role');
        if (isset($this->permissions['protected'][$uri]) && in_array($userRole, $this->permissions['protected'][$uri])) {
            return true;
        }
        $logger = $this->container->get('logger');  // Sử dụng container thay vì session
        $logger->log("Unauthorized access to $uri by user role: " . ($userRole ?? 'none'), 'warning');
        return false;
    }

    private function isValidPath($file, $class, $method): bool
    {
        $is_controller_ok = true;
        if (!file_exists($file)) {
            $is_controller_ok = false;
        }
        if (method_exists($class, $method) === false) {
            $is_controller_ok = false;
        }
        return $is_controller_ok;
    }

    private function getPathFromSeoUrl(string $url_action): string
    {
        $routes = include "system/config/routes.php";
        if (in_array($url_action, array_keys($routes))) {
            return $routes[$url_action];
        } else {
            return "common/common/pageNotFound";
        }
    }
}
