<?php


namespace Centras\Api;


use Centras\Api\Messages\Common;
use Illuminate\Http\JsonResponse;

class Api
{
    /**
     *  Статусы
     */
    public const SUCCESS = 'success';
    public const ERROR   = 'error';

    /**
     * @var array
     */
    protected static array $body
        = [
            'headers' => [
                'status'  => null,
                'message' => null,
                'code'    => null,
            ],
            'data'    => [],
        ];

    /**
     * @param array $data
     * @param int $code
     * @param string $message
     * @return JsonResponse
     */
    public static function response(array $data = [], int $code = 200, string $message = Common::SUCCESFUL): JsonResponse
    {
        self::$body['headers']['code']    = $code;
        self::$body['headers']['status']  = ($code >= 200 && $code <= 299) ? self::SUCCESS : self::ERROR;
        self::$body['headers']['message'] = $message;
        self::$body['data']               = $data;

        return response()->json(self::$body, $code);
    }
}
