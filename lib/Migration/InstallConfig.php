<?php

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
        $conf = \OC::$configDir . '/theming.config.php';

        if ($host && !is_file($conf)) {
            $data[] = "<?php\n";
            $data[] = "# Domain Theming APP Config file (theming.config.php).\n";
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