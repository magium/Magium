<?php

namespace Magium\Cli\Command;

use Guzzle\Http\Client;
use Magium\Cli\Command\Test\TestSkeleton;
use Magium\InvalidConfigurationException;
use Magium\NotFoundException;
use Magium\Util\Api\ApiConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiPing extends Command
{

    protected function configure()
    {
        $this->setName('api:ping');
        $this->setDescription('Pings the API ping endpoint to ensure that the key works');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = new TestSkeleton();
        $test->getInitializer()->configureDi($test);
        $api = $test->get('Magium\Util\Api\ApiConfiguration');
        /* @var $api ApiConfiguration */
        if (!$api instanceof ApiConfiguration) {
            throw new InvalidConfigurationException('Did not receive an instance of ApiConfiguration; received ' . get_class($api));
        }
        $api->setEnabled(true); // Gotta force this for this test

        $output->writeln('Sending un-authenticated payload...');
        $client = new Client('http://' . $api->getApiHostname(). '/api/ping');
        $request = $client->get();
        $response = $request->send();
        $output->writeln(
            'Checking for 200 status message... '
            . ($response->getStatusCode() == '200'?'OK':'Failed')
        );
        $output->writeln(
            'Checking for application/json content type... '
            . (stripos($response->getContentType(), 'application/json') !== false?'OK':'Failed')
        );
        $content = json_decode($response->getBody(), true);
        $output->writeln(
            'Checking for successful response... '
            . (is_array($content) && isset($content['status']) && $content['status'] === 'success'?'OK':'Failed')
        );

        $output->writeln('');
        $output->writeln('Attempting authenticated ping...');

        $request = $test->get('Magium\Util\Api\Request');
        /* @var $request \Magium\Util\Api\Request */
        $response = $request->fetch('/api/ping-authed');

        $output->writeln(
            'Checking for 200 status message... '
            . ($response->getStatusCode() == '200'?'OK':'Failed')
        );
        $output->writeln(
            'Checking for application/json content type... '
            . (stripos($response->getContentType(), 'application/json') !== false?'OK':'Failed')
        );
        $content = $request->getPayload($response);
        $output->writeln(
            'Checking for successful response... '
            . (is_array($content) && isset($content['status']) && $content['status'] === 'success'?'OK':'Failed')
        );


        $output->writeln('');
        $output->writeln('Attempting authenticated echo...');
        $request = $test->get('Magium\Util\Api\Request');
        /* @var $request \Magium\Util\Api\Request */
        $response = $request->push('/api/echo-authed', ['message' => 'hello world']);
        $output->writeln(
            'Checking for 200 status message... '
            . ($response->getStatusCode() == '200'?'OK':'Failed')
        );
        $output->writeln(
            'Checking for application/json content type... '
            . (stripos($response->getContentType(), 'application/json') !== false?'OK':'Failed')
        );
        $content = $request->getPayload($response);
        $output->writeln(
            'Checking for successful response... '
            . (is_array($content) && isset($content['status']) && $content['status'] === 'success'?'OK':'Failed')
        );
        $output->writeln(
            'Checking for matching echo message... '
            . (is_array($content) && isset($content['message']) && $content['message'] === 'hello world'?'OK':'Failed')
        );
    }
    
}