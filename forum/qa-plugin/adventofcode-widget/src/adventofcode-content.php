<?php

class adventofcode_content
{
    public function loadFromPage(string $year, string $leaderboard, string $session): array
    {
        $response = $this->getResponse($year, $leaderboard, $session);
        if ($response === null) {
            return [];
        }

        return $this->parseResponse($response);
    }

    public function resultToCsv(array $users): string
    {
        $csv = fopen('php://memory', 'r+');
        foreach ($users as $user) {
            fputcsv($csv, $user);
        }
        rewind($csv);

        return stream_get_contents($csv);
    }

    public function csvToResult(?string $csv): array
    {
        if (empty($csv)) {
            return [];
        }

        $users = [];
        $rows = explode(PHP_EOL, $csv);
        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }

            [$name, $score, $stars] = str_getcsv($row);
            $users[] = [
                'name' => $name,
                'score' => $score,
                'stars' => $stars,
            ];
        }

        return $users;
    }

    private function getResponse(string $year, string $leaderboard, string $session)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://adventofcode.com/{$year}/leaderboard/private/view/{$leaderboard}.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, 'session=' . $session);
        $aocResponse = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            return null;
        }

        return $aocResponse;
    }

    private function parseResponse(string $response): array
    {
        $data = json_decode($response, true);
        if (!$data) {
            return [];
        }

        $users = [];
        foreach ($data['members'] as $member) {
            $stars = '';
            $days = $member['completion_day_level'];
            foreach (range(1, 25) as $day) {
                $stars .= !empty($days[$day]) ? count($days[$day]) : 0;
            }

            $users[] = [
                'name' => $member['name'] ?? ('Anonim '.$member['id']),
                'score' => $member['local_score'],
                'stars' => $stars,
            ];
        }

        usort($users, function($userA, $userB) {
            return $userB['score'] <=> $userA['score'];
        });

        return $users;
    }
}
