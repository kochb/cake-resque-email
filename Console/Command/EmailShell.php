<?php
App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');

class EmailShell extends AppShell {

    public function send(/*$config, $message = null*/) {
        $config = $this->args[0];
        $message = $this->args[1];

        $email = new CakeEmail('resqueEmail');
        CakeLog::write(LOG_DEBUG, print_r($email->config(), true));

        CakeLog::write(LOG_DEBUG, print_r($config, true));
        $email->config($config);
        CakeLog::write(LOG_DEBUG, print_r($email->config(), true));
        
        $email->send($message);
    }

}
