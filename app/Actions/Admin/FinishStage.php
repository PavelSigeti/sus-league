<?php

namespace App\Actions\Admin;

use App\Models\StageResult;

class FinishStage
{
    public function __invoke($stage, $results)
    {
        $result = count($results);
        foreach ($results as $userId) {
            StageResult::query()->create([
                'stage_id' => $stage->id,
                'user_id' => $userId,
                'result' => $result,
            ]);
            $result--;
        }

        $stage->update([
            'status' => 'finished',
        ]);

        return $stage;
    }
}
