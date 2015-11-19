# Github Auth Bundle

This bundle allows authentication of your users to your website through their
GitHub accounts.

**ATTENTION** this bundle requires the use of FOSUserBundle

## Installation

Through composer, add

    "khepin/github-auth-bundle": "1.5.78"

to your composer.json

## Configuration

You need to setup an app on GitHub (link), once you have your client_id and
client_secret, in config.yml add:

    khepin_github_auth:
        client_id:      xxxx
        client_secret:  xxxx

Then in your security.yml:

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username
        github_provider:
            id: khepin.github.user_provider

### Advanced config

You can provide your own user provider class:

    khepin_github_auth:
        user_provider_class: Acme\Provider\UserProvider

...

