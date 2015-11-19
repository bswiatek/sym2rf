<?php
namespace Khepin\GithubAuthBundle\Security\Github;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Khepin\GithubAuthBundle\Security\Github\GithubUserToken;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationListener extends AbstractAuthenticationListener
{
    protected $client_id;

    protected $client_secret;

    protected function attemptAuthentication(Request $request)
    {
        $client = new \Guzzle\Http\Client('https://github.com/login/oauth/access_token');
        $req = $client->post('', null, [
            // 'client_id' => 'f77f000b2dfc717ade2a',
            // 'client_secret' => '42967a6f718a83e5f85ad609292a78fce81dd46d',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $request->query->get('code')
        ])->setHeader('Accept', 'application/json');

        $res = $req->send()->json();
        $access_token = $res['access_token'];

        $client = new \Guzzle\Http\Client('https://api.github.com');
        $req = $client->get('/user');
        $req->getQuery()->set('access_token', $access_token);
        $res = $req->send()->json();
        $email = $res['email'];

        $token = new GithubUserToken();
        $token->setCredentials($email);

        return $this->authenticationManager->authenticate($token);
    }

    public function setClientId($id)
    {
        $this->client_id = $id;
    }

    public function setClientSecret($secret)
    {
        $this->client_secret = $secret;
    }
}