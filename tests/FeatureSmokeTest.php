<?php

declare(strict_types=1);

final class FeatureSmokeTest
{
    private const BASE_URL = 'http://127.0.0.1:8080';
    private string $cookieFile;
    private string $title;

    public function __construct()
    {
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'lht_cookie_') ?: '';
        $this->title = 'Composer Test Garden Home ' . time();
    }

    public function run(): void
    {
        $this->assertLogoutRequiresPost();
        $this->assertSearchEscapesLikeWildcards();
        $this->assertAdminCannotSelfDemote();
        $this->assertSellerToAdminToBuyerFlow();
    }

    private function assertLogoutRequiresPost(): void
    {
        $response = $this->request('GET', '/logout');
        $this->assertSame(404, $response['status'], 'GET /logout should not mutate session state.');
    }

    private function assertSearchEscapesLikeWildcards(): void
    {
        $response = $this->request('GET', '/listings?q=%');
        $this->assertSame(200, $response['status'], 'Wildcard search request should succeed.');
        $this->assertContains('0 listing(s) found', $response['body'], 'Literal % search should not match every listing.');
    }

    private function assertAdminCannotSelfDemote(): void
    {
        $admin = $this->freshClient();
        $this->login($admin, 'admin@lhtestate.test');
        $page = $this->request('GET', '/admin/users', client: $admin);
        $this->assertSame(200, $page['status'], 'Admin users page should load.');
        $adminId = $this->extractUserId($page['body'], 'admin@lhtestate.test');
        $token = $this->csrf($page['body']);

        $response = $this->request('POST', '/admin/users/' . $adminId . '/update', [
            '_csrf_token' => $token,
            'role' => 'user',
            'status' => 'active',
        ], self::BASE_URL . '/admin/users', $admin);

        $this->assertSame(200, $response['status'], 'Self-demotion post should redirect back to users page.');
        $this->assertContains('You cannot demote or disable your own account.', $response['body'], 'Admin self-demotion should be blocked.');
    }

    private function assertSellerToAdminToBuyerFlow(): void
    {
        $seller = $this->freshClient();
        $this->login($seller, 'seller@lhtestate.test');
        $post = $this->request('GET', '/post', client: $seller);
        $this->assertSame(200, $post['status'], 'Post form should load for seller.');
        $values = $this->selectValues($post['body']);

        $created = $this->request('POST', '/post', [
            '_csrf_token' => $this->csrf($post['body']),
            'title' => $this->title,
            'category_id' => $values['category_id'],
            'province_id' => $values['province_id'],
            'ward_id' => $values['ward_id'],
            'price' => '3100000000',
            'area' => '96',
            'address' => '99 Composer Test Garden Street',
            'contact_name' => 'Demo Seller',
            'contact_phone' => '+84 900 999 888',
            'description' => 'Composer smoke test listing proving pending moderation and public approval behavior for LHT Estate.',
        ], self::BASE_URL . '/post', $seller);
        $this->assertContains('Listing submitted for admin review.', $created['body'], 'Seller create flow should show success.');
        $this->assertContains('pending', $created['body'], 'New seller listing should be pending.');

        $hidden = $this->request('GET', '/listings?q=' . urlencode($this->title), client: $seller);
        $this->assertContains('0 listing(s) found', $hidden['body'], 'Pending listing should not appear in public search.');

        $admin = $this->freshClient();
        $this->login($admin, 'admin@lhtestate.test');
        $pending = $this->request('GET', '/admin/listings?status=pending', client: $admin);
        $this->assertContains($this->title, $pending['body'], 'Pending listing should appear in admin moderation.');
        $approvePath = $this->extractAction($pending['body'], '#/admin/listings/\d+/approve#');
        $approved = $this->request('POST', $approvePath, ['_csrf_token' => $this->csrf($pending['body'])], self::BASE_URL . '/admin/listings?status=pending', $admin);
        $this->assertContains('Listing approved.', $approved['body'], 'Admin approval should show success.');

        $public = $this->request('GET', '/listings?q=' . urlencode($this->title), client: $seller);
        $this->assertContains('1 listing(s) found', $public['body'], 'Approved listing should be searchable.');
        $this->assertContains('3.100.000.000 ₫', $public['body'], 'Approved listing should show formatted price.');
        $detailPath = $this->extractAction($public['body'], '#/listing/[a-z0-9-]+#');

        $buyer = $this->freshClient();
        $this->login($buyer, 'buyer@lhtestate.test');
        $detail = $this->request('GET', $detailPath, client: $buyer);
        $this->assertContains('Save listing', $detail['body'], 'Buyer should see save action.');
        $savePath = $this->extractAction($detail['body'], '#/listing/\d+/save#');
        $saved = $this->request('POST', $savePath, ['_csrf_token' => $this->lastCsrf($detail['body'])], self::BASE_URL . $detailPath, $buyer);
        $this->assertContains('Listing saved.', $saved['body'], 'Buyer save should show success.');
        $savedList = $this->request('GET', '/saved', client: $buyer);
        $this->assertContains($this->title, $savedList['body'], 'Saved page should include the saved listing.');
    }

    private function login(string $client, string $email): void
    {
        $login = $this->request('GET', '/login', client: $client);
        $response = $this->request('POST', '/login', [
            '_csrf_token' => $this->csrf($login['body']),
            'email' => $email,
            'password' => 'password123',
        ], self::BASE_URL . '/login', $client);

        $this->assertSame(200, $response['status'], 'Login request should succeed for ' . $email);
        $this->assertContains('Logout', $response['body'], 'Authenticated navigation should show logout.');
    }

    private function request(string $method, string $path, array $data = [], ?string $referer = null, ?string $client = null): array
    {
        $client ??= $this->cookieFile;
        $url = str_starts_with($path, 'http') ? $path : self::BASE_URL . $path;
        $headers = [];
        if ($referer !== null) {
            $headers[] = 'Referer: ' . $referer;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR => $client,
            CURLOPT_COOKIEFILE => $client,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($body === false) {
            throw new RuntimeException(curl_error($ch));
        }
        curl_close($ch);

        return ['status' => $status, 'body' => (string) $body];
    }

    private function csrf(string $body): string
    {
        preg_match('/name="_csrf_token" value="([^"]+)"/', $body, $matches);
        $this->assertTrue(isset($matches[1]), 'CSRF token should be present.');
        return html_entity_decode($matches[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function lastCsrf(string $body): string
    {
        preg_match_all('/name="_csrf_token" value="([^"]+)"/', $body, $matches);
        $this->assertTrue(isset($matches[1]) && $matches[1] !== [], 'CSRF token should be present.');
        return html_entity_decode(end($matches[1]), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function selectValues(string $body): array
    {
        return [
            'category_id' => $this->extractOptionValue($body, 'category_id', 'Private Homes'),
            'province_id' => $this->extractOptionValue($body, 'province_id', 'Ho Chi Minh City'),
            'ward_id' => $this->extractOptionValue($body, 'ward_id', 'Ben Nghe'),
        ];
    }

    private function extractOptionValue(string $body, string $selectName, string $label): string
    {
        preg_match('/<select[^>]*name="' . preg_quote($selectName, '/') . '"[^>]*>.*?<\/select>/s', $body, $select);
        $this->assertTrue(isset($select[0]), 'Select should exist: ' . $selectName);
        preg_match('/<option value="([^"]+)"[^>]*>[^<]*' . preg_quote($label, '/') . '/s', $select[0], $option);
        $this->assertTrue(isset($option[1]), 'Option should exist: ' . $label);
        return $option[1];
    }

    private function extractUserId(string $body, string $email): int
    {
        $position = strpos($body, $email);
        $this->assertTrue($position !== false, 'User email should be listed: ' . $email);
        $rowStart = strrpos(substr($body, 0, $position), '<tr>');
        $rowEnd = strpos($body, '</tr>', $position);
        $this->assertTrue($rowStart !== false && $rowEnd !== false, 'User table row should exist for ' . $email);
        $row = substr($body, $rowStart, $rowEnd - $rowStart);
        preg_match('#/admin/users/(\d+)/update#', $row, $matches);
        $this->assertTrue(isset($matches[1]), 'User update form should exist for ' . $email);
        return (int) $matches[1];
    }

    private function extractAction(string $body, string $pattern): string
    {
        preg_match($pattern, html_entity_decode($body, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), $matches);
        $this->assertTrue(isset($matches[0]), 'Expected action should exist: ' . $pattern);
        return $matches[0];
    }

    private function freshClient(): string
    {
        return tempnam(sys_get_temp_dir(), 'lht_cookie_') ?: throw new RuntimeException('Could not create cookie jar.');
    }

    private function assertSame(int $expected, int $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new RuntimeException($message . " Expected {$expected}, got {$actual}.");
        }
    }

    private function assertContains(string $needle, string $haystack, string $message): void
    {
        if (!str_contains($haystack, $needle)) {
            throw new RuntimeException($message . " Missing: {$needle}");
        }
    }

    private function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new RuntimeException($message);
        }
    }
}

(new FeatureSmokeTest())->run();
echo "Feature smoke tests passed.\n";
