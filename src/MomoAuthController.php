<?php
/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zengkv\Auth\Momo;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class MomoAuthController implements RequestHandlerInterface
{
    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(Request $request): ResponseInterface
    {

        $queryParams = $request->getQueryParams();

        $openid = array_get($queryParams, 'openid');
        if (! $openid) {
            $authUrl = "https://momo.mozigu.cn/editor/auth/";
            return new RedirectResponse($authUrl);
        }

        $apiUrl = "https://momo.mozigu.cn/editor/auth/api?openid=$openid";
        $info = file_get_contents($apiUrl);
        $info = json_decode($info, true);
        if(! $info["openid"]) {
            throw new Exception('Invalid Openid');
        }

        $user = [
            'id'=> $info["openid"],
            // 'email' => '740832946@qq.com',
            'headimg' => $info["headimg"],
            'nickname' => $info["name"]
        ];
        
        return $this->response->make(
            'momo', $user['id'],
            function (Registration $registration) use ($user) {
                $registration
                    ->provideAvatar($user['headimg'])
                    ->suggestUsername($user['nickname'])
                    // ->provide("username", $user["nickname"])
                    ->setPayload($user);
                // $registration->provideTrustedEmail($user["email"]);
            }
        );
    }
}
