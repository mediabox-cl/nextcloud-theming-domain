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

namespace OCA\ThemingDomain\Controller;

use OCA\ThemingDomain\ThemingDomain;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\Files\NotFoundException;
use OCP\IRequest;

class ThemingDomainController extends Controller
{
    private $themingDomain;

    public function __construct(
        $appName,
        IRequest $request,
        ThemingDomain $themingDomain
    )
    {
        parent::__construct($appName, $request);

        $this->themingDomain = $themingDomain;
    }

    /**
     * @NoCSRFRequired
     * @PublicPage
     */
    public function getStylesheet(): NotFoundResponse|DataDisplayResponse
    {
        try {
            $response = new DataDisplayResponse(
                $this->themingDomain->getCss(),
                Http::STATUS_OK,
                [
                    'Content-Type' => 'text/css'
                ]
            );
            $response->cacheFor(86400);
            return $response;
        } catch (NotFoundException) {
            return new NotFoundResponse();
        }
    }
}