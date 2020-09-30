<?php

namespace Tests\TestCase\Domain\User\Service;

use App\Domain\User\Repository\UserCreatorRepository;
use App\Domain\User\Service\UserCreator;
use App\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Tests\AppTestTrait;

/**
 * Tests.
 *
 * @internal
 * @coversNothing
 */
class UserCreatorTest extends TestCase
{
    use AppTestTrait;

    /**
     * Test.
     */
    public function testCreateUser(): void
    {
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class)->method('insertUser')->willReturn(1);

        $service = $this->container->get(UserCreator::class);

        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];

        $actual = $service->createUser($user);

        static::assertSame(1, $actual);
    }

    public function testCreateUserKoUserExists(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class)->method('UserExists')->with($user['username'], $user['email'])->willReturn(true);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $msg = 'User already exists with name ['.$user['username'].'] or email ['.$user['email'].']';
        $this->expectErrorMessage($msg);

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormMissingUsrName(): void
    {
        $user = [
            'username' => '',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormMissingPassword(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormMissingFirstName(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => '',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserOkFormMissingLastName(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => '',
            'email' => 'john.doe@example.com',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormMissingEmail(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => '',
            'profile' => 'users customers',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormMissingProfile(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => '',
        ];
        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }

    public function testCreateUserKoFormFormatEmail(): void
    {
        $user = [
            'username' => 'john.doe',
            'password' => '1234567',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'profile' => '',
        ];

        // Mock the required repository method
        $this->mock(UserCreatorRepository::class);
        $service = $this->container->get(UserCreator::class);

        $this->expectException(ValidationException::class);
        $this->expectErrorMessage('Please check your input.');

        $actual = $service->createUser($user);
    }
}
