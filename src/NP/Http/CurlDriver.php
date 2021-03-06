<?php

/**
 * @file
 * This file is part of NovaPoshta PHP library.
 *
 * @author  Anton Karpov <awd.com.ua@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/awd-studio/novaposhta
 */

declare(strict_types=1); // strict mode

namespace NP\Http;

use NP\Exception\ErrorException;


/**
 * Class CurlDriver
 * @package NP\Http
 */
class CurlDriver implements DriverInterface
{

    /**
     * Send HTTP request.
     *
     * @param Request $request
     *
     * @return string
     * @throws ErrorException
     */
    public function send(Request $request): string
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL            => $request->getUri(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => $request->getBodyJson(),
                CURLOPT_HTTPHEADER     => ["content-type: application/json"],
            ]);

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new ErrorException("cURL Error #:" . curl_error($curl));
            }
            
            curl_close($curl);

            return (string) $response;
        } catch (ErrorException $exception) {
            throw new ErrorException($exception->getMessage());
        }
    }
}
