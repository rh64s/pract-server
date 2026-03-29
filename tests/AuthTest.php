<?php

use Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Src\Auth\Auth;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . "/..";
        $GLOBALS['app'] = new Src\Application(new Src\Settings([
            'app' => include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php',
            'db' => include $_SERVER['DOCUMENT_ROOT'] . '/config/db.php',
            'path' => include $_SERVER['DOCUMENT_ROOT'] . '/config/path.php',
        ]));

        if (!function_exists('app'))
        {
            function app()
            {
                return $GLOBALS['app'];
            }
        }
    }

    /* ==========================================================
     *                          LOGIN
     * ==========================================================
     * */

    #[DataProvider('additionProviderLogin')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testLogin(string $httpMethod, array $userData, string $message): void {
        $request = $this->createMock(\Src\Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = $httpMethod;

        $result = (new \Controllers\AuthController())->login($request);
        if (!empty($result)) {
            $message = '/' . preg_quote($message, '/') . '/';
            $this->expectOutputRegex($message);
            return;
        }

        $this->assertContains($message, $result);
    }

    public static function additionProviderLogin(): array
    {
        return [
            ['GET', ['login' => '', 'password' => ''], '<h3></h3>'],
            ['POST', ['login' => 'admin', 'password' => 'admin'], '<h2>Профиль</h2>'],
            ['POST', ['login' => md5(time()), 'password' => md5(time())], '<h3>Неправильный логин или пароль</h3>'],
        ];
    }

    /* ==========================================================
     *                          LOGOUT
     * ==========================================================
     * */

    #[DataProvider('additionProviderLogout')]
    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    public function testLogout(array $userData, string $expectedTitle): void
    {
        $request = $this->createMock(\Src\Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = 'POST';
        (new \Controllers\AuthController())->login($request);
        ob_start();
        $this->assertTrue(Auth::check());
//            $request = $this->createMock(\Src\Request::class);
        $request->method = 'GET';
        (new \Controllers\AuthController())->logout();
        // after logout its still on profile page. fix it
        $this->expectOutputRegex('/' . preg_quote($expectedTitle, '/') . '/');
        ob_end_clean();
    }

    public static function additionProviderLogout(): array
    {
        return [
            [['login' => 'admin', 'password' => 'admin'], '<h2>Войдите в систему</h2>'],
        ];
    }
}
