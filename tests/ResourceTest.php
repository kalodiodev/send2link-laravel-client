<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Tests;

use Kalodiodev\Send2Link\Send2LinkService;

class ResourceTest extends TestCase
{
    public function testAccountResourceUrl()
    {
        $client = new Send2LinkService('https://api.test', 'secret', 5);
        $account = $client->account();

        $this->assertSame('https://api.test/api/v1/account', $account->queryUrl());
    }

    public function testProjectsResourceUrlWithWorkspaceAndPagination()
    {
        $client = new Send2LinkService('https://api.test', 'secret', 5);

        $projects = $client->projects('acme');
        $projects->paginate(1, 20);

        $expected = 'https://api.test/api/v1/workspaces/acme/projects?pageNumber=1&pageSize=20';

        $this->assertSame($expected, $projects->queryUrl());
    }

    public function testShortlinksResourceUrlForProjectWithPagination()
    {
        $client = new Send2LinkService('https://api.test', 'secret', 5);

        $shortlinks = $client->shortLinks('acme', 'project-uuid');
        $shortlinks->paginate(2, 10);

        $expected = 'https://api.test/api/v1/workspaces/acme/projects/project-uuid/shortlinks?pageNumber=2&pageSize=10';

        $this->assertSame($expected, $shortlinks->queryUrl());
    }


    public function testWorkspaceCreateUrl()
    {
        $client = new Send2LinkService('https://api.test', 'secret', 5);
        $workspaces = $client->workspaces();

        $this->assertSame('https://api.test/api/v1/workspaces', $workspaces->queryUrl());
    }

    public function testResourceUrlEncodesPathSegments()
    {
        $client = new Send2LinkService('https://api.test', 'secret', 5);

        $projects = $client->projects('my workspace');
        $expected = 'https://api.test/api/v1/workspaces/my%20workspace/projects';

        $this->assertSame($expected, $projects->queryUrl());
    }
}
