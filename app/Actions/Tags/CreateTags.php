<?php

namespace App\Actions\Tags;

use App\Models\Conteudo\Tag;
use Ramsey\Uuid\Uuid;

class CreateTags
{
    private array $tag_ids = [];

    public function handle(array|string $tags): array
    {
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $this->tag_ids[] = $this->findOrAddNewTag($tag)->id;
            }
        } else {
            $this->tag_ids[] = $this->findOrAddNewTag($tags)->id;
        }

        return $this->tag_ids;
    }

    private function findOrAddNewTag($tag): Tag
    {
        return Uuid::isValid($tag) ? Tag::findOrFail($tag) : Tag::firstOrCreate(['nome' => $tag]);
    }
}
