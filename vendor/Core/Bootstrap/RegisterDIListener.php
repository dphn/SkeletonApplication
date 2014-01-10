<?php

namespace Core\Bootstrap;

class RegisterDIListener
{
    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (! isset($config['di'])) {
            return;
        }

        foreach ($config['di'] as $key => $value) {
            $value = (array) $value;
            $isShared = true;
            if (isset($value[1])) {
                $isShared = (bool) $value[1];
            }
            $di->set($key, $value[0], $isShared);
        }
    }
}
