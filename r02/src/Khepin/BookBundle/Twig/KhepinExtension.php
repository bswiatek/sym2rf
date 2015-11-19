<?php
namespace Khepin\BookBundle\Twig;

class KhepinExtension extends \Twig_Extension
{
    protected $javascripts = [];

    public function __construct()
    {
        $this->environment = new \Twig_Environment(new \Twig_Loader_String());
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('jslater', [$this, 'jslater']),
            new \Twig_SimpleFunction('jsnow', [$this, 'jsnow'])
        ];
    }

    public function jslater($src)
    {
        $this->javascripts[] = $src;
    }

    public function jsnow()
    {
        $template = '
            {% for script in scripts %}
            <script type="text/javascript" src="{{script}}" />
            {% endfor %}
        ';

        $scripts = array_unique($this->javascripts);
        return $this->environment->render($template, compact('scripts'));
    }

    public function getName()
    {
        return 'khepin_extension';
    }
}