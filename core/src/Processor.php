<?php

namespace App;

use Error;
use Exception;
use Slim\Http\Response;

class Processor
{
    /** @var Container */
    protected $container;
    protected $properties = [];
    protected $scope = '';


    /**
     * Processor constructor.
     *
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * @return Response
     */
    public function process()
    {
        if (!$this->checkScope()) {
            return $this->failure('У вас нет разрешения ' . $this->scope . '/' . strtolower($this->container->request->getMethod()));
        }
        $this->setProperties(
            $this->container->request->isGet()
                ? $this->container->request->getQueryParams()
                : $this->container->request->getParams()
        );

        $method = strtolower($this->container->request->getMethod());

        if (!method_exists($this, $method)) {
            return $this->failure('Указан несуществующий метод процессора', 404);
        }

        try {
            return $this->{$method}();
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        } catch (Error $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @return bool
     */
    protected function checkScope()
    {
        if ($this->container->request->isOptions() || empty($this->scope)) {
            return true;
        }

        // Allow access for all actions in this processor
        if (in_array($this->scope, $this->container->user_scopes)) {
            return true;
        }
        // Allow access only for this action
        $scope = $this->scope . '/' . strtolower($this->container->request->getMethod());
        if (in_array($scope, $this->container->user_scopes)) {
            return true;
        }

        return false;
    }


    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    protected function getProperty($key, $default = null)
    {
        return isset($this->properties[$key])
            ? $this->properties[$key]
            : $default;
    }


    /**
     * @param $key
     * @param $value
     */
    protected function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }


    /**
     * @param $key
     */
    protected function unsetProperty($key)
    {
        unset($this->properties[$key]);
    }


    /**
     * @return array
     */
    protected function getProperties()
    {
        return $this->properties;
    }


    /**
     * @param array $properties
     */
    protected function setProperties(array $properties)
    {
        $this->properties = $properties;
    }


    /**
     * @return Response
     */
    public function options()
    {
        return $this->success()
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT, PATCH, UPDATE')
            ->withHeader('Access-Control-Allow-Origin', $this->container->request->getHeader('HTTP_ORIGIN'));
    }


    /**
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    public function success($data = [], $code = 200)
    {
        // prolong token if it will expire in 2 hours
        /*if ($this->container->token && $this->container->token['exp'] < (time() + 7200)) {
            $data['token'] = $this->container->makeToken($this->container->token['id']);
        }*/

        return $this->container->response->withJson($data, $code, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ->withHeader('Access-Control-Allow-Origin', $this->container->request->getHeader('HTTP_ORIGIN'));
    }


    /**
     * @param string $message
     * @param int $code
     *
     * @return Response
     */
    public function failure($message = '', $code = 422)
    {
        return $this->container->response->withJson($message, $code, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ->withHeader('Access-Control-Allow-Origin', $this->container->request->getHeader('HTTP_ORIGIN'));
    }
}