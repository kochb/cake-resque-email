CakeResque Email Plugin
=======================

This plugin provides all the tools you need to immediately begin sending emails
via a CakeResque powered queue.  This saves the user time during the standard
HTTP request cycle.

Installation
------------
1. Install the cake-resque plugin.

    git submodule add git://github.com/kamisama/Cake-Resque.git

2. Configure CakeResque as per the [CakeResque instructions](https://github.com/kamisama/Cake-Resque/blob/master/README.md).

3. Install the cake-resque-email plugin.

    git submodule add git://github.com/kochb/cake-resque-email.git app/Plugin/ResqueEmail

4. Ensure both are listed in your plugins array.

    CakePlugin::load(array(
        'Resque' => array('bootstrap' => true),
        'ResqueEmail'
    ));

Usage
-----
1. Ensure that your application is sending email via [CakeEmail](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html).

2. In your email.php file, set the transport for any emails you would like to
   process via a queue to `ResqueEmail.Queued`. Example:

    public $default = array(
		'transport' => 'ResqueEmail.Queued',
		'from' => array('mailer@example.com' => 'Our Team')
    );

3. In your email.php file, add an email configuration directive to be used when
   processing the queue, and name it $resqueEmail.  Example:

    public $resqueEmail = array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => 465,
        'username' => 'my@gmail.com',
        'password' => 'secret',
        'transport' => 'Smtp'
    );

4. Ensure that a worker is running and give it a test.  You should receive your
   message on delay through the queue, and load times on pages that send emails
   should improve.

Known Issues
------------
You should expect awkward behavior when using html/both as the email format AND
specifying a message via CakeEmail::send('message').  There shouldn't be much of
a reason to mix the two since an HTML email should be using CakeEmail::viewVars
instead.  Text emails and CakeEmail::send('message') work perfectly fine.
