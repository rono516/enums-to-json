<?php

namespace rono516\EnumsToJson\Commands;

use BackedEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use rono516\EnumsToJson\Attributes\EnumToJson;
use rono516\EnumsToJson\Exceptions\LaravelEnumToJsonException;
use spatie\StructureDiscoverer\Discover;

class EnumsToJsonCommand extends Command
{
    public $signature = 'enums-to-json:generate';

    public $description = 'It creates a json file from attributed enums.';

    public function handle(): int
    {
        $output = [];

        $this->getQualifiedEnums()
            ->each(function (BackedEnum | string $enum) use ($output) {
                // Logic
                // $fileName = str($enum)->classBasename()->snake()->append('.json')->toString();
                $fileName = $this->generateFileName($enum);

                if ($output[$fileName] ?? false) // if name already exists
                {
                    // throw an exception
                    throw LaravelEnumToJsonException::nameCollision($fileName);
                }
                $output[$fileName] = $this->prepareEnumData($enum);

            });

        // get the json representation of that enum
        // generate a file on given disk
        foreach ($output as $fileName => $contents) {
            Storage::disk(config('enums-to-json.disk'))
                ->put(
                    str(config('enums-to-json.path') . '/' . $fileName)->replace('//', '/')->toString(),
                    json_encode($contents),
                );
        }

        $this->info("Generated " . count($output) . ' files');

        return self::SUCCESS;
    }

    protected function getQualifiedEnums(): \Illuminate\Support\Collection
    {
        $enums = Discover::in(...config('enums-to-json.enum_locations'))
            ->enums()
            ->withAttribute(EnumToJson::class)
            ->get();
        return collect($enums);
    }

    protected function prepareEnumData(BackedEnum | string $enum)
    {
        return collect($enum::cases())
            ->map(function ($el) {
                return [
                    'label' => $el->name,
                    'value' => $el->value,
                ];
            });
    }

    protected function generateFileName(BackedEnum|string $enum): string
    {
        if (method_exists($enum, 'jsonFileName')) {
            return str($enum::jsonFileName())->finish('.json')->toString();
        }

        return str($enum)->classBasename()->snake()->append('.json')->toString();
    }
}
