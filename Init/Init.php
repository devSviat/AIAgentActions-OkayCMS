<?php


namespace Okay\Modules\Sviat\AIAgentActions\Init;


use Okay\Helpers\MainHelper;
use Okay\Core\Modules\AbstractInit;
use Okay\Modules\Sviat\AIAgentActions\Extenders\FrontExtender;

class Init extends AbstractInit
{
    public function install()
    {
        $this->setBackendMainController('DescriptionAdmin');
    }

    public function init()
    {
        $this->addPermission('sviat__ai_agent_actions');

        $this->registerBackendController('DescriptionAdmin');
        $this->addBackendControllerPermission('DescriptionAdmin', 'sviat__ai_agent_actions');

        // Передаємо переклади в JavaScript
        $this->registerQueueExtension(
            [MainHelper::class, 'commonAfterControllerProcedure'],
            [FrontExtender::class, 'assignTranslations']
        );
    }
}
