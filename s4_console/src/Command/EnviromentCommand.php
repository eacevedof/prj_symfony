<?php
//ejemplo:https://symfony.com/doc/current/console.html
//src/Command/EnviromentCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnviromentCommand extends Command
{
    public function __construct(bool $requirePassword = false)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn"t work in this case
        // because configure() needs the properties set in this constructor
        $this->requirePassword = $requirePassword;
        parent::__construct();
    }//__construct

    protected function configure()
    {
        //./bin/console app:command:create-user
        $this
        // the name of the command (the part after "bin/console")
        ->setName("app:command:create-enviroment")
        // the short description shown while running "php bin/console list"
        ->setDescription("Creates a new prject enviroment.")
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to create a project enviroment")
        ;
    }//configure
       
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            "Creating proyects",
            "============",
            "",
        ]);

        // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
        // that generates and returns the messages with the "yield" PHP keyword
        //$output->writeln($this->someMethod());

        // outputs a message followed by a "\n"
        $output->writeln("Whoa!");

        // outputs a message without adding a "\n" at the end of the line
        $output->write("You are about to ");
        $output->write("create a user.");
    }//execute

}//EnviromentCommand