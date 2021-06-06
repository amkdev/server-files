<?php
/**
 * ServerFiles plugin for Craft CMS 3.1
 *
 * Retrieve a list of files based on a specified folder path.
 *
 * @author    You & Me Digital, Alexander M. Korn
 * @link      https://github.com/amkdev/server-files
 * @copyright Copyright (c) 2019 You & Me Digital, Alexander M. Korn
 */

namespace amkdev\serverfiles;

use Craft;
use craft\base\Plugin;
use amkdev\serverfiles\variables\ServerFilesVariable;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;

class ServerFiles extends Plugin
{

    public static $plugin;
    public $schemaVersion = '1.0.0';

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                $variable = $event->sender;
                $variable->set('serverfiles', ServerFilesVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'server-files',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

}