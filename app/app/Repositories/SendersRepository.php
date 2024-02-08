<?php

namespace App\Repositories;

use App\Models\Sender;
use App\Repositories\Interface\SendersRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SendersRepository implements SendersRepositoryInterface
{


    public function persistSender(array $sender)
    {
        try {
            $persist = Sender::create([
                'uuid' => Str::uuid(),
                'name' => $sender['_nome'],
            ]);

            if (!$persist) {
                throw new \Exception("Error to persist sender {$sender['_nome']}");
            }

            return ['message' => 'Senders persisted successfully', 'code' => Response::HTTP_OK];
        } catch (\Exception $e) {
            return ['message' => $e->getMessage()];
        }
    }

    public function getSenderByUuid(string $senderName): string
    {
        return Sender::select('uuid')->where('name', $senderName)->first()->uuid;
    }
}
