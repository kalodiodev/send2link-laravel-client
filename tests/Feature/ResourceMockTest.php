<?php

declare(strict_types=1);

namespace Kalodiodev\Send2Link\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Kalodiodev\Send2Link\Send2LinkService;
use Kalodiodev\Send2Link\Models\Account;
use Kalodiodev\Send2Link\Models\Workspace;
use Kalodiodev\Send2Link\Exceptions\UnauthorizedException;
use Kalodiodev\Send2Link\Exceptions\NotFoundException;
use Kalodiodev\Send2Link\Exceptions\ValidationException;
use Kalodiodev\Send2Link\Tests\TestCase;

class ResourceMockTest extends TestCase
{
    private Send2LinkService $service;
    private string $mockBaseUrl = 'https://send2link.eu';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new Send2LinkService($this->mockBaseUrl, 'fake-key');
    }

    public function testAccountGet()
    {
        Http::fake([
            '*api/v1/account' => Http::response([
                'email' => 'test@example.com',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'createdAt' => '2024-01-01',
                'projectsCount' => 5,
                'shortLinksCount' => 10
            ], 200)
        ]);

        $response = $this->service->account()->get();
        $account = $response->getItem();

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals('test@example.com', $account->getEmail());
        $this->assertEquals('John', $account->getFirstName());
        $this->assertEquals(5, $account->getProjectsCount());
    }

    public function testWorkspacesAll()
    {
        Http::fake([
            '*api/v1/workspaces' => Http::response([
                'content' => [
                    ['name' => 'Workspace 1', 'slug' => 'w1', 'workspaceRole' => 'OWNER'],
                    ['name' => 'Workspace 2', 'slug' => 'w2', 'workspaceRole' => 'MEMBER'],
                ]
            ], 200)
        ]);

        $workspaces = $this->service->workspaces()->all();

        $this->assertCount(2, $workspaces);
        $this->assertInstanceOf(Workspace::class, $workspaces->first());
        $this->assertEquals('Workspace 1', $workspaces->first()->getName());
    }

    public function testProjectsAll()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects*' => Http::response([
                'content' => [
                    ['uuid' => 'p1', 'name' => 'Project 1', 'description' => 'Desc', 'createdAt' => '2024-01-01', 'updatedAt' => '2024-01-01'],
                ],
                'page' => 0,
                'size' => 25,
                'totalElements' => 1,
                'totalPages' => 1,
                'first' => true,
                'last' => true
            ], 200)
        ]);

        $response = $this->service->projects('w1')->all();
        $projects = $response->getItems();

        $this->assertCount(1, $projects);
        $this->assertEquals('Project 1', $projects->first()->getName());
    }

    public function testProjectsCreate()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects' => Http::response([
                'uuid' => 'pnew',
                'name' => 'New Project',
                'description' => 'New Desc',
                'createdAt' => '2024-01-01',
                'updatedAt' => '2024-01-01'
            ], 201)
        ]);

        $response = $this->service->projects('w1')->create('New Project', 'New Desc');
        $project = $response->getItem();

        $this->assertEquals('pnew', $project->getUuid());
        $this->assertEquals('New Project', $project->getName());
    }

    public function testShortLinksAll()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1/shortlinks*' => Http::response([
                'content' => [
                    [
                        'uuid' => 's1',
                        'link' => 'https://2ln.eu/s1',
                        'destination' => 'https://example.com',
                        'enabled' => true,
                        'createdAt' => '2024-01-01',
                        'updatedAt' => '2024-01-01'
                    ],
                ],
                'page' => 0,
                'size' => 25,
                'totalElements' => 1,
                'totalPages' => 1,
                'first' => true,
                'last' => true
            ], 200)
        ]);

        $response = $this->service->shortLinks('w1', 'p1')->all();
        $links = $response->getItems();

        $this->assertCount(1, $links);
        $this->assertEquals('https://example.com', $links->first()->getDestination());
    }

    public function testShortLinksCreate()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1/shortlinks' => Http::response([
                'uuid' => 's1',
                'link' => 'https://2ln.eu/s1',
                'destination' => 'https://example.com',
                'enabled' => true,
                'createdAt' => '2024-01-01',
                'updatedAt' => '2024-01-01'
            ], 201)
        ]);

        $response = $this->service->shortLinks('w1', 'p1')->create('https://example.com', true);
        $link = $response->getItem();

        $this->assertEquals('s1', $link->getUuid());
        $this->assertEquals('https://example.com', $link->getDestination());
    }

    public function testShortLinksUpdate()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1/shortlinks/s1' => Http::response([], 200)
        ]);

        $this->service->shortLinks('w1', 'p1')->update('s1', 'https://new.com', false);
        Http::assertSent(function ($request) {
            return $request->url() == 'https://send2link.eu/api/v1/workspaces/w1/projects/p1/shortlinks/s1' &&
                   $request->method() == 'PATCH' &&
                   $request['destination'] == 'https://new.com' &&
                   $request['enabled'] === false;
        });
    }

    public function testShortLinksDelete()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1/shortlinks/s1' => Http::response([], 204)
        ]);

        $this->service->shortLinks('w1', 'p1')->delete('s1');
        Http::assertSent(function ($request) {
            return $request->url() == 'https://send2link.eu/api/v1/workspaces/w1/projects/p1/shortlinks/s1' &&
                   $request->method() == 'DELETE';
        });
    }

    public function testProjectsUpdate()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1' => Http::response([], 200)
        ]);

        $this->service->projects('w1')->update('p1', 'Updated Project', 'Updated Desc');
        Http::assertSent(function ($request) {
            return $request->url() == 'https://send2link.eu/api/v1/workspaces/w1/projects/p1' &&
                   $request->method() == 'PATCH' &&
                   $request['name'] == 'Updated Project';
        });
    }

    public function testProjectsDelete()
    {
        Http::fake([
            '*api/v1/workspaces/w1/projects/p1' => Http::response([], 204)
        ]);

        $this->service->projects('w1')->delete('p1');
        Http::assertSent(function ($request) {
            return $request->url() == 'https://send2link.eu/api/v1/workspaces/w1/projects/p1' &&
                   $request->method() == 'DELETE';
        });
    }

    public function testDomainsAll()
    {
        Http::fake([
            '*api/v1/domains' => Http::response([
                'content' => ['2ln.eu', 'custom.com']
            ], 200)
        ]);

        $domains = $this->service->domains()->all();

        $this->assertCount(2, $domains);
        $this->assertEquals('2ln.eu', $domains->first()->getName());
    }

    public function testUnauthorizedException()
    {
        Http::fake([
            '*' => Http::response(['message' => 'Unauthorized'], 401)
        ]);

        $this->expectException(UnauthorizedException::class);
        $this->service->account()->get();
    }

    public function testNotFoundException()
    {
        Http::fake([
            '*' => Http::response(['message' => 'Not Found'], 404)
        ]);

        $this->expectException(NotFoundException::class);
        $this->service->projects('w1')->find('non-existent');
    }

    public function testValidationException()
    {
        Http::fake([
            '*' => Http::response([
                'message' => 'The given data was invalid.',
                'errors' => ['name' => ['The name field is required.']]
            ], 422)
        ]);

        try {
            $this->service->projects('w1')->create('', '');
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertArrayHasKey('name', $e->getErrors());
        }
    }
}
