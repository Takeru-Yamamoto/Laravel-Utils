<?php

namespace MyCustom\Utils\Http;

use MyCustom\Utils\BaseUtil;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

final class HttpUtil extends BaseUtil
{
    private string $url;
    private array $params;
    public Http $http;

    /* Response Variables */
    public ?Response $response;
    private ?bool $isSuccess;
    private ?int $statusCode;
    private array $responseHeaders;
    private mixed $responseBody;
    private ?string $responseReason;



    /* Method Variables */
    private ?string $method;


    /* Auth Variables */
    private ?string $auth;
    private ?string $userName;
    private ?string $password;


    /* Request Headers Variables */
    private array $requestHeaders;


    /* Body Format Variables */
    private ?string $bodyFormat;


    /* Timeout Variables */
    private ?int $timeout;
    private ?int $connectTimeout;


    /* Retry Variables */
    private ?int $retryTimes;
    private ?int $retrySleeps;


    /* .etc Variables */
    private ?int $maxRedirects;
    private bool $withoutRedirecting;
    private bool $withoutVerifying;
    private array $urlParams;


    function __construct(string $url, array $params)
    {
        parent::__construct(config("mycustoms.utils.logging_util_http", false), $this);

        $this->url      = $url;
        $this->params   = $params;
        $this->http     = new Http;

        $this->response        = null;
        $this->isSuccess       = null;
        $this->statusCode      = null;
        $this->responseHeaders = [];
        $this->responseBody    = null;
        $this->responseReason  = null;

        $this->method = null;

        $this->auth     = null;
        $this->userName = null;
        $this->password = null;

        $this->requestHeaders = [];

        $this->bodyFormat = null;

        $this->timeout        = null;
        $this->connectTimeout = null;

        $this->retryTimes  = null;
        $this->retrySleeps = null;

        $this->maxRedirects       = null;
        $this->withoutRedirecting = false;
        $this->withoutVerifying   = false;
        $this->urlParams          = [];
    }

    function __clone()
    {
        $this->url      = clone $this->url;
        $this->params   = clone $this->params;
        $this->http     = clone $this->http;

        $this->response        = clone $this->response;
        $this->isSuccess       = clone $this->isSuccess;
        $this->statusCode      = clone $this->statusCode;
        $this->responseHeaders = clone $this->responseHeaders;
        $this->responseBody    = clone $this->responseBody;
        $this->responseReason  = clone $this->responseReason;

        $this->method = clone $this->method;

        $this->auth     = clone $this->auth;
        $this->userName = clone $this->userName;
        $this->password = clone $this->password;

        $this->requestHeaders = clone $this->requestHeaders;

        $this->bodyFormat = clone $this->bodyFormat;

        $this->timeout        = clone $this->timeout;
        $this->connectTimeout = clone $this->connectTimeout;

        $this->retryTimes  = clone $this->retryTimes;
        $this->retrySleeps = clone $this->retrySleeps;

        $this->maxRedirects       = clone $this->maxRedirects;
        $this->withoutRedirecting = clone $this->withoutRedirecting;
        $this->withoutVerifying   = clone $this->withoutVerifying;
        $this->urlParams          = clone $this->urlParams;
    }

    public function params(): array
    {
        return [
            "url" => $this->url,
            "params" => $this->params,

            "isSuccess"       => $this->isSuccess,
            "statusCode"      => $this->statusCode,
            "responseHeaders" => $this->responseHeaders,
            "responseBody"    => $this->responseBody,
            "responseReason"  => $this->responseReason,

            "method" => $this->method,

            "auth"     => $this->auth,
            "userName" => $this->userName,
            "password" => $this->password,

            "requestHeaders" => $this->requestHeaders,

            "bodyFormat" => $this->bodyFormat,

            "timeout"        => $this->timeout,
            "connectTimeout" => $this->connectTimeout,

            "retryTimes"  => $this->retryTimes,
            "retrySleeps" => $this->retrySleeps,

            "maxRedirects"       => $this->maxRedirects,
            "withoutRedirecting" => $this->withoutRedirecting,
            "withoutVerifying"   => $this->withoutVerifying,
            "urlParams"          => $this->urlParams,
        ];
    }

