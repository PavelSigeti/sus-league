<?php

namespace App\Actions\User;

class CalcStageGroupTable
{
    public function __invoke($results, $drops)
    {
        $response = clone $results;

        $dnf = count($results) + 1;
        foreach ($results as $userId => $user) {
            foreach ($user as $key => $race) {
                if($race['place'] === null) {
                    $results[$userId][$key]['place'] = $dnf;
                    $results[$userId][$key]['null'] = true;
                }
            }
            $results[$userId] = $user->sort(function($a, $b){
                return ($a->place > $b->place) ? 1 : -1;
            })->values();
            if($drops > 0 && $results[$userId]->count() >= $drops) {
                for($i=1; $i<=$drops; $i++) {
                    $dropId = $results[$userId]->last()->race_id;
                    $results[$userId] = $results[$userId]
                        ->splice(0, $results[$userId]->count() - 1);
                    $response[$userId]->where('race_id', $dropId)->first()->drop = true;
                }
            }
            $response[$userId]['sum'] = $results[$userId]->sum(['place']);
            $results[$userId]['sum'] = $results[$userId]->sum(['place']);
        }

        $rawData = $results->sort(function($a, $b){
            if($a['sum'] < $b['sum']) {
                return -1;
            } elseif ($a['sum'] > $b['sum']) {
                return 1;
            }
            else {
                $raceAmount = $b->count() - 1;
                for($i=0; $i < $raceAmount; $i++) {
                    if($a[$i]->place < $b[$i]->place) {
                        return -1;
                    } elseif ($a[$i]->place > $b[$i]->place) {
                        return 1;
                    } elseif ($i == $raceAmount-1) {
                        $aRaceId = $a->max('race_id');
                        $bRaceId = $b->max('race_id');

                        $aPlace = $a->where('race_id', $aRaceId)->first()->place;
                        $bPlace = $b->where('race_id', $bRaceId)->first()->place;

                        if($aPlace < $bPlace) {
                            return -1;
                        } else {
                            return 1;
                        }
                    }
                }
            }
        });

        $sortIds = $rawData->keys()->toArray();

        $response = $response->sortBy(function($user, $key) use ($sortIds){
            return array_search($key, $sortIds);
        })->values();


        return $response;
    }
}
