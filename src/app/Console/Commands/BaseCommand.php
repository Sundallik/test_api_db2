<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

abstract class BaseCommand extends Command
{
    protected function validationRules(): array
    {
        return [];
    }

    protected function validationMessages(): array
    {
        return [];
    }

    abstract protected function handleCommand(): int;

    /**
     * Execute the console command.
     */
    public final function handle(): int
    {
        try {
            if (!$this->validateArgs()) return self::FAILURE;

            $this->handleCommand();

            return self::SUCCESS;
        } catch (ModelNotFoundException $e) {
            $this->fail(class_basename($e->getModel()) . " with this id not found");
        } catch (QueryException $e) {
            $e->errorInfo[1] == 1062 ? $this->fail("Entity already exists") : $this->fail($e->getMessage());
        } catch (\Exception $e) {
            $this->error('Command failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected final function validateArgs(): bool
    {
        $data = array_map(function($value) {
            return is_numeric($value) ? (int) $value : $value;
        }, $this->arguments());

        $validator = Validator::make(
            $data,
            $this->validationRules(),
            $this->validationMessages()
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        return true;
    }
}
