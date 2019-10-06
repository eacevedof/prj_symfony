<?php
//ejemplo:https://symfony.com/doc/current/console.html
//src/Command/CreateUserCommand.php
//./bin/console app:command:create-user
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    public function __construct(bool $requirePassword = false)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn"t work in this case
        // because configure() needs the properties set in this constructor
        $this->requirePassword = $requirePassword;
        parent::__construct();
        $this
        // configure an argument
        ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
    }//__construct

    protected function configure()
    {
        //./bin/console app:command:create-user
        $this
        // the name of the command (the part after "bin/console")
        ->setName("app:command:create-user")
        // the short description shown while running "php bin/console list"
        ->setDescription("Creates a new user.")
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to create a user...")
        ;
    }//configure
       
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);
    
        // retrieve the argument value using getArgument()
        $output->writeln('Username: '.$input->getArgument('username'));
    }

}//CreateUserCommand