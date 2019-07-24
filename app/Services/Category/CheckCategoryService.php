<?php

namespace App\Services\Category;

use App\Models\Email;
use App\Models\Category;
use App\Models\Definition;
use Illuminate\Support\Str;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckCategoryService
{
    use SelfCallingService;

    /**
     * Check if the category applies to the given email.
     *
     * @param  \App\Models\Email  $email
     * @param  \App\Models\Category  $category
     */
    public function run(Email $email, Category $category): bool
    {
        $emailData = [
            'from' => strtolower($email->from_address),
            'subject' => strtolower($email->subject),
            'body' => strtolower($email->body),
        ];

        $results = collect($category->definitions)->map(function ($definition) use ($emailData) {
            return $this->processDefinition($definition, $emailData[$definition->definition_type]);
        });

        return ! $results->contains(false);
    }

    /**
     * Process the given definition on the given data.
     *
     * @param  \App\Models\Definition  $definition
     * @param  string  $data
     */
    private function processDefinition(Definition $definition, string $data): bool
    {
        if ('exact' === $definition->rule_type) {
            if ($data === strtolower($definition->definition)) {
                return true;
            }

            return $definition->optional ? true : false;
        }
        if ('words' === $definition->rule_type) {
            if (Str::contains($data, \explode(PHP_EOL, strtolower($definition->definition)))) {
                return true;
            }

            return $definition->optional ? true : false;
        }
        if ('regex' === $definition->rule_type) {
            if (\preg_match(strtolower($definition->definition), $data)) {
                return true;
            }

            return $definition->optional ? true : false;
        }
    }
}
