<?php
declare(strict_types=1);

namespace App\Service;

use Psr\Http\Message\ServerRequestInterface;

class Jwt implements \Tuupola\Middleware\JwtAuthentication\RuleInterface
{
    /**
     * Stores all the options passed to the rule
     */
    private $options = [
        "path" => ["/"],
        "ignore" => [],
    ];


    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }


    public function __invoke(ServerRequestInterface $request): bool
    {
        $uri = "/" . $request->getUri()->getPath();
        $uri = preg_replace("#/+#", "/", $uri);

        foreach ((array)$this->options["force"] as $path) {
            $path = rtrim($path, "/");
            if (!!preg_match("@^{$path}(/.*)?$@", $uri)) {
                return true;
            }
        }

        /* If request path is matches ignore should not authenticate. */
        foreach ((array)$this->options["ignore"] as $ignore) {
            $ignore = rtrim($ignore, "/");
            if (!!preg_match("@^{$ignore}(/.*)?$@", $uri)) {
                return false;
            }
        }

        /* Otherwise check if path matches and we should authenticate. */
        foreach ((array)$this->options["path"] as $path) {
            $path = rtrim($path, "/");
            if (!!preg_match("@^{$path}(/.*)?$@", $uri)) {
                return true;
            }
        }

        return false;
    }
}