    /* Response */
    private function checkResponse(): self
    {
        $this->isSuccess       = $this->response->successful();
        $this->statusCode      = $this->response->status();
        $this->responseHeaders = $this->response->headers();
        $this->responseBody    = $this->response->json();
        $this->responseReason  = $this->response->reason();

        if ($this->isLogging) {
            littleEmphasisLogStart("RESPONSE LOG");

            infoLog("isSuccess: " . $this->isSuccess);
            infoLog("statusCode: " . $this->statusCode);
            infoLog("headers: " . jsonEncode($this->responseHeaders));
            infoLog("body: " . jsonEncode($this->responseBody));
            infoLog("reason: " . $this->responseReason);

            littleEmphasisLogEnd("RESPONSE LOG");

            emphasisLogEnd("HTTP CONNECT");
        }

        return $this;
    }
    public function hasResponse(): bool
    {
        return !is_null($this->response);
    }
    public function isSuccess(): bool
    {
        return !is_null($this->isSuccess) && $this->isSuccess;
    }
    public function statusCode(): ?int
    {
        return $this->isSuccess() ? $this->statusCode : null;
    }
    public function headers(): array
    {
        return $this->responseHeaders;
    }
    public function body(): mixed
    {
        return $this->responseBody;
    }
    public function bodyAsString(): ?string
    {
        return $this->isSuccess() ? $this->response->body() : null;
    }
    public function bodyAsObject(): mixed
    {
        return $this->isSuccess() ? $this->response->object() : null;
    }
    public function bodyAsCollection(): ?Collection
    {
        return $this->isSuccess() ? $this->response->collect() : null;
    }
    public function reason(): ?string
    {
        return $this->isSuccess() ? $this->responseReason : null;
    }


    /* Method */
    private function send(string $method): self
    {
        $this->method = $method;

        if ($this->isLogging) {
            emphasisLogStart("HTTP CONNECT");

            infoLog("url: " . $this->url);
            infoLog("params: " . jsonEncode($this->params));

            infoLog("method: " . $this->method);

            if (!is_null($this->auth)) infoLog("auth: " . $this->auth);

            if (!empty($this->requestHeaders)) infoLog("requestHeaders: " . jsonEncode($this->requestHeaders));

            if (!is_null($this->bodyFormat)) infoLog("bodyFormat: " . $this->bodyFormat);

            if (!is_null($this->timeout)) infoLog("timeout: " . $this->timeout);
            if (!is_null($this->connectTimeout)) infoLog("connectTimeout: " . $this->connectTimeout);

            if (!is_null($this->retryTimes)) infoLog("retryTimes: " . $this->retryTimes);
            if (!is_null($this->retrySleeps)) infoLog("retrySleeps: " . $this->retrySleeps);

            if (!is_null($this->maxRedirects)) infoLog("maxRedirects: " . $this->maxRedirects);
            if ($this->withoutRedirecting) infoLog("withoutRedirecting: " . $this->withoutRedirecting);
            if ($this->withoutVerifying) infoLog("withoutVerifying: " . $this->withoutVerifying);
            if (!empty($this->urlParams)) infoLog("urlParams: " . jsonEncode($this->urlParams));
        }

        $this->response = match ($method) {
            "get"    => $this->http->get($this->url, $this->params),
            "post"   => $this->http->post($this->url, $this->params),
            "put"    => $this->http->put($this->url, $this->params),
            "delete" => $this->http->delete($this->url, $this->params),
            "head"   => $this->http->head($this->url, $this->params),
            "patch"  => $this->http->patch($this->url, $this->params),
        };

        return $this->checkResponse();
    }
    public function get(): self
    {
        return $this->send("get");
    }
    public function post(): self
    {
        return $this->send("post");
    }
    public function put(): self
    {
        return $this->send("put");
    }
    public function delete(): self
    {
        return $this->send("delete");
    }
    public function head(): self
    {
        return $this->send("head");
    }
    public function patch(): self
    {
        return $this->send("patch");
    }


