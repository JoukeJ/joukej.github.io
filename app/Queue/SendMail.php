<?php namespace App\Queue;

use App\Exceptions\GeneralException;
use League\Flysystem\Exception;

class SendMail
{
    /**
     * @todo this is the most basic implementation
     * @param $job
     * @param $data
     */
    public function fire($job, $data)
    {
        $response = \Mail::send($data['email']->body, $data['params'],
            function ($message) use ($data) {
                $message->to($data['email']->to)
                    ->subject($data['email']->subject);
            });

        try {
            $result = $this->handleResponse($data, $response);

            if ($result === true) {
                $job->delete();
            } elseif ($job->attempts() >= 3) {
                $job->delete();
            }
        } catch (Exception $e) {
            \Log::error($e->getMessage(), ['mail_id' => $data['email']->id, 'response_body' => $response->getBody()]);
        }
    }

    /**
     * @param $data
     * @param $response
     * @return bool
     * @throws GeneralException
     */
    protected function handleResponse($data, $response)
    {
        if ($response->getStatusCode() === "200") {
            $json = $response->json()[0];

            $data['email']->status = $json['status'];

            if ($json['status'] === 'error') {
                $data['email']->reject_reason = sprintf('code %s | %s: %s', $json['code'], $json['name'],
                    $json['message']);
            } else {
                $data['email']->identifier = $json['_id'];
                $data['email']->reject_reason = $json['reject_reason'];
            }

            if ($data['email']->save()) {
                return true;
            }

            throw new GeneralException("Cannot update email model with mandrill id, status");
        }

        return false;
    }
}
