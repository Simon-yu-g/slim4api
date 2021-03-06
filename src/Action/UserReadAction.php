<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Logger;

/**
 * Action.
 */
final class UserReadAction
{
    /**
     * @var UserReader
     */
    private $userReader;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * The constructor.
     *
     * @param UserReader    $userReader The user reader
     * @param LoggerFactory $lf         The loggerFactory
     */
    public function __construct(UserReader $userReader, LoggerFactory $lf)
    {
        $this->userReader = $userReader;
        $this->logger = $lf->addFileHandler('error.log')->addConsoleHandler()->createInstance('error');
    }

    /**
     * Invoke.
     *
     * @param ServerRequestInterface $request  The request
     * @param ResponseInterface      $response The response
     * @param array                  $args     The route arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        // Collect input from the HTTP request
        $userId = (int) $args['id'];

        // Feed the logger
        $this->logger->debug("UserReadAction: id: {$userId}");

        // Invoke the Domain with inputs and retain the result
        $userData = $this->userReader->getUserDetails($userId);

        // Transform the result into the JSON representation
        $result = [
            'user_id' => $userData->id,
            'username' => $userData->username,
            'password' => $userData->password,
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
            'email' => $userData->email,
            'profile' => $userData->profile,
        ];

        // Build the HTTP response
        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
