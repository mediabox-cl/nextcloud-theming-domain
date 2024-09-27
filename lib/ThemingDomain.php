<?php

namespace OCA\ThemingDomain;

use OCA\ThemingDomain\AppInfo\Application;
use OCP\IConfig;
use OCP\IRequest;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;

class ThemingDomain
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param IRequest $request
     * @param IConfig $config
     */
    public function __construct(
        private IRequest $request,
        private IConfig  $config,
    )
    {
        $host = $this->request->getServerHost();
        $domains = $this->config->getSystemValue(Application::APP_ID, []);

        if (isset($domains[$host]) && is_array($domains[$host])) {
            $this->data = $domains[$host];
            $this->data['host'] = $host;
        } else {
            $this->data = [];
        }
    }

    /**
     * Get Domain data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get CSS for the server host
     *
     * @return string
     */
    public function getCss(): string
    {
        $css = $variables = $scss = '';

        if (isset($this->data['variables']) && is_array($this->data['variables'])) {
            foreach ($this->data['variables'] as $key => $value) {
                if (!is_string($value)) {
                    continue;
                }
                $variables .= "$key:$value; ";
            }
        }

        if (isset($this->data['scss']) && is_string($this->data['scss'])) {
            $file = dirname(__DIR__) . '/scss/' . ltrim($this->data['scss'], '/');
            if (is_file($file)) {
                try {
                    $compiler = new Compiler();
                    $compiled = $compiler->compileFile($file);
                    $scss .= $compiled->getCss();
                } catch (SassException) {
                }
            }
        }

        if ($variables) {
            $css .= ":root { $variables }";
        }

        if ($scss) {
            $css .= " $scss";
        }

        return $css;
    }
}