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

namespace OCA\ThemingDomain\Migration;

use OCP\IRequest;
use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;
use Psr\Log\LoggerInterface;

class InstallConfig implements IRepairStep
{
    public function __construct(
        private IRequest        $request,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * Returns the step's name
     */
    public function getName()
    {
        return 'Create the Domain Theming APP config file.';
    }

    /**
     * @param IOutput $output
     */
    public function run(IOutput $output)
    {
        $host = $this->request->getServerHost();
        $conf = \OC::$configDir . '/theming.domain.config.php';

        if ($host && !is_file($conf)) {
            $data[] = "<?php\n";
            $data[] = "# Domain Theming APP Config file (theming.domain.config.php).\n";
            $data[] = '$CONFIG = array(';
            $data[] = '    \'theming_domain\' => array(';
            $data[] = "        '$host' => array()";
            $data[] = '    )';
            $data[] = ');';

            file_put_contents($conf, implode("\n", $data));

            $this->logger->info('Domain Theming APP Config file created.', ['app' => 'theming_domain']);
        }
    }
}