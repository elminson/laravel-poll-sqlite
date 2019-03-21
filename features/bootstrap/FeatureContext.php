<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImMwZWY0MTIwNDhhYWVlNWZlMWY1ZGY1NDhlMDczNDQzMWZjOTQ4YmIzNzBkM2RhODJhZjhlMGQwN2E2YWUxODc4YjVkYTIxYjBjNDk2ZDJlIn0.eyJhdWQiOiIzIiwianRpIjoiYzBlZjQxMjA0OGFhZWU1ZmUxZjVkZjU0OGUwNzM0NDMxZmM5NDhiYjM3MGQzZGE4MmFmOGUwZDA3YTZhZTE4NzhiNWRhMjFiMGM0OTZkMmUiLCJpYXQiOjE1NTMxODM4NzksIm5iZiI6MTU1MzE4Mzg3OSwiZXhwIjoxNTg0ODA2Mjc5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.he-6gCQ2pLJSgB8FbIG2X579ln31vVqY4lr_MUC92ydacTloM5eN5euqUUfw_gEnFprhjeG0JoSE-pRMKZVB3e1QrAI6LQv37Y2a0wzYAFsGEQyX5qZcWxwe_4A0W6dHJz9jmwQpBGMl_b3ZC_9ME1yvx0vtILuHOtUgv32kEk9XvKnTCWg1BFDPTkUJVn6xyYpnn7iULXJr9H_b4-pz_hs8YpufgtXKoKwGNl-5G0Kqsnx_eyDpYANhSFOW7wtmI3a3WSn4tzHt5uxxqsXsZPBhIpEOrIS_b4iBH4h2-7aSrvJsIR-sKQMoeQ-cEqNxzh1LiA0L-zwzdusr5FN7Ic5LJVrT-H8LCmQisk7OYOMJY2Azw-U19PQgnCMAtPD4gcEu2PXQuEE3o_UDXqrOuN-Mfx7b1asbVYZjZ-5p4R3anVGbjuCvDXjS6RPA_y03gNTEo324Y47Scsk2qEC_9slpZC2hFyBV7K2p8Dqmc0lP-JsR1kkwFaygN0yPysW88aDHBGLpybKXiPsSrYXKb63RWWrobqK5omWrhYuPtPG6TXUpo542zrmO1Un_XW_Sz32OihtZrEdIo12ASZw19iTNNyWo79caFEEqZN_UNlYUwURxFCDckDxLsn5DpJwIaGmqmcJ6oyTCKwIOa0EdbN_soOUvABf19nSY5i_KtGw";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data = json_decode($this->responseBody);
        $count  = count($data);
        return ($count == $arg1);
    }

    /**
     * @Then the response contains a title of :arg1
     */
    public function theResponseContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if($data->title == $arg1){

        } else {
            throw new Exception("The Title dont match!");
        }
    }
}
