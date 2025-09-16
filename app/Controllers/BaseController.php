<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Automatically loaded helpers
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'text'];

    /**
     * Common services available in all controllers
     */
    protected $session;
    protected $email;

    /**
     * Init controller
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do not edit this line
        parent::initController($request, $response, $logger);

        // Load commonly used services
        $this->session = service('session');   // Session library
        $this->email   = service('email');     // Email service

        // You can preload more models or libraries here if needed
        // Example: $this->userModel = new \App\Models\UserModel();
    }

    /**
     * Flash message helper for easy redirects
     *
     * @param string $key
     * @param string $message
     */
    protected function flash(string $key, string $message)
    {
        $this->session->setFlashdata($key, $message);
    }

    /**
     * Redirect back with error
     *
     * @param string $message
     */
    protected function backWithError(string $message)
    {
        return redirect()->back()->with('error', $message);
    }

    /**
     * Redirect back with success
     *
     * @param string $message
     */
    protected function backWithSuccess(string $message)
    {
        return redirect()->back()->with('success', $message);
    }
}
