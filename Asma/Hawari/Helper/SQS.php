<?php

namespace Asma\Hawari\Helper;

use Aws\Credentials\Credentials;
use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;
use Psr\Log\LoggerInterface;

class SQS
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function preparePayLoad()
    {
        $this->logger->debug('FROM SQS');

        $key = 'AKIAYVE46RIIQQAPQUFF';
        $secret = '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';

        $credentials = new Credentials($key, $secret);

        $client = new SqsClient([
            'profile' => '__default_policy_ID',
            'region' => 'us-west-2',
            'version' => '2008-10-17',
            'credentials' => $credentials
        ]);

        $params = [
            'DelaySeconds' => 10,
            'MessageAttributes' => [
                "Title" => [
                    'DataType' => "String",
                    'StringValue' => "SQS TEST FROM MAGENTO"
                ],
                "Author" => [
                    'DataType' => "String",
                    'StringValue' => "Asma Hawari."
                ],
                "WeeksOn" => [
                    'DataType' => "Number",
                    'StringValue' => "6"
                ]
            ],
            'MessageBody' => "Information about current NY Times fiction bestseller for week of 19/05/2022.",
            'QueueUrl' => 'https://sqs.us-east-1.amazonaws.com/595182127633/Magento'
        ];

        try {
            $result = $client->sendMessage($params);
            var_dump($result);
        } catch (AwsException $e) {
            error_log($e->getMessage());
        }
    }
}
