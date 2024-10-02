<?php

declare(strict_types=1);

/*
 * @copyright Copyright (c) 2024 Michael Epstein <mepstein@live.cl>
 *
 * @author Michael Epstein <mepstein@live.cl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
                $variables .= " $key: $value;\n";
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
            $css .= ":root {\n$variables}\n";
        }

        if ($scss) {
            $css .= $css ? "\n$scss" : $scss;
        }

        return $css;
    }
}