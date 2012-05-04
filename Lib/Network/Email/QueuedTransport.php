<?php
/**
 * Queued Transport
 *
 * Enables emails to be added to a queue for final shipping at a later time.
 *
 * Licensed under The MIT License
 * 
 * @author Brad Koch <bradkoch2007@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class QueuedTransport extends AbstractTransport {

/**
 * Send
 * 
 * A bit of a misnomer, because this actually just adds it to a CakeResque
 * queue.  The actual sending of the email will be performed later by a worker.
 *
 * @params CakeEmail $email
 * @return array
 */
	public function send(CakeEmail $email) {
        // Take a copy of the existing configuration.
        $config = array(
//            'config' => $email->config(),
            'headers' => $email->getHeaders(),
            'from' => $email->from(),
            'sender' => $email->sender(),
            'replyTo' => $email->replyTo(),
            'readReceipt' => $email->readReceipt(),
            'returnPath' => $email->returnPath(),
            'to' => $email->to(),
            'cc' => $email->cc(),
            'bcc' => $email->bcc(),
            'subject' => $email->subject(),
            'viewRender' => $email->viewRender(),
            'viewVars' => $email->viewVars(),
            'emailFormat' => $email->emailFormat(),
            'messageId' => $email->messageId(),
            'attachments' => $email->attachments()
        );
//        unset($config['config']['transport']);
        $template = $email->template();
        $config['template'] = $template['template'];
        $config['layout'] = $template['layout'];

        // Clean it up to avoid errors.
        $config = array_filter($config, function ($v) {return (bool) $v;});
        debug($config);

        // Include a message, if they sent one via plain text.
        $message = $email->message(CakeEmail::MESSAGE_HTML) ? null : $email->message(CakeEmail::MESSAGE_TEXT);

        // Drop it in a queue.
        Resque::enqueue('email', 'ResqueEmail.EmailShell', array(
            'send',
            $config,
            $message
        ));

        return array(
            'headers' => $email->getHeaders(),
            'message' => $email->message(),
        );
    }

}
