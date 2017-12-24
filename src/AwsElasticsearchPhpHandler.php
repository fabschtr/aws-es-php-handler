<?php
namespace Fabschtr\AwsEsPhpHandler;


use Aws\Signature\SignatureV4;


class AwsElasticsearchPhpHandler
{
    private $signature;
    private $credentialProvider;

    /**
     * Set AWS credentials and region.
     *
     * AwsElasticsearchPhpHandler constructor.
     * @param $key
     * @param $secret
     * @param $region
     */
    public function __construct($key, $secret, $region)
    {
        $this->credentialProvider = \Aws\Credentials\CredentialProvider::fromCredentials(
            new \Aws\Credentials\Credentials($key, $secret)
        );
        $this->signature = new SignatureV4('es', $region);
    }

    /**
     * Returns handler.
     *
     * @param array $request
     * @return \Closure
     */
    public function __invoke(array $request)
    {
        $psr7Handler = \Aws\default_http_handler();
        $request['headers']['Host'][0] = parse_url($request['headers']['Host'][0])['host'];

        $psr7Request = new \GuzzleHttp\Psr7\Request(
            $request['http_method'],
            (new \GuzzleHttp\Psr7\Uri($request['uri']))
                ->withScheme($request['scheme'])
                ->withHost($request['headers']['Host'][0]),
            $request['headers'],
            $request['body']
        );

        $signedRequest = $this->signature->signRequest(
            $psr7Request,
            call_user_func($this->credentialProvider)->wait()
        );

        $response = $psr7Handler($signedRequest)->wait();

        return new \GuzzleHttp\Ring\Future\CompletedFutureArray([
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody()->detach(),
            'transfer_stats' => ['total_time' => 0],
            'effective_url' => (string)$psr7Request->getUri(),
        ]);
    }
}
