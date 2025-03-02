<?php

namespace Common;

use Engine\Container;
use Service\Output;

class CommonController
{
    private $output;

    public function __construct(Container $container)
    {
        $this->output = $container->get('output');
    }

    public function pageNotFound(): void
    {
        http_response_code(404);
        $this->output->load("common/404", ['error' => 'Page not found']);
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->output->load("common/403", ['error' => 'You do not have permission to access this page']);
    }
}
