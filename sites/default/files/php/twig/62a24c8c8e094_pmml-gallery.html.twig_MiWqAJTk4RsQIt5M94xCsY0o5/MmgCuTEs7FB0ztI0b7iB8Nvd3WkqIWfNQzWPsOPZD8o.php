<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/pmml_gallery/templates/pmml-gallery.html.twig */
class __TwigTemplate_f7de0067a0e1860d9069c31ec0c2111bf7a214624911736debfaab57bd575629 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "

<div ";
        // line 3
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "lightbox-gallery"], "method", false, false, true, 3), 3, $this->source), "html", null, true);
        echo ">
    <div class=\"lightbox-gallery-row\">
        ";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 6
            echo "            <div class=\"image-block\">
                <div class=\"image-block-data\">
                    ";
            // line 8
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["row"], 8, $this->source), "html", null, true);
            echo "
                </div>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        echo "    </div>
</div>

<div class=\"lightbox-gallery-modal\" id=\"lightBoxGallery\">
    <div class=\"lightbox-gallery-modal-header\">
        <a href=\"\" class=\"gallery-close-icon\"></a>
    </div>
    <div class=\"lightbox-gallery-modal-body\">
        <div class=\"lightbox-gallery-left gallery-clearfix\">
            
        </div>
        <div class=\"lightbox-gallery-right gallery-clearfix\">
            <div class=\"lightbox-gallery-title\">
                
            </div>
            <div class=\"lightbox-gallery-button\">
                
            </div>
        </div>
    </div>
    <div class=\"lightbox-gallery-modal-footer\">
        
    </div>
    <div class=\"lightbox-gallery-controls\">
        <div class=\"left-control\">
            <a href=\"\"> &#8249; </a>
        </div>
        <div class=\"right-control\">
            <a href=\"\"> &#8250; </a>
        </div>

    </div>
</div>
    

";
    }

    public function getTemplateName()
    {
        return "modules/pmml_gallery/templates/pmml-gallery.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 12,  56 => 8,  52 => 6,  48 => 5,  43 => 3,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/pmml_gallery/templates/pmml-gallery.html.twig", "/Applications/MAMP/htdocs/LEARNING/DRUPAL/drupal10/modules/pmml_gallery/templates/pmml-gallery.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 5);
        static $filters = array("escape" => 3);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for'],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
