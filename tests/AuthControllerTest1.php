<?php

declare(strict_types=1);

namespace tests;

use app\controller\AuthController;
use app\model\AuthDAL;
use app\Helpers\Cookies;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

class AuthControllerTest1 extends TestCase
{
    /**
     * @param array<string, mixed> $input
     * @throws Exception
     * @throws RandomException
     */
    #[DataProvider('loginDataProvider')] public function testLogin(array $input): void
    {
        $authDAL = $this->createMock(AuthDAL::class);
        $cookies = $this->createMock(Cookies::class);

        $controller = new AuthController($authDAL, $cookies);

        $authDAL->expects($this->once())
            ->method('emailExists')
            ->with($input['email'])
            ->willReturn(true);

        $authDAL->expects($this->once())
            ->method('getPassword')
            ->with($input['email'])
            ->willReturn($input['password']);

        $controller->login();

        $this->assertEquals($input['email'], $_SESSION['user_email'] ?? null);
        $this->assertEquals($input['password'], $_SESSION['user_password'] ?? null);
    }

    public static function loginDataProvider(): array
    {
        return [
            [
                'email' => 'test@example.com',
                'password' => 'hashed_secret',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @throws Exception
     */
    #[DataProvider('registerDataProvider')] public function testRegister(array $input): void
    {
        $authDAL = $this->createMock(AuthDAL::class);
        $cookies = $this->createMock(Cookies::class);

        $controller = new AuthController($authDAL, $cookies);

        $authDAL->expects($this->once())
            ->method('emailExists')
            ->with($input['email'])
            ->willReturn(false);

        $authDAL->expects($this->once())
            ->method('studentnoExists')
            ->with($input['student_no'])
            ->willReturn(false);

        try {
            $controller->register();
        } catch (RandomException) {

        }

        $this->assertEquals($input['student_no'], $_SESSION['user_student_no'] ?? null);
        $this->assertEquals($input['fullname'], $_SESSION['user_fullname'] ?? null);
        $this->assertEquals($input['address'], $_SESSION['user_address'] ?? null);
        $this->assertEquals($input['dateOfBirth'], $_SESSION['user_dateOfBirth'] ?? null);
        $this->assertEquals($input['gender'], $_SESSION['user_gender'] ?? null);
        $this->assertEquals($input['phone_number'], $_SESSION['user_phone_number'] ?? null);
        $this->assertEquals($input['email'], $_SESSION['user_email'] ?? null);
        $this->assertEquals($input['password'], $_SESSION['user_password'] ?? null);
    }

    public static function registerDataProvider(): array
    {
        return [
            [
                'student_no' => '123456',
                'fullname' => 'John Doe',
                'address' => '123 Main St',
                'dateOfBirth' => '1990-01-01',
                'gender' => 'Male',
                'phone_number' => '123-456-7890',
                'email' => 'test@example.com',
                'password' => 'hashed_secret',
            ],
        ];
    }
}

