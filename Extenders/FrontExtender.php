<?php


namespace Okay\Modules\Sviat\AIAgentActions\Extenders;


use Okay\Core\Design;
use Okay\Core\FrontTranslations;
use Okay\Core\Languages;
use Okay\Core\Modules\Extender\ExtensionInterface;
use Okay\Core\Modules\Modules;

class FrontExtender implements ExtensionInterface
{
    private Design $design;
    private FrontTranslations $frontTranslations;
    private Languages $languages;
    private Modules $modules;

    public function __construct(
        Design            $design,
        FrontTranslations $frontTranslations,
        Languages         $languages,
        Modules           $modules
    ) {
        $this->design = $design;
        $this->frontTranslations = $frontTranslations;
        $this->languages = $languages;
        $this->modules = $modules;
    }

    /**
     * Передає переклади в JavaScript для поточної мови сайту
     * Отримуємо переклади безпосередньо через Modules::getModuleFrontTranslations() для поточної мови
     */
    public function assignTranslations()
    {
        // Отримуємо поточну мову
        $langLabel = $this->languages->getLangLabel();
        
        // Отримуємо правильний файл перекладів через getModuleFrontTranslationsLangFile
        // який враховує fallback логіку і може повернути файл з теми або модуля
        $langFile = $this->modules->getModuleFrontTranslationsLangFile('Sviat', 'AIAgentActions', $langLabel);
        
        $moduleTranslations = [];
        if ($langFile && file_exists($langFile)) {
            // Завантажуємо переклади напряму з файлу для поточної мови
            $lang = [];
            include $langFile;
            $moduleTranslations = $lang;
        } else {
            // Якщо файл не знайдено, використовуємо стандартний метод (з fallback)
            $moduleTranslations = $this->modules->getModuleFrontTranslations('Sviat', 'AIAgentActions', $langLabel);
        }
        
        // Ключі перекладів, які потрібно передати в JavaScript
        $translationKeys = [
            'sviat__ai_agent_actions__prompt_title',
            'sviat__ai_agent_actions__prompt_product_name',
            'sviat__ai_agent_actions__prompt_product_url',
            'sviat__ai_agent_actions__prompt_features',
            'sviat__ai_agent_actions__prompt_description',
            'sviat__ai_agent_actions__prompt_task_1',
            'sviat__ai_agent_actions__prompt_task_2',
            'sviat__ai_agent_actions__prompt_task_3',
            'sviat__ai_agent_actions__prompt_task_4',
            'sviat__ai_agent_actions__prompt_task_5',
            'sviat__ai_agent_actions__prompt_task_6',
            'sviat__ai_agent_actions__prompt_warning',
        ];

        $translations = [];
        foreach ($translationKeys as $key) {
            // Отримуємо переклад з масиву перекладів модуля для поточної мови
            $translationValue = $moduleTranslations[$key] ?? '';
            
            // Якщо не знайдено в модулі, спробуємо через FrontTranslations (fallback)
            if (empty($translationValue)) {
                $translationValue = $this->frontTranslations->getTranslation($key) ?? '';
            }
            
            // Конвертуємо ключ з повного на короткий, залишаючи префікс prompt_
            // sviat__ai_agent_actions__prompt_title -> prompt_title
            $shortKey = str_replace('sviat__ai_agent_actions__', '', $key);
            $translations[$shortKey] = $translationValue;
        }

        $this->design->assignJsVar('ai_agent_actions_translations', $translations);
    }
}