    /* Auth */
    public function basicAuth(string $userName, string $password): self
    {
        $this->auth     = "Basic";
        $this->userName = $userName;
        $this->password = $password;

        $this->http = $this->http->withBasicAuth($userName, $password);
        return $this;
    }
    public function digestAuth(string $userName, string $password): self
    {
        $this->auth     = "Digest";
        $this->userName = $userName;
        $this->password = $password;

        $this->http = $this->http->withDigestAuth($userName, $password);
        return $this;
    }


    /* Headers */
    public function requestHeaders(array $headers): self
    {
        $this->requestHeaders = array_merge_recursive($this->requestHeaders, $headers);

        $this->http = $this->http->withHeaders($headers);
        return $this;
    }
    public function contentType(string $contentType): self
    {
        return $this->requestHeaders(["Content-Type" => $contentType]);
    }
    public function bearerToken(string $token, $type = "Bearer"): self
    {
        return $this->requestHeaders(["Authorization" => trim($type . ' ' . $token)]);
    }
    public function userAgent(string|bool $userAgent): self
    {
        return $this->requestHeaders(["User-Agent" => trim($userAgent)]);
    }
    public function accept(string $contentType): self
    {
        return $this->requestHeaders(["Accept" => $contentType]);
    }


    /* Body Format */
    public function bodyFormat(string $bodyFormat): self
    {
        $this->bodyFormat = $bodyFormat;

        $this->http = $this->http->bodyFormat($bodyFormat);
        return $this;
    }


    /* Timeout */
    public function timeout(int $seconds = 30): self
    {
        $this->timeout = $seconds;

        $this->http = $this->http->timeout($seconds);
        return $this;
    }
    public function connectTimeout(int $seconds = 10): self
    {
        $this->connectTimeout = $seconds;

        $this->http = $this->http->connectTimeout($seconds);
        return $this;
    }


    /* Retry */
    public function retry(int $times, int $sleepMilliseconds = 0, ?callable $when = null): self
    {
        $this->retryTimes = $times;
        $this->retrySleeps = $sleepMilliseconds;

        $this->http = $this->http->retry($times, $sleepMilliseconds, $when);
        return $this;
    }


    /* etc. */
    public function maxRedirects(int $max): self
    {
        $this->maxRedirects = $max;

        $this->http = $this->http->maxRedirects($max);
        return $this;
    }
    public function withoutRedirecting(): self
    {
        $this->withoutRedirecting = true;

        $this->http = $this->http->withoutRedirecting();
        return $this;
    }
    public function withoutVerifying(): self
    {
        $this->withoutVerifying = true;

        $this->http = $this->http->withoutVerifying();
        return $this;
    }
    public function urlParams(array $urlParams): self
    {
        $this->urlParams = $urlParams;

        $this->http = $this->http->withUrlParameters($urlParams);
        return $this;
    }


    /* Useful */
    public function acceptJson(): self
    {
        return $this->accept("application/json");
    }

    public function withBody(string $content, string $contentType = "application/json"): self
    {
        $this->http = $this->http->withBody($content, $contentType);

        return $this->bodyFormat("body")->contentType($contentType);
    }
    public function asJson(): self
    {
        return $this->bodyFormat("json")->contentType("application/json");
    }
    public function asForm(): self
    {
        return $this->bodyFormat("form_params")->contentType("application/x-www-form-urlencoded");
    }
    public function asMultipart(): self
    {
        return $this->bodyFormat("multipart");
    }
}
