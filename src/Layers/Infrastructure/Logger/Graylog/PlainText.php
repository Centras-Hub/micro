<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;


class PlainText
{
    /**
     * @var array
     */
    protected array $payload = [
        "version" => "1.1",
        "host" => "kias",
        "title" => "new message",
        "request_id" => "2343",
        "partner_id" => "2",
        "order_id" => "234234",
        "payment_id" => "35235",
        "product_id" => "325423534",
        "payload" => [
            "some_shit" => "is hapened",
            "this_f" => "pedik",
            "tuff" => [
                "param1" => "param2",
                "pediki" => 1
            ]
        ]
    ];
}
