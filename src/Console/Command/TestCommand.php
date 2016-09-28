<?php

namespace EndpointTesting\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;

use EndpointTesting\Log\File;
use EndpointTesting\Log\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class TestCommand extends Command
{
    protected $client;
    protected $report;

    protected function configure()
    {
        $this->setName('test')
            ->setDescription('Tests urls')
            ->setHelp("This command allows you to test urls...")
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename of the log.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path   = $input->getArgument('filename');
        $file   = new File($path);
        $parser = new Parser($file);

        $this->client = new Client;
        $this->checkUrls('http://dev.ahs.com/', $output, $parser, $file);
    }

    protected function checkUrl($domain, $url)
    {
        try {
            $response = $this->client->request('GET', $domain . $url);
            $this->report[$url] = $response->getStatusCode();
        } catch (ClientException $exception) {
            // $this->report[$url] = $response->getStatusCode();
        } catch (ServerException $exception) {
            // $this->report[$url] = $response->getStatusCode();
        }
    }

    protected function checkUrls($domain, OutputInterface $output, Parser $parser, File $file)
    {
        $urls = $parser->getUrls($file);
        $count = count($urls);
        $progress = new ProgressBar($output, 100);

        foreach ($urls as $i => $url) {
            $this->checkUrl($domain, $url);
            $progress->setProgress((int)($i/$count) * 100);
        }
        $progress->finish();
        $output->writeln([
            '',
            str_repeat('=', 80),
            'Summary',
            ' - Total Urls: ' . count($urls),
            ' - Successful Urls: ' . count($this->report),
            str_repeat('=', 80),
        ]);

        return $this;
    }

    protected function getClient()
    {
        return $this->client;
    }
/**
 * Test an array of urls
 * @param  array $urls The list of urls to clean
 */
function test_urls($urls) {
    $client = new GuzzleHttp\Client;
    foreach ($urls as $url) {
        echo $url, PHP_EOL;
        try {
            $response = $client->request('GET', $url);
            echo $response->getStatusCode(), ' ', $url, PHP_EOL;
        } catch (GuzzleHttp\Exception\ClientException $exception) {
            print_r($exception->getMessage());
        }
    }
}
}