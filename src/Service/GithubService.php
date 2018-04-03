<?php

/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     03.04.2018
 * @link      http://github.com/heiglandreas/getlatestassets
 */

namespace Org_Heigl\GetLatestAssets\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Org_Heigl\GetLatestAssets\Exception\AssetNotFound;
use Org_Heigl\GetLatestAssets\Exception\NoAssetsFound;
use Org_Heigl\GetLatestAssets\Exception\TroubleWithGithubApiAccess;
use Exception;

class GithubService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke($user, $project, $file) : Uri
    {
        try {
            $result = $this->client->get(sprintf(
                '/repos/%1$s/%2$s/releases/latest',
                $user,
                $project
            ));
        } catch (Exception $e) {
            throw new TroubleWithGithubApiAccess(
                'Something went south while accessing the Github-API',
                400,
                $e
            );
        }

        $result = json_decode($result->getBody()->getContents(), true);

        if (! isset($result['assets'])) {
            throw new NoAssetsFound(sprintf(
                'There are no assets available for release %1$s at %2$s',
                $result['tag_name'],
                $result['html_url']
            ));
        }

        foreach($result['assets'] as $asset) {
            if ($asset['name'] === $file) {
                return new Uri($asset['browser_download_url']);
            }
        }

        throw new AssetNotFound('No Asset with that name in the latest release found');
    }
}
